@extends('admin.layouts.app')

@section('title', 'Data Guru')

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
                    <h3>Data Guru</h3>
                    <p class="text-subtitle text-muted">Semua informasi mengenai data guru</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first text-md-end text-start mb-2 mb-md-0">
                    @if (auth()->user()->level == 1)
                        <button type="button" class="btn btn-primary rounded-pill d-inline-flex align-items-center"
                            data-bs-toggle="modal" data-bs-target="#tambahGuruModal">
                            <i class="bi bi-plus-circle me-2"></i>
                            <span>Tambah Guru</span>
                        </button>
                    @endif
                </div>
            </div>
        </div>
        <section class="section">
            <div class="card">
                <div class="card-header">
                    Table Data Guru
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="table-guru">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>NIP</th>
                                    <th>Nama Lengkap</th>
                                    <th>Jenis Kelamin</th>
                                    <th>Tempat, Tanggal Lahir</th>
                                    <th>Alamat</th>
                                    <th>Telepon</th>
                                    <th>Email</th>
                                    <th>Agama</th>
                                    <th>Tahun Masuk</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($teachers as $index => $teacher)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $teacher->nip }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-md me-3">
                                                    @if ($teacher->photo)
                                                        <img src="{{ asset('storage/' . $teacher->photo) }}"
                                                            alt="Foto Profil">
                                                    @else
                                                        <img src="{{ asset('assets/images/faces/default.jpg') }}"
                                                            alt="Foto Default">
                                                    @endif
                                                </div>
                                                <span>{{ $teacher->full_name }}</span>
                                            </div>
                                        </td>
                                        <td>{{ $teacher->gender === 'M' ? 'Laki-laki' : 'Perempuan' }}</td>
                                        <td>{{ $teacher->birth_place }},
                                            {{ \Carbon\Carbon::parse($teacher->birth_date)->format('d-m-Y') }}
                                        </td>
                                        <td>{{ $teacher->address }}</td>
                                        <td>{{ $teacher->phone }}</td>
                                        <td>{{ $teacher->email ?? '-' }}</td>
                                        <td>{{ $teacher->religion }}</td>
                                        <td>{{ $teacher->enrollment_year }}</td>
                                        <td>
                                            <span
                                                class="badge {{ $teacher->status === 'active' ? 'bg-success' : 'bg-secondary' }}">
                                                {{ ucfirst($teacher->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if (auth()->user()->level == 1)
                                                    <a href="#" class="btn btn-sm btn-warning edit-btn"
                                                        data-id="{{ $teacher->id }}" data-nip="{{ $teacher->nip }}"
                                                        data-full_name="{{ $teacher->full_name }}"
                                                        data-gender="{{ $teacher->gender }}"
                                                        data-birth_place="{{ $teacher->birth_place }}"
                                                        data-birth_date="{{ $teacher->birth_date }}"
                                                        data-address="{{ $teacher->address }}"
                                                        data-phone="{{ $teacher->phone }}"
                                                        data-email="{{ $teacher->email }}"
                                                        data-religion="{{ $teacher->religion }}"
                                                        data-enrollment_year="{{ $teacher->enrollment_year }}"
                                                        data-status="{{ $teacher->status }}"
                                                        data-photo="{{ asset('storage/' . $teacher->photo) }}"
                                                        data-url="{{ route('admin.teacher.update', $teacher->id) }}">
                                                        Edit
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-danger ms-2 delete-btn"
                                                        data-id="{{ $teacher->id }}"
                                                        data-url="{{ route('admin.teacher.destroy', $teacher->id) }}">
                                                        Hapus
                                                    </button>
                                                @else
                                                    {{-- Jika level != 1, bisa ditampilkan teks kosong atau “—” --}}
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Modal Tambah -->
    <div class="modal fade" id="tambahGuruModal" tabindex="-1" aria-labelledby="tambahGuruModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-white" id="tambahGuruModalLabel">Tambah Data Guru Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.teacher.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-3">
                            <!-- Kolom Kiri -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nip" class="form-label">NIP <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="nip" name="nip" maxlength="20"
                                        required>
                                </div>

                                <div class="mb-3">
                                    <label for="full_name" class="form-label">Nama Lengkap <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="full_name" name="full_name" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                                    <div class="d-flex gap-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="gender" id="genderM"
                                                value="M" required>
                                            <label class="form-check-label" for="genderM">Laki-laki</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="gender" id="genderF"
                                                value="F">
                                            <label class="form-check-label" for="genderF">Perempuan</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="birth_place" class="form-label">Tempat Lahir <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="birth_place" name="birth_place"
                                            required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="birth_date" class="form-label">Tanggal Lahir <span
                                                class="text-danger">*</span></label>
                                        <input type="date" class="form-control" id="birth_date" name="birth_date"
                                            required>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="phone" class="form-label">Nomor Telepon <span
                                            class="text-danger">*</span></label>
                                    <input type="tel" class="form-control" id="phone" name="phone" required>
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email">
                                </div>

                                <div class="mb-3">
                                    <label for="address" class="form-label">Alamat <span
                                            class="text-danger">*</span></label>
                                    <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
                                </div>
                            </div>

                            <!-- Kolom Kanan -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="religion" class="form-label">Agama <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select" id="religion" name="religion" required>
                                        <option value="">Pilih Agama</option>
                                        <option value="Islam">Islam</option>
                                        <option value="Kristen">Kristen</option>
                                        <option value="Katolik">Katolik</option>
                                        <option value="Hindu">Hindu</option>
                                        <option value="Buddha">Buddha</option>
                                        <option value="Konghucu">Konghucu</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="enrollment_year" class="form-label">Tahun Masuk <span
                                            class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="enrollment_year"
                                        name="enrollment_year" min="2000" max="2099" step="1"
                                        value="<?= date('Y') ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label for="status" class="form-label">Status Guru <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select" id="status" name="status" required>
                                        <option value="">Pilih Status</option>
                                        <option value="active">Aktif</option>
                                        <option value="inactive">Tidak Aktif</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="photo" class="form-label">Foto Profil</label>
                                    <input class="form-control" type="file" id="photo" name="photo"
                                        accept="image/*" onchange="previewPhoto(event)">
                                    <small class="text-muted">Format: JPG, PNG (Maks 2MB)</small>
                                    <div class="mt-1">
                                        <img id="photo-preview" src="#" alt="Preview Foto"
                                            class="img-thumbnail d-none" style="max-width: 100px;">
                                    </div>
                                </div>
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

    <!-- Modal Edit -->
    <div class="modal fade" id="editTeacherModal" tabindex="-1" aria-labelledby="editTeacherModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form id="form-edit-teacher" method="POST" enctype="multipart/form-data">
                @csrf
                @method('POST') <!-- method POST sesuai route Anda -->
                <div class="modal-content">
                    <div class="modal-header bg-warning">
                        <h5 class="modal-title text-white">Edit Data Guru</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <!-- Kolom Kiri -->
                            <div class="col-md-6">
                                <input type="hidden" id="edit_id">
                                <div class="mb-3">
                                    <label for="edit_nip" class="form-label">NIP <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="edit_nip" name="nip" required>
                                </div>
                                <div class="mb-3">
                                    <label for="edit_full_name" class="form-label">Nama Lengkap <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="edit_full_name" name="full_name"
                                        required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                                    <div class="d-flex gap-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="gender"
                                                id="edit_genderM" value="M" required>
                                            <label class="form-check-label" for="edit_genderM">Laki-laki</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="gender"
                                                id="edit_genderF" value="F">
                                            <label class="form-check-label" for="edit_genderF">Perempuan</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="edit_birth_place" class="form-label">Tempat Lahir <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="edit_birth_place"
                                            name="birth_place" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="edit_birth_date" class="form-label">Tanggal Lahir <span
                                                class="text-danger">*</span></label>
                                        <input type="date" class="form-control" id="edit_birth_date"
                                            name="birth_date" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="edit_phone" class="form-label">Nomor Telepon <span
                                            class="text-danger">*</span></label>
                                    <input type="tel" class="form-control" id="edit_phone" name="phone" required>
                                </div>
                                <div class="mb-3">
                                    <label for="edit_email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="edit_email" name="email">
                                </div>
                                <div class="mb-3">
                                    <label for="edit_address" class="form-label">Alamat <span
                                            class="text-danger">*</span></label>
                                    <textarea class="form-control" id="edit_address" name="address" rows="3" required></textarea>
                                </div>
                            </div>
                            <!-- Kolom Kanan -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_religion" class="form-label">Agama <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select" id="edit_religion" name="religion" required>
                                        <option value="">Pilih Agama</option>
                                        <option value="Islam">Islam</option>
                                        <option value="Kristen">Kristen</option>
                                        <option value="Katolik">Katolik</option>
                                        <option value="Hindu">Hindu</option>
                                        <option value="Buddha">Buddha</option>
                                        <option value="Konghucu">Konghucu</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="edit_enrollment_year" class="form-label">Tahun Masuk <span
                                            class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="edit_enrollment_year"
                                        name="enrollment_year" required>
                                </div>
                                <div class="mb-3">
                                    <label for="edit_status" class="form-label">Status Guru <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select" id="edit_status" name="status" required>
                                        <option value="">Pilih Status</option>
                                        <option value="active">Aktif</option>
                                        <option value="inactive">Tidak Aktif</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="edit_photo" class="form-label">Foto Profil</label>
                                    <input class="form-control" type="file" id="edit_photo" name="photo"
                                        accept="image/*">
                                    <small class="text-muted">Format: JPG, PNG (Maks 2MB)</small>
                                    <br>
                                    <img id="edit_photo_preview" src="" alt="Preview Foto" class="mt-2 rounded"
                                        style="max-width: 100px; display: none;">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-warning">Update Data</button>
                    </div>
                </div>
            </form>
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

    @if ($errors->has('nip'))
        <script>
            Swal.fire({
                icon: 'warning',
                title: 'Peringatan!',
                text: '{{ $errors->first('nip') }}',
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
        // Simple Datatable
        let tableGuru = document.querySelector('#table-guru');
        let dataTable = new simpleDatatables.DataTable(tableGuru);
    </script>

    {{-- PREVIEW FOTO --}}
    <script>
        function previewPhoto(event) {
            const input = event.target;
            const preview = document.getElementById('photo-preview');

            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('d-none');
                };

                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>

    {{-- MODAL EDIT --}}
    <script>
        document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', function() {
                const form = document.getElementById('form-edit-teacher');
                const photoPreview = document.getElementById('edit_photo_preview');

                // Gunakan URL update dari data-url
                form.action = this.dataset.url;

                // Set preview foto
                if (photoPreview && this.dataset.photo) {
                    photoPreview.src = this.dataset.photo;
                    photoPreview.style.display = 'block';
                }

                // Set nilai form
                document.getElementById('edit_nip').value = this.dataset.nip;
                document.getElementById('edit_full_name').value = this.dataset.full_name;
                document.getElementById('edit_birth_place').value = this.dataset.birth_place;
                document.getElementById('edit_birth_date').value = this.dataset.birth_date;
                document.getElementById('edit_phone').value = this.dataset.phone;
                document.getElementById('edit_email').value = this.dataset.email;
                document.getElementById('edit_address').value = this.dataset.address;
                document.getElementById('edit_religion').value = this.dataset.religion;
                document.getElementById('edit_enrollment_year').value = this.dataset.enrollment_year;
                document.getElementById('edit_status').value = this.dataset.status;

                // Gender radio
                document.getElementById('edit_genderM').checked = this.dataset.gender === 'M';
                document.getElementById('edit_genderF').checked = this.dataset.gender === 'F';

                // Tampilkan modal
                new bootstrap.Modal(document.getElementById('editTeacherModal')).show();
            });
        });
    </script>

    <script>
        document.getElementById('edit_photo').addEventListener('change', function(event) {
            const photoPreview = document.getElementById('edit_photo_preview');
            if (event.target.files && event.target.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    photoPreview.src = e.target.result;
                    photoPreview.style.display = 'block';
                }
                reader.readAsDataURL(event.target.files[0]);
            }
        });
    </script>

    {{-- HAPUS DATA --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deleteButtons = document.querySelectorAll('.delete-btn');

            deleteButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    const teacherId = this.getAttribute('data-id');
                    const deleteUrl = this.getAttribute('data-url');

                    Swal.fire({
                        title: 'Yakin ingin menghapus?',
                        text: "Data guru dan foto akan dihapus permanen!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            fetch(deleteUrl, {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': document.querySelector(
                                            'meta[name="csrf-token"]').getAttribute(
                                            'content'),
                                        'Accept': 'application/json'
                                    }
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        Swal.fire('Berhasil!', data.message, 'success')
                                            .then(() => {
                                                location.reload(); // reload halaman
                                            });
                                    } else {
                                        Swal.fire('Gagal', data.message, 'error');
                                    }
                                })
                                .catch(() => {
                                    Swal.fire('Gagal',
                                        'Terjadi kesalahan saat menghubungi server.',
                                        'error');
                                });
                        }
                    });
                });
            });
        });
    </script>
@endpush
