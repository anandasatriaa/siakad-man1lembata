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
                    <button type="button" class="btn btn-primary rounded-pill d-inline-flex align-items-center"
                        data-bs-toggle="modal" data-bs-target="#tambahGuruModal">
                        <i class="bi bi-plus-circle me-2"></i>
                        <span>Tambah Guru</span>
                    </button>
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
                                    <th>NIS</th>
                                    <th>Nama Lengkap</th>
                                    <th>Jenis Kelamin</th>
                                    <th>Telepon</th>
                                    <th>Wali Kelas</th>
                                    <th>Tahun Masuk</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data Dummy 1 -->
                                <tr>
                                    <td>202401001</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-md me-3">
                                                <img src="{{ asset('assets/images/faces/2.jpg') }}" alt="Foto Profil">
                                            </div>
                                            <span>Budi Santoso</span>
                                        </div>
                                    </td>
                                    <td>Laki-laki</td>
                                    <td>0812-3456-7890</td>
                                    <td>X IPA 1</td>
                                    <td>2024</td>
                                    <td>
                                        <span class="badge bg-success">Aktif</span>
                                    </td>
                                </tr>
        
                                <!-- Data Dummy 2 -->
                                <tr>
                                    <td>202401002</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-md me-3">
                                                <img src="{{ asset('assets/images/faces/3.jpg') }}" alt="Foto Profil">
                                            </div>
                                            <span>Siti Aminah</span>
                                        </div>
                                    </td>
                                    <td>Perempuan</td>
                                    <td>0813-9876-5432</td>
                                    <td>X IPS 2</td>
                                    <td>2024</td>
                                    <td>
                                        <span class="badge bg-success">Aktif</span>
                                    </td>
                                </tr>
        
                                <!-- Data Dummy 3 -->
                                <tr>
                                    <td>202301005</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-md me-3">
                                                <img src="{{ asset('assets/images/faces/4.jpg') }}" alt="Foto Profil">
                                            </div>
                                            <span>Andi Wijaya</span>
                                        </div>
                                    </td>
                                    <td>Laki-laki</td>
                                    <td>0821-1122-3344</td>
                                    <td>XI IPA 3</td>
                                    <td>2023</td>
                                    <td>
                                        <span class="badge bg-secondary">Non-Aktif</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="tambahGuruModal" tabindex="-1" aria-labelledby="tambahGuruModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-white" id="tambahGuruModalLabel">Tambah Data Guru Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form>
                    <div class="modal-body">
                        <div class="row g-3">
                            <!-- Kolom Kiri -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nis" class="form-label">NIS <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="nis" name="nis" maxlength="20"
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
                                    <label for="address" class="form-label">Alamat <span
                                            class="text-danger">*</span></label>
                                    <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
                                </div>
                            </div>

                            <!-- Kolom Kanan -->
                            <div class="col-md-6">
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
                                    <label for="class_id" class="form-label">Kelas <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select" id="class_id" name="class_id" required>
                                        <option value="">Pilih Kelas</option>
                                        <!-- Option akan diisi dari database -->
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="guardian_name" class="form-label">Nama Wali <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="guardian_name" name="guardian_name"
                                        required>
                                </div>

                                <div class="mb-3">
                                    <label for="guardian_phone" class="form-label">Telepon Wali <span
                                            class="text-danger">*</span></label>
                                    <input type="tel" class="form-control" id="guardian_phone" name="guardian_phone"
                                        required>
                                </div>

                                <div class="mb-3">
                                    <label for="photo" class="form-label">Foto Profil</label>
                                    <input class="form-control" type="file" id="photo" name="photo"
                                        accept="image/*">
                                    <small class="text-muted">Format: JPG, PNG (Maks 2MB)</small>
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
@endsection

@push('js')
    <script>
        // Simple Datatable
        let tableGuru = document.querySelector('#table-guru');
        let dataTable = new simpleDatatables.DataTable(tableGuru);
    </script>
@endpush
