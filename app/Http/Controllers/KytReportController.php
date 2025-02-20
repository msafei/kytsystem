<?php

namespace App\Http\Controllers;

use App\Models\KytReport;
use App\Models\KytSign;
use App\Models\Employee;
use App\Models\Company;
use App\Models\Department;
use App\Models\FlowKytReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Arr;

class KytReportController extends Controller
{
    public function index()
    {
        $kytReports = KytReport::with(['department', 'company'])->get();
        return view('kyt_reports.index', compact('kytReports'));
    }

    public function create()
    {
        $user = Auth::user();
    
        // Menentukan daftar perusahaan yang bisa dipilih user
        if ($user->role == 0 || ($user->employee && $user->employee->company->companyType == 1)) {
            $companies = Company::all();
        } else {
            $companies = Company::where('id', $user->employee->company_id)->get();
        }
    
        $departments = Department::all();
        return view('kyt_reports.create', compact('companies', 'departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'company_id' => 'nullable|exists:companies,id',
            'department_id' => 'nullable|exists:departments,id',
            'date' => 'nullable|date',
            'projectTitle' => 'nullable|string|max:255',
            'shift' => 'nullable|integer|in:1,2,3',
            'workingStart' => 'nullable',
            'workingEnd' => 'nullable',
            'instructors' => 'nullable|json',
            'attendants' => 'nullable|json',
            'potentialDangerous' => 'nullable|string',
            'mostDanger' => 'nullable|string',
            'countermeasures' => 'nullable|string',
            'keyWord' => 'nullable|string',
        ]);

        try {
            KytReport::create([
                'user_id' => Auth::id(),
                'company_id' => $request->company_id,
                'department_id' => $request->department_id ?? null,
                'date' => $request->date,
                'projectTitle' => $request->projectTitle,
                'shift' => $request->shift,
                'workingStart' => $request->workingStart,
                'workingEnd' => $request->workingEnd,
                'instructors' => json_decode($request->instructors, true),
                'attendants' => json_decode($request->attendants, true),
                'potentialDangerous' => $request->potentialDangerous,
                'mostDanger' => $request->mostDanger,
                'countermeasures' => $request->countermeasures,
                'keyWord' => $request->keyWord,
                'status' => 0, // Pending
            ]);

            return redirect()->route('kyt_reports.index')->with('success', 'KYT Report berhasil disimpan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan KYT Report.')->withInput();
        }
    }

    public function edit($id)
    {
        $kytReport = KytReport::findOrFail($id);
        $user = Auth::user();

        $companies = ($user->role == 0 || ($user->employee && $user->employee->company->companyType == 1))
            ? Company::all()
            : Company::where('id', $user->employee->company_id)->get();

        return view('kyt_reports.edit', compact('kytReport', 'companies'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'company_id' => 'required|exists:companies,id',
            'projectTitle' => 'required|string|max:255',
            'instructors' => 'nullable|array|max:2',
            'attendants' => 'nullable|array|max:16',
        ]);

        $kytReport = KytReport::findOrFail($id);
        $kytReport->update([
            'company_id' => $request->company_id,
            'projectTitle' => $request->projectTitle,
            'instructors' => json_decode($request->instructors, true),
            'attendants' => json_decode($request->attendants, true),
        ]);

        return redirect()->route('kyt_reports.index')->with('success', 'KYT Report updated successfully.');
    }

    public function destroy($id)
    {
        KytReport::findOrFail($id)->delete();
        return redirect()->route('kyt_reports.index')->with('success', 'KYT Report deleted successfully.');
    }

    // Function to get employees based on selected company
    public function getEmployeesByCompany($company_id)
    {
        $employees = Employee::where('company_id', $company_id)->get(['id', 'nik', 'name']);
        return response()->json($employees);
    }

    // Function to view KYT report and generate PDF
    public function view($id)
    {
        $kytReport = KytReport::with(['department', 'company', 'user'])->findOrFail($id);
    
        // Pastikan instructors dan attendants berupa array satu dimensi
        $instructors = Arr::wrap($kytReport->instructors);
        $attendants = Arr::wrap($kytReport->attendants);
        // dd($instructors); // Debugging sebelum query
       
        
    
        // Ambil data nama karyawan berdasarkan NIK
        $instructorNames = Employee::whereIn('nik', $instructors)->pluck('name')->toArray();
        //  dd($instructorNames); // Debugging setelah query
        $attendantNames = Employee::whereIn('nik', $attendants)->pluck('name')->toArray();
    
        // Format instructors untuk PDF
        $formattedInstructors = [];
        for ($i = 0; $i < 2; $i++) {
            $formattedInstructors["instructors_" . ($i + 1)] = $instructorNames[$i] ?? "-";
        }
        // dd($formattedInstructors);
        
    
        // Format attendants untuk PDF
        $formattedAttendants = [];
        for ($i = 0; $i < 16; $i++) {
            $formattedAttendants["attendants_" . ($i + 1)] = $attendantNames[$i] ?? "-";
        }
    
        $data = array_merge([
            'user_id' => $kytReport->user->name,
            'department_id' => $kytReport->department->name ?? '-',
            'company_name' => $kytReport->company->name ?? '-',
            'title' => $kytReport->projectTitle ?? '-',
            'date' => $kytReport->date,
            'shift' => $kytReport->shift ?? '-',
            'workingStart_workingEnd' => $kytReport->workingStart . " - " . $kytReport->workingEnd,
            'potentialDangerous' => $kytReport->potentialDangerous ?? '-',
            'mostDanger' => $kytReport->mostDanger ?? '-',
            'countermeasures' => $kytReport->countermeasures ?? '-',
            'keyWord' => $kytReport->keyWord ?? '-',
            'checkedBy' => $kytReport->checkedBy ?? '-',
            'preparedBy' => $kytReport->user->employee->name ?? '-',
            'reviewedBy' => $kytReport->reviewedBy ?? '-',
            'approvedBy1' => $kytReport->approvedBy1 ?? '-',
            'approvedBy2' => $kytReport->approvedBy2 ?? '-',
        ], $formattedInstructors, $formattedAttendants);
        
        // **Tambahkan debugging**
        // dd($data);
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('kyt_reports.pdf', $data)
            ->setPaper('A4', 'portrait');
        
        return $pdf->stream("KYT_Report_{$kytReport->id}.pdf");        
    }

    // Function to approve the KYT Report
    public function approve($kytReportId)
    {
        $user = Auth::user();
        $kytReport = KytReport::findOrFail($kytReportId);
    
        // Cek apakah user memiliki employee_id
        if (!$user->employee_id) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk melakukan approval.');
        }
    
        // Generate signEncryp dengan panjang maksimal 5 karakter
        $signEncryp = substr(Crypt::encryptString(str_pad(strval(rand(1000000000, 9999999999)), 20, 'A', STR_PAD_RIGHT)), 0, 30);
    
        // Simpan tanda tangan di kyt_signs
        $sign = KytSign::create([
            'kyt_report_id' => $kytReport->id,
            'user_id' => $user->id,
            'signEncryp' => $signEncryp,
        ]);
    
        // Update status sesuai flow approval
        $flow = FlowKytReport::where('position_id', $user->employee->position_id)->first();
        if ($flow->flowStatus == 1) {
            $kytReport->checkedBy = $sign->id;
            $kytReport->status = 1;
        } elseif ($flow->flowStatus == 2 ) {
            $kytReport->reviewedBy = $sign->id;
            $kytReport->status = 2;
        } elseif ($flow->flowStatus == 3) {
            $kytReport->approved1By = $sign->id;
            $kytReport->status = 3;
        } elseif ($flow->flowStatus == 4) {
            $kytReport->approved2By = $sign->id;
            $kytReport->status = 4;
        } else {
            return redirect()->back()->with('error', 'Approval tidak dapat diproses.');
        }
        
        // Simpan tanda tangan di kyt_signs
        $sign = KytSign::create([
            'kyt_report_id' => $kytReport->id,
            'user_id' => $user->id,
            'signEncryp' => $signEncryp,
        ]);
                
        $kytReport->save();
       
        return redirect()->route('kyt_reports.index')->with('success', 'Approval berhasil dilakukan.');
    }
    

    // Function to reject the KYT Report
    public function reject(Request $request, $kytReportId)
    {
        $kytReport = KytReport::findOrFail($kytReportId);
        $kytReport->status = 5; // Status rejected
        $kytReport->save();

        return redirect()->back()->with('success', 'Laporan KYT telah ditolak.');
    }
}
