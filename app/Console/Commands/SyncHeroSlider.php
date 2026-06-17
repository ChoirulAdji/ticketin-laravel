<?php

namespace App\Console\Commands;

use App\Models\Event;
use App\Models\HeroSlider;
use Illuminate\Console\Command;

class SyncHeroSlider extends Command
{
    protected $signature   = 'ticketin:sync-slider';
    protected $description = 'Sync semua event published ke hero_sliders';

    public function handle(): void
    {
        $events = Event::published()->whereNotNull('gambar_cover')->get();
        $added  = 0;

        foreach ($events as $event) {
            if (!HeroSlider::where('event_id', $event->id)->exists()) {
                HeroSlider::create([
                    'event_id' => $event->id,
                    'urutan'   => HeroSlider::max('urutan') + 1,
                    'aktif'    => true,
                ]);
                $this->line("  ✓ Added: {$event->judul}");
                $added++;
            }
        }

        $this->info("Selesai. {$added} event ditambahkan ke hero slider.");
    }
}
