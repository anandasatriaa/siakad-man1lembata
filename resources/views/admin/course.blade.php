@extends('admin.layouts.app')

@section('title', 'Mata Pelajaran')

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
                    <h3>Mata Pelajaran</h3>
                    <p class="text-subtitle text-muted">Semua informasi mengenai mata pelajaran</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first text-md-end text-start mb-2 mb-md-0">
                    <button type="button" class="btn btn-primary rounded-pill d-inline-flex align-items-center"
                        data-bs-toggle="modal" data-bs-target="#tambahMataPelajaranModal">
                        <i class="bi bi-plus-circle me-2"></i>
                        <span>Tambah Mata Pelajaran</span>
                    </button>
                </div>
            </div>
        </div>
        <section class="section">
            <div class="card">
                <div class="card-header">
                    Table Data Mata Pelajaran
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="table-mata-pelajaran">
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
    <div class="modal fade" id="tambahMataPelajaranModal" tabindex="-1" aria-labelledby="tambahMataPelajaranModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-white" id="tambahMataPelajaranModalLabel">Tambah Data Mata Pelajaran Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form>
                    <div class="modal-body">
                        <div class="row g-3">
                            <!-- Kolom Kiri -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="full_name" class="form-label">Nama Mata Pelajaran <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="full_name" name="full_name" required>
                                </div>
                            </div>

                            <!-- Kolom Kanan -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="religion" class="form-label">Kelas <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select" id="religion" name="religion" required>
                                        <option value="">Pilih Kelas</option>
                                        <option value="X">X</option>
                                        <option value="XI">XI</option>
                                        <option value="XII">XII</option>
                                    </select>
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
        let tableMataPelajaran = document.querySelector('#table-mata-pelajaran');
        let dataTable = new simpleDatatables.DataTable(tableMataPelajaran);
    </script>
@endpush
