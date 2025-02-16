@extends('templates.app')

@section('title', 'Users')

@section('content')
<div class="container mx-auto mt-8">
    <h2 class="text-2xl font-bold mb-4">Daftar Users</h2>

    <!-- Tombol Tambah User -->
    <a href="{{ route('users.create') }}" class="bg-green-500 text-white px-4 py-2 rounded mb-4 inline-block">
        + Users
    </a>

    <!-- Tabel DataTables -->
    <div class="bg-white p-6 rounded-lg shadow-md">
        <table id="users-table" class="w-full border-collapse">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border px-4 py-2">ID</th>
                    <th class="border px-4 py-2">Username</th>
                    <th class="border px-4 py-2">Status</th>
                    <th class="border px-4 py-2">Role</th>
                    <th class="border px-4 py-2">Created At</th>
                    <th class="border px-4 py-2">Action</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<!-- Tambahkan jQuery & DataTables -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">

<script>
$(document).ready(function() {
    $('#users-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('users.data') }}",
        columns: [
            { data: 'id', name: 'id' },
            { data: 'username', name: 'username' },
            { data: 'status_id', name: 'status_id' },
            { data: 'role', name: 'role' },
            { data: 'created_at', name: 'created_at' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });
});
</script>
@endsection
