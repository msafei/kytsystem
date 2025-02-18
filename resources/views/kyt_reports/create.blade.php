@extends('templates.app')

@section('title', 'Add KYT Report')

@section('content')
<div class="container mx-auto mt-6">
    <h1 class="text-2xl font-bold mb-4">Create KYT Report</h1>

    @if ($errors->any())
    <div class="bg-red-500 text-white p-3 rounded">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('kyt_reports.store') }}" method="POST" id="kytForm">
        @csrf

        <!-- Project Title -->
        <div>
            <label>Project Title</label>
            <input type="text" name="projectTitle" class="border w-full p-2 rounded" required>
        </div>

        <!-- Company Selection -->
        <div>
            <label>Company</label>
            <select name="company_id" id="company_id" class="border w-full p-2 rounded">
                <option value="" disabled selected>-- Select Company --</option>
                @foreach($companies as $company)
                    <option value="{{ $company->id }}" data-company-type="{{ $company->companyType }}">{{ $company->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Department Selection -->
        <div id="department_section">
            <label>Department</label>
            <select name="departement_id" id="departement_id" class="border w-full p-2 rounded">
                <option value="" disabled selected>-- Select Department --</option>
                @foreach($departments as $department)
                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Instructor Selection -->
        <div>
            <label>Instructor</label>
            <div class="flex space-x-2">
                <select id="instructor_select" class="border p-2 rounded">
                    <option value="" disabled selected>-- Select Instructor --</option>
                </select>
                <button type="button" id="add_instructor" class="bg-green-500 text-white px-3 py-1 rounded" disabled>+</button>
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
            <input type="hidden" name="instructors" id="instructors_data">
        </div>

        <!-- Attendant Selection -->
        <div>
            <label>Attendant</label>
            <div class="flex space-x-2">
                <select id="attendant_select" class="border p-2 rounded">
                    <option value="" disabled selected>-- Select Attendant --</option>
                </select>
                <button type="button" id="add_attendant" class="bg-green-500 text-white px-3 py-1 rounded" disabled>+</button>
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
            <input type="hidden" name="attendants" id="attendants_data">
        </div>

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded mt-4">Save</button>
    </form>
</div>

<script>
document.getElementById("company_id").addEventListener("change", function() {
    let companyId = this.value;
    let companyType = this.options[this.selectedIndex].dataset.companyType;

    document.getElementById("departement_id").disabled = companyType !== "1";

    fetch(`/kyt_reports/get-employees/${companyId}`)
        .then(response => response.json())
        .then(data => {
            let instructorSelect = document.getElementById("instructor_select");
            let attendantSelect = document.getElementById("attendant_select");

            instructorSelect.innerHTML = '<option value="" disabled selected>-- Select Instructor --</option>';
            attendantSelect.innerHTML = '<option value="" disabled selected>-- Select Attendant --</option>';

            data.forEach(employee => {
                let option = `<option value="${employee.nik}" data-name="${employee.name}">${employee.name}</option>`;
                instructorSelect.innerHTML += option;
                attendantSelect.innerHTML += option;
            });
        });
});

// Enable/Disable Add Buttons
document.getElementById("instructor_select").addEventListener("change", function () {
    document.getElementById("add_instructor").disabled = !this.value;
});
document.getElementById("attendant_select").addEventListener("change", function () {
    document.getElementById("add_attendant").disabled = !this.value;
});

// Array untuk menyimpan data instructor dan attendant
let instructorData = [];
let attendantData = [];

// Tambah Instructor
document.getElementById("add_instructor").addEventListener("click", function() {
    if (instructorData.length < 2) {
        let select = document.getElementById("instructor_select");
        let nik = select.value;
        let name = select.options[select.selectedIndex].dataset.name;

        let table = document.getElementById("instructor_table");
        let row = table.insertRow();
        row.innerHTML = `<td>${instructorData.length + 1}</td><td>${nik}</td><td>${name}</td><td><button type="button" onclick="removeInstructor(${instructorData.length})">X</button></td>`;
        
        instructorData.push(nik);
        document.getElementById("instructors_data").value = JSON.stringify(instructorData);
    }
});

function removeInstructor(index) {
    instructorData.splice(index, 1);
    document.getElementById("instructors_data").value = JSON.stringify(instructorData);
    document.getElementById("instructor_table").deleteRow(index);
}

// Tambah Attendant
document.getElementById("add_attendant").addEventListener("click", function() {
    if (attendantData.length < 16) {
        let select = document.getElementById("attendant_select");
        let nik = select.value;
        let name = select.options[select.selectedIndex].dataset.name;

        let table = document.getElementById("attendant_table");
        let row = table.insertRow();
        row.innerHTML = `<td>${attendantData.length + 1}</td><td>${nik}</td><td>${name}</td><td><button type="button" onclick="removeAttendant(${attendantData.length})">X</button></td>`;
        
        attendantData.push(nik);
        document.getElementById("attendants_data").value = JSON.stringify(attendantData);
    }
});

function removeAttendant(index) {
    attendantData.splice(index, 1);
    document.getElementById("attendants_data").value = JSON.stringify(attendantData);
    document.getElementById("attendant_table").deleteRow(index);
}

// Event listener sebelum submit form
document.getElementById("kytForm").addEventListener("submit", function() {
    document.getElementById("instructors_data").value = JSON.stringify(instructorData);
    document.getElementById("attendants_data").value = JSON.stringify(attendantData);
});
</script>
@endsection
