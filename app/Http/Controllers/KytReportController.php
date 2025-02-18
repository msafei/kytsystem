<?php

namespace App\Http\Controllers;

use App\Models\KytReport;
use App\Models\Company;
use App\Models\Department;
use App\Models\Employee;
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
            'departement_id' => 'nullable|exists:departments,id',
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

        try {
            KytReport::create([
                'user_id' => Auth::id(),
                'company_id' => $request->company_id,
                'departement_id' => $request->departement_id ?? null,
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
            'departement_id' => 'nullable|exists:departments,id',
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
            'departement_id' => $request->departement_id ?? null,
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
        $employees = Employee::where('company_id', $company_id)->get(['id', 'nik', 'name']);
        return response()->json($employees);
    }
}
