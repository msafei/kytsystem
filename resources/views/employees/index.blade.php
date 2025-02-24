@extends('templates.app')

@section('title', 'Employees')

@section('content')
<div class="container mx-auto p-6">
    <h2 class="text-2xl font-bold mb-4">Employee List</h2>
    
    <a href="{{ route('employees.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded">+ Add Employee</a>

    <table class="table-auto w-full mt-4 border">
        <thead>
            <tr class="bg-gray-200">
                <th class="border px-4 py-2">ID</th>
                <th class="border px-4 py-2">NIK</th>
                <th class="border px-4 py-2">Name</th>
                <th class="border px-4 py-2">Position</th>
                <th class="border px-4 py-2">Company</th>
                <th class="border px-4 py-2">Department</th>
                <th class="border px-4 py-2">Username</th>
                <th class="border px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($employees as $employee)
            <tr>
                <td class="border px-4 py-2">{{ $employee->id }}</td>
                <td class="border px-4 py-2">{{ $employee->nik }}</td>
                <td class="border px-4 py-2">{{ $employee->name }}</td>
                <td class="border px-4 py-2">{{ $employee->position->name ?? '-' }}</td>
                <td class="border px-4 py-2">{{ $employee->company->name ?? '-' }}</td>
                <td class="border px-4 py-2">{{ $employee->department->name ?? '-' }}</td>
                <td class="border px-4 py-2">
                    {{ $employee->user ? $employee->user->username : '-' }}
                </td>
                <td class="border px-4 py-2">
                    <!-- Edit Button -->
                    <a href="{{ route('employees.edit', $employee->id) }}" class="bg-yellow-500 text-white px-2 py-1 rounded">Edit</a>
                    
                    <!-- Hapus Button -->
                    <form action="{{ route('employees.destroy', $employee->id) }}" method="POST" class="inline-block">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 text-white px-2 py-1 rounded">Delete</button>
                    </form>

                    <!-- Add User Button (Hanya jika user belum ada) -->
                    @if(!$employee->user_id)
                    <button onclick="confirmAddUser({{ $employee->id }})" class="bg-green-500 text-white px-2 py-1 rounded">Add User</button>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Konfirmasi JavaScript -->
<script>
    function confirmAddUser(employeeId) {
        if (confirm("Are you sure you want to create a user for this employee?")) {
            window.location.href = "/employees/" + employeeId + "/add-user";
        }
    }
</script>

@endsection
