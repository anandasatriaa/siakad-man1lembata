@extends('student.layouts.app')

@section('title', 'Profil Siswa')

@push('css')
    <style>
        .form-section {
            background-color: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 0.5rem;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .form-section h3 {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: #2d3748;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        label {
            display: block;
            font-weight: 500;
            margin-bottom: 0.25rem;
        }

        input[type="text"],
        input[type="email"],
        input[type="date"],
        input[type="file"],
        select,
        textarea {
            width: 100%;
            border: 1px solid #cbd5e0;
            border-radius: 0.375rem;
            padding: 0.5rem;
            font-size: 1rem;
            color: #2d3748;
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="date"]:focus,
        select:focus,
        textarea:focus {
            outline: none;
            border-color: #3182ce;
            box-shadow: 0 0 0 1px #3182ce;
        }

        .btn-primary {
            background-color: #3182ce;
            color: #fff;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            font-weight: 500;
        }

        .btn-primary:hover {
            background-color: #2c5282;
        }

        .btn-secondary {
            background-color: #e2e8f0;
            color: #2d3748;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            font-weight: 500;
        }

        .btn-secondary:hover {
            background-color: #cbd5e0;
        }

        .profile-photo {
            width: 120px;
            height: 120px;
            border-radius: 9999px;
            object-fit: cover;
            margin-bottom: 0.5rem;
        }

        .error-text {
            color: #e53e3e;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        .success-message {
            background-color: #f0fff4;
            border-left: 4px solid #48bb78;
            padding: 0.75rem 1rem;
            margin-bottom: 1.5rem;
            color: #276749;
        }

        .error-message {
            background-color: #fff5f5;
            border-left: 4px solid #f56565;
            padding: 0.75rem 1rem;
            margin-bottom: 1.5rem;
            color: #c53030;
        }
    </style>
@endpush

@section('content')
    <div class="container mx-auto px-4 py-6">

        {{-- Pesan sukses/gagal umum --}}
        @if (session('success'))
            <div class="success-message">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="error-message">
                {{ session('error') }}
            </div>
        @endif
        @if (session('success_password'))
            <div class="success-message">
                {{ session('success_password') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- ===============================
             Bagian 1: Form Edit Profil
             =============================== --}}
            <div class="form-section">
                <h3>Ubah Profil</h3>
                <form action="{{ route('student.profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    {{-- 1. Nama (user.name) --}}
                    <div class="form-group">
                        <label for="name">Nama Akun</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                            disabled>
                        @error('name')
                            <div class="error-text">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- 2. Email (user.email) --}}
                    <div class="form-group">
                        <label for="email">Email Akun</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                            required>
                        @error('email')
                            <div class="error-text">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- 3. Full Name (students.full_name) --}}
                    <div class="form-group">
                        <label for="full_name">Nama Lengkap</label>
                        <input type="text" name="full_name" id="full_name"
                            value="{{ old('full_name', $student->full_name) }}" required disabled>
                        @error('full_name')
                            <div class="error-text">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- 4. NIS (students.nis) --}}
                    <div class="form-group">
                        <label for="nis">NIS</label>
                        <input type="text" name="nis" id="nis" value="{{ old('nis', $student->nis) }}"
                            required disabled>
                        @error('nis')
                            <div class="error-text">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- 5. Gender --}}
                    <div class="form-group">
                        <label for="gender">Jenis Kelamin</label>
                        <select name="gender" id="gender" required disabled>
                            <option value="">-- Pilih Jenis Kelamin --</option>
                            <option value="M" {{ old('gender', $student->gender) == 'M' ? 'selected' : '' }}>Laki-laki
                            </option>
                            <option value="F" {{ old('gender', $student->gender) == 'F' ? 'selected' : '' }}>Perempuan
                            </option>
                        </select>
                        @error('gender')
                            <div class="error-text">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- 6. Birth Place --}}
                    <div class="form-group">
                        <label for="birth_place">Tempat Lahir</label>
                        <input type="text" name="birth_place" id="birth_place"
                            value="{{ old('birth_place', $student->birth_place) }}">
                        @error('birth_place')
                            <div class="error-text">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- 7. Birth Date --}}
                    <div class="form-group">
                        <label for="birth_date">Tanggal Lahir</label>
                        <input type="date" name="birth_date" id="birth_date"
                            value="{{ old('birth_date', $student->birth_date ? $student->birth_date->format('Y-m-d') : '') }}">
                        @error('birth_date')
                            <div class="error-text">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- 8. Address --}}
                    <div class="form-group">
                        <label for="address">Alamat</label>
                        <textarea name="address" id="address" rows="2">{{ old('address', $student->address) }}</textarea>
                        @error('address')
                            <div class="error-text">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- 9. Phone --}}
                    <div class="form-group">
                        <label for="phone">No. Telepon</label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone', $student->phone) }}">
                        @error('phone')
                            <div class="error-text">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- 10. Religion --}}
                    <div class="form-group">
                        <label for="religion">Agama</label>
                        <select name="religion" id="religion" required>
                            <option value="">-- Pilih Agama --</option>
                            @php
                                $options = ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Budha', 'Lainnya'];
                                $current = old('religion', $student->religion);
                            @endphp
                            @foreach ($options as $agama)
                                <option value="{{ $agama }}" {{ $current === $agama ? 'selected' : '' }}>
                                    {{ $agama }}
                                </option>
                            @endforeach
                        </select>
                        @error('religion')
                            <div class="error-text">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- 11. Guardian Name --}}
                    <div class="form-group">
                        <label for="guardian_name">Nama Wali</label>
                        <input type="text" name="guardian_name" id="guardian_name"
                            value="{{ old('guardian_name', $student->guardian_name) }}">
                        @error('guardian_name')
                            <div class="error-text">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- 12. Guardian Phone --}}
                    <div class="form-group">
                        <label for="guardian_phone">No. Telepon Wali</label>
                        <input type="text" name="guardian_phone" id="guardian_phone"
                            value="{{ old('guardian_phone', $student->guardian_phone) }}">
                        @error('guardian_phone')
                            <div class="error-text">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- 13. Foto Profil --}}
                    <div class="form-group">
                        <label for="photo">Foto Profil (jpg, png, maks 2MB)</label>
                        @if ($student->photo)
                            <div>
                                <img src="{{ asset('storage/' . $student->photo) }}" alt="Foto Profil"
                                    class="profile-photo">
                            </div>
                        @endif
                        <input type="file" name="photo" id="photo" accept="image/*">
                        @error('photo')
                            <div class="error-text">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Tombol Submit --}}
                    <div class="form-group">
                        <button type="submit" class="btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>

            {{-- =========================================
             Bagian 2: Form Ganti Password
             ========================================= --}}
            <div class="form-section">
                <h3>Ganti Kata Sandi</h3>
                <form action="{{ route('student.profile.password') }}" method="POST">
                    @csrf

                    {{-- 1. Current Password --}}
                    <div class="form-group">
                        <label for="current_password">Password Saat Ini</label>
                        <input type="password" name="current_password" id="current_password" required>
                        @error('current_password')
                            <div class="error-text">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- 2. New Password --}}
                    <div class="form-group">
                        <label for="new_password">Password Baru</label>
                        <input type="password" name="new_password" id="new_password" required>
                        @error('new_password')
                            <div class="error-text">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- 3. Confirm New Password --}}
                    <div class="form-group">
                        <label for="new_password_confirmation">Konfirmasi Password Baru</label>
                        <input type="password" name="new_password_confirmation" id="new_password_confirmation" required>
                    </div>

                    {{-- Tombol Submit --}}
                    <div class="form-group">
                        <button type="submit" class="btn-secondary">Ganti Password</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection

@push('js')
@endpush
