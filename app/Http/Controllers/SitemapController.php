<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class SitemapController extends Controller
{
    public function index()
    {
        $sitemap = Sitemap::create();

        $sitemap->add(
            Url::create(route('user.home'))
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                ->setPriority(1.0)
        );

        $sitemap->add(
            Url::create(route('user.destinations.index'))
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                ->setPriority(0.9)
        );

        Destination::with('primaryImage')
            ->orderByDesc('updated_at')
            ->each(function (Destination $destination) use ($sitemap) {
                $sitemap->add(
                    Url::create(route('user.destinations.show', $destination))
                        ->setLastModificationDate($destination->updated_at)
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                        ->setPriority(0.8)
                );
            });

        return $sitemap->toResponse(request());
    }
}
