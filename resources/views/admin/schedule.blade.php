@extends('admin.layouts.app')

@section('title', 'Jadwal Pelajaran')

@push('css')
    <style>
        .toggle-icon {
            font-weight: bold;
            font-size: 1.2rem;
        }
    </style>
@endpush

@section('content')
    <div class="page-heading">
        <div class="page-title">
            <div class="row align-items-center">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Jadwal Pelajaran</h3>
                    <p class="text-subtitle text-muted">Semua informasi mengenai Jadwal Pelajaran</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first text-md-end text-start mb-2 mb-md-0">
                    @if(auth()->user()->level == 1)
                        <button type="button" class="btn btn-primary rounded-pill d-inline-flex align-items-center"
                            data-bs-toggle="modal" data-bs-target="#addScheduleModal">
                            <i class="bi bi-plus-circle me-2"></i>
                            <span>Tambah Jadwal</span>
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <div class="section">
            <div class="card">
                <div class="card-header">Jadwal Pelajaran</div>
                <div class="card-body">
                    <!-- Nav Tabs untuk X, XI, XII -->
                    <ul class="nav nav-tabs nav-justified" role="tablist">
                        @foreach(['X', 'XI', 'XII'] as $grade)
                            <li class="nav-item" role="presentation">
                                <a class="nav-link {{ $loop->first ? 'active' : '' }}" id="tab-{{ strtolower($grade) }}"
                                    data-bs-toggle="tab" href="#content-{{ strtolower($grade) }}" role="tab">
                                    Kelas {{ $grade }}
                                </a>
                            </li>
                        @endforeach
                    </ul>

                    <div class="tab-content border border-top-0 p-3">
                        @foreach(['X', 'XI', 'XII'] as $grade)
                            <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}"
                                id="content-{{ strtolower($grade) }}" role="tabpanel">
                                <div class="list-group">
                                    @foreach($classes->where('category', $grade) as $class)
                                        {{-- Edit Button dan Collapse Trigger --}}
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            @if(auth()->user()->level == 1)
                                                <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                                    data-bs-target="#editScheduleModal-{{ $class->id }}">
                                                    <i class="bi bi-pencil-square me-1"></i>Edit
                                                </button>
                                            @endif
                                            <button class="btn btn-sm btn-outline-secondary flex-grow-1 ms-2 text-start"
                                                data-bs-toggle="collapse" data-bs-target="#jadwal-{{ $class->id }}"
                                                aria-expanded="false">
                                                {{ $class->name }}
                                            </button>
                                        </div>

                                        <div class="collapse" id="jadwal-{{ $class->id }}">
                                            <div class="card card-body mb-3">
                                                @php
                                                    $maxPerDay = $class->schedules->groupBy('day')->map->count()->max() ?? 0;
                                                  @endphp
                                                <table class="table table-bordered text-center mb-0">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th>Hari</th>
                                                            @for($i = 1; $i <= $maxPerDay; $i++)
                                                                <th>Jadwal {{ $i }}</th>
                                                            @endfor
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($days as $day)
                                                            @php
                                                                $daySch = $class->schedules->where('day', $day)->sortBy('start_time')->values();
                                                            @endphp
                                                            <tr>
                                                                <td>{{ $day }}</td>
                                                                @for($j = 0; $j < $maxPerDay; $j++)
                                                                    <td>
                                                                        @if(isset($daySch[$j]))
                                                                            @php $sch = $daySch[$j]; @endphp
                                                                            {{ \Carbon\Carbon::parse($sch->start_time)->format('H:i') }} -
                                                                            {{ \Carbon\Carbon::parse($sch->end_time)->format('H:i') }}<br>
                                                                            @if($sch->course)
                                                                                <strong>{{ $sch->course->name }}</strong>
                                                                            @else
                                                                                <em>Istirahat</em>
                                                                            @endif<br>
                                                                            @if($sch->teacher)
                                                                                <small>{{ $sch->teacher->full_name }}</small>
                                                                            @endif
                                                                        @else
                                                                            <em>Istirahat</em>
                                                                        @endif
                                                                    </td>
                                                                @endfor
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- Add Schedule Modal --}}
        <div class="modal fade" id="addScheduleModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <form method="POST" action="{{ route('admin.schedule.store') }}">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">Tambah Jadwal Pelajaran</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            {{-- Pilih Kelas --}}
                            <div class="mb-3">
                                <label class="form-label">Kelas</label>
                                <select name="class_id" class="form-select" required>
                                    <option value="" disabled selected>-- Pilih Kelas --</option>
                                    @foreach($classes as $c)
                                        <option value="{{ $c->id }}">{{ $c->name }} ({{ $c->category }})</option>
                                    @endforeach
                                </select>
                            </div>
                            {{-- Tabel Dinamis Add --}}
                            <div class="mb-3">
                                <button type="button" class="btn btn-sm btn-success mb-2" id="btn-add-row">
                                    <i class="bi bi-plus-circle"></i> Tambah Baris
                                </button>
                                <div class="table-responsive">
                                    <table class="table table-bordered align-middle" id="schedule-table">
                                        <thead class="table-light text-center">
                                            <tr>
                                                <th>Hari</th>
                                                <th>Mata Pelajaran</th>
                                                <th>Guru</th>
                                                <th>Jam Mulai</th>
                                                <th>Jam Selesai</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <select name="days[]" class="form-select form-select-sm" required>
                                                        <option value="" disabled selected>-- Pilih Hari --</option>
                                                        @foreach($days as $d)
                                                            <option value="{{ $d }}">{{ $d }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <select name="course_ids[]" class="form-select form-select-sm" required>
                                                        <option value="" disabled selected>-- Pilih Mata Pelajaran --
                                                        </option>
                                                        <option value="istirahat">Istirahat</option>
                                                        @foreach($courses as $course)
                                                            <option value="{{ $course->id }}">{{ $course->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td>
                                                    <select name="teacher_ids[]" class="form-select form-select-sm"
                                                        required>
                                                        <option value="" disabled selected>-- Pilih Guru --</option>
                                                        <option value="istirahat">Istirahat</option>
                                                        @foreach($teachers as $t)
                                                            <option value="{{ $t->id }}">{{ $t->full_name }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td><input type="time" name="start_times[]"
                                                        class="form-control form-control-sm" required></td>
                                                <td><input type="time" name="end_times[]"
                                                        class="form-control form-control-sm" required></td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-sm btn-danger btn-remove-row"><i
                                                            class="bi bi-trash"></i></button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Edit Modals --}}
        @foreach($classes as $class)
            <div class="modal fade" id="editScheduleModal-{{ $class->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <form method="POST" action="{{ route('admin.schedule.update', $class->id) }}">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Jadwal – {{ $class->name }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="class_id" value="{{ $class->id }}">
                                <button type="button" class="btn btn-sm btn-success mb-2 btn-add-row"><i
                                        class="bi bi-plus-circle"></i> Tambah Baris</button>
                                <div class="table-responsive">
                                    <table class="table table-bordered align-middle" id="schedule-table-{{ $class->id }}">
                                        <thead class="table-light text-center">
                                            <tr>
                                                <th>Hari</th>
                                                <th>Mata Pelajaran</th>
                                                <th>Guru</th>
                                                <th>Jam Mulai</th>
                                                <th>Jam Selesai</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($class->schedules->sortBy(['day', 'start_time']) as $sch)
                                                <tr>
                                                    <td>
                                                        <select name="days[]" class="form-select form-select-sm" required>
                                                            @foreach($days as $d)
                                                                <option value="{{ $d }}" {{ $sch->day === $d ? 'selected' : '' }}>{{ $d }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select name="course_ids[]" class="form-select form-select-sm" required>
                                                            <option value="istirahat" {{ is_null($sch->course_id) ? 'selected' : '' }}>
                                                                Istirahat</option>
                                                            @foreach($courses as $c)
                                                                <option value="{{ $c->id }}" {{ $sch->course_id === $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <select name="teacher_ids[]" class="form-select form-select-sm" required>
                                                            <option value="istirahat" {{ is_null($sch->teacher_id) ? 'selected' : '' }}>
                                                                Istirahat</option>
                                                            @foreach($teachers as $t)
                                                                <option value="{{ $t->id }}" {{ $sch->teacher_id === $t->id ? 'selected' : '' }}>{{ $t->full_name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td><input type="time" name="start_times[]" class="form-control form-control-sm"
                                                            value="{{ $sch->start_time }}" required></td>
                                                    <td><input type="time" name="end_times[]" class="form-control form-control-sm"
                                                            value="{{ $sch->end_time }}" required></td>
                                                    <td class="text-center">
                                                        <button type="button" class="btn btn-sm btn-danger btn-remove-row"><i
                                                                class="bi bi-trash"></i></button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach


        <!-- Modal Tambah Jadwal -->
        <div class="modal fade" id="addScheduleModal" tabindex="-1" aria-labelledby="addScheduleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <form method="POST" action="{{ route('admin.schedule.store') }}">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="addScheduleModalLabel">Tambah Jadwal Pelajaran</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="modal-body">

                            {{-- 1. Pilih Kelas --}}
                            <div class="mb-4">
                                <label for="class_id" class="form-label">Kelas</label>
                                <select name="class_id" id="class_id" class="form-select" required>
                                    <option value="" selected disabled>-- Pilih Kelas --</option>
                                    @foreach ($classes as $c)
                                        <option value="{{ $c->id }}">{{ $c->name }} ({{ $c->category }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- 2. Tabel Dinamis untuk Menambah Baris Jadwal --}}
                            <div class="mb-3">
                                <button type="button" class="btn btn-sm btn-success mb-2" id="btn-add-row">
                                    <i class="bi bi-plus-circle me-1"></i> Tambah Baris
                                </button>

                                <div class="table-responsive">
                                    <table class="table table-bordered align-middle" id="schedule-table">
                                        <thead class="table-light text-center">
                                            <tr>
                                                <th style="width: 14%;">Hari</th>
                                                <th style="width: 28%;">Mata Pelajaran</th>
                                                <th style="width: 20%;">Guru Pengajar</th>
                                                <th style="width: 14%;">Jam Mulai</th>
                                                <th style="width: 14%;">Jam Selesai</th>
                                                <th style="width: 10%;">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {{-- 1 baris awal (bisa disalin oleh JS) --}}
                                            <tr>
                                                {{-- Hari --}}
                                                <td class="align-middle">
                                                    <select name="days[]" class="form-select form-select-sm" required>
                                                        <option value="" selected disabled>-- Pilih Hari --</option>
                                                        @foreach ($days as $d)
                                                            <option value="{{ $d }}">{{ $d }}</option>
                                                        @endforeach
                                                    </select>
                                                </td>

                                                {{-- Mata Pelajaran (termasuk opsi Istirahat) --}}
                                                <td class="align-middle">
                                                    <select name="course_ids[]" class="form-select form-select-sm" required>
                                                        <option value="" selected disabled>-- Pilih Mata Pelajaran --
                                                        </option>
                                                        <option value="istirahat">Istirahat</option>
                                                        @foreach ($courses as $course)
                                                            <option value="{{ $course->id }}">
                                                                {{ $course->name }} ({{ $course->grade }}) –
                                                                {{ $course->code }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>

                                                {{-- Guru Pengajar --}}
                                                <td class="align-middle">
                                                    <select name="teacher_ids[]" class="form-select form-select-sm"
                                                        required>
                                                        <option value="" selected disabled>-- Pilih Guru --</option>
                                                        <option value="istirahat">Istirahat</option>
                                                        @foreach ($teachers as $t)
                                                            <option value="{{ $t->id }}">{{ $t->full_name }}
                                                                ({{ $t->nip }})
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>

                                                {{-- Jam Mulai --}}
                                                <td class="align-middle">
                                                    <input type="time" name="start_times[]"
                                                        class="form-control form-control-sm" required>
                                                </td>

                                                {{-- Jam Selesai --}}
                                                <td class="align-middle">
                                                    <input type="time" name="end_times[]"
                                                        class="form-control form-control-sm" required>
                                                </td>

                                                {{-- Tombol Hapus (tidak menghapus baris pertama jika hanya satu tersisa)
                                                --}}
                                                <td class="text-center align-middle">
                                                    <button type="button" class="btn btn-sm btn-danger btn-remove-row"
                                                        title="Hapus Baris">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan Jadwal</button>
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
            document.addEventListener('DOMContentLoaded', function () {
                const tableBody = document.querySelector('#schedule-table tbody');
                const btnAdd = document.getElementById('btn-add-row');

                // Fungsi: Buat baris baru dengan elemen input/select yang sama
                function createNewRow() {
                    const firstRow = tableBody.querySelector('tr');
                    const newRow = firstRow.cloneNode(true);

                    // Kosongkan semua input/select di newRow
                    newRow.querySelectorAll('select').forEach(sel => {
                        sel.selectedIndex = 0;
                    });
                    newRow.querySelectorAll('input[type="time"]').forEach(inp => {
                        inp.value = '';
                    });

                    return newRow;
                }

                // Tambah baris saat tombol ditekan
                btnAdd.addEventListener('click', function () {
                    const newRow = createNewRow();
                    tableBody.appendChild(newRow);
                    updateRemoveButtons();
                });

                // Hapus baris jika tombol Hapus pada baris ditekan
                function updateRemoveButtons() {
                    tableBody.querySelectorAll('.btn-remove-row').forEach(btn => {
                        btn.onclick = function () {
                            const currentRows = tableBody.querySelectorAll('tr').length;
                            if (currentRows > 1) {
                                this.closest('tr').remove();
                            }
                        };
                    });
                }

                // Inisialisasi pertama kali
                updateRemoveButtons();
            });
        </script>

        {{-- COLLAPSE --}}
        <script>
            document.querySelectorAll('[data-bs-toggle="collapse"]').forEach(button => {
                const targetId = button.getAttribute('data-bs-target');
                const target = document.querySelector(targetId);
                const icon = button.querySelector('.toggle-icon');

                // Toggle icon saat collapse dibuka/tutup
                target.addEventListener('show.bs.collapse', () => {
                    icon.textContent = '−';
                });

                target.addEventListener('hide.bs.collapse', () => {
                    icon.textContent = '+';
                });
            });
        </script>

        {{-- JS Dynamic Rows --}}
        <script>
            document.addEventListener('click', function (e) {
                // tombol tambah baris
                if (e.target.closest('.btn-add-row')) {
                    const modal = e.target.closest('.modal');
                    const tbody = modal.querySelector('tbody');
                    const tr = tbody.querySelector('tr').cloneNode(true);
                    tr.querySelectorAll('select, input').forEach(el => el.value = '');
                    tbody.appendChild(tr);
                }
                // tombol hapus baris
                if (e.target.closest('.btn-remove-row')) {
                    const tr = e.target.closest('tr');
                    const tbody = tr.parentNode;
                    if (tbody.querySelectorAll('tr').length > 1) tr.remove();
                }
            });
        </script>
    @endpush