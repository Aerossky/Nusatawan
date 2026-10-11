# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

**Nusatawan** adalah platform perencanaan wisata Indonesia (skripsi) — pengguna bisa browse destinasi, melihat prakiraan cuaca 5 hari per destinasi (OpenWeatherMap), membuat itinerary, dan mengajukan destinasi baru untuk diverifikasi admin.

Live: [nusatawan.aerossky.com](https://nusatawan.aerossky.com)

---

## Commands

### Development (all-in-one)
```bash
composer run dev
```
Menjalankan 4 proses sekaligus via `concurrently`: `php artisan serve`, `queue:listen`, `pail` (log streaming), dan `vite`.

### Frontend only
```bash
npm run dev      # Vite dev server with HMR
npm run build    # Production build
```

### Backend only
```bash
php artisan serve
```

### Database
```bash
php artisan migrate
php artisan migrate:fresh --seed   # Reset + seed semua data
php artisan db:seed --class=UserSeeder   # Seed spesifik
```

### Storage symlink (wajib setelah fresh install)
```bash
php artisan storage:link
```

### Testing
```bash
php artisan test
php artisan test --filter=NamaTest   # Jalankan satu test
./vendor/bin/phpunit tests/Feature/NamaTest.php
```

### Code style
```bash
./vendor/bin/pint   # Laravel Pint (PSR-12)
```

### Cache clearing
```bash
php artisan optimize:clear   # Clear semua cache sekaligus
```

---

## Architecture

### Service Pattern

Controllers hanya menerima request, memanggil service, dan return response. Business logic sepenuhnya di `app/Services/`.

```
Request → Controller → Service → Model/DB
```

**Dependency injection** via constructor — service di-inject ke controller, bukan di-instantiate manual.

### Service Structure

```
app/Services/
├── WeatherService.php           # OpenWeatherMap API + caching
├── ItineraryService.php         # CRUD itinerary & itinerary destinations
├── DestinationSubmissionService.php  # Alur submit → approve/reject → jadi Destination
├── CategoryService.php
├── AuthService.php
├── LikeService.php
├── ReviewService.php
├── ProfileService.php
├── UserService.php
├── StatsService.php             # Statistik dashboard admin & itinerary user
└── Destination/
    ├── DestinationService.php       # Facade: CRUD + delegasi ke sub-service
    ├── DestinationQueryService.php  # Builder query: search, filter, sort, slug lookup
    ├── DestinationGeoService.php    # Haversine formula untuk nearby destinations
    └── DestinationImageService.php  # Upload, primary image, delete images
```

`DestinationService` adalah entry point — ia mendelegasikan ke `QueryService`, `GeoService`, dan `ImageService`.

### Authentication & Authorization

Dua role di `users.isAdmin` (boolean). Tidak ada Laravel Sanctum/Passport — custom middleware:

- `auth.user` (`IsUser`) → redirect ke login jika belum auth
- `auth.admin` (`IsAdmin`) → cek `Auth::user()->isAdmin`

Route dikelompokkan di `routes/web.php`:
- Public: `/`, `/tentang`, `destinasi` (read only)
- `middleware('auth.user')` → profil, itinerary, favorit, submit destinasi, like, review
- `middleware('auth.admin')` → `/admin/*`

### Models & Relationships

```
User ──< Itinerary ──< ItineraryDestination >── Destination
User ──< LikedDestination >── Destination
User ──< Review >── Destination
User ──< DestinationSubmission ──< DestinationSubmissionImage
Destination ──< DestinationImage
Destination >── Category
```

**Slug**: `Destination` (dari `place_name`) dan `Itinerary` (dari `title`) menggunakan package `cviebrock/eloquent-sluggable` dengan `onUpdate: true`. Route `destinasi/{destination:slug}` dan `rencana-perjalanan/{itinerary}` (Itinerary override `getRouteKeyName()` ke `slug`).

### Destination Submission Flow

1. User submit form → `DestinationSubmission` (status: `pending`) + images di `storage/destination-submissions/`
2. Admin review di `/admin/pengajuan-destinasi/{id}`
3. Admin approve → `DestinationSubmissionService::approveSubmission()` membuat `Destination` baru, copy file gambar ke `storage/destinations/`, set `DestinationSubmission.status = 'approved'`
4. Admin reject → update status ke `rejected` + `admin_note`

### Weather Integration

`WeatherService` hits OpenWeatherMap `/forecast` (5 hari, interval 3 jam). Response di-cache dengan key `weather_forecast_{lat}_{lng}_{days}_{units}` selama 60 menit (configurable via `OPENWEATHER_CACHE_MINUTES`).

Di `ItineraryController::show()`, weather di-fetch per koordinat destinasi, di-cache lagi dengan key `weather_coordinate_{lat_2dp}_{lng_2dp}` untuk menghindari duplikasi API call jika dua destinasi koordinatnya berdekatan.

### Frontend

**CSS Framework**: TailwindCSS v4 + PostCSS. Config di `tailwind.config.js`.  
**UI Components**: Flowbite (komponen interaktif), Swiper (slider gambar).

**Vite entry points** (semua didefinisikan di `vite.config.js`):
- `resources/css/app.css` — main styles
- `resources/css/custom/preview.css`, `map.css` — page-specific styles
- `resources/js/app.js` — bootstrap utama (Axios, Flowbite init)
- `resources/js/like.js` — AJAX like/unlike
- `resources/js/share.js` — share destination
- `resources/js/location-search.js` — pencarian destinasi di itinerary (geolocation + name search)
- `resources/js/pages/create-destination/` — editor teks + image preview
- `resources/js/pages/destination-submission/` — form submission user (map, upload, validation, tab)

**Blade layouts**: `layouts/user.blade.php` dan `layouts/admin.blade.php`.  
**Blade components**: `resources/views/components/` — `destination-card`, `button`, `section`, `ui/toast`, `ui/alert`, `user/navbar`, `user/footer`, `admin/aside`, `admin/navbar`.

### URL Conventions (Bahasa Indonesia)

| Bahasa | URL |
|--------|-----|
| destinasi | `destinasi` |
| rencana perjalanan | `rencana-perjalanan` |
| pengajuan destinasi | `pengajuan-destinasi` |
| profil | `profil` |
| destinasi favorit | `destinasi-favorit` |
| pengguna (admin) | `admin/pengguna` |
| kategori (admin) | `admin/kategori` |

### .env Keys yang diperlukan

```
DB_*                        # MySQL credentials
OPENWEATHER_API_KEY=        # OpenWeatherMap API key
OPENWEATHER_CACHE_MINUTES=60
APP_URL=
```

### Image Storage

Gambar disimpan ke Laravel `public` disk (`storage/app/public`):
- Destination images: `destinations/`
- Submission images: `destination-submissions/`

Akses via URL: `Storage::url($path)` atau helper `asset('storage/' . $path)`. Wajib jalankan `php artisan storage:link` agar `/public/storage` tersambung.

---

## Commit Convention

Menggunakan [Conventional Commits](https://www.conventionalcommits.org/):

```
<type>(<scope>): <description>
```

**Types:**
| Type | Kapan dipakai |
|------|---------------|
| `feat` | fitur baru |
| `fix` | perbaikan bug |
| `refactor` | refaktor tanpa perubahan fungsional |
| `style` | perubahan tampilan/CSS tanpa logika |
| `chore` | maintenance, dependency update, config |
| `docs` | perubahan dokumentasi saja |
| `test` | penambahan atau perbaikan test |
| `perf` | optimasi performa |

**Scope** (opsional): area yang diubah, misal `navbar`, `itinerary`, `auth`, `admin`, `destination`.

**Contoh:**
```
feat(navbar): redesign with responsive layout and gradient hero state
fix(auth): store intended URL before redirecting to login
refactor(itinerary): remove manual destination count aggregation
chore: update composer dependencies
docs: add commit convention to CLAUDE.md
```
