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
                    @php
                        $grades = ['X', 'XI', 'XII'];
                        $classes = [
                            'X' => [
                                (object) [
                                    'name' => 'X IPA 1',
                                    'teacher' => (object) [
                                        'name' => 'Pak Budi',
                                        'avatar' => asset('assets/images/faces/2.jpg'),
                                    ],
                                    'students' => [
                                        (object) [
                                            'name' => 'Andi',
                                            'avatar' => asset('assets/images/faces/1.jpg'),
                                            'phone' => '081234567890',
                                            'address' => 'Jl. Merdeka No.10, Jakarta',
                                            'email' => 'andi@example.com',
                                        ],
                                        (object) [
                                            'name' => 'Siti',
                                            'avatar' => asset('assets/images/faces/3.jpg'),
                                            'phone' => '082345678901',
                                            'address' => 'Jl. Melati No.5, Bandung',
                                            'email' => 'siti@example.com',
                                        ],
                                        (object) [
                                            'name' => 'Rina',
                                            'avatar' => asset('assets/images/faces/4.jpg'),
                                            'phone' => '083456789012',
                                            'address' => 'Jl. Mawar No.8, Surabaya',
                                            'email' => 'rina@example.com',
                                        ],
                                    ],
                                ],
                                (object) [
                                    'name' => 'X IPA 2',
                                    'teacher' => (object) ['name' => 'Bu Ani', 'avatar' => asset('assets/images/faces/5.jpg')],
                                    'students' => [
                                        (object) [
                                            'name' => 'Joko',
                                            'avatar' => asset('assets/images/faces/6.jpg'),
                                            'phone' => '084567890123',
                                            'address' => 'Jl. Kenanga No.12, Yogyakarta',
                                            'email' => 'joko@example.com',
                                        ],
                                        (object) [
                                            'name' => 'Dewi',
                                            'avatar' => asset('assets/images/faces/7.jpg'),
                                            'phone' => '085678901234',
                                            'address' => 'Jl. Anggrek No.3, Medan',
                                            'email' => 'dewi@example.com',
                                        ],
                                    ],
                                ],
                            ],
                            'XI' => [
                                (object) [
                                    'name' => 'XI IPA 1',
                                    'teacher' => (object) ['name' => 'Pak Agus', 'avatar' => asset('assets/images/faces/8.jpg')],
                                    'students' => [
                                        (object) [
                                            'name' => 'Tono',
                                            'avatar' => asset('assets/images/faces/9.jpg'),
                                            'phone' => '086789012345',
                                            'address' => 'Jl. Cempaka No.7, Semarang',
                                            'email' => 'tono@example.com',
                                        ],
                                        (object) [
                                            'name' => 'Maya',
                                            'avatar' => asset('assets/images/faces/10.jpg'),
                                            'phone' => '087890123456',
                                            'address' => 'Jl. Dahlia No.4, Malang',
                                            'email' => 'maya@example.com',
                                        ],
                                    ],
                                ],
                            ],
                            'XII' => [
                                (object) [
                                    'name' => 'XII IPS 1',
                                    'teacher' => (object) ['name' => 'Bu Rina', 'avatar' => asset('assets/images/faces/11.jpg')],
                                    'students' => [
                                        (object) [
                                            'name' => 'Beni',
                                            'avatar' => asset('assets/images/faces/12.jpg'),
                                            'phone' => '088901234567',
                                            'address' => 'Jl. Flamboyan No.9, Bali',
                                            'email' => 'beni@example.com',
                                        ],
                                        (object) [
                                            'name' => 'Lia',
                                            'avatar' => asset('assets/images/faces/13.jpg'),
                                            'phone' => '089012345678',
                                            'address' => 'Jl. Kamboja No.6, Makassar',
                                            'email' => 'lia@example.com',
                                        ],
                                    ],
                                ],
                            ],
                        ];
                    @endphp

                    <ul class="nav nav-tabs nav-justified mb-4" role="tablist">
                        @foreach($grades as $grade)
                            <li class="nav-item flex-fill text-center" role="presentation">
                                <button class="nav-link w-100 @if($loop->first) active @endif" id="tab-{{ $grade }}"
                                    data-bs-toggle="tab" data-bs-target="#kelas-{{ $grade }}" type="button" role="tab"
                                    aria-controls="kelas-{{ $grade }}"
                                    aria-selected="@if($loop->first) true @else false @endif">
                                    Kelas {{ $grade }}
                                </button>
                            </li>
                        @endforeach
                    </ul>

                    <div class="tab-content">
                        @foreach($grades as $grade)
                            <div class="tab-pane fade @if($loop->first) show active @endif" id="kelas-{{ $grade }}"
                                role="tabpanel" aria-labelledby="tab-{{ $grade }}">

                                @if(!empty($classes[$grade]))
                                    @foreach($classes[$grade] as $idx => $class)
                                        @php $collapseId = "collapse-{$grade}-{$idx}"; @endphp
                                        <div class="card mb-3">
                                            <div class="card-header d-flex justify-content-between align-items-center">
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar avatar-md me-3">
                                                        <img src="{{ $class->teacher->avatar }}" class="rounded-circle"
                                                            alt="Foto Wali Kelas">
                                                    </div>
                                                    <div>
                                                        <strong>{{ $class->name }}</strong><br>
                                                        <small class="text-muted">Wali: {{ $class->teacher->name }}</small>
                                                    </div>
                                                </div>
                                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="collapse"
                                                    data-bs-target="#{{ $collapseId }}" aria-expanded="false"
                                                    aria-controls="{{ $collapseId }}">
                                                    Lihat Murid
                                                </button>
                                            </div>
                                            <div id="{{ $collapseId }}" class="collapse">
                                                <div class="card-body">
                                                    <h6>Daftar Murid:</h6>
                                                    <ul class="list-group">
                                                        @foreach($class->students as $student)
                                                            <li class="list-group-item">
                                                                <div class="d-flex align-items-center mb-2">
                                                                    <div class="avatar avatar-sm me-3">
                                                                        <img src="{{ $student->avatar }}" class="rounded-circle"
                                                                            alt="Foto Murid">
                                                                    </div>
                                                                    <div>
                                                                        <strong>{{ $student->name }}</strong><br>
                                                                        <small class="text-muted">{{ $student->email }}</small>
                                                                    </div>
                                                                </div>
                                                                <div class="ms-5">
                                                                    <div><i class="bi bi-telephone-fill me-1"></i>{{ $student->phone }}
                                                                    </div>
                                                                    <div><i class="bi bi-geo-alt-fill me-1"></i>{{ $student->address }}
                                                                    </div>
                                                                </div>
                                                            </li>
                                                        @endforeach
                                                    </ul>
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
                                    <label for="" class="form-label">Wali Kelas <span class="text-danger">*</span></label>
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
                                    <label for="" class="form-label">Siswa <span class="text-danger">*</span></label>
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
        document.querySelectorAll('[data-bs-toggle="collapse"]').forEach(btn => {
            btn.addEventListener('click', function () {
                const chevron = this.querySelector('.chevron');
                const target = document.querySelector(this.dataset.bsTarget);
                target.addEventListener('shown.bs.collapse', () => chevron.innerHTML = '\u25B2');
                target.addEventListener('hidden.bs.collapse', () => chevron.innerHTML = '\u25BC');
            });
        });
    </script>
@endpush