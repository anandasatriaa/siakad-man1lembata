@extends('admin.layouts.app')

@section('title', 'Admin Dashboard')

@push('css')
@endpush

@section('content')
    <div class="page-heading">
        <h3>Dashboard</h3>
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
            <div class="col-12 col-lg-12">
                <div class="row">
                    <div class="col-6 col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body px-3 py-4-5">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="stats-icon purple">
                                            <i class="iconly-boldUser"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <h6 class="text-muted font-semibold">Siswa Aktif</h6>
                                        <h6 class="font-extrabold mb-0">{{ number_format($totalStudents) }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body px-3 py-4-5">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="stats-icon blue">
                                            <i class="iconly-boldAdd-User"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <h6 class="text-muted font-semibold">Guru Aktif</h6>
                                        <h6 class="font-extrabold mb-0">{{ number_format($totalTeachers) }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body px-3 py-4-5">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="stats-icon green">
                                            <i class="iconly-boldCategory"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <h6 class="text-muted font-semibold">Kelas</h6>
                                        <h6 class="font-extrabold mb-0">{{ number_format($totalClasses) }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-3 col-md-6">
                        <div class="card">
                            <div class="card-body px-3 py-4-5">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="stats-icon red">
                                            <i class="iconly-boldBookmark"></i>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <h6 class="text-muted font-semibold">Mata Pelajaran</h6>
                                        <h6 class="font-extrabold mb-0">{{ number_format($totalCourses) }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-4">
                    {{-- Jadwal Hari Ini --}}
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Jadwal Hari Ini ({{ \Carbon\Carbon::now()->translatedFormat('l, d M Y') }})</h4>
                            </div>
                            <div class="card-body p-3">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Kelas</th>
                                            <th>Mapel</th>
                                            <th>Guru</th>
                                            <th>Waktu</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($todaySchedules as $s)
                                        <tr>
                                            <td>{{ $s->schoolClass->name }}</td>
                                            <td>{{ $s->course->name ?? '-' }}</td>
                                            <td>{{ $s->teacher->full_name ?? '-' }}</td>
                                            <td>{{ $s->start_time }} - {{ $s->end_time }}</td>
                                        </tr>
                                        @empty
                                        <tr><td colspan="4" class="text-center">Tidak ada jadwal hari ini</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Distribusi Gender
        new Chart(document.getElementById('chartGender'), {
            type: 'pie',
            data: {
                labels: ['Laki-laki','Perempuan'],
                datasets: [{ data: [{{ $genderDistribution['M'] }}, {{ $genderDistribution['F'] }}] }]
            }
        });
        // Siswa per Kelas
        new Chart(document.getElementById('chartPerClass'), {
            type: 'bar',
            data: {
                labels: [{!! $classesWithCount->pluck('name')->map(fn($n) => "'{$n}'")->join(',') !!}],
                datasets: [{ label: 'Jumlah Siswa', data: [{!! $classesWithCount->pluck('students_count')->join(',') !!}] }]
            },
            options: { scales: { y: { beginAtZero: true } } }
        });
    </script>
@endpush