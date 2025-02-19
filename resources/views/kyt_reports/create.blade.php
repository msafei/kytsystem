@extends('templates.app')

@section('title', 'Add KYT Report')

@section('content')
<div class="container mx-auto mt-6">
    <h1 class="text-2xl font-bold mb-4">Create KYT Report</h1>

    @if (session('success'))
        <div class="bg-green-500 text-white p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-500 text-white p-3 rounded mb-4">
            <strong>Error:</strong> {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
    <div class="bg-red-500 text-white p-3 rounded mb-4">
        <strong>Terjadi kesalahan:</strong>
        <ul>
            @foreach ($errors->all() as $error)
                <li>- {{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('kyt_reports.store') }}" method="POST" id="kytForm">
        @csrf

        <!-- Date -->
        <div>
            <label>Date</label>
            <input type="date" name="date" value="{{ date('Y-m-d') }}" class="border w-full p-2 rounded bg-gray-200" readonly>
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

        <!-- Project Title -->
        <div>
            <label>Project Title</label>
            <input type="text" name="projectTitle" class="border w-full p-2 rounded" required>
        </div>

        <!-- Department Selection -->
        <div>
            <label>Department</label>
            <select name="department_id" id="department_id" class="border w-full p-2 rounded">
                <option value="" disabled selected>-- Select Department --</option>
                @foreach($departments as $department)
                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Shift Selection -->
        <div>
            <label>Shift</label>
            <select name="shift" class="border w-full p-2 rounded">
                <option value="" selected>-- Select Shift --</option>
                <option value="1">Shift 1</option>
                <option value="2">Shift 2</option>
                <option value="3">Shift 3</option>
            </select>
        </div>
        
        <!-- Working Time -->
        <div>
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
                <button type="button" id="add_instructor" class="bg-green-500 text-white px-3 py-1 rounded">+</button>
            </div>
            <table class="w-full mt-2 border">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NIK</th>
                        <th>Name</th>
                        <th>Position</th>
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
                <button type="button" id="add_attendant" class="bg-green-500 text-white px-3 py-1 rounded">+</button>
            </div>
            <table class="w-full mt-2 border">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NIK</th>
                        <th>Name</th>
                        <th>Position</th>
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
document.addEventListener("DOMContentLoaded", function() {
    document.getElementById("company_id").addEventListener("change", function() {
        fetch(`/kyt_reports/get-employees/${this.value}`)
            .then(response => response.json())
            .then(data => {
                let instructorSelect = document.getElementById("instructor_select");
                let attendantSelect = document.getElementById("attendant_select");

                instructorSelect.innerHTML = `<option value="" disabled selected>-- Select Instructor --</option>`;
                attendantSelect.innerHTML = `<option value="" disabled selected>-- Select Attendant --</option>`;

                data.forEach(employee => {
                    let option = `<option value="${employee.nik}" data-name="${employee.name}" data-position="${employee.position || '-'}">
                                    ${employee.name}
                                  </option>`;
                    instructorSelect.innerHTML += option;
                    attendantSelect.innerHTML += option;
                });
            });
    });

    let instructorData = [];
    let attendantData = [];

    function updateTable(tableId, dataArray, inputId, maxLimit, addButtonId) {
        let table = document.getElementById(tableId);
        table.innerHTML = "";
        dataArray.forEach((employee, index) => {
            let row = table.insertRow();
            row.innerHTML = `<td>${index + 1}</td>
                             <td>${employee.nik}</td>
                             <td>${employee.name}</td>
                             <td>${employee.position}</td>
                             <td><button type="button" class="remove-btn bg-red-500 text-white px-2 py-1 rounded" data-index="${index}">X</button></td>`;
        });

        // Simpan hanya NIK ke input hidden
        document.getElementById(inputId).value = JSON.stringify(dataArray.map(e => e.nik));

        // Disable tombol jika mencapai batas
        document.getElementById(addButtonId).disabled = dataArray.length >= maxLimit;
    }

    document.getElementById("add_instructor").addEventListener("click", function() {
        let select = document.getElementById("instructor_select");
        let nik = select.value;
        let name = select.options[select.selectedIndex]?.dataset.name;
        let position = select.options[select.selectedIndex]?.dataset.position || "-";

        if (nik && !instructorData.find(item => item.nik === nik)) {
            instructorData.push({ nik, name, position });
            updateTable("instructor_table", instructorData, "instructors_data", 2, "add_instructor");
        }
    });

    document.getElementById("add_attendant").addEventListener("click", function() {
        let select = document.getElementById("attendant_select");
        let nik = select.value;
        let name = select.options[select.selectedIndex]?.dataset.name;
        let position = select.options[select.selectedIndex]?.dataset.position || "-";

        if (nik && !attendantData.find(item => item.nik === nik)) {
            attendantData.push({ nik, name, position });
            updateTable("attendant_table", attendantData, "attendants_data", 16, "add_attendant");
        }
    });

    document.getElementById("instructor_table").addEventListener("click", function(event) {
        if (event.target.classList.contains("remove-btn")) {
            let index = event.target.getAttribute("data-index");
            instructorData.splice(index, 1);
            updateTable("instructor_table", instructorData, "instructors_data", 2, "add_instructor");
        }
    });

    document.getElementById("attendant_table").addEventListener("click", function(event) {
        if (event.target.classList.contains("remove-btn")) {
            let index = event.target.getAttribute("data-index");
            attendantData.splice(index, 1);
            updateTable("attendant_table", attendantData, "attendants_data", 16, "add_attendant");
        }
    });
});
    </script>       
    @endsection