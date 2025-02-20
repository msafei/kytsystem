<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FlowKytReport;
use App\Models\Position;
use App\Models\Company;

class FlowKytReportController extends Controller
{
    public function index()
    {
        $flows = FlowKytReport::with('position')->get();
        return view('flow_kyt_reports.index', compact('flows'));
    }

    public function create()
    {
        $user = auth()->user();
    
        // Menentukan daftar perusahaan yang bisa dipilih user
        if ($user->role == 0) {
            $companies = Company::all();
        } else {
            $companies = Company::where('id', $user->employee->company_id)->get();
        }
    
        // Jika companyType = 1, tampilkan semua posisi sesuai companyType
        $positions = Position::where('companyType', 1)->get();
    
        return view('flow_kyt_reports.create', compact('companies', 'positions'));
    }
    
    public function store(Request $request)
    {
    
        // Validasi dan penyimpanan
        $request->validate([
            'flowStatus' => 'required|integer|in:1,2,3,4',
            'companyType' => 'required|in:1,2',
            'position_id' => 'required|exists:positions,id',
        ]);
    
        FlowKytReport::create([
            'flowStatus' => $request->flowStatus,
            'companyType' => $request->companyType,
            'position_id' => $request->position_id,
        ]);
    
        return redirect()->route('flow_kyt_reports.index')->with('success', 'Flow KYT Report created successfully.');
    }
    
    
    
    public function getPositionsByCompanyType(Request $request)
    {
        $positions = Position::where('companyType', $request->companyType)->get();
        return response()->json($positions);
    }
    

    public function destroy($id)
    {
        FlowKytReport::findOrFail($id)->delete();
        return redirect()->route('flow_kyt_reports.index')->with('success', 'Flow KYT Report deleted successfully.');
    }
}
