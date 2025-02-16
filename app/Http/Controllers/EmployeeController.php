<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Employee;
use App\Models\User;
use App\Models\Position;
use App\Models\Company;
use App\Models\Department;


class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::with(['user', 'position', 'company', 'department'])->get();
        return view('employees.index', compact('employees'));
    }

    public function create()
    {
        $users = User::all();
        $positions = Position::all();
        $companies = Company::all();
        $departments = Department::all();
        return view('employees.create', compact('users', 'positions', 'companies', 'departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nik' => 'required|string|unique:employees,nik',
            'name' => 'required|string|max:100',
            'position_id' => 'required|exists:positions,id',
            'company_id' => 'required|exists:companies,id',
            'department_id' => 'required|exists:departments,id',
        ]);
    
        // Cek role user untuk menentukan status
        $status = 1; // Default Active
        $user = auth()->user();
    
        if ($user->role == 1) {
            $status = 1;
        } elseif ($user->employee_id && $user->company->companyType == 2) {
            $status = 2; // Pending
        }
    
        Employee::create(array_merge($request->all(), ['status' => $status]));
    
        return redirect()->route('employees.index')->with('success', 'Employee added successfully.');
    }
    

    public function edit($id)
    {
        $employee = Employee::findOrFail($id);
        $positions = Position::all();
        $companies = Company::all();
        $departments = Department::all();
        return view('employees.edit', compact('employee', 'positions', 'companies', 'departments'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nik' => 'required|string|unique:employees,nik,'.$id,
            'name' => 'required|string|max:100',
            'position_id' => 'required|exists:positions,id',
            'company_id' => 'required|exists:companies,id',
            'department_id' => 'required|exists:departments,id',
            'status' => 'required|in:0,1',
        ]);

        $employee = Employee::findOrFail($id);
        $employee->update($request->all());

        return redirect()->route('employees.index')->with('success', 'Employee updated successfully.');
    }

    public function destroy($id)
    {
        Employee::findOrFail($id)->delete();
        return redirect()->route('employees.index')->with('success', 'Employee deleted successfully.');
    }

    public function createUser($id)
    {
        $employee = Employee::findOrFail($id);

        if ($employee->user) {
            return redirect()->route('employees.index')->with('error', 'User already exists for this employee.');
        }

        $username = strtolower(str_replace(' ', '', $employee->name)) . $employee->nik;
        $password = Hash::make($username);
        $role = $employee->position->defaultRole; // Mengambil defaultRole dari tabel position

        $user = User::create([
            'employee_id' => $employee->id,
            'username' => $username,
            'password' => $password,
            'role' => $role,
            'status_id' => 1, // Default active
        ]);

        return redirect()->route('employees.index')->with('success', 'User created successfully. Username: ' . $user->username);
    }

}
