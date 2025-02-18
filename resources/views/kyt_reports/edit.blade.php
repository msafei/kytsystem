@extends('templates.app')

@section('title', 'Edit KYT Report')

@section('content')
<div class="container mx-auto mt-6">
    <h1 class="text-2xl font-bold mb-4">Edit KYT Report</h1>

    <form action="{{ route('kyt_reports.update', $kytReport->id) }}" method="POST" class="bg-white p-6 shadow-md rounded-md">
        @csrf
        @method('PUT')

        <!-- Company Selection -->
        <div>
            <label>Company</label>
            <select name="company_id" id="company_id" class="border w-full p-2 rounded">
                @foreach($companies as $company)
                    <option value="{{ $company->id }}" {{ $kytReport->company_id == $company->id ? 'selected' : '' }}>{{ $company->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Project Title -->
        <div>
            <label>Project Title</label>
            <input type="text" name="projectTitle" value="{{ $kytReport->projectTitle }}" class="border w-full p-2 rounded" required>
        </div>

        <!-- Instructor Selection -->
        <div>
            <label>Instructor</label>
            <div class="flex space-x-2">
                <select id="instructor_select" class="border p-2 rounded"></select>
                <button type="button" id="add_instructor" class="bg-green-500 text-white px-3 py-1 rounded">+</button>
            </div>
            <table class="w-full mt-2 border">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NIK</th>
                        <th>Name</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="instructor_table"></tbody>
            </table>
        </div>

        <!-- Attendant Selection -->
        <div>
            <label>Attendant</label>
            <div class="flex space-x-2">
                <select id="attendant_select" class="border p-2 rounded"></select>
                <button type="button" id="add_attendant" class="bg-green-500 text-white px-3 py-1 rounded">+</button>
            </div>
            <table class="w-full mt-2 border">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NIK</th>
                        <th>Name</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="attendant_table"></tbody>
            </table>
        </div>

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded mt-4">Update</button>
    </form>
</div>

<script>
    document.getElementById("company_id").addEventListener("change", function() {
        fetch(`/kyt_reports/get-employees/${this.value}`)
            .then(response => response.json())
            .then(data => {
                let instructorDropdown = document.getElementById("instructor_select");
                let attendantDropdown = document.getElementById("attendant_select");
                instructorDropdown.innerHTML = "";
                attendantDropdown.innerHTML = "";

                data.forEach(employee => {
                    let option = `<option value="${employee.id}">${employee.nik} - ${employee.name}</option>`;
                    instructorDropdown.innerHTML += option;
                    attendantDropdown.innerHTML += option;
                });
            });
    });
</script>
@endsection
