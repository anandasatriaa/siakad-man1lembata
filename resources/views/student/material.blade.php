@extends('student.layouts.app')

@section('title', 'Materi Pembelajaran')

@push('css')
    <style>
        .material-card {
            border: 1px solid #e2e8f0;
            border-radius: 0.5rem;
            padding: 1rem;
            margin-bottom: 1rem;
            background-color: #ffffff;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .material-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #2d3748;
        }

        .material-meta {
            font-size: 0.875rem;
            color: #718096;
            margin-bottom: 0.75rem;
        }

        .material-description {
            font-size: 1rem;
            color: #4a5568;
            margin-bottom: 0.75rem;
            white-space: pre-wrap;
        }

        .material-link {
            font-size: 0.875rem;
            color: #3182ce;
            text-decoration: underline;
        }

        .no-material {
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
    <div class="container mx-auto px-4 py-6">
        <h2 class="text-2xl font-semibold mb-6">Daftar Materi Pembelajaran</h2>

        @if ($materials->isEmpty())
            <div class="no-material">
                Belum ada materi pembelajaran untuk kelas Anda.
            </div>
        @else
            @foreach ($materials as $material)
                <div class="material-card">
                    <div class="material-title">
                        {{ $material->title }}
                    </div>
                    <div class="material-meta">
                        @if ($material->course)
                            Mata Pelajaran: <strong>{{ $material->course->name }}</strong> ({{ $material->course->code }})
                            &nbsp;|&nbsp;
                        @endif
                        Pengajar: <strong>{{ $material->teacher ? $material->teacher->full_name : '-' }}</strong>
                        &nbsp;|&nbsp;
                        Dipublikasikan: <strong>
                            {{ $material->published_at
                                ? \Carbon\Carbon::parse($material->published_at)->timezone('Asia/Jakarta')->format('d F Y H:i')
                                : '-' }}
                        </strong>
                    </div>
                    @if ($material->description)
                        <div class="material-description">
                            {!! nl2br(e($material->description)) !!}
                        </div>
                    @endif
                    <div>
                        <a href="{{ asset('storage/' . $material->file_path) }}" target="_blank" class="material-link">
                            Unduh / Lihat File Materi &rarr;
                        </a>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
@endsection

@push('js')
@endpush
