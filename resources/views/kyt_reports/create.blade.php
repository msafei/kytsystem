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

        <!-- Tanggal Hari Ini -->
        <div>
            <label>Date</label>
            <input type="date" name="date" value="{{ date('Y-m-d') }}" class="border w-full p-2 rounded" readonly>
        </div>        

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

        <!-- Working Time -->
        <div id="working_time">
        <label>Working Time</label>            
        <div class="flex space-x-4">
            <div>
                <label>Start</label>
                <input type="time" name="workingStart" class="border p-2 rounded">
            </div>
            <div>
                <label>End</label>
                <input type="time" name="workingEnd" class="border p-2 rounded">
            </div>
        </div>
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
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="attendant_table"></tbody>
            </table>
        </div>


        <!-- Potential Dangerous -->
        <div>
            <label>Potential Dangerous</label>
            <textarea name="potentialDangerous" class="border w-full p-2 rounded"></textarea>
        </div>

        <!-- Most Dangerous -->
        <div>
            <label>Most Dangerous</label>
            <textarea name="mostDanger" class="border w-full p-2 rounded"></textarea>
        </div>

        <!-- Countermeasures -->
        <div>
            <label>Countermeasures</label>
            <textarea name="countermeasures" class="border w-full p-2 rounded"></textarea>
        </div>

        <!-- Keywords -->
        <div>
            <label>Keywords</label>
            <textarea name="keyWord" class="border w-full p-2 rounded"></textarea>
        </div>        

                <!-- Form Hidden -->
                <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                <input type="hidden" name="reviewedBy" value="">
                <input type="hidden" name="approvedBy1" value="">
                <input type="hidden" name="approvedBy2" value="">
                <input type="hidden" name="status" value="0">
                <input type="hidden" name="instructors" id="instructors_data">
                <input type="hidden" name="attendants" id="attendants_data">

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
                let option = `<option value="${employee.nik}">${employee.name}</option>`;
                instructorSelect.innerHTML += option;
                attendantSelect.innerHTML += option;
            });

            document.getElementById("add_instructor").disabled = false;
            document.getElementById("add_attendant").disabled = false;
        });
});

// Array untuk menyimpan data instructor dan attendant (hanya NIK)
let instructorData = [];
let attendantData = [];

// Tambah Instructor
document.getElementById("add_instructor").addEventListener("click", function() {
    if (instructorData.length < 2) {
        let select = document.getElementById("instructor_select");
        let nik = select.value;

        if (!nik || instructorData.includes(nik)) return;

        let table = document.getElementById("instructor_table");
        let row = table.insertRow();
        row.innerHTML = `<td>${instructorData.length + 1}</td><td>${nik}</td>
                         <td><button type="button" onclick="removeInstructor(${instructorData.length})">X</button></td>`;
        
        instructorData.push(nik);
        updateInstructorData();
    }
});

function removeInstructor(index) {
    instructorData.splice(index, 1);
    updateInstructorData();
    document.getElementById("instructor_table").deleteRow(index);
}

// Tambah Attendant
document.getElementById("add_attendant").addEventListener("click", function() {
    if (attendantData.length < 16) {
        let select = document.getElementById("attendant_select");
        let nik = select.value;

        if (!nik || attendantData.includes(nik)) return;

        let table = document.getElementById("attendant_table");
        let row = table.insertRow();
        row.innerHTML = `<td>${attendantData.length + 1}</td><td>${nik}</td>
                         <td><button type="button" onclick="removeAttendant(${attendantData.length})">X</button></td>`;
        
        attendantData.push(nik);
        updateAttendantData();
    }
});

function removeAttendant(index) {
    attendantData.splice(index, 1);
    updateAttendantData();
    document.getElementById("attendant_table").deleteRow(index);
}

// Update Input Hidden dan Tampilkan di Form
function updateInstructorData() {
    document.getElementById("instructors_data").value = JSON.stringify(instructorData);
    document.getElementById("instructors_data_display").value = JSON.stringify(instructorData, null, 2);
}

function updateAttendantData() {
    document.getElementById("attendants_data").value = JSON.stringify(attendantData);
    document.getElementById("attendants_data_display").value = JSON.stringify(attendantData, null, 2);
}

// Event listener sebelum submit form
document.getElementById("kytForm").addEventListener("submit", function(event) {
    document.getElementById("instructors_data").value = JSON.stringify(instructorData);
    document.getElementById("attendants_data").value = JSON.stringify(attendantData);
});
</script>
@endsection
