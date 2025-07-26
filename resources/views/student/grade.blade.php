@extends('student.layouts.app')

@section('title', 'Nilai Siswa')

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

        .btn-excel {
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

        .btn-search:hover {
            background: #388e3c;
        }

        .btn-print {
            background: #f57c00;
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
                    <h3>Nilai Akademik</h3>
                    <p class="text-subtitle text-muted">Laporan lengkap nilai akademik Anda.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="content">
            {{-- Bagian Informasi Siswa --}}
            <div class="student-card active mb-4">
                <div class="student-card-header">
                    <img src="{{ $student->photo ? asset('storage/' . $student->photo) : 'https://via.placeholder.com/50' }}"
                        alt="Foto Siswa">
                    <div>
                        <div class="student-card-name">{{ $student->full_name }}</div>
                        <div class="student-card-class">
                            {{ $student->class->name ?? 'Belum ada kelas' }} | NIS: {{ $student->nis }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Bagian Tabel Nilai --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Daftar Nilai Akademik</h3>
                </div>
                <table>
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
                        @forelse($grades as $grade)
                            <tr>
                                <td>
                                    <div>{{ $grade->course->name ?? 'N/A' }}</div>
                                    <div class="subject-code">{{ $grade->course->code ?? '-' }}</div>
                                </td>
                                <td>{{ $grade->teacher->full_name ?? 'N/A' }}</td>
                                <td class="score">{{ $grade->assignment_score }}</td>
                                <td class="score">{{ $grade->mid_exam_score }}</td>
                                <td class="score">{{ $grade->final_exam_score }}</td>
                                <td class="score">{{ number_format($grade->final_score, 2) }}</td>
                                <td>
                                    @php
                                        $finalScore = $grade->final_score;
                                        if ($finalScore >= 85)
                                            $letterGrade = 'A';
                                        elseif ($finalScore >= 70)
                                            $letterGrade = 'B';
                                        elseif ($finalScore >= 55)
                                            $letterGrade = 'C';
                                        else
                                            $letterGrade = 'D';
                                    @endphp
                                    <span class="grade {{ $letterGrade }}">{{ $letterGrade }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-5">
                                    Belum ada data nilai yang diinput.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Bagian Ringkasan Nilai --}}
            @if($grades->isNotEmpty())
                <div class="summary">
                    <div class="summary-card">
                        <h3>Rata-rata Nilai</h3>
                        <div class="summary-value">{{ number_format($average, 2) }}</div>
                        <div class="summary-description">Dari {{ $grades->count() }} mata pelajaran</div>
                    </div>
                    <div class="summary-card">
                        <h3>Nilai Tertinggi</h3>
                        <div class="summary-value">{{ number_format($highestRecord->final_score, 2) }}</div>
                        <div class="summary-description">{{ $highestRecord->course->name ?? '' }}</div>
                    </div>
                    <div class="summary-card">
                        <h3>Nilai Terendah</h3>
                        <div class="summary-value">{{ number_format($lowestRecord->final_score, 2) }}</div>
                        <div class="summary-description">{{ $lowestRecord->course->name ?? '' }}</div>
                    </div>
                    <div class="summary-card">
                        <h3>Peringkat Kelas</h3>
                        <div class="summary-value">{{ $rank }}</div>
                        <div class="summary-description">Dari {{ $totalStudentsInClass }} siswa</div>
                    </div>
                </div>
            @endif
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
        // Simulasi fungsi pencetakan
        document.querySelector('.btn-print').addEventListener('click', function () {
            alert('Fitur cetak laporan akan membuka jendela pencetakan');
        });

        // Simulasi fungsi pencarian
        document.querySelector('.btn-search').addEventListener('click', function () {
            const kelas = document.getElementById('kelas').value;
            const semester = document.getElementById('semester').value;
            const siswa = document.getElementById('siswa').value;
            const pelajaran = document.getElementById('mata-pelajaran').value;

            alert(`Menampilkan data untuk ${kelas} - ${semester}${siswa ? ' - Siswa: ' + siswa : ''}${pelajaran ? ' - Pelajaran: ' + pelajaran : ''}`);
        });

        // Simulasi perubahan semester
        document.querySelector('.semester-select').addEventListener('change', function () {
            const semester = this.value;
            alert(`Memuat data untuk ${semester}`);
        });

        // Fungsi untuk memilih siswa
        const studentCards = document.querySelectorAll('.student-card');
        studentCards.forEach(card => {
            card.addEventListener('click', function () {
                studentCards.forEach(c => c.classList.remove('active'));
                this.classList.add('active');

                const studentName = this.querySelector('.student-card-name').textContent;
                const studentClass = this.querySelector('.student-card-class').textContent.split(' | ')[0];
                document.querySelector('.card-title .student-name').textContent = studentName;

                // Update data nilai berdasarkan siswa yang dipilih
                alert(`Memuat data nilai untuk ${studentName}`);
            });
        });
    </script>

@endpush