@extends('teacher.layouts.app')

@section('title', 'Upload Materi')

@push('css')
    <style>
        
    </style>
@endpush

@section('content')
<div class="container">
    <h2 class="mb-4">Daftar Materi Saya</h2>

    {{-- Pesan sukses --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Tombol Tambah Materi --}}
    <div class="mb-3 text-end">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAdd">
            <i class="bi bi-plus-circle me-1"></i> Tambah Materi
        </button>
    </div>

    {{-- 1) Tabel List Materi --}}
    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Judul</th>
                    <th>Kelas</th>
                    <th>Mata Pelajaran</th>
                    <th>Deskripsi</th>
                    <th>File</th>
                    <th>Diunggah</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($materials as $index => $mat)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $mat->title }}</td>
                        <td>{{ $mat->classroom ? $mat->classroom->name : '-' }}</td>
                        <td>{{ $mat->course ? $mat->course->name : '-' }}</td>
                        <td>{!! \Illuminate\Support\Str::limit($mat->description, 50, '...') !!}</td>
                        <td>
                            <a href="{{ asset('storage/' . $mat->file_path) }}" target="_blank" class="btn btn-sm btn-success">
                                <i class="bi bi-file-earmark-arrow-down-fill me-1"></i> Unduh
                            </a>
                        </td>
                        <td>{{ $mat->published_at ? $mat->published_at->format('d-m-Y H:i') : '-' }}</td>
                        <td>
                            {{-- Tombol Edit --}}
                            <button class="btn btn-sm btn-warning btn-edit"
                                data-id="{{ $mat->id }}"
                                data-title="{{ $mat->title }}"
                                data-class_id="{{ $mat->class_id }}"
                                data-course_id="{{ $mat->course_id }}"
                                data-description="{{ $mat->description }}"
                                data-file_path="{{ $mat->file_path }}"
                                data-file_type="{{ $mat->file_type }}"
                                data-bs-toggle="modal"
                                data-bs-target="#modalEdit">
                                <i class="bi bi-pencil-square"></i>
                            </button>

                            {{-- Tombol Hapus --}}
                            <form action="{{ route('teacher.material.destroy', $mat->id) }}" method="POST" class="d-inline-block"
                                  onsubmit="return confirm('Yakin ingin menghapus materi ini?');">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted">
                            Belum ada materi yang diunggah.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ================================================================= --}}
{{-- 2) Modal Add Materi --}}
{{-- ================================================================= --}}
<div class="modal fade" id="modalAdd" tabindex="-1" aria-labelledby="modalAddLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Materi Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('teacher.material.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    {{-- Judul --}}
                    <div class="mb-3">
                        <label for="addTitle" class="form-label">Judul <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="addTitle"
                               class="form-control @error('title') is-invalid @enderror"
                               value="{{ old('title') }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Kelas --}}
                    <div class="mb-3">
                        <label for="addClass" class="form-label">Pilih Kelas</label>
                        <select name="class_id" id="addClass"
                                class="form-select @error('class_id') is-invalid @enderror">
                            <option value="">— Pilih Kelas (opsional) —</option>
                            @foreach($classes as $cls)
                                <option value="{{ $cls->id }}" {{ old('class_id') == $cls->id ? 'selected' : '' }}>
                                    {{ $cls->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('class_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Course --}}
                    <div class="mb-3">
                        <label for="addCourse" class="form-label">Pilih Mata Pelajaran</label>
                        <select name="course_id" id="addCourse"
                                class="form-select @error('course_id') is-invalid @enderror">
                            <option value="">— Pilih Mata Pelajaran (opsional) —</option>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
                                    {{ $course->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('course_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Deskripsi --}}
                    <div class="mb-3">
                        <label for="addDescription" class="form-label">Deskripsi</label>
                        <textarea name="description" id="addDescription" rows="3"
                                  class="form-control @error('description') is-invalid @enderror"
                                  placeholder="Deskripsi singkat (opsional)">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- File --}}
                    <div class="mb-3">
                        <label for="addFile" class="form-label">Upload File <span class="text-danger">*</span></label>
                        <input type="file" name="file" id="addFile"
                               class="form-control @error('file') is-invalid @enderror" required>
                        @error('file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Format: pdf|doc|docx|ppt|pptx|zip (maks 10MB)</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ================================================================= --}}
{{-- 3) Modal Edit Materi --}}
{{-- ================================================================= --}}
<div class="modal fade" id="modalEdit" tabindex="-1" aria-labelledby="modalEditLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Materi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formEdit" action="#" method="POST" enctype="multipart/form-data">
                @csrf
                {{-- Karena update menggunakan POST, nanti URL-nya kita ganti via JS --}}
                <div class="modal-body">
                    {{-- Judul --}}
                    <div class="mb-3">
                        <label for="editTitle" class="form-label">Judul <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="editTitle"
                               class="form-control" required>
                    </div>

                    {{-- Kelas --}}
                    <div class="mb-3">
                        <label for="editClass" class="form-label">Pilih Kelas</label>
                        <select name="class_id" id="editClass" class="form-select">
                            <option value="">— Pilih Kelas (opsional) —</option>
                            @foreach($classes as $cls)
                                <option value="{{ $cls->id }}">{{ $cls->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Course --}}
                    <div class="mb-3">
                        <label for="editCourse" class="form-label">Pilih Mata Pelajaran</label>
                        <select name="course_id" id="editCourse" class="form-select">
                            <option value="">— Pilih Mata Pelajaran (opsional) —</option>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}">{{ $course->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Deskripsi --}}
                    <div class="mb-3">
                        <label for="editDescription" class="form-label">Deskripsi</label>
                        <textarea name="description" id="editDescription" rows="3"
                                  class="form-control"></textarea>
                    </div>

                    {{-- File (opsional ganti) --}}
                    <div class="mb-3">
                        <label for="editFile" class="form-label">Ganti File (opsional)</label>
                        <input type="file" name="file" id="editFile" class="form-control">
                        <div class="form-text">
                            Upload file baru jika ingin mengganti (format: pdf|doc|ppt|zip, maks 10MB).
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-warning">
                        Update
                    </button>
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
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                timer: 2000,
                showConfirmButton: false
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const editButtons = document.querySelectorAll('.btn-edit');
        editButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                const id          = this.getAttribute('data-id');
                const title       = this.getAttribute('data-title');
                const classId     = this.getAttribute('data-class_id');
                const courseId    = this.getAttribute('data-course_id');
                const description = this.getAttribute('data-description');

                // Atur action form ke route update sesuai id
                const formEdit = document.getElementById('formEdit');
                formEdit.action  = "{{ url('guru/material/update') }}/" + id;

                // Isi field di modal
                document.getElementById('editTitle').value       = title;
                document.getElementById('editClass').value       = classId;
                document.getElementById('editCourse').value      = courseId;
                document.getElementById('editDescription').value = description;
            });
        });
    });
</script>
@endpush
