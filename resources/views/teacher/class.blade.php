@extends('teacher.layouts.app')

@section('title', 'Daftar Kelas yang diajar')

@push('css')
<style>
    .avatar-placeholder {
        font-weight: bold;
    }
    .table-hover tbody tr:hover {
        background-color: rgba(41, 128, 185, 0.1);
        cursor: pointer;
    }
    .card {
        border-radius: 0.5rem;
        overflow: hidden;
    }
</style>
@endpush

@section('content')
<div class="container">
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Kelas yang Saya Ajar</h4>
        </div>
        
        <div class="card-body">
            @if(empty($classesData))
                <div class="alert alert-info">
                    Anda belum mengajar di kelas manapun
                </div>
            @else
                @foreach($classesData as $classData)
                <div class="card mb-4 border border-primary">
                    <div class="card-header bg-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="mb-0 text-primary">
                                {{ $classData['class']->name }} 
                                <span class="badge bg-secondary">{{ $classData['class']->category }}</span>
                            </h3>
                            <div>
                                @foreach($classData['courses'] as $course)
                                    <span class="badge bg-info fs-6 me-1">{{ $course->name }}</span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <h4 class="mb-3 text-muted">Daftar Siswa</h4>
                        
                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Foto</th>
                                        <th>NIS</th>
                                        <th>Nama Lengkap</th>
                                        <th>Jenis Kelamin</th>
                                        <th>Tahun Masuk</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($classData['students'] as $index => $student)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            @if($student->photo)
                                                <img src="{{ asset('storage/'.$student->photo) }}" 
                                                     alt="Foto {{ $student->full_name }}" 
                                                     class="rounded-circle" 
                                                     width="40" height="40">
                                            @else
                                                <div class="avatar-placeholder rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center" 
                                                     style="width:40px; height:40px">
                                                    {{ substr($student->full_name, 0, 1) }}
                                                </div>
                                            @endif
                                        </td>
                                        <td>{{ $student->nis }}</td>
                                        <td>{{ $student->full_name }}</td>
                                        <td>{{ $student->gender == 'M' ? 'Laki-laki' : 'Perempuan' }}</td>
                                        <td>{{ $student->enrollment_year }}</td>
                                        <td>
                                            <span class="badge {{ $student->status == 'active' ? 'bg-success' : 'bg-danger' }}">
                                                {{ $student->status == 'active' ? 'Aktif' : 'Non-Aktif' }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <div class="card-footer bg-light">
                        <small class="text-muted">
                            Total {{ count($classData['students']) }} siswa di kelas ini
                        </small>
                    </div>
                </div>
                @endforeach
            @endif
        </div>
    </div>
</div>
@endsection

@push('js')
@endpush
