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
                    @if (auth()->user()->level == 1)
                        <button type="button" class="btn btn-primary rounded-pill d-inline-flex align-items-center"
                            data-bs-toggle="modal" data-bs-target="#modalAdd">
                            <i class="bi bi-plus-circle me-2"></i>
                            <span>Tambah Mata Pelajaran</span>
                        </button>
                    @endif
                </div>
            </div>
        </div>
        <div class="section">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Data Mata Pelajaran</h4>
                </div>
                <div class="card-body">
                    @foreach (['X', 'XI', 'XII'] as $grade)
                        <h5 class="mt-4">Kelas {{ $grade }}</h5>
                        @if (!empty($courses[$grade]))
                            <ul class="list-group mb-3">
                                @foreach ($courses[$grade] as $course)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong>{{ $course->name }}</strong>
                                            @if ($course->code)
                                                <span class="text-muted">({{ $course->code }})</span>
                                            @endif
                                        </div>
                                        @if (auth()->user()->level == 1)
                                            <div>
                                                <button class="btn btn-sm btn-warning btn-edit"
                                                    data-id="{{ $course->id }}" data-name="{{ $course->name }}"
                                                    data-code="{{ $course->code }}" data-grade="{{ $course->grade }}"
                                                    data-route="{{ route('admin.course.update', $course->id) }}"
                                                    data-bs-toggle="modal" data-bs-target="#modalEdit">
                                                    Edit
                                                </button>
                                                <button class="btn btn-sm btn-danger btn-delete"
                                                    data-id="{{ $course->id }}">Hapus</button>
                                                <form id="delete-form-{{ $course->id }}"
                                                    action="{{ route('admin.course.destroy', $course->id) }}"
                                                    method="POST" style="display: none;">
                                                    @csrf
                                                </form>
                                            </div>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-muted">Belum ada mata pelajaran untuk kelas {{ $grade }}.</p>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Add -->
    <div class="modal fade" id="modalAdd" tabindex="-1" aria-labelledby="modalAddLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('admin.course.store') }}" method="POST" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAddLabel">Tambah Mata Pelajaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Kode Mata Pelajaran <span class="text-danger">*</span></label>
                        <input type="text" name="code" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Mata Pelajaran <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kelas <span class="text-danger">*</span></label>
                        <select name="grade" class="form-select" required>
                            <option value="">Pilih Kelas</option>
                            <option value="X">X</option>
                            <option value="XI">XI</option>
                            <option value="XII">XII</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary">Simpan</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Edit --}}
    <div class="modal fade" id="modalEdit" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form id="formEdit" method="POST" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Edit Mata Pelajaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Kode <span class="text-danger">*</span></label>
                        <input type="text" name="code" id="edit-code" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="edit-name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kelas <span class="text-danger">*</span></label>
                        <select name="grade" id="edit-grade" class="form-select" required>
                            <option value="X">X</option>
                            <option value="XI">XI</option>
                            <option value="XII">XII</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary">Simpan</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
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
        document.querySelectorAll('.btn-delete').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.dataset.id;
                Swal.fire({
                    title: 'Yakin ingin menghapus?',
                    text: "Tindakan ini tidak bisa dibatalkan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, hapus!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('delete-form-' + id).submit();
                    }
                });
            });
        });
    </script>

    <script>
        document.querySelectorAll('.btn-edit').forEach(button => {
            button.addEventListener('click', function() {
                const name = this.dataset.name;
                const code = this.dataset.code;
                const grade = this.dataset.grade;
                const route = this.dataset.route;

                document.getElementById('edit-name').value = name;
                document.getElementById('edit-code').value = code;
                document.getElementById('edit-grade').value = grade;

                const form = document.getElementById('formEdit');
                form.action = route; // Gunakan route dari Blade
            });
        });
    </script>
@endpush
