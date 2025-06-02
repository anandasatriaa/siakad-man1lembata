@extends('admin.layouts.app')

@section('title', 'Akun & Password')

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
                    <h3>Data Akun dan Password</h3>
                    <p class="text-subtitle text-muted">Semua informasi mengenai data akun dan password</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first text-md-end text-start mb-2 mb-md-0">
                    <button type="button" class="btn btn-primary rounded-pill d-inline-flex align-items-center"
                        data-bs-toggle="modal" data-bs-target="#tambahAkunModal">
                        <i class="bi bi-plus-circle me-2"></i>
                        <span>Tambah Akun</span>
                    </button>
                </div>
            </div>
        </div>

        {{-- Info tentang default password saat di‐reset --}}
        <div class="row mb-3">
            <div class="col-12">
                <div class="alert alert-info mb-0">
                    <i class="bi bi-info-circle me-1"></i>
                    <small>
                        Apabila Anda klik “Reset Password” pada salah satu akun, maka password akun tersebut akan
                        diatur ulang menjadi <strong>lembata123</strong>.
                    </small>
                </div>
            </div>
        </div>
        <section class="section">
            <div class="card">
                <div class="card-header">
                    Table Data Akun dan Password
                </div>
                <div class="card-body">
                    {{-- Tabel Daftar Akun --}}
                    <table id="table-akun" class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Peran</th>
                                <th>Tanggal Dibuat</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $index => $user)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @if ($user->level == '3')
                                            Guru
                                        @elseif ($user->level == '4')
                                            Siswa
                                        @else
                                            Lainnya ({{ $user->level }})
                                        @endif
                                    </td>
                                    <td>{{ $user->created_at->format('d-m-Y H:i') }}</td>
                                    <td>
                                        {{-- Tombol Reset Password --}}
                                        <a href="{{ route('admin.account.reset', $user->id) }}"
                                            class="btn btn-warning btn-sm">
                                            <i class="bi bi-arrow-counterclockwise"></i>
                                            Reset Password
                                        </a>

                                        {{-- Tombol Edit memanggil modal --}}
                                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#editUserModal-{{ $user->id }}">
                                            <i class="bi bi-pencil-square"></i>
                                            Edit
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">Belum ada akun terdaftar.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>

    {{-- ========== MODAL TAMBAH AKUN ========== --}}
    <div class="modal fade" id="tambahAkunModal" tabindex="-1" aria-labelledby="tambahAkunModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <form action="{{ route('admin.account.store') }}" method="POST">
                    @csrf

                    <div class="modal-header">
                        <h5 class="modal-title" id="tambahAkunModalLabel">Tambah Akun Baru</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        {{-- Tombol untuk menambah baris --}}
                        <button type="button" id="btnAddRow" class="btn btn-sm btn-success mb-3">
                            <i class="bi bi-plus-circle me-1"></i> Tambah Baris
                        </button>

                        {{-- Container untuk menampung semua baris input --}}
                        <div id="rowsContainer">
                            {{-- Baris template (disembunyikan) --}}
                            <div class="row mb-2 align-items-end d-none" id="rowTemplate" data-index="__INDEX__">
                                <div class="col-md-4">
                                    <label for="accounts[__INDEX__][person]" class="form-label">Nama Guru/Siswa</label>
                                    <select name="accounts[__INDEX__][person]" class="form-select select2 select-person"
                                        disabled required>
                                        <option value="" disabled selected>— Pilih Guru / Siswa —</option>
                                        <optgroup label="Guru">
                                            @foreach ($teachers as $t)
                                                <option value="teacher_{{ $t->id }}">{{ $t->full_name }}</option>
                                            @endforeach
                                        </optgroup>
                                        <optgroup label="Siswa">
                                            @foreach ($students as $s)
                                                <option value="student_{{ $s->id }}">{{ $s->full_name }}</option>
                                            @endforeach
                                        </optgroup>
                                    </select>

                                    <input type="hidden" name="accounts[__INDEX__][type]" class="input-type" disabled
                                        required>
                                    <input type="hidden" name="accounts[__INDEX__][person_id]" class="input-person-id"
                                        disabled required>
                                </div>

                                <div class="col-md-3">
                                    <label for="accounts[__INDEX__][email]" class="form-label">Email</label>
                                    <input type="email" name="accounts[__INDEX__][email]" class="form-control"
                                        placeholder="contoh@example.com" disabled required>
                                </div>

                                <div class="col-md-3">
                                    <label for="accounts[__INDEX__][password]" class="form-label">Password</label>
                                    <input type="text" name="accounts[__INDEX__][password]" class="form-control"
                                        placeholder="••••••••" disabled required>
                                </div>

                                <div class="col-md-2 text-center">
                                    <button type="button" class="btn btn-danger btn-sm btnRemoveRow"
                                        style="margin-top: 1.7rem;">
                                        <i class="bi bi-trash"></i> Hapus
                                    </button>
                                </div>
                            </div>
                            {{-- End baris template --}}
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Batal
                        </button>
                        <button type="submit" class="btn btn-primary">
                            Simpan Akun
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @foreach ($users as $user)
    <!-- Modal Edit Email untuk user {{ $user->id }} -->
    <div
        class="modal fade"
        id="editUserModal-{{ $user->id }}"
        tabindex="-1"
        aria-labelledby="editUserModalLabel-{{ $user->id }}"
        aria-hidden="true"
    >
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('admin.account.update', $user->id) }}" method="POST">
                    @csrf

                    <div class="modal-header">
                        <h5
                            class="modal-title"
                            id="editUserModalLabel-{{ $user->id }}"
                        >
                            Edit Email: {{ $user->name }}
                        </h5>
                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                            aria-label="Close"
                        ></button>
                    </div>

                    <div class="modal-body">
                        {{-- Tampilkan validasi error khusus untuk form ini --}}
                        @if ($errors->has("email.$user->id") || $errors->has('email'))
                            <div class="alert alert-danger">
                                @if ($errors->has("email.$user->id"))
                                    {{ $errors->first("email.$user->id") }}
                                @else
                                    {{ $errors->first('email') }}
                                @endif
                            </div>
                        @endif

                        <div class="mb-3">
                            <label for="email-{{ $user->id }}" class="form-label">
                                Email Baru
                            </label>
                            <input
                                type="email"
                                name="email"
                                id="email-{{ $user->id }}"
                                class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email', $user->email) }}"
                                required
                            >
                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button
                            type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal"
                        >
                            Batal
                        </button>
                        <button type="submit" class="btn btn-primary">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach

@endsection

@push('js')
    <script>
        // Simple Datatable
        let tableAccount = document.querySelector('#table-akun');
        let dataTable = new simpleDatatables.DataTable(tableAccount);
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
        document.addEventListener('DOMContentLoaded', function() {
            let index = 0;
            const btnAddRow = document.getElementById('btnAddRow');
            const rowsContainer = document.getElementById('rowsContainer');
            const rowTemplate = document.getElementById('rowTemplate');

            function addRow() {
                index++;

                // 1. Clone template
                const newRow = rowTemplate.cloneNode(true);
                newRow.classList.remove('d-none');
                newRow.removeAttribute('id'); // Hapus id agar tidak bentrok
                newRow.setAttribute('data-index', index);

                // 2. Ganti semua "__INDEX__" menjadi index
                newRow.innerHTML = newRow.innerHTML.replace(/__INDEX__/g, index);

                // 3. Append ke dalam rowsContainer
                rowsContainer.appendChild(newRow);

                // 4. Hapus atribut `disabled` dari semua input/select di newRow
                newRow.querySelectorAll('input, select').forEach(el => el.removeAttribute('disabled'));

                // 5. Pasang listener untuk tombol hapus pada newRow
                newRow.querySelector('.btnRemoveRow').addEventListener('click', function() {
                    newRow.remove();
                });

                // 6. Inisialisasi Select2 hanya pada <select class="select2"> di newRow
                const $select = $(newRow).find('.select2');
                $select.select2({
                    placeholder: "Pilih guru/siswa...",
                    width: '100%',
                    dropdownParent: $('#tambahAkunModal')
                });
            }

            // Ketika tombol “Tambah Baris” diklik
            btnAddRow.addEventListener('click', addRow);

            // (Opsional) Kalau mau langsung satu baris saat modal dibuka, 
            // panggil addRow() di sini atau di event "show.bs.modal"
            // Contoh:
            $('#tambahAkunModal').on('show.bs.modal', function() {
                // Kosongkan dulu kalau masih ada sisa
                rowsContainer.innerHTML = '';
                index = 0;
                addRow();
            });
        });
    </script>

    <script>
        // Handler memecah value "teacher_5" → type="teacher", person_id="5"
        $(document).on('change', '.select-person', function() {
            const val = $(this).val(); // misal: "teacher_5" atau "student_10"
            if (!val) return;

            const [type, id] = val.split('_');
            const parent = $(this).closest('.row');
            parent.find('.input-type').val(type);
            parent.find('.input-person-id').val(id);
        });
    </script>
@endpush
