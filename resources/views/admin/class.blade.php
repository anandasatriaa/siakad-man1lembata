@extends('admin.layouts.app')

@section('title', 'Kelas')

@push('css')
    <style>
        .avatar img {
            border-radius: 50%;
            object-fit: cover;
        }
    </style>
@endpush

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row align-items-center">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Data Kelas</h3>
                    <p class="text-subtitle text-muted">Semua informasi mengenai data kelas</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first text-md-end text-start mb-2 mb-md-0">
                    @if (auth()->user()->level == 1)
                        <button type="button" class="btn btn-primary rounded-pill d-inline-flex align-items-center"
                            data-bs-toggle="modal" data-bs-target="#tambahKelasModal">
                            <i class="bi bi-plus-circle me-2"></i>
                            <span>Tambah Kelas</span>
                        </button>
                    @endif
                </div>
            </div>
        </div>
        <section class="section">
            <div class="card">
                <div class="card-header">
                    Table Data Kelas
                </div>
                <div class="card-body">
                    @php
                        $grades = ['X', 'XI', 'XII'];
                    @endphp

                    <ul class="nav nav-tabs nav-justified mb-4" role="tablist">
                        @foreach ($grades as $grade)
                            <li class="nav-item flex-fill text-center" role="presentation">
                                <button class="nav-link w-100 @if ($loop->first) active @endif"
                                    id="tab-{{ $grade }}" data-bs-toggle="tab"
                                    data-bs-target="#kelas-{{ $grade }}" type="button" role="tab"
                                    aria-controls="kelas-{{ $grade }}"
                                    aria-selected="@if ($loop->first) true @else false @endif">
                                    Kelas {{ $grade }}
                                </button>
                            </li>
                        @endforeach
                    </ul>

                    <div class="tab-content">
                        @foreach ($grades as $grade)
                            <div class="tab-pane fade @if ($loop->first) show active @endif"
                                id="kelas-{{ $grade }}" role="tabpanel" aria-labelledby="tab-{{ $grade }}">

                                @if (!empty($classes[$grade]))
                                    @foreach ($classes[$grade] as $idx => $class)
                                        @php $collapseId = "collapse-{$grade}-{$idx}"; @endphp
                                        <div class="card mb-3">
                                            <div class="card-header d-flex justify-content-between align-items-center">
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar avatar-md me-3">
                                                        <img src="{{ optional($class->teacher)->photo ? asset('storage/' . $class->teacher->photo) : asset('assets/images/faces/default.jpg') }}"
                                                            class="rounded-circle" alt="Foto Wali Kelas">
                                                    </div>
                                                    <div>
                                                        <strong>{{ $class->name }}</strong><br>
                                                        <small class="text-muted">Wali:
                                                            {{ optional($class->teacher)->full_name ?? 'Belum ditentukan' }}</small>
                                                    </div>
                                                </div>
                                                <div class="btn-group">
                                                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="collapse"
                                                        data-bs-target="#{{ $collapseId }}" aria-expanded="false"
                                                        aria-controls="{{ $collapseId }}">
                                                        Lihat Murid
                                                    </button>
                                                    @if (auth()->user()->level == 1)
                                                        <button class="btn btn-sm btn-outline-warning"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#modalEditKelas-{{ $class->id }}">
                                                            Edit
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>
                                            <div id="{{ $collapseId }}" class="collapse">
                                                <div class="card-body">
                                                    <h6>Daftar Murid:</h6>
                                                    <ul class="list-group">
                                                        @foreach ($class->students as $student)
                                                            <li class="list-group-item">
                                                                <div class="d-flex align-items-center mb-2">
                                                                    <div class="avatar avatar-sm me-3">
                                                                        <img src="{{ $student->avatar }}"
                                                                            class="rounded-circle" alt="Foto Murid">
                                                                    </div>
                                                                    <div>
                                                                        <strong>{{ $student->full_name }}</strong>
                                                                        <small
                                                                            class="text-muted">{{ $student->email }}</small>
                                                                    </div>
                                                                </div>
                                                                <div class="ms-5">
                                                                    <div><i
                                                                            class="bi bi-telephone-fill me-1"></i>{{ $student->phone }}
                                                                    </div>
                                                                    <div><i
                                                                            class="bi bi-geo-alt-fill me-1"></i>{{ $student->address }}
                                                                    </div>
                                                                </div>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Modal Edit Kelas -->
                                        <div class="modal fade" id="modalEditKelas-{{ $class->id }}" tabindex="-1"
                                            aria-labelledby="modalEditKelasLabel-{{ $class->id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <form action="{{ route('admin.class.update', $class->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        <div class="modal-header">
                                                            <h5 class="modal-title"
                                                                id="modalEditKelasLabel-{{ $class->id }}">Edit Kelas
                                                                {{ $class->name }}</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label class="form-label">Nama Kelas</label>
                                                                <input type="text" class="form-control"
                                                                    value="{{ $class->name }}" readonly>
                                                            </div>

                                                            <div class="mb-3">
                                                                <label for="teacher_id" class="form-label">Wali
                                                                    Kelas</label>
                                                                <select name="teacher_id" class="form-select" required>
                                                                    @foreach ($teachers as $teacher)
                                                                        <option value="{{ $teacher->id }}"
                                                                            {{ optional($class->teacher)->id == $teacher->id ? 'selected' : '' }}>
                                                                            {{ $teacher->full_name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                            <div class="mb-3">
                                                                <label class="form-label">Daftar Siswa</label>
                                                                <div class="row">
                                                                    @foreach ($students as $student)
                                                                        <div class="col-md-6">
                                                                            <div class="form-check">
                                                                                <input class="form-check-input"
                                                                                    type="checkbox" name="students[]" id="student-{{ $student->id }}"
                                                                                    value="{{ $student->id }}"
                                                                                    {{ in_array($student->id, $class->students->pluck('id')->toArray()) ? 'checked' : '' }}>
                                                                                <label class="form-check-label" for="student-{{ $student->id }}">
                                                                                    {{ $student->full_name }}
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            </div>

                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="submit" class="btn btn-primary">Simpan
                                                                Perubahan</button>
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Tutup</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <p class="text-muted">Belum ada data kelas untuk Kelas {{ $grade }}.</p>
                                @endif
                            </div>
                        @endforeach
                    </div>

                </div>
            </div>
        </section>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="tambahKelasModal" tabindex="-1" aria-labelledby="tambahKelasModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-white" id="tambahKelasModalLabel">Tambah Data Kelas Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.class.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-3">
                            <!-- Kolom Kiri -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nama Kelas <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="teacher_id" class="form-label">Wali Kelas <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select" id="teacher_id" name="teacher_id" required>
                                        <option value="">Pilih Wali Kelas</option>
                                        @foreach ($teachers as $teacher)
                                            <option value="{{ $teacher->id }}">{{ $teacher->full_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Kolom Kanan -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="category" class="form-label">Category <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select" id="category" name="category" required>
                                        <option value="">Pilih Kategori</option>
                                        <option value="X">X</option>
                                        <option value="XI">XI</option>
                                        <option value="XII">XII</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="students" class="form-label">Siswa <span class="text-danger">*</span></label>
                                <select class="form-select select2" id="students" name="students[]" multiple="multiple"
                                    required>
                                    @foreach ($students as $student)
                                        <option value="{{ $student->id }}">{{ $student->full_name }}</option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Pilih satu atau lebih siswa dari daftar.</small>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        // Simple Datatable
        let tableKelas = document.querySelector('#table-kelas');
        let dataTable = new simpleDatatables.DataTable(tableKelas);
    </script>

    <script>
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: '{{ session('success') }}',
                timer: 3000,
                showConfirmButton: false
            });
        @elseif (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: '{{ session('error') }}',
                showConfirmButton: true
            });
        @elseif (session('warning'))
            Swal.fire({
                icon: 'warning',
                title: 'Peringatan',
                html: `{!! nl2br(e(session('warning'))) !!}`,
                confirmButtonText: 'OK'
            });
        @endif
    </script>

    <script>
        document.querySelectorAll('[data-bs-toggle="collapse"]').forEach(btn => {
            btn.addEventListener('click', function() {
                const chevron = this.querySelector('.chevron');
                const target = document.querySelector(this.dataset.bsTarget);
                target.addEventListener('shown.bs.collapse', () => chevron.innerHTML = '\u25B2');
                target.addEventListener('hidden.bs.collapse', () => chevron.innerHTML = '\u25BC');
            });
        });
    </script>

    {{-- Select2 --}}
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "Pilih siswa...",
                width: '100%' // agar sesuai dengan form-control
            });
        });
    </script>
@endpush
