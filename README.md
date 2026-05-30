# Nusatawan 🌴
### Travel planning platform for Indonesian destinations — with real-time weather

<div align="center">

![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-005C84?style=for-the-badge&logo=mysql&logoColor=white)
![TailwindCSS](https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)
![OpenWeatherMap](https://img.shields.io/badge/OpenWeatherMap-FF6B35?style=for-the-badge&logoColor=white)

</div>

---

Nusatawan helps tourists plan trips around Indonesia by combining destination info with a 5-day weather forecast. The idea came from a simple frustration — most tourism sites tell you *where* to go, but not whether the weather will cooperate when you get there.

🌐 Live: [nusatawan.aerossky.com](https://nusatawan.aerossky.com)

---

## Features

- 🏖️ Browse tourist destinations across Indonesia with complete info
- 🌤️ 5-day weather forecast with 3-hour intervals per destination
- 🔍 Search by location, category, or name
- 📍 Map integration for accurate location display
- 📱 Fully responsive on all devices

---

## Architecture

Built with a **Service Pattern** — business logic is separated into dedicated Service classes, keeping controllers thin and code reusable across the app.

```
app/
├── Http/Controllers/     # Thin controllers, delegate to services
├── Services/             # Business logic lives here
│   ├── DestinationService.php
│   ├── WeatherService.php
│   └── TourismService.php
└── Models/               # Eloquent models
```

---

## Tech Stack

| Layer | Technology |
|---|---|
| Backend | Laravel (PHP), Service Pattern |
| Frontend | Blade, TailwindCSS, Vite |
| Database | MySQL |
| Weather API | OpenWeatherMap |

---

## Running locally

Prerequisites: PHP >= 8.0, Composer, Node.js, MySQL

```bash
git clone https://github.com/Aerossky/Nusatawan.git
cd Nusatawan

composer install && npm install

cp .env.example .env
php artisan key:generate

# Set your DB credentials in .env, then:
php artisan migrate --seed

# Add OpenWeatherMap API key to .env:
# OPENWEATHER_API_KEY=your_key_here

npm run build
php artisan serve
```

---

## Dataset

Destination data sourced from [Kaggle — Destinasi Wisata Dataset](https://www.kaggle.com/datasets/athreal/destinasi-wisata-dataset) by Rahma Annisa & Aqila Darin Makkyah.

---

## Developer

**Risky** — [@Aerossky](https://github.com/Aerossky) · [LinkedIn](https://linkedin.com/in/risky-aerossky) · risky4tech@gmail.com

---

## License

MIT
