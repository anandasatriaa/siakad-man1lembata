@extends('student.layouts.app')

@section('title', 'Jadwal Pelajaran')

@push('css')
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 0.5rem;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #f5f5f5;
        }
    </style>
@endpush

@section('content')
    <div class="container mx-auto px-4 py-6">
        <h2 class="text-2xl font-semibold mb-4">Jadwal Pelajaran Kelas: {{ $schedules->first()->class->name ?? '-' }}</h2>

        @if ($schedules->isEmpty())
            <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4">
                Belum ada jadwal pelajaran untuk kelas Anda.
            </div>
        @else
            <table>
                <thead>
                    <tr>
                        <th>Hari</th>
                        <th>Jam Mulai</th>
                        <th>Jam Selesai</th>
                        <th>Mata Pelajaran</th>
                        <th>Guru Pengampu</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($schedules as $schedule)
                        <tr>
                            <td>{{ $schedule->day }}</td>
                            <td>{{ \Carbon\Carbon::createFromFormat('H:i', $schedule->start_time)->format('H:i') }}</td>
                            <td>{{ \Carbon\Carbon::createFromFormat('H:i', $schedule->end_time)->format('H:i') }}</td>
                            <td>
                                @if ($schedule->course)
                                    {{ $schedule->course->name }} ({{ $schedule->course->code }})
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if ($schedule->teacher)
                                    {{ $schedule->teacher->full_name }}
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection

@push('js')
@endpush
