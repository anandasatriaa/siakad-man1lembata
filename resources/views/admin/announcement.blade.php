@extends('admin.layouts.app')

@section('title', 'Pengumuman')

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
                    <h3>Pengumuman</h3>
                    <p class="text-subtitle text-muted">Semua informasi mengenai Pengumuman</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first text-md-end text-start mb-2 mb-md-0">
                    <button type="button" class="btn btn-primary rounded-pill d-inline-flex align-items-center"
                        data-bs-toggle="modal" data-bs-target="#addAnnouncementModal">
                        <i class="bi bi-plus-circle me-2"></i>
                        <span>Tambah Pengumuman</span>
                    </button>
                </div>
            </div>
        </div>
        <div class="section">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Data Pengumuman</h4>
                </div>
                <div class="card-body">
                    <!-- Tabel Pengumuman -->
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Judul</th>
                                <th>Isi</th>
                                <th>Status</th>
                                <th>Dibuat</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($announcements as $item)
                                <tr>
                                    <td>{{ $item->title }}</td>
                                    <td>{{ Str::limit($item->content, 50) }}</td>
                                    <td>
                                        <span class="badge {{ $item->is_active ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $item->is_active ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </td>
                                    <td>{{ $item->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        <!-- Tombol Edit: kirim data ke modal -->
                                        <button class="btn btn-sm btn-warning editBtn" data-id="{{ $item->id }}"
                                            data-title="{{ $item->title }}" data-content="{{ $item->content }}"
                                            data-active="{{ $item->is_active }}" data-bs-toggle="modal"
                                            data-bs-target="#editAnnouncementModal">
                                            Edit
                                        </button>

                                        <!-- Tombol Hapus -->
                                        <button class="btn btn-sm btn-danger deleteBtn" data-id="{{ $item->id }}">
                                            Hapus
                                        </button>

                                        <!-- Form tersembunyi -->
                                        <form id="deleteForm-{{ $item->id }}"
                                            action="{{ route('admin.announcement.destroy', $item->id) }}" method="POST"
                                            style="display: none;">
                                            @csrf
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah -->
    <div class="modal fade" id="addAnnouncementModal" tabindex="-1" aria-labelledby="addAnnouncementModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('admin.announcement.store') }}" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addAnnouncementModalLabel">Tambah Pengumuman</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Judul</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Isi</label>
                        <textarea name="content" class="form-control" rows="4" required></textarea>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_active" id="addIsActive" checked>
                        <label class="form-check-label" for="addIsActive">Aktif</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit -->
    <div class="modal fade" id="editAnnouncementModal" tabindex="-1" aria-labelledby="editAnnouncementModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" id="editAnnouncementForm" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="editAnnouncementModalLabel">Edit Pengumuman</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="editId">
                    <div class="mb-3">
                        <label>Judul</label>
                        <input type="text" name="title" id="editTitle" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Isi</label>
                        <textarea name="content" id="editContent" class="form-control" rows="4" required></textarea>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_active" id="editIsActive">
                        <label class="form-check-label" for="editIsActive">Aktif</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Update</button>
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
        document.addEventListener('DOMContentLoaded', function () {
            // SweetAlert konfirmasi hapus
            document.querySelectorAll('.deleteBtn').forEach(button => {
                button.addEventListener('click', function () {
                    const id = this.getAttribute('data-id');

                    Swal.fire({
                        title: 'Yakin ingin menghapus?',
                        text: "Data yang dihapus tidak dapat dikembalikan!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById(`deleteForm-${id}`).submit();
                        }
                    });
                });
            });

            // Script edit modal (tetap dipakai)
            const editForm = document.getElementById('editAnnouncementForm');
            const editIdInput = document.getElementById('editId');
            const editTitle = document.getElementById('editTitle');
            const editContent = document.getElementById('editContent');
            const editIsActive = document.getElementById('editIsActive');

            document.querySelectorAll('.editBtn').forEach(button => {
                button.addEventListener('click', () => {
                    const id = button.getAttribute('data-id');
                    const title = button.getAttribute('data-title');
                    const content = button.getAttribute('data-content');
                    const isActive = button.getAttribute('data-active') == "1";

                    editIdInput.value = id;
                    editTitle.value = title;
                    editContent.value = content;
                    editIsActive.checked = isActive;

                    editForm.action = "{{ route('admin.announcement.update', '__id__') }}".replace('__id__', id);
                });
            });
        });

    </script>
@endpush