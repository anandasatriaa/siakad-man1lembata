@extends('admin.layouts.app')

@section('title', 'Nilai')

@push('css')

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f5f7fa;
            color: #333;
            line-height: 1.6;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .content {
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            padding: 30px;
        }

        .content-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }

        .content-header h2 {
            color: #2c3e50;
            font-size: 1.8rem;
        }

        .filters {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-bottom: 25px;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
        }

        .filter-group label {
            margin-bottom: 8px;
            font-weight: 500;
            color: #555;
        }

        .filter-group select,
        .filter-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background: #f9f9f9;
            font-size: 1rem;
        }

        .filter-group.full-row {
            grid-column: span 2;
        }

        .btn {
            background: #1e88e5;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 500;
            transition: background 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn:hover {
            background: #1565c0;
        }

        .btn-search {
            background: #4caf50;
        }

        .btn-search:hover {
            background: #388e3c;
        }

        .btn-print {
            background: #f57c00;
        }

        .btn-print:hover {
            background: #e65100;
        }

        .card {
            background: #f9f9f9;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 25px;
            border-left: 4px solid #1e88e5;
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .card-title {
            font-size: 1.3rem;
            color: #1e88e5;
        }

        .semester-select {
            padding: 8px 15px;
            border-radius: 6px;
            border: 1px solid #ddd;
            background: white;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th,
        td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        th {
            background-color: #f1f8ff;
            color: #1e88e5;
            font-weight: 600;
        }

        tr:hover {
            background-color: #f9f9f9;
        }

        .student-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .student-info img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }

        .student-name {
            font-weight: 600;
        }

        .student-details {
            font-size: 0.85rem;
            color: #777;
        }

        .subject-code {
            color: #777;
            font-size: 0.9rem;
        }

        .score {
            font-weight: 700;
            color: #2c3e50;
        }

        .grade {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-weight: 600;
            text-align: center;
            min-width: 70px;
        }

        .grade.A {
            background: #e8f5e9;
            color: #2e7d32;
        }

        .grade.B {
            background: #f1f8e9;
            color: #689f38;
        }

        .grade.C {
            background: #fffde7;
            color: #f9a825;
        }

        .grade.D {
            background: #fff3e0;
            color: #ef6c00;
        }

        .grade.E {
            background: #ffebee;
            color: #c62828;
        }

        .summary {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }

        .summary-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
            text-align: center;
            border-top: 4px solid #1e88e5;
        }

        .summary-card h3 {
            font-size: 1.1rem;
            color: #666;
            margin-bottom: 10px;
        }

        .summary-value {
            font-size: 2rem;
            font-weight: 700;
            color: #2c3e50;
        }

        .summary-description {
            color: #777;
            font-size: 0.9rem;
            margin-top: 5px;
        }

        .student-list {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .student-card {
            background: white;
            border-radius: 8px;
            padding: 15px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            width: calc(33.333% - 15px);
            cursor: pointer;
            transition: all 0.3s;
            border: 2px solid transparent;
        }

        .student-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .student-card.active {
            border-color: #1e88e5;
            background: #f1f8ff;
        }

        .student-card-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 10px;
        }

        .student-card img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
        }

        .student-card-name {
            font-weight: 600;
            font-size: 1.1rem;
        }

        .student-card-class {
            font-size: 0.9rem;
            color: #666;
        }

        .student-card-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
            font-size: 0.85rem;
        }

        .student-card-detail {
            background: #f5f5f5;
            padding: 8px;
            border-radius: 5px;
            text-align: center;
        }

        .student-card-detail .label {
            font-size: 0.8rem;
            color: #777;
        }

        .student-card-detail .value {
            font-weight: 600;
            margin-top: 3px;
        }

        @media (max-width: 1100px) {
            .filters {
                grid-template-columns: repeat(2, 1fr);
            }

            .student-card {
                width: calc(50% - 15px);
            }
        }

        @media (max-width: 900px) {
            .filters {
                grid-template-columns: 1fr;
            }

            .filter-group.full-row {
                grid-column: span 1;
            }

            .student-card {
                width: 100%;
            }
        }

        @media (max-width: 600px) {
            .content-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }

            .card-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
        }
    </style>

@endpush

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row align-items-center">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Nilai</h3>
                    <p class="text-subtitle text-muted">Semua informasi mengenai Nilai</p>
                </div>
            </div>
        </div>
        <div class="section">

            <div class="content">
                <div class="content-header">
    <h2><i class="bi bi-bar-chart-line"></i> Laporan Nilai Akademik</h2>
    <div>
        <button class="btn btn-print">
            <i class="bi bi-printer"></i> Cetak Laporan
        </button>
        <button class="btn">
            <i class="bi bi-file-earmark-excel"></i> Export Excel
        </button>
    </div>
</div>

                {{-- Filter form --}}
                <form method="GET" action="{{ route('admin.grade.index') }}" class="filters">
                    {{-- Kelas --}}
                    <select name="class_id" id="kelas">
                        <option value="">Semua Kelas</option>
                        @foreach($classes as $cls)
                            <option value="{{ $cls->id }}" {{ $classId == $cls->id ? 'selected' : '' }}>
                                {{ $cls->name }}
                            </option>
                        @endforeach
                    </select>
                    {{-- Cari Siswa --}}
                    <select name="student_id" id="siswa">
                        <option value="">Semua Siswa</option>
                        @foreach($students as $std)
                            <option value="{{ $std->id }}" {{ optional($selectedStudent)->id == $std->id ? 'selected' : '' }}>
                                {{ $std->full_name }} ({{ $std->nis }})
                            </option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-search">Tampilkan</button>
                </form>

                @if($selectedStudent)
                    {{-- Card Siswa --}}
                    <div class="student-card active">
                        <div class="student-card-header">
                            <img src="{{ $selectedStudent->photo_url }}" alt="Siswa">
                            <div>
                                <div class="student-card-name">{{ $selectedStudent->full_name }}</div>
                                <div class="student-card-class">
                                    {{ $selectedStudent->class->name }} | NIS: {{ $selectedStudent->nis }}
                                </div>
                            </div>
                        </div>
                        <div class="student-card-details">
                            <div class="student-card-detail">
                                <div class="label">Rata-rata</div>
                                <div class="value">{{ number_format($average, 2) }}</div>
                            </div>
                            <div class="student-card-detail">
                                <div class="label">Peringkat</div>
                                <div class="value">{{ $rank }}</div>
                            </div>
                        </div>
                    </div>

                    {{-- Tabel Nilai --}}
                    <div class="card mt-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h3 class="card-title">
                                Daftar Nilai Siswa: {{ $selectedStudent->full_name }}
                                ({{ $selectedStudent->class->name }})
                            </h3>
                            {{-- <select class="semester-select"
                                onchange="location.search='?semester='+this.value+'&class_id='+{{ $classId }}+'&student_id='+{{ $selectedStudent->id }}">
                                <option value="1" {{ $semester==1 ? 'selected' : '' }}>Semester 1</option>
                                <option value="2" {{ $semester==2 ? 'selected' : '' }}>Semester 2</option>
                            </select> --}}
                        </div>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Mata Pelajaran</th>
                                    <th>Guru Pengampu</th>
                                    <th>Nilai Tugas</th>
                                    <th>Nilai UTS</th>
                                    <th>Nilai UAS</th>
                                    <th>Nilai Akhir</th>
                                    <th>Grade</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($gradeRecords as $rec)
                                    <tr>
                                        <td>
                                            {{ $rec->course->name }}
                                            <div class="subject-code">{{ $rec->course->code }}</div>
                                        </td>
                                        <td>{{ $rec->teacher->full_name }}</td>
                                        <td class="score">{{ $rec->assignment_score }}</td>
                                        <td class="score">{{ $rec->mid_exam_score }}</td>
                                        <td class="score">{{ $rec->final_exam_score }}</td>
                                        <td class="score">{{ $rec->final_score }}</td>
                                        <td>
                                            @php
                                                $g = $rec->final_score;
                                                if ($g >= 85)
                                                    $grade = 'A';
                                                elseif ($g >= 70)
                                                    $grade = 'B';
                                                elseif ($g >= 55)
                                                    $grade = 'C';
                                                else
                                                    $grade = 'D';
                                              @endphp
                                            <span class="grade {{ $grade }}">{{ $grade }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Summary --}}
                    <div class="summary d-flex gap-3 mt-4">
                        <div class="summary-card">
                            <h3>Rata‑rata Nilai</h3>
                            <div class="summary-value">{{ number_format($average, 2) }}</div>
                            <div class="summary-description">
                                Dari {{ $gradeRecords->count() }} mata pelajaran
                            </div>
                        </div>

                        <div class="summary-card">
    <h3>Nilai Tertinggi</h3>
    <div class="summary-value">{{ number_format($highest ?? 0, 2) }}</div>
</div>

<div class="summary-card">
    <h3>Nilai Terendah</h3>
    <div class="summary-value">{{ number_format($lowest ?? 0, 2) }}</div>
</div>

                        <div class="summary-card">
                            <h3>Peringkat Kelas</h3>
                            <div class="summary-value">{{ $rank ?? '-' }}</div>
                            <div class="summary-description">
                                Dari {{ count($allInClass) }} siswa
                            </div>
                        </div>
                    </div>
                @endif

                @if(isset($studentsInClass))
                    @foreach($studentsInClass as $student)
                        <div class="student-card active mb-3">
                            <div class="student-card-header">
                                <img src="{{ $student->photo_url }}" alt="Siswa">
                                <div>
                                    <div class="student-card-name">{{ $student->full_name }}</div>
                                    <div class="student-card-class">
                                        {{ $student->class->name }} | NIS: {{ $student->nis }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card mb-4">
                            <div class="card-header">
                                <h3 class="card-title">
                                    Daftar Nilai Siswa: {{ $student->full_name }}
                                </h3>
                            </div>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Mata Pelajaran</th>
                                        <th>Guru Pengampu</th>
                                        <th>Nilai Tugas</th>
                                        <th>Nilai UTS</th>
                                        <th>Nilai UAS</th>
                                        <th>Nilai Akhir</th>
                                        <th>Grade</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($gradeRecords->where('student_id', $student->id) as $rec)
                                        <tr>
                                            <td>
                                                {{ $rec->course->name }}
                                                <div class="subject-code">{{ $rec->course->code }}</div>
                                            </td>
                                            <td>{{ $rec->teacher->full_name }}</td>
                                            <td class="score">{{ $rec->assignment_score }}</td>
                                            <td class="score">{{ $rec->mid_exam_score }}</td>
                                            <td class="score">{{ $rec->final_exam_score }}</td>
                                            <td class="score">{{ $rec->final_score }}</td>
                                            <td>
                                                @php
                                                    $g = $rec->final_score;
                                                    if ($g >= 85)
                                                        $grade = 'A';
                                                    elseif ($g >= 70)
                                                        $grade = 'B';
                                                    elseif ($g >= 55)
                                                        $grade = 'C';
                                                    else
                                                        $grade = 'D';
                                                @endphp
                                                <span class="grade {{ $grade }}">{{ $grade }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @php
                            $records = $gradeRecords->where('student_id', $student->id);
                            $scores = $records->pluck('final_score');
                            $average = $scores->avg();
                            $highest = $scores->max();
                            $lowest = $scores->min();

                            // Hitung peringkat berdasarkan rata-rata semua siswa di kelas
                            $studentAverages = $studentsInClass->mapWithKeys(function ($s) use ($gradeRecords) {
                                $avg = $gradeRecords->where('student_id', $s->id)->pluck('final_score')->avg();
                                return [$s->id => $avg ?? 0]; // gunakan 0 agar tetap masuk ranking, atau bisa pakai null kalau mau diabaikan
                            });

                            $sorted = $studentAverages->sortDesc();
                            $rankings = $sorted->keys()->values();
                            $rank = $rankings->search($student->id) !== false ? $rankings->search($student->id) + 1 : '-';
                        @endphp

                        <div class="summary d-flex gap-3 mb-5">
                            <div class="summary-card">
                                <h3>Rata‑rata Nilai</h3>
                                <div class="summary-value">{{ number_format($average, 2) }}</div>
                                <div class="summary-description">
                                    Dari {{ $records->count() }} mata pelajaran
                                </div>
                            </div>

                            <div class="summary-card">
    <h3>Nilai Tertinggi</h3>
    <div class="summary-value">{{ number_format($highest ?? 0, 2) }}</div>
</div>

<div class="summary-card">
    <h3>Nilai Terendah</h3>
    <div class="summary-value">{{ number_format($lowest ?? 0, 2) }}</div>
</div>

                            <div class="summary-card">
                                <h3>Peringkat Kelas</h3>
                                <div class="summary-value">{{ $rank }}</div>
                                <div class="summary-description">
                                    Dari {{ count($studentAverages) }} siswa
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>

@endsection

@push('js')
    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Sukses',
                text: '{{ session('success') }}',
                showConfirmButton: false,
                timer: 2000
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'warning',
                title: 'Peringatan',
                text: '{{ session('error') }}',
                showConfirmButton: true
            });
        </script>
    @endif
    @if ($errors->any())
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal menyimpan',
                html: `{!! implode('<br>', $errors->all()) !!}`
            });
        </script>
    @endif

    <script>
        const btnPrint = document.querySelector('.btn-print');
        if (btnPrint) {
            btnPrint.addEventListener('click', function () {
                alert('Fitur cetak laporan akan membuka jendela pencetakan');
            });
        }

        const btnSearch = document.querySelector('.btn-search');
        if (btnSearch) {
            btnSearch.addEventListener('click', function () {
                const kelas = document.getElementById('kelas').value;
                const semesterEl = document.getElementById('semester');
                const semester = semesterEl ? semesterEl.value : '-';
                const siswa = document.getElementById('siswa').value;
                const pelajaranEl = document.getElementById('mata-pelajaran');
                const pelajaran = pelajaranEl ? pelajaranEl.value : '';
            });
        }

        const semesterSelect = document.querySelector('.semester-select');
        if (semesterSelect) {
            semesterSelect.addEventListener('change', function () {
                const semester = this.value;
            });
        }

        const studentCards = document.querySelectorAll('.student-card');
        if (studentCards.length) {
            studentCards.forEach(card => {
                card.addEventListener('click', function () {
                    studentCards.forEach(c => c.classList.remove('active'));
                    this.classList.add('active');

                    const studentName = this.querySelector('.student-card-name').textContent;
                    const studentClass = this.querySelector('.student-card-class').textContent.split(' | ')[0];
                    document.querySelector('.card-title .student-name').textContent = studentName;
                });
            });
        }

        const kelasSelect = document.getElementById('kelas');
        const siswaSelect = document.getElementById('siswa');

        if (kelasSelect && siswaSelect) {
            kelasSelect.addEventListener('change', function () {
                const classId = this.value;
                siswaSelect.innerHTML = '<option value="">Memuat siswa...</option>';

                if (classId) {
                    fetch(route('admin.grade.students-by-class', classId))
                        .then(response => response.json())
                        .then(data => {
                            siswaSelect.innerHTML = '<option value="">Semua Siswa</option>';
                            data.forEach(student => {
                                const option = document.createElement('option');
                                option.value = student.id;
                                option.textContent = `${student.full_name} (${student.nis})`;
                                siswaSelect.appendChild(option);
                            });
                        });
                } else {
                    siswaSelect.innerHTML = '<option value="">Semua Siswa</option>';
                }
            });
        }

        function route(name, param = null) {
            let routes = {
                'admin.grade.students-by-class': '{{ route('admin.grade.students-by-class', '___param___') }}',
            };
            if (param !== null) {
                return routes[name].replace('___param___', param);
            }
            return routes[name];
        }
    </script>

@endpush