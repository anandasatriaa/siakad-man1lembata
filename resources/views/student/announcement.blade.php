@extends('student.layouts.app')

@section('title', 'Pengumuman')

@push('css')
    <style>
        .announcement-card {
            border: 1px solid #e2e8f0;
            border-radius: 0.5rem;
            padding: 1rem;
            margin-bottom: 1rem;
            background-color: #ffffff;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .announcement-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .announcement-date {
            font-size: 0.875rem;
            color: #718096;
            margin-bottom: 0.75rem;
        }

        .announcement-content {
            font-size: 1rem;
            color: #2d3748;
        }

        .no-announcement {
            text-align: center;
            padding: 2rem;
            background-color: #f7fafc;
            border: 1px dashed #cbd5e0;
            border-radius: 0.5rem;
            color: #4a5568;
        }
    </style>
@endpush

@section('content')

    <div class="page-heading">
        <div class="page-title">
            <div class="row align-items-center">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Daftar Pengumuman</h3>
                    <p class="text-subtitle text-muted">Semua informasi pengumuman berada di sini</p>
                </div>
            </div>
        </div>
        <section class="section">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        @if ($announcements->isEmpty())
                            <div class="no-announcement">
                                Saat ini belum ada pengumuman.
                            </div>
                        @else
                            @foreach ($announcements as $announcement)
                                <div class="announcement-card">
                                    <div class="announcement-title">
                                        {{ $announcement->title }}
                                    </div>
                                    <div class="announcement-date">
                                        {{ \Carbon\Carbon::parse($announcement->created_at)->format('d F Y H:i') }}
                                    </div>
                                    <div class="announcement-content">
                                        {!! nl2br(e($announcement->content)) !!}
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('js')
@endpush