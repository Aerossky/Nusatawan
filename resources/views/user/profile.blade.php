@extends('layouts.user')
@section('title', 'Profil Saya')

@section('content')
    <x-section>
        <div class="mt-14"></div>

        <!-- Profile Header -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
            <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6">
                <div class="w-24 h-24 rounded-full overflow-hidden border-4 border-gray-100 shrink-0">
                    @if (Auth::user()->image)
                        <img class="w-full h-full object-cover" src="{{ asset('storage/' . $profile->image) }}"
                            alt="Foto profil {{ Auth::user()->name }}">
                    @else
                        <img class="w-full h-full object-cover"
                            src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=random&color=fff&bold=true&size=100"
                            alt="Inisial {{ Auth::user()->name }}">
                    @endif
                </div>
                <div class="text-center sm:text-left">
                    <h1 class="text-xl font-bold text-gray-900">{{ $profile->name }}</h1>
                    <p class="text-sm text-gray-500 mt-0.5">{{ $profile->email }}</p>
                    <div class="mt-2">
                        @if ($profile->status)
                            <span class="inline-block bg-green-100 text-green-700 text-xs px-2.5 py-1 rounded-full font-medium">Aktif</span>
                        @else
                            <span class="inline-block bg-red-100 text-red-700 text-xs px-2.5 py-1 rounded-full font-medium">Tidak Aktif</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 mb-6">
            <a href="{{ route('user.itinerary.index') }}"
                class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 text-center hover:shadow-md hover:border-blue-100 transition-all duration-200 group">
                <div class="text-2xl font-bold text-blue-600 group-hover:scale-105 transition-transform">
                    {{ Auth::user()->itineraries()->count() }}
                </div>
                <div class="text-xs text-gray-500 mt-1 font-medium">Rencana Perjalanan</div>
            </a>
            <a href="{{ route('user.destination-submission.index') }}"
                class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 text-center hover:shadow-md hover:border-blue-100 transition-all duration-200 group">
                <div class="text-2xl font-bold text-blue-600 group-hover:scale-105 transition-transform">
                    {{ $destinationUserTotal }}
                </div>
                <div class="text-xs text-gray-500 mt-1 font-medium">Destinasi Diajukan</div>
            </a>
            <a href="{{ route('user.destination-favorite.index') }}"
                class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 text-center hover:shadow-md hover:border-blue-100 transition-all duration-200 group col-span-2 sm:col-span-1">
                <div class="text-2xl font-bold text-blue-600 group-hover:scale-105 transition-transform">
                    {{ $likedDestinationUserTotal }}
                </div>
                <div class="text-xs text-gray-500 mt-1 font-medium">Destinasi Favorit</div>
            </a>
        </div>

        <!-- Edit Profile Form -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-lg font-bold text-gray-900 mb-6">Edit Profil</h2>

            <form action="{{ route('user.profile.update', $profile) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PATCH')

                <!-- Foto Profil -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Foto Profil</label>
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 rounded-full overflow-hidden border-2 border-gray-200 shrink-0">
                            @if (Auth::user()->image)
                                <img id="preview-image" class="w-full h-full object-cover"
                                    src="{{ asset('storage/' . $profile->image) }}"
                                    alt="Foto profil {{ Auth::user()->name }}">
                            @else
                                <img id="preview-image" class="w-full h-full object-cover"
                                    src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=random&color=fff&bold=true&size=100"
                                    alt="Inisial {{ Auth::user()->name }}">
                            @endif
                        </div>
                        <div class="flex-1">
                            <input type="file" id="image" name="image" accept="image/*"
                                class="w-full text-sm text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            <p class="text-xs text-gray-400 mt-1">JPEG, PNG, JPG, GIF — maks. 2MB</p>
                            @error('image')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Nama & Email -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5" for="name">Nama Lengkap</label>
                        <input type="text" id="name" name="name"
                            value="{{ old('name', $profile->name) }}"
                            class="w-full px-3 py-2 text-sm border {{ $errors->has('name') ? 'border-red-400' : 'border-gray-300' }} rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5" for="email">Email</label>
                        <input type="email" id="email" name="email"
                            value="{{ old('email', $profile->email) }}"
                            class="w-full px-3 py-2 text-sm border {{ $errors->has('email') ? 'border-red-400' : 'border-gray-300' }} rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Password -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5" for="password">Kata Sandi Baru</label>
                        <input type="password" id="password" name="password"
                            class="w-full px-3 py-2 text-sm border {{ $errors->has('password') ? 'border-red-400' : 'border-gray-300' }} rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $errors->first('password') }}</p>
                        @else
                            <p class="text-xs text-gray-400 mt-1">Kosongkan jika tidak ingin mengubah.</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5" for="password_confirmation">Konfirmasi Kata Sandi</label>
                        <input type="password" id="password_confirmation" name="password_confirmation"
                            class="w-full px-3 py-2 text-sm border {{ $errors->has('password_confirmation') ? 'border-red-400' : 'border-gray-300' }} rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('password_confirmation')
                            <p class="text-red-500 text-xs mt-1">{{ $errors->first('password_confirmation') }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                    @if (session('success'))
                        <p class="text-sm text-green-600 font-medium flex items-center gap-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            {{ session('success') }}
                        </p>
                    @else
                        <span></span>
                    @endif
                    <button type="submit"
                        class="px-5 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </x-section>
@endsection

@push('scripts')
    <script>
        document.getElementById('image').addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    document.getElementById('preview-image').src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
@endpush
