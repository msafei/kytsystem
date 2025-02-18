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
        $user = auth()->user();
    
        // Jika role 0 (full access), tampilkan semua company
        if ($user->role == 0) {
            $companies = \App\Models\Company::all();
        } else {
            // Jika companyType = 1 (Main Company), tampilkan semua
            if ($user->company->companyType == 1) {
                $companies = \App\Models\Company::all();
            } else {
                // Jika bukan Main Company, hanya tampilkan company milik user
                $companies = \App\Models\Company::where('id', $user->company_id)->get();
            }
        }
    
        $departments = \App\Models\Department::all(); // Departemen akan disaring di front-end
        $positions = []; // Akan dimuat berdasarkan companyType di front-end
    
        return view('employees.create', compact('companies', 'departments', 'positions'));
    }      

    public function getPositionsByCompany(Request $request)
    {
        $company = \App\Models\Company::find($request->company_id);

        if (!$company) {
            return response()->json([]);
        }

        $positions = \App\Models\Position::where('companyType', $company->companyType)->get();

        return response()->json($positions);
    }


    public function store(Request $request)
    {
        $request->validate([
            'nik' => 'required|string|unique:employees,nik',
            'name' => 'required|string|max:100',
            'position_id' => 'required|exists:positions,id',
            'company_id' => 'required|exists:companies,id',
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
    
    public function generateUsername($name)
    {
        // Hilangkan spasi dan buat username acak
        $username = strtolower(str_replace(' ', '', $name));
        $randomString = substr(md5(mt_rand()), 0, 6);
        $username = substr($username, 0, 4) . $randomString;
    
        // Pastikan panjang username antara 4 - 10 karakter
        if (strlen($username) < 4) {
            $username .= mt_rand(1000, 9999);
        } elseif (strlen($username) > 10) {
            $username = substr($username, 0, 10);
        }
    
        // Pastikan username unik di database
        while (\App\Models\User::where('username', $username)->exists()) {
            $username .= substr(md5(mt_rand()), 0, 1);
        }
    
        return $username;
    }
    
    public function addUser($employee_id)
    {
        $employee = \App\Models\Employee::findOrFail($employee_id);
    
        // Cek jika user sudah ada
        if ($employee->user_id) {
            return redirect()->route('employees.index')->with('error', 'User already exists for this employee.');
        }
    
        // Ambil posisi employee untuk mendapatkan defaultRole
        $position = \App\Models\Position::find($employee->position_id);
        $role = $position ? $position->defaultRole : 2; // Default ke role 2 jika tidak ditemukan
    
        // Generate username & password acak
        $username = $this->generateUsername($employee->name);
        $password = bcrypt($username);
    
        // Buat user baru
        $user = \App\Models\User::create([
            'employee_id' => $employee->id,
            'username' => $username,
            'password' => $password,
            'role' => $role,
        ]);
    
        // Update Employee dengan User ID yang baru dibuat
        $employee->update(['user_id' => $user->id]);
    
        return redirect()->route('employees.index')->with('success', 'User created successfully.');
    }
    
    

}
