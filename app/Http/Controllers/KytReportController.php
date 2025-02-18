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
            'instructors' => 'required|string', // JSON string dari form
            'attendants' => 'required|string', // JSON string dari form
        ]);

        try {
            // Decode JSON dari input form
            $instructors = json_decode($request->input('instructors'), true);
            $attendants = json_decode($request->input('attendants'), true);

            // Pastikan instructors dan attendants dalam format array
            if (!is_array($instructors) || !is_array($attendants)) {
                return redirect()->back()->with('error', 'Format data instructors dan attendants tidak valid.')->withInput();
            }

            // Simpan data KYT Report
            KytReport::create([
                'user_id' => auth()->id(),
                'company_id' => $request->input('company_id'),
                'departement_id' => $request->input('departement_id') ?? null,
                'projectTitle' => $request->input('projectTitle'),
                'instructors' => $instructors, // Simpan dalam format array (otomatis dikonversi ke JSON)
                'attendants' => $attendants, // Simpan dalam format array (otomatis dikonversi ke JSON)
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

        return view('kyt_reports.edit', compact('kytReport', 'companies'));
    }

    public function update(Request $request, KytReport $kytReport)
    {
        $request->validate([
            'company_id' => 'required|exists:companies,id',
            'projectTitle' => 'required|string|max:255',
            'instructors' => 'required|string',
            'attendants' => 'required|string',
        ]);

        $instructors = json_decode($request->input('instructors'), true);
        $attendants = json_decode($request->input('attendants'), true);

        if (!is_array($instructors) || !is_array($attendants)) {
            return redirect()->back()->with('error', 'Format data instructors dan attendants tidak valid.')->withInput();
        }

        $kytReport->update([
            'company_id' => $request->company_id,
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
