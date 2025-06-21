@extends('student.layouts.app')

@section('title', 'Siswa Dashboard')

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
        <h3>Dashboard Siswa</h3>
    </div>
    <div class="page-content">
        @if($announcements->isNotEmpty())
        <div class="card mb-4" style="border-bottom: 5px solid #435ebe">
            <div class="card-header">
                <h2>Pengumuman Terbaru</h2>
            </div>
            <div class="card-body">
                @foreach($announcements as $ann)
                    <div class="mb-3">
                        <h5>{{ $ann->title }}</h5>
                        <p class="text-muted"><small>Diposting pada {{ $ann->created_at->format('d M Y H:i') }}</small></p>
                        <p>{!! nl2br(e($ann->content)) !!}</p>
                        <hr>
                    </div>
                @endforeach
            </div>
        </div>
        @else
            <p>Tidak ada pengumuman aktif saat ini.</p>
        @endif
        <section class="row">
        <div class="col-lg-8">
            {{-- Jadwal Hari Ini --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Jadwal Hari Ini ({{ \Carbon\Carbon::now()->translatedFormat('l, d M Y') }})</h5>
                </div>
                <div class="card-body p-3">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Waktu</th>
                                <th>Mata Pelajaran</th>
                                <th>Guru</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse($todaySchedules as $s)
                            <tr>
                                <td>{{ $s->start_time }} - {{ $s->end_time }}</td>
                                <td>{{ $s->course->name ?? '-' }}</td>
                                <td>{{ $s->teacher->full_name ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-center">Tidak ada jadwal hari ini</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Materi Terbaru --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Materi Baru</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        @foreach($materials as $mat)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>{{ Str::limit($mat->title, 40) }}</span>
                            <a href="{{ asset('storage/' . $mat->file_path) }}" class="badge bg-primary">Download</a>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            {{-- Profil Siswa --}}
            <div class="card mb-4">
                <div class="card-body text-center">
                    <img src="{{ asset('storage/' . $profile->photo) }}" class="rounded-circle mb-3" width="100" height="100">
                    <h5>{{ $profile->full_name }}</h5>
                    <p>NIS: {{ $profile->nis }}</p>
                    <p>Kelas: {{ $profile->class->name ?? '-' }}</p>
                </div>
            </div>

            {{-- Quick Links --}}
            <div class="list-group shadow-sm">
                <a href="{{ route('student.announcement.index') }}" class="list-group-item list-group-item-action">
                    <i class="bi bi-megaphone me-2"></i> Pengumuman
                </a>
                <a href="{{ route('student.schedule.index') }}" class="list-group-item list-group-item-action">
                    <i class="bi bi-calendar3 me-2"></i> Jadwal Lengkap
                </a>
                <a href="{{ route('student.material.index') }}" class="list-group-item list-group-item-action">
                    <i class="bi bi-journal-bookmark me-2"></i> Materi
                </a>
                <a href="{{ route('student.profile.index') }}" class="list-group-item list-group-item-action">
                    <i class="bi bi-person-circle me-2"></i> Profil Saya
                </a>
            </div>
        </div>
    </section>
    </div>
@endsection

@push('js')
@endpush
