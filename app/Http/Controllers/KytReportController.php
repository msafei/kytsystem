<?php

namespace App\Http\Controllers;

use App\Models\KytReport;
use App\Models\Company;
use App\Models\Department;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade as Pdf;
use Illuminate\Support\Arr;


class KytReportController extends Controller
{
    public function index()
    {
        $kytReports = KytReport::with('user', 'company', 'department')->get();
        return view('kyt_reports.index', compact('kytReports'));
    }

    public function view($id)
{
    $kytReport = KytReport::with(['department', 'company', 'user'])->findOrFail($id);

    // Pastikan instructors dan attendants berupa array satu dimensi
    $instructors = is_array($kytReport->instructors) ? $kytReport->instructors : json_decode($kytReport->instructors, true) ?? [];
    $attendants = is_array($kytReport->attendants) ? $kytReport->attendants : json_decode($kytReport->attendants, true) ?? [];

    // Pastikan hanya NIK yang digunakan dalam pencarian
    $instructors = array_filter($instructors, fn($nik) => is_string($nik));
    $attendants = array_filter($attendants, fn($nik) => is_string($nik));

    // Ambil data lengkap berdasarkan NIK
    $instructorData = Employee::whereIn('nik', $instructors)
        ->with('position') // Pastikan ada relasi dengan tabel positions
        ->get(['nik', 'name', 'position_id']);

    $attendantData = Employee::whereIn('nik', $attendants)
        ->with('position')
        ->get(['nik', 'name', 'position_id']);

    // Format instructors
    $formattedInstructors = [];
    for ($i = 0; $i < 2; $i++) {
        if (isset($instructorData[$i])) {
            $positionName = optional($instructorData[$i]->position)->name ?? '-';
            $formattedInstructors["instructors " . ($i + 1)] = "{$instructorData[$i]->nik} - {$instructorData[$i]->name} ({$positionName})";
        } else {
            $formattedInstructors["instructors " . ($i + 1)] = "Null";
        }
    }

    // Format attendants
    $formattedAttendants = [];
    for ($i = 0; $i < 16; $i++) {
        if (isset($attendantData[$i])) {
            $positionName = optional($attendantData[$i]->position)->name ?? '-';
            $formattedAttendants["attendants " . ($i + 1)] = "{$attendantData[$i]->nik} - {$attendantData[$i]->name} ({$positionName})";
        } else {
            $formattedAttendants["attendants " . ($i + 1)] = "-";
        }
    }

    // Data untuk PDF
    $data = array_merge([
        'user_id' => $kytReport->user->name ?? '-',
        'department_id' => $kytReport->department->name ?? '-',
        'company_name' => $kytReport->company->name ?? '-',
        'date' => $kytReport->date,
        'shift' => $kytReport->shift,
        'workingStart_workingEnd' => $kytReport->workingStart . " - " . $kytReport->workingEnd,
        'potentialDangerous' => $kytReport->potentialDangerous,
        'mostDanger' => $kytReport->mostDanger,
        'countermeasures' => $kytReport->countermeasures,
        'keyWord' => $kytReport->keyWord,
        'CheckedBy' => $kytReport->user->employee->name ?? '-',
        'preparedBy' => $kytReport->user->name ?? '-',
        'reviewedBy' => $kytReport->reviewedBy ?? '-',
        'approvedBy1' => $kytReport->approvedBy1 ?? '-',
        'approvedBy2' => $kytReport->approvedBy2 ?? '-',
    ], $formattedInstructors, $formattedAttendants);

    // Generate PDF
    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('kyt_reports.pdf', $data)
        ->setPaper('A4', 'portrait');

    return $pdf->stream("KYT_Report_{$kytReport->id}.pdf");
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
            'company_id' => 'required|exists:companies,id',
            'department_id' => 'nullable|exists:departments,id',
            'date' => 'required|date',
            'projectTitle' => 'required|string|max:255',
            'shift' => 'nullable|integer|in:1,2,3',
            'workingStart' => 'required',
            'workingEnd' => 'required',
            'instructors' => 'required|json',
            'attendants' => 'required|json',
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

    public function edit(KytReport $kytReport)
    {
        $companies = Company::all();
        $departments = Department::all();

        return view('kyt_reports.edit', compact('kytReport', 'companies', 'departments'));
    }

    public function update(Request $request, KytReport $kytReport)
    {
        $request->validate([
            'company_id' => 'required|exists:companies,id',
            'department_id' => 'nullable|exists:departments,id',
            'date' => 'required|date',
            'projectTitle' => 'required|string|max:255',
            'workingStart' => 'required',
            'workingEnd' => 'required',
            'instructors' => 'required|json',
            'attendants' => 'required|json',
            'potentialDangerous' => 'nullable|string',
            'mostDanger' => 'nullable|string',
            'countermeasures' => 'nullable|string',
            'keyWord' => 'nullable|string',
        ]);

        $kytReport->update([
            'company_id' => $request->company_id,
            'department_id' => $request->department_id ?? null,
            'date' => $request->date,
            'projectTitle' => $request->projectTitle,
            'workingStart' => $request->workingStart,
            'workingEnd' => $request->workingEnd,
            'instructors' => json_decode($request->instructors, true),
            'attendants' => json_decode($request->attendants, true),
            'potentialDangerous' => $request->potentialDangerous,
            'mostDanger' => $request->mostDanger,
            'countermeasures' => $request->countermeasures,
            'keyWord' => $request->keyWord,
        ]);

        return redirect()->route('kyt_reports.index')->with('success', 'KYT Report berhasil diperbarui.');
    }

    public function destroy(KytReport $kytReport)
    {
        $kytReport->delete();
        return redirect()->route('kyt_reports.index')->with('success', 'KYT Report berhasil dihapus.');
    }

    /**
     * Mengambil daftar employees berdasarkan company_id untuk dropdown Instructor dan Attendant.
     */
    public function getEmployeesByCompany($company_id)
    {
        $employees = Employee::where('company_id', $company_id)
            ->leftJoin('positions', 'employees.position_id', '=', 'positions.id')
            ->select('employees.id', 'employees.nik', 'employees.name', 'positions.name as position')
            ->get();
    
        return response()->json($employees);
    }
    
}
