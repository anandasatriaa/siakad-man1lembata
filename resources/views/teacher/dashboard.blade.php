@extends('teacher.layouts.app')

@section('title', 'Guru Dashboard')

@push('css')
    <style>
        .table-hover tbody tr:hover {
            background-color: rgba(41, 128, 185, 0.1);
        }

        .card-header {
            border-radius: 0.25rem 0.25rem 0 0 !important;
        }
    </style>
@endpush

@section('content')

    <div class="page-heading">
        <h3>Dashboard Guru</h3>
    </div>
    <div class="page-content">
        <section class="row">
            <div class="col-12 col-lg-12">
                {{-- Pengumuman --}}
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <i class="bi bi-megaphone-fill me-2"></i> Pengumuman Terbaru
                    </div>
                    <div class="card-body">
                        @forelse($announcements as $ann)
                            <div class="mb-3">
                                <h5 class="fw-bold">{{ $ann->title }}</h5>
                                <small class="text-muted">{{ $ann->created_at->format('d M Y') }}</small>
                                <p>{{ Str::limit($ann->content, 120) }}</p>
                            </div>
                        @empty
                            <p class="text-muted">Tidak ada pengumuman aktif.</p>
                        @endforelse
                        <a href="{{ route('teacher.announcement.index') }}" class="btn btn-sm btn-outline-primary">Lihat
                            Semua</a>
                    </div>
                </div>

                <div class="row gx-4 mb-4">
                    {{-- Jadwal Hari Ini --}}
                    <div class="col-lg-6">
                        <div class="card shadow-sm">
                            <div class="card-header bg-success text-white">
                                <i class="bi bi-calendar-event-fill me-2"></i> Jadwal Hari Ini
                                <span class="float-end">{{ \Carbon\Carbon::now()->translatedFormat('l, d M Y') }}</span>
                            </div>
                            <ul class="list-group list-group-flush">
                                @forelse($schedules as $s)
                                    <li class="list-group-item">
                                        <strong>{{ $s->start_time }} - {{ $s->end_time }}</strong> &mdash;
                                        {{ $s->schoolClass->name ?? 'N/A' }} /
                                        {{ $s->course->name ?? 'N/A' }}
                                    </li>
                                @empty
                                    <li class="list-group-item text-center text-muted">Tidak ada jadwal hari ini.</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>

                    {{-- Ringkasan --}}
                    <div class="col-lg-6">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="card text-center shadow-sm">
                                    <div class="card-body">
                                        <i class="bi bi-building fs-1 text-primary"></i>
                                        <h6 class="mt-2 text-muted">Kelas Diampu</h6>
                                        <h4 class="fw-bold">{{ $classesTaught }}</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card text-center shadow-sm">
                                    <div class="card-body">
                                        <i class="bi bi-people fs-1 text-success"></i>
                                        <h6 class="mt-2 text-muted">Total Siswa</h6>
                                        <h4 class="fw-bold">{{ $studentsCount }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Materi Terbaru --}}
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-info text-white">
                        <i class="bi bi-folder2-open me-2"></i> Materi Terbaru
                    </div>
                    <ul class="list-group list-group-flush">
                        @forelse($materials as $mat)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ Str::limit($mat->title, 50) }}
                                <a href="{{ asset('storage/' . $mat->file_path) }}" class="btn btn-sm btn-outline-info">
                                    <i class="bi bi-download"></i> Download
                                </a>
                            </li>
                        @empty
                            <li class="list-group-item text-center text-muted">Belum ada materi.</li>
                        @endforelse
                    </ul>
                </div>

                {{-- Quick Links --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-around">
                            <a href="{{ route('teacher.material.index') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle me-1"></i> Tambah Materi
                            </a>
                            <a href="{{ route('teacher.grade.index') }}" class="btn btn-success">
                                <i class="bi bi-journal-check me-1"></i> Upload Nilai
                            </a>
                            <a href="{{ route('teacher.announcement.index') }}" class="btn btn-info">
                                <i class="bi bi-megaphone me-1"></i> Lihat Pengumuman
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>


@endsection

@push('js')
@endpush