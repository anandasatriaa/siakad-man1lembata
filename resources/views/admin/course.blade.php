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
                    <section class="section">
                        <div class="card">
                            <div class="card-header">
                                Table Data Mata Pelajaran per Kelas (Klik untuk buka/tutup)
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover" id="table-mata-pelajaran">
                                        <thead>
                                            <tr>
                                                <th colspan="2">Kelas / Klik untuk expand</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {{-- Kelas X --}}
                                            <tr class="table-primary" data-bs-toggle="collapse" data-bs-target=".groupX"
                                                aria-expanded="false" style="cursor: pointer;">
                                                <td colspan="2">Kelas X</td>
                                            </tr>
                                            <tr class="collapse groupX">
                                                <td></td>
                                                <td>Matematika</td>
                                            </tr>
                                            <tr class="collapse groupX">
                                                <td></td>
                                                <td>Fisika</td>
                                            </tr>
                                            <tr class="collapse groupX">
                                                <td></td>
                                                <td>Kimia</td>
                                            </tr>
                                            <tr class="collapse groupX">
                                                <td></td>
                                                <td>Biologi</td>
                                            </tr>
                                            <tr class="collapse groupX">
                                                <td></td>
                                                <td>Bahasa Inggris</td>
                                            </tr>

                                            {{-- Kelas XI --}}
                                            <tr class="table-primary" data-bs-toggle="collapse" data-bs-target=".groupXI"
                                                aria-expanded="false" style="cursor: pointer;">
                                                <td colspan="2">Kelas XI</td>
                                            </tr>
                                            <tr class="collapse groupXI">
                                                <td></td>
                                                <td>Ekonomi</td>
                                            </tr>
                                            <tr class="collapse groupXI">
                                                <td></td>
                                                <td>Sosiologi</td>
                                            </tr>
                                            <tr class="collapse groupXI">
                                                <td></td>
                                                <td>Geografi</td>
                                            </tr>
                                            <tr class="collapse groupXI">
                                                <td></td>
                                                <td>Sejarah</td>
                                            </tr>
                                            <tr class="collapse groupXI">
                                                <td></td>
                                                <td>Bahasa Indonesia</td>
                                            </tr>

                                            {{-- Kelas XII --}}
                                            <tr class="table-primary" data-bs-toggle="collapse" data-bs-target=".groupXII"
                                                aria-expanded="false" style="cursor: pointer;">
                                                <td colspan="2">Kelas XII</td>
                                            </tr>
                                            <tr class="collapse groupXII">
                                                <td></td>
                                                <td>Sastra Indonesia</td>
                                            </tr>
                                            <tr class="collapse groupXII">
                                                <td></td>
                                                <td>Antropologi</td>
                                            </tr>
                                            <tr class="collapse groupXII">
                                                <td></td>
                                                <td>Bahasa Asing</td>
                                            </tr>
                                            <tr class="collapse groupXII">
                                                <td></td>
                                                <td>Seni Budaya</td>
                                            </tr>
                                            <tr class="collapse groupXII">
                                                <td></td>
                                                <td>Kewirausahaan</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </section>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="tambahMataPelajaranModal" tabindex="-1" aria-labelledby="tambahMataPelajaranModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-white" id="tambahMataPelajaranModalLabel">Tambah Data Mata Pelajaran Baru
                    </h5>
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
@endpush