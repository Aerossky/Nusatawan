@extends('layouts.user')
@section('title', 'Pengajuan Destinasi Saya')

@section('content')
    <x-section>
        <div class="mt-14"></div>

        <!-- Page Header -->
        <div class="mb-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Pengajuan Destinasi Saya</h1>
                    <p class="text-sm text-gray-500 mt-1">Pantau status destinasi yang kamu ajukan ke Nusatawan</p>
                </div>
                <a href="{{ route('user.destination-submission.create') }}"
                    class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Ajukan Destinasi Baru
                </a>
            </div>
        </div>

        @if ($destinationSubmissions->isEmpty())
            <!-- Empty State -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 px-6 py-16 flex flex-col items-center text-center">
                <div class="w-16 h-16 bg-blue-50 rounded-full flex items-center justify-center mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Belum ada pengajuan</h3>
                <p class="text-gray-500 text-sm mb-6 max-w-sm">Kamu belum pernah mengajukan destinasi. Yuk bagikan tempat wisata favoritmu ke komunitas Nusatawan!</p>
                <a href="{{ route('user.destination-submission.create') }}"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Ajukan Sekarang
                </a>
            </div>
        @else
            <!-- Submission List -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                @foreach ($destinationSubmissions as $destinationSubmission)
                    <div class="bg-white rounded-xl overflow-hidden shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-300 flex flex-col">
                        <!-- Image -->
                        <div class="relative h-48 overflow-hidden">
                            <img src="{{ asset($destinationSubmission->images->first() ? 'storage/' . $destinationSubmission->images->first()->url : 'images/auth.png') }}"
                                alt="{{ $destinationSubmission->place_name }}"
                                class="w-full h-full object-cover transition-transform duration-500 hover:scale-105">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>

                            <!-- Category badge -->
                            <div class="absolute top-3 right-3">
                                <span class="bg-blue-600/90 text-white text-xs font-medium px-2.5 py-1 rounded-full backdrop-blur-sm">
                                    {{ $destinationSubmission->category->name }}
                                </span>
                            </div>

                            <!-- Status badge on image -->
                            <div class="absolute top-3 left-3">
                                @if ($destinationSubmission->status === 'approved')
                                    <span class="inline-flex items-center gap-1 bg-emerald-500/90 text-white text-xs font-medium px-2.5 py-1 rounded-full backdrop-blur-sm">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                        Diterima
                                    </span>
                                @elseif ($destinationSubmission->status === 'rejected')
                                    <span class="inline-flex items-center gap-1 bg-red-500/90 text-white text-xs font-medium px-2.5 py-1 rounded-full backdrop-blur-sm">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                                        Ditolak
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 bg-amber-500/90 text-white text-xs font-medium px-2.5 py-1 rounded-full backdrop-blur-sm">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/></svg>
                                        Menunggu
                                    </span>
                                @endif
                            </div>

                            <!-- Place name -->
                            <div class="absolute bottom-0 left-0 right-0 p-4">
                                <h3 class="text-white text-lg font-bold leading-tight">{{ $destinationSubmission->place_name }}</h3>
                                <p class="text-white/70 text-xs mt-0.5">{{ $destinationSubmission->administrative_area }}, {{ $destinationSubmission->province }}</p>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="p-4 flex flex-col flex-grow">
                            <p class="text-xs text-gray-400 flex items-center gap-1 mb-3">
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                Diajukan {{ $destinationSubmission->created_at->format('d M Y') }}
                            </p>

                            @if ($destinationSubmission->status === 'rejected' && $destinationSubmission->admin_note)
                                <div class="mb-3 bg-red-50 border border-red-100 rounded-lg p-3 text-xs text-red-700">
                                    <p class="font-medium mb-0.5">Alasan penolakan:</p>
                                    <p>{{ $destinationSubmission->admin_note }}</p>
                                </div>
                            @elseif ($destinationSubmission->status === 'approved' && $destinationSubmission->admin_note)
                                <div class="mb-3 bg-emerald-50 border border-emerald-100 rounded-lg p-3 text-xs text-emerald-700">
                                    <p class="font-medium mb-0.5">Catatan admin:</p>
                                    <p>{{ $destinationSubmission->admin_note }}</p>
                                </div>
                            @endif

                            <div class="mt-auto pt-3 border-t border-gray-100 flex justify-end">
                                @if ($destinationSubmission->status === 'approved' && $destinationSubmission->destination_id)
                                    <a href="{{ route('user.destinations.show', $destinationSubmission->destination_id) }}"
                                        class="inline-flex items-center gap-1 text-sm font-medium text-blue-600 hover:text-blue-800 transition-colors">
                                        Lihat Destinasi
                                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
                                    </a>
                                @elseif ($destinationSubmission->status === 'rejected')
                                    <a href="{{ route('user.destination-submission.create') }}"
                                        class="inline-flex items-center gap-1 text-sm font-medium text-blue-600 hover:text-blue-800 transition-colors">
                                        Ajukan Ulang
                                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>
                                    </a>
                                @else
                                    <span class="text-xs text-gray-400 italic">Sedang ditinjau admin</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if ($destinationSubmissions->hasPages())
                <div class="mt-8">
                    {{ $destinationSubmissions->links() }}
                </div>
            @endif
        @endif
    </x-section>
@endsection
