@extends('teacher.layouts.app')

@section('title', 'Input Nilai Siswa')

@push('css')
    <style>
        .card-nilai {
            transition: transform 0.2s, box-shadow 0.2s;
            border-radius: 0.5rem;
        }

        .card-nilai:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .badge {
            font-size: 0.8rem;
            font-weight: normal;
        }
    </style>
@endpush

@section('content')

    <div class="page-heading">
        <div class="page-title">
            <div class="row align-items-center">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Input Nilai Siswa</h3>
                    <p class="text-subtitle text-muted">Input nilai dapat dilakukan di halaman ini</p>
                </div>
            </div>
        </div>
        <section class="section">
            <div class="card">
                <div class="card-body">
                    @if($classes->isEmpty())
                        <div class="alert alert-info">
                            Anda belum mengajar di kelas manapun
                        </div>
                    @else
                        <div class="row">
                            @foreach($classes as $class)
                                <div class="col-md-6 mb-4">
                                    <div class="card card-nilai h-100"
                                        style="border-top: 5px solid #435ebe; border-bottom: 5px solid #435ebe;">
                                        <div class="card-header bg-light">
                                            <h5 class="mb-0 text-primary">
                                                {{ $class->name }}
                                                <span class="badge bg-secondary">{{ $class->category }}</span>
                                            </h5>
                                        </div>

                                        <div class="card-body" style="border-top: 1px solid #435ebe">
                                            <h6 class="text-muted mt-2">Mata Pelajaran:</h6>
                                            <div class="d-flex flex-wrap gap-2 mb-3">
                                                @foreach($class->courses as $course)
                                                    <a href="{{ route('teacher.grade.form', ['class_id' => $class->id, 'course_id' => $course->id]) }}"
                                                        class="btn btn-outline-primary btn-sm">
                                                        {{ $course->name }}
                                                    </a>
                                                @endforeach
                                            </div>

                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="text-muted">
                                                    <i class="bi bi-people-fill"></i>
                                                    {{ $class->students_count }} Siswa
                                                </span>
                                                <span class="text-muted">
                                                    <i class="bi bi-journal-bookmark-fill"></i>
                                                    {{ count($class->courses) }} Mapel
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </section>
    </div>
@endsection

@push('js')

@endpush