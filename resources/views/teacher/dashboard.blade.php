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
        .day-header {
            font-weight: bold;
            background-color: #f8f9fa;
            padding: 0.5rem 1.25rem;
            border-bottom: 1px solid rgba(0,0,0,.125);
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
                    <div class="card-body mt-2">
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
                    {{-- Seluruh Jadwal Guru --}}
                    <div class="col-lg-6">
                        <div class="card shadow-sm">
                            <div class="card-header bg-success text-white">
                                <i class="bi bi-calendar-week-fill me-2"></i> Jadwal Mengajar Anda
                            </div>
                            <ul class="list-group list-group-flush">
                                @forelse($schedules as $day => $daySchedules)
                                    <li class="list-group-item p-0">
                                        <div class="day-header">{{ $day }}</div>
                                        <ul class="list-group list-group-flush">
                                            @foreach($daySchedules as $s)
                                                <li class="list-group-item ps-4">
                                                    <strong>{{ \Carbon\Carbon::parse($s->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($s->end_time)->format('H:i') }}</strong> &mdash;
                                                    {{ $s->class->name ?? 'N/A' }} /
                                                    {{ $s->course->name ?? 'Istirahat' }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    </li>
                                @empty
                                    <li class="list-group-item text-center text-muted">Anda belum memiliki jadwal mengajar.</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>

                    {{-- Ringkasan --}}
                    <div class="col-lg-6">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="card text-center shadow-sm h-100">
                                    <div class="card-body d-flex flex-column justify-content-center">
                                        <i class="bi bi-building fs-1 text-primary"></i>
                                        <h6 class="mt-2 text-muted">Kelas Diampu</h6>
                                        <h4 class="fw-bold">{{ $classesTaught }}</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card text-center shadow-sm h-100">
                                    <div class="card-body d-flex flex-column justify-content-center">
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
                        <i class="bi bi-folder2-open me-2"></i> Materi Terbaru Anda
                    </div>
                    <ul class="list-group list-group-flush">
                        @forelse($materials as $mat)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="fw-bold">{{ Str::limit($mat->title, 50) }}</span>
                                    <br>
                                    <small class="text-muted">
                                        Diunggah pada: {{ $mat->published_at ? \Carbon\Carbon::parse($mat->published_at)->format('d M Y') : 'N/A' }}
                                    </small>
                                </div>
                                <a href="{{ asset('storage/' . $mat->file_path) }}" class="btn btn-sm btn-outline-info" target="_blank">
                                    <i class="bi bi-download"></i> Download
                                </a>
                            </li>
                        @empty
                            <li class="list-group-item text-center text-muted">Belum ada materi yang diunggah.</li>
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