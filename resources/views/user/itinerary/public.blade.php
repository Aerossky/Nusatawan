@extends('layouts.user')
@section('title', $itinerary->title)
@section('meta_description', 'Rencana perjalanan ' . $itinerary->title . ' oleh ' . $itinerary->user->name . '. ' . \Carbon\Carbon::parse($itinerary->startDate)->format('d M Y') . ' – ' . \Carbon\Carbon::parse($itinerary->endDate)->format('d M Y') . '. Lihat detail destinasi dan prakiraan cuaca.')
@section('og_title', $itinerary->title . ' — Rencana Perjalanan di Nusatawan')
@section('og_description', 'Rencana perjalanan ' . $itinerary->title . ' oleh ' . $itinerary->user->name . '. Lihat detail destinasi lengkap dengan prakiraan cuaca.')
@section('canonical', route('user.itinerary.public', $itinerary->slug))

@section('content')
    <div class="mt-[70px]"></div>
    <div class="max-w-7xl mx-auto px-3 sm:px-4 lg:px-8 py-4 sm:py-6 lg:py-8">

        {{-- Header --}}
        <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6 sm:mb-8">
            <div class="px-3 sm:px-4 lg:px-6 py-4 sm:py-5">
                {{-- Public badge --}}
                <div class="flex items-center gap-2 mb-3">
                    <span class="inline-flex items-center gap-1 text-xs text-green-700 bg-green-50 border border-green-200 px-2 py-0.5 rounded-full">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                        </svg>
                        Rencana Publik
                    </span>
                </div>

                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-3 sm:gap-4">
                    <div class="flex-1 min-w-0">
                        <h1 class="text-xl sm:text-2xl font-bold text-gray-900">{{ $itinerary->title }}</h1>
                        <p class="mt-1 text-sm text-gray-500">
                            {{ \Carbon\Carbon::parse($itinerary->startDate)->format('d M Y') }} –
                            {{ \Carbon\Carbon::parse($itinerary->endDate)->format('d M Y') }}
                        </p>
                        {{-- Owner info --}}
                        <div class="flex items-center gap-2 mt-2">
                            <img src="{{ $itinerary->user->image ? asset('storage/' . $itinerary->user->image) : 'https://ui-avatars.com/api/?name=' . urlencode($itinerary->user->name) . '&background=random&color=fff&bold=true&size=40' }}"
                                alt="{{ $itinerary->user->name }}"
                                class="w-5 h-5 rounded-full object-cover">
                            <span class="text-xs text-gray-500">oleh {{ $itinerary->user->name }}</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 flex-shrink-0">
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                            {{ $itinerary->status == 'ongoing' ? 'bg-blue-100 text-blue-800' : ($itinerary->status == 'completed' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800') }}">
                            {{ $itinerary->status == 'draft' ? 'Rencana' : ($itinerary->status == 'ongoing' ? 'Sedang Berlangsung' : 'Selesai') }}
                        </span>

                        {{-- Share/copy button --}}
                        <button id="copy-public-url"
                            data-url="{{ route('user.itinerary.public', $itinerary->slug) }}"
                            class="inline-flex items-center gap-1 px-3 py-1 text-xs border border-gray-200 rounded-md hover:bg-gray-50 transition-colors text-gray-600">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
                            </svg>
                            Salin Link
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Destinasi --}}
        <div class="mb-6 sm:mb-8">
            <h2 class="text-lg sm:text-xl font-semibold text-gray-900 mb-4 sm:mb-6">Destinasi</h2>

            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="divide-y divide-gray-200">
                    @php
                        $sortedDestinations = $itinerary->itineraryDestinations->sortBy(function ($d) {
                            return $d->visit_date_time ?? '9999-12-31 23:59:59';
                        });

                        $destinationsByDate = [];
                        foreach ($sortedDestinations as $dest) {
                            $dateKey = $dest->visit_date_time
                                ? \Carbon\Carbon::parse($dest->visit_date_time)->format('Y-m-d')
                                : 'no-date';
                            $destinationsByDate[$dateKey][] = $dest;
                        }

                        $allDates = [];
                        $currentDate = \Carbon\Carbon::parse($itinerary->startDate);
                        $endDate = \Carbon\Carbon::parse($itinerary->endDate);
                        while ($currentDate <= $endDate) {
                            $dateKey = $currentDate->format('Y-m-d');
                            $allDates[$dateKey] = $destinationsByDate[$dateKey] ?? [];
                            $currentDate->addDay();
                        }

                        $globalIndex = 1;
                    @endphp

                    @foreach ($allDates as $dateKey => $destinations)
                        {{-- Date Header --}}
                        <div class="px-3 sm:px-4 lg:px-6 py-3 sm:py-4 bg-blue-50">
                            <div class="text-sm sm:text-base font-medium text-blue-800">
                                {{ \Carbon\Carbon::parse($dateKey)->format('d M Y') }}
                                <span class="text-xs sm:text-sm text-blue-600 ml-2">
                                    ({{ count($destinations) }} destinasi)
                                </span>
                            </div>
                        </div>

                        @if (count($destinations) > 0)
                            @foreach ($destinations as $itineraryDestination)
                                <div class="px-3 sm:px-4 lg:px-6 py-4 hover:bg-gray-50 transition-colors">
                                    <div class="flex flex-col lg:flex-row gap-4">
                                        {{-- Destination Info --}}
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-start gap-3">
                                                <div class="h-8 w-8 sm:h-10 sm:w-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-800 font-semibold text-sm flex-shrink-0">
                                                    {{ $globalIndex++ }}
                                                </div>
                                                <div class="min-w-0 flex-1">
                                                    @if ($itineraryDestination->destination)
                                                        <a href="{{ route('user.destinations.show', $itineraryDestination->destination) }}"
                                                            class="text-sm sm:text-base font-medium text-gray-900 hover:text-blue-600 transition-colors">
                                                            {{ $itineraryDestination->destination->place_name }}
                                                        </a>
                                                    @else
                                                        <div class="text-sm sm:text-base font-medium text-gray-900">Destinasi Tanpa Nama</div>
                                                    @endif
                                                    <div class="mt-1 flex flex-col sm:flex-row sm:items-center gap-1 sm:gap-4">
                                                        @if (isset($itineraryDestination->destination->administrative_area))
                                                            <span class="text-xs sm:text-sm text-gray-500">
                                                                {{ $itineraryDestination->destination->administrative_area }},
                                                                {{ $itineraryDestination->destination->province }}
                                                            </span>
                                                        @endif
                                                        @if ($itineraryDestination->visit_date_time)
                                                            <span class="inline-flex px-2 py-0.5 rounded text-xs bg-blue-50 text-blue-700">
                                                                {{ \Carbon\Carbon::parse($itineraryDestination->visit_date_time)->format('H:i') }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            @if ($itineraryDestination->note)
                                                <div class="mt-3 ml-0 sm:ml-13">
                                                    <div class="text-xs sm:text-sm text-gray-600 bg-gray-50 p-3 rounded-lg">
                                                        {{ $itineraryDestination->note }}
                                                    </div>
                                                </div>
                                            @endif
                                        </div>

                                        {{-- Weather Info --}}
                                        @if (isset($weatherData[$itineraryDestination->id]))
                                            @php $weather = $weatherData[$itineraryDestination->id]; @endphp
                                            <div class="flex-shrink-0 w-full lg:w-64">
                                                <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-3 border border-blue-200">
                                                    <div class="flex items-center justify-between mb-2">
                                                        <h4 class="text-xs font-medium text-blue-800">Prakiraan Cuaca</h4>
                                                        <span class="text-xs text-blue-600">{{ $weather['forecast_time'] }}</span>
                                                    </div>
                                                    <div class="flex items-center gap-3">
                                                        <div class="flex-shrink-0">
                                                            <img src="https://openweathermap.org/img/wn/{{ $weather['icon'] }}@2x.png"
                                                                alt="{{ $weather['description'] }}" class="w-12 h-12">
                                                        </div>
                                                        <div class="flex-1 min-w-0">
                                                            <div class="text-xl font-bold text-blue-900">{{ $weather['temperature'] }}°C</div>
                                                            <div class="text-xs text-blue-700 capitalize">{{ $weather['description'] }}</div>
                                                            <div class="text-xs text-blue-600 mt-1">Terasa {{ $weather['feels_like'] }}°C</div>
                                                        </div>
                                                    </div>
                                                    <div class="mt-3 grid grid-cols-2 gap-2 text-xs">
                                                        <div class="flex items-center gap-1">
                                                            <svg class="w-3 h-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"/>
                                                            </svg>
                                                            <span class="text-blue-700">{{ $weather['humidity'] }}%</span>
                                                        </div>
                                                        <div class="flex items-center gap-1">
                                                            <svg class="w-3 h-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                            </svg>
                                                            <span class="text-blue-700">{{ $weather['wind_speed_kmh'] }} km/h</span>
                                                        </div>
                                                    </div>
                                                    @if (isset($weather['city']) && $weather['city'])
                                                        <div class="mt-2 text-xs text-blue-600">{{ $weather['city'] }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif

                                        {{-- Maps link --}}
                                        @if (isset($itineraryDestination->destination->latitude) && isset($itineraryDestination->destination->longitude))
                                            @php
                                                $mapQuery = isset($itineraryDestination->destination->place_name) && !empty($itineraryDestination->destination->place_name)
                                                    ? urlencode($itineraryDestination->destination->place_name) . '/@' . $itineraryDestination->destination->latitude . ',' . $itineraryDestination->destination->longitude
                                                    : $itineraryDestination->destination->latitude . ',' . $itineraryDestination->destination->longitude;
                                            @endphp
                                            <div class="flex sm:flex-col items-center sm:items-end gap-2 flex-shrink-0">
                                                <a href="https://www.google.com/maps/search/?api=1&query={{ $mapQuery }}"
                                                    target="_blank"
                                                    class="inline-flex items-center p-2 border border-gray-300 shadow-sm text-xs rounded text-gray-700 bg-white hover:bg-gray-50 transition-colors"
                                                    title="Lihat di Google Maps">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-green-600" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                                    </svg>
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="px-3 sm:px-4 lg:px-6 py-6 text-center">
                                <p class="text-sm text-gray-400">Tidak ada destinasi untuk tanggal ini</p>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>

        {{-- CTA --}}
        <div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-lg p-6 text-center text-white">
            <h3 class="text-lg font-semibold mb-1">Terinspirasi?</h3>
            <p class="text-sm text-blue-100 mb-4">Buat rencana perjalananmu sendiri di Nusatawan — gratis, lengkap dengan prakiraan cuaca.</p>
            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                @guest
                    <a href="{{ route('auth.register') }}"
                        class="inline-block bg-white text-blue-700 font-medium px-5 py-2 rounded-md text-sm hover:bg-blue-50 transition-colors">
                        Daftar Gratis
                    </a>
                    <a href="{{ route('auth.login') }}"
                        class="inline-block border border-white text-white font-medium px-5 py-2 rounded-md text-sm hover:bg-blue-700 transition-colors">
                        Masuk
                    </a>
                @else
                    <a href="{{ route('user.itinerary.create') }}"
                        class="inline-block bg-white text-blue-700 font-medium px-5 py-2 rounded-md text-sm hover:bg-blue-50 transition-colors">
                        Buat Rencana Baru
                    </a>
                @endguest
            </div>
        </div>

    </div>
@endsection

@push('scripts')
<script>
document.getElementById('copy-public-url')?.addEventListener('click', function () {
    const url = this.dataset.url;
    navigator.clipboard.writeText(url).then(() => {
        const original = this.innerHTML;
        this.innerHTML = '<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Tersalin!';
        setTimeout(() => { this.innerHTML = original; }, 2000);
    });
});
</script>
@endpush
