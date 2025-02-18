<?php

namespace App\Http\Controllers;

use App\Models\KytReport;
use App\Models\Company;
use App\Models\Employee;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KytReportController extends Controller
{
    public function index()
    {
        $kytReports = KytReport::with('user', 'company', 'department')->get();
        return view('kyt_reports.index', compact('kytReports'));
    }

    public function create()
    {
        $user = auth()->user();

        // Ambil semua company jika user role = 0 atau companyType = 1
        if ($user->role == 0 || (isset($user->employee) && $user->employee->company->companyType == 1)) {
            $companies = Company::all();
        } else {
            $companies = Company::where('id', $user->employee->company_id)->get();
        }

        // Ambil semua departemen
        $departments = Department::all();

        return view('kyt_reports.create', compact('companies', 'departments'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'company_id' => 'required|exists:companies,id',
            'departement_id' => 'nullable|exists:departments,id',
            'projectTitle' => 'required|string|max:255',
            'instructors' => 'required|array|min:1|max:2',
            'attendants' => 'required|array|min:1|max:16',
        ]);
    
        try {
            \Log::info('Instructors:', $request->input('instructors'));
            \Log::info('Attendants:', $request->input('attendants'));
    
            // Konversi data yang dikirim sebagai array string ke dalam array JSON
            $instructors = collect($request->input('instructors'))->map(function ($instructor) {
                return ['nik' => $instructor, 'name' => '']; // Nama dikosongkan karena tidak dikirim
            });
    
            $attendants = collect($request->input('attendants'))->map(function ($attendant) {
                return ['nik' => $attendant, 'name' => '']; // Nama dikosongkan karena tidak dikirim
            });
    
            KytReport::create([
                'user_id' => auth()->id(),
                'company_id' => $request->input('company_id'),
                'departement_id' => $request->input('departement_id') ?? null,
                'projectTitle' => $request->input('projectTitle'),
                'instructors' => json_encode($instructors), // Simpan sebagai JSON
                'attendants' => json_encode($attendants), // Simpan sebagai JSON
                'status' => 0, // Pending
            ]);
    
            return redirect()->route('kyt_reports.index')->with('success', 'KYT Report berhasil disimpan.');
        } catch (\Exception $e) {
            \Log::error('Error saving KYT Report: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan KYT Report.')->withInput();
        }
    }
    
    

    public function edit(KytReport $kytReport)
    {
        $user = Auth::user();

        $companies = ($user->role == 0 || ($user->employee && $user->employee->company->companyType == 1))
            ? Company::all()
            : Company::where('id', $user->employee->company_id)->get();

        $departments = Department::all();

        return view('kyt_reports.edit', compact('kytReport', 'companies', 'departments'));
    }

    public function update(Request $request, KytReport $kytReport)
    {
        $request->validate([
            'company_id' => 'required|exists:companies,id',
            'departement_id' => 'nullable|exists:departments,id',
            'projectTitle' => 'required|string|max:255',
            'instructors' => 'nullable|array|max:2',
            'attendants' => 'nullable|array|max:16',
        ]);

        $instructors = collect($request->input('instructors'))->map(function ($instructor) {
            return [
                'nik' => $instructor['nik'],
                'name' => $instructor['name'],
            ];
        })->toArray();

        $attendants = collect($request->input('attendants'))->map(function ($attendant) {
            return [
                'nik' => $attendant['nik'],
                'name' => $attendant['name'],
            ];
        })->toArray();

        $kytReport->update([
            'company_id' => $request->company_id,
            'departement_id' => $request->departement_id ?? null,
            'projectTitle' => $request->projectTitle,
            'instructors' => $instructors,
            'attendants' => $attendants,
        ]);

        return redirect()->route('kyt_reports.index')->with('success', 'KYT Report updated successfully.');
    }

    public function destroy(KytReport $kytReport)
    {
        $kytReport->delete();
        return redirect()->route('kyt_reports.index')->with('success', 'KYT Report deleted successfully.');
    }

    public function getEmployeesByCompany($company_id)
    {
        $employees = Employee::where('company_id', $company_id)->get(['id', 'nik', 'name']);
        return response()->json($employees);
    }
}
