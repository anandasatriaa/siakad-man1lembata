@extends('teacher.layouts.app')

@section('title', 'Input Nilai Siswa')

@push('css')
    <style>
        .avatar-placeholder {
            font-weight: bold;
            font-size: 0.9rem;
        }

        .grade-input {
            text-align: center;
            font-weight: bold;
        }

        .final-grade {
            text-align: center;
            font-weight: bold;
            font-size: 1.1rem;
            padding: 5px;
            border-radius: 4px;
            background-color: #f8f9fa;
        }

        .text-success {
            color: #28a745;
        }

        .text-warning {
            color: #ffc107;
        }

        .text-danger {
            color: #dc3545;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(13, 110, 253, 0.05);
        }

        th {
            white-space: nowrap;
        }
    </style>
@endpush

@section('content')
    {{-- Pesan sukses --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="container">
        <div class="alert alert-info">
            <i class="bi bi-info-circle me-1"></i>
            KKM (Kriteria Ketuntasan Minimal): Nilai akhir >= <strong>75</strong> dianggap tuntas.
        </div>
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0 text-white">Input Nilai: {{ $course->name }}</h4>
                        <p class="mb-0">Kelas: {{ $class->name }}</p>
                    </div>
                    <a href="{{ route('teacher.grade.index') }}" class="btn btn-light btn-sm">
                        <span class="d-flex align-items-center"><i class="bi bi-arrow-left me-1"></i> Kembali</span>
                    </a>
                </div>
            </div>

            <div class="card-body">
                <form id="gradeForm" method="POST" action="{{ route('teacher.grade.store') }}">
                    @csrf
                    <input type="hidden" name="class_id" value="{{ $class->id }}">
                    <input type="hidden" name="course_id" value="{{ $course->id }}">

                    <div class="table-responsive mt-2">
                        <table class="table table-hover table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%">#</th>
                                    <th>Nama Siswa</th>
                                    <th>Tugas (30%)</th>
                                    <th>UTS (30%)</th>
                                    <th>UAS (40%)</th>
                                    <th>Nilai Akhir</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($students as $index => $student)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($student->photo)
                                                    <img src=" {{ asset('storage/' . $student->photo) }}"
                                                        alt="Foto {{ $student->full_name }}" class="rounded-circle me-2" width="35"
                                                        height="35">
                                                @else
                                                    <div class="avatar-placeholder rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center me-2"
                                                        style="width:35px; height:35px">
                                                        {{ substr($student->full_name, 0, 1) }}
                                                </div> @endif <div>
                                                    <div>{{ $student->full_name }}</div>
                                                    <small class="text-muted">{{ $student->nis }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td width="15%">
                                            <input type="number" name="scores[{{ $student->id }}][assignment]"
                                                class="form-control form-control-sm grade-input" min="0" max="100" step="0.01"
                                                data-student="{{ $student->id }}"
                                                value="{{ $existingGrades[$student->id]['assignment'] ?? '' }}" required>
                                        </td>
                                        <td width="15%">
                                            <input type="number" name="scores[{{ $student->id }}][mid_exam]"
                                                class="form-control form-control-sm grade-input" min="0" max="100" step="0.01"
                                                data-student="{{ $student->id }}"
                                                value="{{ $existingGrades[$student->id]['mid_exam'] ?? '' }}" required>
                                        </td>
                                        <td width="15%">
                                            <input type="number" name="scores[{{ $student->id }}][final_exam]"
                                                class="form-control form-control-sm grade-input" min="0" max="100" step="0.01"
                                                data-student="{{ $student->id }}"
                                                value="{{ $existingGrades[$student->id]['final_exam'] ?? '' }}" required>
                                        </td>
                                        <td width="15%">
                                            <div class="final-grade" id="final-grade-{{ $student->id }}"
                                                data-student="{{ $student->id }}">
                                                {{ $existingGrades[$student->id]['final_grade'] ?? '0.00' }}
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-save"></i> Simpan Semua Nilai
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('js')
    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                timer: 2000,
                showConfirmButton: false
            });
        </script>
    @endif

    @if ($errors->any())
        <script>
            Swal.fire({
                icon: 'warning',
                title: 'Validasi Gagal!',
                html: `{!! implode('<br>', $errors->all()) !!}`
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: '{{ session('error') }}',
            });
        </script>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Hitung nilai akhir saat input berubah
            document.querySelectorAll('.grade-input').forEach(input => {
                input.addEventListener('input', function () {
                    const studentId = this.dataset.student;
                    calculateFinalGrade(studentId);
                });
            });

            // Fungsi hitung nilai akhir
            function calculateFinalGrade(studentId) {
                const assignment = parseFloat(document.querySelector(`input[name="scores[${studentId}][assignment]"]`).value) ||
                    0;
                const midExam = parseFloat(document.querySelector(`input[name="scores[${studentId}][mid_exam]"]`).value) || 0;
                const finalExam = parseFloat(document.querySelector(`input[name="scores[${studentId}][final_exam]"]`).value) ||
                    0;

                // Hitung nilai akhir (30% tugas + 30% UTS + 40% UAS)
                const finalGrade = (assignment * 0.3) + (midExam * 0.3) + (finalExam * 0.4);

                // Tampilkan nilai akhir
                document.getElementById(`final-grade-${studentId}`).textContent = finalGrade.toFixed(2);

                // Warna berdasarkan nilai
                const gradeElement = document.getElementById(`final-grade-${studentId}`);
                gradeElement.classList.remove('text-success', 'text-warning', 'text-danger');

                if (finalGrade >= 75) {
                    gradeElement.classList.add('text-success');
                    gradeElement.classList.add('fw-bold');
                } else {
                    gradeElement.classList.add('text-danger');
                    gradeElement.classList.add('fw-bold');
                }
            }

            // Hitung ulang semua nilai saat halaman dimuat
            document.querySelectorAll('.grade-input').forEach(input => {
                const studentId = input.dataset.student;
                calculateFinalGrade(studentId);
            });
        });
    </script>
@endpush