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
                    @if (auth()->user()->level == 1)
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
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="tab-x" data-bs-toggle="tab" href="#content-x"
                                role="tab">Kelas X</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="tab-xi" data-bs-toggle="tab" href="#content-xi" role="tab">Kelas
                                XI</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="tab-xii" data-bs-toggle="tab" href="#content-xii" role="tab">Kelas
                                XII</a>
                        </li>
                    </ul>

                    <div class="tab-content border border-top-0 p-3" id="gradeTabsContent">
                        {{-- ==================================== --}}
                        {{-- TAB KELAS X --}}
                        {{-- ==================================== --}}
                        <div class="tab-pane fade show active" id="content-x" role="tabpanel" aria-labelledby="tab-x">
                            <div class="list-group">
                                @foreach ($classes->where('category', 'X') as $class)
                                    {{-- Tombol Collapse untuk tiap kelas X --}}
                                    <button
                                        class="list-group-item list-group-item-action d-flex justify-content-between align-items-center collapsed"
                                        data-bs-toggle="collapse" data-bs-target="#jadwal-{{ $class->id }}"
                                        aria-expanded="false" aria-controls="jadwal-{{ $class->id }}">
                                        {{ $class->name }}
                                        <span class="toggle-icon">+</span>
                                    </button>

                                    {{-- Konten Collapse --}}
                                    <div class="collapse" id="jadwal-{{ $class->id }}">
                                        <div class="card card-body mt-2">
                                            @php
                                                // Hitung jumlah jadwal terbanyak per hari di kelas ini
                                                $maxPerDay =
                                                    $class->schedules
                                                        ->groupBy('day')
                                                        ->map(fn($group) => $group->count())
                                                        ->max() ?? 0;
                                            @endphp

                                            <table class="table table-bordered text-center mb-0">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Hari</th>
                                                        @for ($i = 1; $i <= $maxPerDay; $i++)
                                                            <th>Jadwal {{ $i }}</th>
                                                        @endfor
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($days as $day)
                                                        @php
                                                            // Kumpulkan semua jadwal untuk hari ini
                                                            $daySchedules = $class->schedules
                                                                ->where('day', $day)
                                                                ->sortBy('start_time')
                                                                ->values();
                                                        @endphp

                                                        {{-- Jika hari ini sama sekali tidak ada jadwal --}}
                                                        @if ($daySchedules->isEmpty())
                                                            <tr>
                                                                <td>{{ $day }}</td>
                                                                @for ($j = 0; $j < $maxPerDay; $j++)
                                                                    <td><em>Istirahat</em></td>
                                                                @endfor
                                                            </tr>
                                                        @else
                                                            {{-- Jika ada satu atau lebih jadwal --}}
                                                            <tr>
                                                                <td>{{ $day }}</td>
                                                                @for ($j = 0; $j < $maxPerDay; $j++)
                                                                    <td>
                                                                        @if (isset($daySchedules[$j]))
                                                                            @php $sch = $daySchedules[$j]; @endphp

                                                                            {{-- Tampilkan Jam Mulai – Jam Selesai di dalam “Jadwal $j+1” --}}
                                                                            {{ \Carbon\Carbon::parse($sch->start_time)->format('H:i') }}
                                                                            -
                                                                            {{ \Carbon\Carbon::parse($sch->end_time)->format('H:i') }}
                                                                            <br>

                                                                            {{-- Tampilkan Mata Pelajaran (atau “Istirahat” jika course = null)
                                                                            --}}
                                                                            @if ($sch->course)
                                                                                <strong>{{ $sch->course->name }}</strong>
                                                                            @else
                                                                                <em>Istirahat</em>
                                                                            @endif
                                                                            <br>

                                                                            {{-- Tampilkan Nama Guru (jika ada) --}}
                                                                            @if ($sch->teacher)
                                                                                <small>{{ $sch->teacher->full_name }}</small>
                                                                            @endif
                                                                        @else
                                                                            {{-- Jika hari ini hanya punya sejumlah jadwal < $maxPerDay, sisanya
                                                                                “Istirahat” --}} <em>Istirahat</em>
                                                                        @endif
                                                                    </td>
                                                                @endfor
                                                            </tr>
                                                        @endif
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- ==================================== --}}
                        {{-- TAB KELAS XI --}}
                        {{-- ==================================== --}}
                        <div class="tab-pane fade" id="content-xi" role="tabpanel" aria-labelledby="tab-xi">
                            <div class="list-group">
                                @foreach ($classes->where('category', 'XI') as $class)
                                    <button
                                        class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
                                        data-bs-toggle="collapse" data-bs-target="#jadwal-{{ $class->id }}"
                                        aria-expanded="false" aria-controls="jadwal-{{ $class->id }}">
                                        {{ $class->name }}
                                        <span class="badge bg-secondary">▼</span>
                                    </button>

                                    <div class="collapse" id="jadwal-{{ $class->id }}">
                                        <div class="card card-body mt-2">
                                            @php
                                                $maxPerDay =
                                                    $class->schedules
                                                        ->groupBy('day')
                                                        ->map(fn($group) => $group->count())
                                                        ->max() ?? 0;
                                            @endphp

                                            <table class="table table-bordered text-center mb-0">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Hari</th>
                                                        @for ($i = 1; $i <= $maxPerDay; $i++)
                                                            <th>Jadwal {{ $i }}</th>
                                                        @endfor
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($days as $day)
                                                        @php
                                                            $daySchedules = $class->schedules
                                                                ->where('day', $day)
                                                                ->sortBy('start_time')
                                                                ->values();
                                                        @endphp

                                                        @if ($daySchedules->isEmpty())
                                                            <tr>
                                                                <td>{{ $day }}</td>
                                                                @for ($j = 0; $j < $maxPerDay; $j++)
                                                                    <td><em>Istirahat</em></td>
                                                                @endfor
                                                            </tr>
                                                        @else
                                                            <tr>
                                                                <td>{{ $day }}</td>
                                                                @for ($j = 0; $j < $maxPerDay; $j++)
                                                                    <td>
                                                                        @if (isset($daySchedules[$j]))
                                                                            @php $sch = $daySchedules[$j]; @endphp
                                                                            {{ \Carbon\Carbon::parse($sch->start_time)->format('H:i') }}
                                                                            -
                                                                            {{ \Carbon\Carbon::parse($sch->end_time)->format('H:i') }}
                                                                            <br>
                                                                            @if ($sch->course)
                                                                                <strong>{{ $sch->course->name }}</strong>
                                                                            @else
                                                                                <em>Istirahat</em>
                                                                            @endif
                                                                            <br>
                                                                            @if ($sch->teacher)
                                                                                <small>{{ $sch->teacher->full_name }}</small>
                                                                            @endif
                                                                        @else
                                                                            <em>Istirahat</em>
                                                                        @endif
                                                                    </td>
                                                                @endfor
                                                            </tr>
                                                        @endif
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- ==================================== --}}
                        {{-- TAB KELAS XII --}}
                        {{-- ==================================== --}}
                        <div class="tab-pane fade" id="content-xii" role="tabpanel" aria-labelledby="tab-xii">
                            <div class="list-group">
                                @foreach ($classes->where('category', 'XII') as $class)
                                    <button
                                        class="list-group-item list-group-item-action d-flex justify-content-between align-items-center"
                                        data-bs-toggle="collapse" data-bs-target="#jadwal-{{ $class->id }}"
                                        aria-expanded="false" aria-controls="jadwal-{{ $class->id }}">
                                        {{ $class->name }}
                                        <span class="badge bg-secondary">▼</span>
                                    </button>

                                    <div class="collapse" id="jadwal-{{ $class->id }}">
                                        <div class="card card-body mt-2">
                                            @php
                                                $maxPerDay =
                                                    $class->schedules
                                                        ->groupBy('day')
                                                        ->map(fn($group) => $group->count())
                                                        ->max() ?? 0;
                                            @endphp

                                            <table class="table table-bordered text-center mb-0">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Hari</th>
                                                        @for ($i = 1; $i <= $maxPerDay; $i++)
                                                            <th>Jadwal {{ $i }}</th>
                                                        @endfor
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($days as $day)
                                                        @php
                                                            $daySchedules = $class->schedules
                                                                ->where('day', $day)
                                                                ->sortBy('start_time')
                                                                ->values();
                                                        @endphp

                                                        @if ($daySchedules->isEmpty())
                                                            <tr>
                                                                <td>{{ $day }}</td>
                                                                @for ($j = 0; $j < $maxPerDay; $j++)
                                                                    <td><em>Istirahat</em></td>
                                                                @endfor
                                                            </tr>
                                                        @else
                                                            <tr>
                                                                <td>{{ $day }}</td>
                                                                @for ($j = 0; $j < $maxPerDay; $j++)
                                                                    <td>
                                                                        @if (isset($daySchedules[$j]))
                                                                            @php $sch = $daySchedules[$j]; @endphp
                                                                            {{ \Carbon\Carbon::parse($sch->start_time)->format('H:i') }}
                                                                            -
                                                                            {{ \Carbon\Carbon::parse($sch->end_time)->format('H:i') }}
                                                                            <br>
                                                                            @if ($sch->course)
                                                                                <strong>{{ $sch->course->name }}</strong>
                                                                            @else
                                                                                <em>Istirahat</em>
                                                                            @endif
                                                                            <br>
                                                                            @if ($sch->teacher)
                                                                                <small>{{ $sch->teacher->full_name }}</small>
                                                                            @endif
                                                                        @else
                                                                            <em>Istirahat</em>
                                                                        @endif
                                                                    </td>
                                                                @endfor
                                                            </tr>
                                                        @endif
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Jadwal -->
    <div class="modal fade" id="addScheduleModal" tabindex="-1" aria-labelledby="addScheduleModalLabel" aria-hidden="true">
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
                                                <select name="teacher_ids[]" class="form-select form-select-sm" required>
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

                                            {{-- Tombol Hapus (tidak menghapus baris pertama jika hanya satu tersisa) --}}
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
        document.addEventListener('DOMContentLoaded', function() {
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
            btnAdd.addEventListener('click', function() {
                const newRow = createNewRow();
                tableBody.appendChild(newRow);
                updateRemoveButtons();
            });

            // Hapus baris jika tombol Hapus pada baris ditekan
            function updateRemoveButtons() {
                tableBody.querySelectorAll('.btn-remove-row').forEach(btn => {
                    btn.onclick = function() {
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

@endpush
