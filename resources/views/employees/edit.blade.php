@extends('templates.app')

@section('title', 'Edit Employee')

@section('content')
<div class="container mx-auto p-6">
    <div class="bg-white p-8 rounded-lg shadow-md w-2/3 mx-auto">
        <h2 class="text-2xl font-bold mb-6 text-center">Edit Employee</h2>

        <form method="POST" action="{{ route('employees.update', $employee->id) }}">
            @csrf
            @method('POST')

            <!-- NIK -->
            <div class="mb-4">
                <label class="block text-gray-700">NIK</label>
                <input type="text" name="nik" value="{{ $employee->nik }}" required class="w-full px-3 py-2 border rounded">
            </div>

            <!-- Name -->
            <div class="mb-4">
                <label class="block text-gray-700">Name</label>
                <input type="text" name="name" value="{{ $employee->name }}" required class="w-full px-3 py-2 border rounded">
            </div>

            <!-- Position Dropdown -->
            <div class="mb-4">
                <label class="block text-gray-700">Position</label>
                <select name="position_id" required class="w-full px-3 py-2 border rounded">
                    <option value="null">Silahkan Pilih Position</option>
                    @foreach($positions as $position)
                        <option value="{{ $position->id }}" {{ $employee->position_id == $position->id ? 'selected' : '' }}>
                            {{ $position->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Company Dropdown -->
            <div class="mb-4">
                <label class="block text-gray-700">Company</label>
                <select name="company_id" required class="w-full px-3 py-2 border rounded">
                    <option value="null">Silahkan Pilih Company</option>
                    @foreach($companies as $company)
                        <option value="{{ $company->id }}" {{ $employee->company_id == $company->id ? 'selected' : '' }}>
                            {{ $company->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Department Dropdown -->
            <div class="mb-4">
                <label class="block text-gray-700">Department</label>
                <select name="department_id" required class="w-full px-3 py-2 border rounded">
                    <option value="null">Silahkan Pilih Department</option>
                    @foreach($departments as $department)
                        <option value="{{ $department->id }}" {{ $employee->department_id == $department->id ? 'selected' : '' }}>
                            {{ $department->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Status (Hanya untuk Edit) -->
            <div class="mb-4">
                <label class="block text-gray-700">Status</label>
                <select name="status" required class="w-full px-3 py-2 border rounded">
                    <option value="1" {{ $employee->status == 1 ? 'selected' : '' }}>Active</option>
                    <option value="2" {{ $employee->status == 2 ? 'selected' : '' }}>Pending</option>
                </select>
            </div>

            <button type="submit" class="w-full bg-green-500 text-white py-2 rounded">Update Employee</button>
        </form>
    </div>
</div>
@endsection
