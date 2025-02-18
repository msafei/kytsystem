@extends('templates.app')

@section('title', 'Edit KYT Report')

@section('content')
<div class="container mx-auto mt-6">
    <h1 class="text-2xl font-bold mb-4">Edit KYT Report</h1>

    @if ($errors->any())
    <div class="bg-red-500 text-white p-3 rounded">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('kyt_reports.update', $kytReport->id) }}" method="POST" id="kytForm">
        @csrf
        @method('PUT')

        <!-- Hidden Fields -->
        <input type="hidden" name="id" value="{{ $kytReport->id }}">
        <input type="hidden" name="user_id" value="{{ $kytReport->user_id }}">
        <input type="hidden" name="reviewedBy">
        <input type="hidden" name="approvedBy1">
        <input type="hidden" name="approvedBy2">
        <input type="hidden" name="status" value="{{ $kytReport->status }}">

        <!-- Date -->
        <div>
            <label>Date</label>
            <input type="text" name="date" class="border w-full p-2 rounded bg-gray-200" value="{{ $kytReport->date }}" readonly>
        </div>

        <!-- Company Selection -->
        <div>
            <label>Company</label>
            <select name="company_id" id="company_id" class="border w-full p-2 rounded">
                @foreach($companies as $company)
                    <option value="{{ $company->id }}" data-company-type="{{ $company->companyType }}" 
                        {{ $kytReport->company_id == $company->id ? 'selected' : '' }}>
                        {{ $company->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Project Title -->
        <div>
            <label>Project Title</label>
            <input type="text" name="projectTitle" class="border w-full p-2 rounded" value="{{ $kytReport->projectTitle }}" required>
        </div>

        <!-- Department Selection -->
        <div>
            <label>Department</label>
            <select name="departement_id" id="departement_id" class="border w-full p-2 rounded">
                @foreach($departments as $department)
                    <option value="{{ $department->id }}" {{ $kytReport->departement_id == $department->id ? 'selected' : '' }}>
                        {{ $department->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Working Time -->
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label>Working Start</label>
                <input type="time" name="workingStart" class="border w-full p-2 rounded" value="{{ $kytReport->workingStart }}" required>
            </div>
            <div>
                <label>Working End</label>
                <input type="time" name="workingEnd" class="border w-full p-2 rounded" value="{{ $kytReport->workingEnd }}" required>
            </div>
        </div>

        <!-- Instructor Selection -->
        <div>
            <label>Instructor</label>
            <div class="flex space-x-2">
                <select id="instructor_select" class="border p-2 rounded">
                    <option value="" disabled selected>-- Select Instructor --</option>
                </select>
                <button type="button" id="add_instructor" class="bg-green-500 text-white px-3 py-1 rounded">+</button>
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
            <input type="hidden" name="instructors" id="instructors_data" value="{{ json_encode($kytReport->instructors) }}">
        </div>

        <!-- Attendant Selection -->
        <div>
            <label>Attendant</label>
            <div class="flex space-x-2">
                <select id="attendant_select" class="border p-2 rounded">
                    <option value="" disabled selected>-- Select Attendant --</option>
                </select>
                <button type="button" id="add_attendant" class="bg-green-500 text-white px-3 py-1 rounded">+</button>
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
            <input type="hidden" name="attendants" id="attendants_data" value="{{ json_encode($kytReport->attendants) }}">
        </div>

        <!-- Textareas -->
        <div>
            <label>Potential Dangerous</label>
            <textarea name="potentialDangerous" class="border w-full p-2 rounded">{{ $kytReport->potentialDangerous }}</textarea>
        </div>

        <div>
            <label>Most Danger</label>
            <textarea name="mostDanger" class="border w-full p-2 rounded">{{ $kytReport->mostDanger }}</textarea>
        </div>

        <div>
            <label>Countermeasures</label>
            <textarea name="countermeasures" class="border w-full p-2 rounded">{{ $kytReport->countermeasures }}</textarea>
        </div>

        <div>
            <label>Keyword</label>
            <textarea name="keyWord" class="border w-full p-2 rounded">{{ $kytReport->keyWord }}</textarea>
        </div>

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded mt-4">Update</button>
    </form>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        let companyId = document.getElementById("company_id").value;
        if (companyId) {
            loadEmployees(companyId);
        }
    
        document.getElementById("company_id").addEventListener("change", function() {
            loadEmployees(this.value);
        });
    
        function loadEmployees(companyId) {
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
                });
        }
    
        let instructorData = JSON.parse(document.getElementById("instructors_data").value || "[]");
        let attendantData = JSON.parse(document.getElementById("attendants_data").value || "[]");
    
        function renderTable(tableId, dataArray, inputId) {
            let table = document.getElementById(tableId);
            table.innerHTML = "";
            dataArray.forEach((nik, index) => {
                let row = table.insertRow();
                row.innerHTML = `<td>${index + 1}</td><td>${nik}</td><td><button type="button" class="remove-btn" data-index="${index}">X</button></td>`;
            });
            document.getElementById(inputId).value = JSON.stringify(dataArray);
        }
    
        renderTable("instructor_table", instructorData, "instructors_data");
        renderTable("attendant_table", attendantData, "attendants_data");
    
        document.getElementById("add_instructor").addEventListener("click", function() {
            let select = document.getElementById("instructor_select");
            let nik = select.value;
            if (nik && instructorData.length < 2) {
                instructorData.push(nik);
                renderTable("instructor_table", instructorData, "instructors_data");
            }
        });
    
        document.getElementById("add_attendant").addEventListener("click", function() {
            let select = document.getElementById("attendant_select");
            let nik = select.value;
            if (nik && attendantData.length < 16) {
                attendantData.push(nik);
                renderTable("attendant_table", attendantData, "attendants_data");
            }
        });
    
        document.getElementById("instructor_table").addEventListener("click", function(event) {
            if (event.target.classList.contains("remove-btn")) {
                let index = event.target.getAttribute("data-index");
                instructorData.splice(index, 1);
                renderTable("instructor_table", instructorData, "instructors_data");
            }
        });
    
        document.getElementById("attendant_table").addEventListener("click", function(event) {
            if (event.target.classList.contains("remove-btn")) {
                let index = event.target.getAttribute("data-index");
                attendantData.splice(index, 1);
                renderTable("attendant_table", attendantData, "attendants_data");
            }
        });
    });
    </script>
@endsection
