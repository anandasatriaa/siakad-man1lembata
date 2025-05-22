@extends('admin.layouts.app')

@section('title', 'Kelas')

@push('css')

<style>
    .avatar img {
    border-radius: 50%;
    object-fit: cover;
}

.clickable-row {
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .clickable-row:hover {
        background-color: #f8f9fa;
    }
    
    .collapse-row td {
        padding: 0 !important;
        border-bottom: 2px solid #dee2e6;
    }
    
    .bi-chevron-right {
        transition: transform 0.3s ease;
    }
    
    .collapsed .bi-chevron-right {
        transform: rotate(90deg);
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
                    <button type="button" class="btn btn-primary rounded-pill d-inline-flex align-items-center"
                        data-bs-toggle="modal" data-bs-target="#tambahKelasModal">
                        <i class="bi bi-plus-circle me-2"></i>
                        <span>Tambah Kelas</span>
                    </button>
                </div>
            </div>
        </div>
        <section class="section">
            <div class="card">
                <div class="card-header">
                    Table Data Kelas
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="table-kelas">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>No</th>
                                    <th>Nama Kelas</th>
                                    <th>Wali Kelas</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data Dummy 1 -->
                                <tr data-bs-toggle="collapse" data-bs-target="#siswaXIPA1" aria-expanded="false" 
                                    class="clickable-row align-middle">
                                    <td class="text-center">
                                        <i class="bi bi-chevron-right"></i>
                                    </td>
                                    <td>1</td>
                                    <td>X IPA 1</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-md me-3">
                                                <img src="{{ asset('assets/images/faces/2.jpg') }}" alt="Foto Profil">
                                            </div>
                                            <span>Budi Santoso</span>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="collapse" id="siswaXIPA1">
                                    <td colspan="4">
                                        <div class="p-3">
                                            <h6 class="mb-3">Daftar Siswa X IPA 1</h6>
                                            <div class="table-responsive">
                                                <table class="table table-sm">
                                                    <thead>
                                                        <tr>
                                                            <th>NIS</th>
                                                            <th>Nama Siswa</th>
                                                            <th>Jenis Kelamin</th>
                                                            <th>Telepon</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>202401001</td>
                                                            <td><div class="avatar avatar-md me-3">
                                                                <img src="{{ asset('assets/images/faces/6.jpg') }}" alt="Foto Profil">
                                                            </div> Andi Wijaya</td>
                                                            <td>Laki-laki</td>
                                                            <td>0812-3456-7890</td>
                                                        </tr>
                                                        <tr>
                                                            <td>202401002</td>
                                                            <td><div class="avatar avatar-md me-3">
                                                                <img src="{{ asset('assets/images/faces/7.jpg') }}" alt="Foto Profil">
                                                            </div> Siti Aminah</td>
                                                            <td>Perempuan</td>
                                                            <td>0813-9876-5432</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
        
                                <!-- Data Dummy 2 -->
                                <tr data-bs-toggle="collapse" data-bs-target="#siswaXIPA2" aria-expanded="false" 
                                    class="clickable-row align-middle">
                                    <td class="text-center">
                                        <i class="bi bi-chevron-right"></i>
                                    </td>
                                    <td>2</td>
                                    <td>X IPA 2</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-md me-3">
                                                <img src="{{ asset('assets/images/faces/1.jpg') }}" alt="Foto Profil">
                                            </div>
                                            <span>Ani Rahayu</span>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="collapse" id="siswaXIPA2">
                                    <td colspan="4">
                                        <div class="p-3">
                                            <h6 class="mb-3">Daftar Siswa X IPA 2</h6>
                                            <div class="table-responsive">
                                                <table class="table table-sm">
                                                    <thead>
                                                        <tr>
                                                            <th>NIS</th>
                                                            <th>Nama Siswa</th>
                                                            <th>Jenis Kelamin</th>
                                                            <th>Telepon</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>202401003</td>
                                                            <td><div class="avatar avatar-md me-3">
                                                                <img src="{{ asset('assets/images/faces/7.jpg') }}" alt="Foto Profil">
                                                            </div> Rina Melati</td>
                                                            <td>Perempuan</td>
                                                            <td>0812-1111-2222</td>
                                                        </tr>
                                                        <tr>
                                                            <td>202401004</td>
                                                            <td><div class="avatar avatar-md me-3">
                                                                <img src="{{ asset('assets/images/faces/8.jpg') }}" alt="Foto Profil">
                                                            </div> Dedi Pratama</td>
                                                            <td>Laki-laki</td>
                                                            <td>0813-3333-4444</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
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
    <div class="modal fade" id="tambahKelasModal" tabindex="-1" aria-labelledby="tambahKelasModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-white" id="tambahKelasModalLabel">Tambah Data Kelas Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form>
                    <div class="modal-body">
                        <div class="row g-3">
                            <!-- Kolom Kiri -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="full_name" class="form-label">Nama Kelas <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="full_name" name="full_name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="" class="form-label">Wali Kelas <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select" id="" name="" required>
                                        <option value="">Pilih Wali Kelas</option>
                                        <option value="Andi">Andi</option>
                                        <option value="Raden">Raden</option>
                                        <option value="Bagas">Bagas</option>
                                        <option value="Andre">Andre</option>
                                        <option value="Adrian">Adrian</option>
                                        <option value="Salma">Salma</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Kolom Kanan -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="" class="form-label">Siswa <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select" id="" name="" required>
                                        <option value="">Pilih Siswa</option>
                                        <option value="Andi">Andi</option>
                                        <option value="Raden">Raden</option>
                                        <option value="Bagas">Bagas</option>
                                        <option value="Andre">Andre</option>
                                        <option value="Adrian">Adrian</option>
                                        <option value="Salma">Salma</option>
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
        let tableKelas = document.querySelector('#table-kelas');
        let dataTable = new simpleDatatables.DataTable(tableKelas);
    </script>

<script>
    document.querySelectorAll('.clickable-row').forEach(row => {
        row.addEventListener('click', (e) => {
            const icon = row.querySelector('.bi-chevron-right');
            row.classList.toggle('collapsed');
        });
    });
</script>
@endpush
