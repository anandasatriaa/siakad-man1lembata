@extends('teacher.layouts.app')

@section('title', 'Input Nilai Siswa')

@push('css')
<style>
    <style>
    .card {
        transition: transform 0.2s, box-shadow 0.2s;
        border-radius: 0.5rem;
    }
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    .badge {
        font-size: 0.8rem;
        font-weight: normal;
    }
</style>
</style>
@endpush

@section('content')
<div class="container">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Input Nilai Siswa</h4>
        </div>
        
        <div class="card-body">
            @if($classes->isEmpty())
                <div class="alert alert-info">
                    Anda belum mengajar di kelas manapun
                </div>
            @else
                <div class="row">
                    @foreach($classes as $class)
                    <div class="col-md-6 mb-4">
                        <div class="card border-primary h-100">
                            <div class="card-header bg-light">
                                <h5 class="mb-0 text-primary">
                                    {{ $class->name }} 
                                    <span class="badge bg-secondary">{{ $class->category }}</span>
                                </h5>
                            </div>
                            
                            <div class="card-body">
                                <h6 class="text-muted">Mata Pelajaran:</h6>
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
                                        {{ $class->students_count ?? 0 }} Siswa
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
</div>
@endsection

@push('js')

@endpush
