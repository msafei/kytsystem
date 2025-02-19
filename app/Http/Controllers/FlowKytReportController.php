<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FlowKytReport;
use App\Models\Position;

class FlowKytReportController extends Controller
{
    public function index()
    {
        $flows = FlowKytReport::with('position')->get();
        return view('flow_kyt_reports.index', compact('flows'));
    }

    public function create()
    {
        $positions = Position::where('companyType', 1)->get();
        return view('flow_kyt_reports.create', compact('positions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'flow' => 'required|integer|in:1,2,3,4',
            'position_id' => 'required|exists:positions,id',
        ]);

        FlowKytReport::create($request->all());

        return redirect()->route('flow_kyt_reports.index')->with('success', 'Flow KYT Report added successfully.');
    }

    public function destroy($id)
    {
        FlowKytReport::findOrFail($id)->delete();
        return redirect()->route('flow_kyt_reports.index')->with('success', 'Flow KYT Report deleted successfully.');
    }
}
