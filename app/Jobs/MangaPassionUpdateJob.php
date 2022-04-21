<?php

namespace App\Jobs;

use App\Helpers\ImageHelpers;
use App\Models\Series;
use App\Services\SeriesService;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Log;

class MangaPassionUpdateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(SeriesService $seriesService): void
    {
        Log::info('Started updating series metadata');
        $series = Series::whereNotNull('mangapassion_id')->whereNot('status', '2')->orderBy('name')->get();
        foreach ($series as $s) {
            try {
                Log::info("Updating series metadata for {$s->name}...");
                $s = $seriesService->refreshMetadata($s);
                $s->save();

                Log::info("Updating image for {$s->name}...");
                $image = ImageHelpers::getImage($s->image_url);
                if (!empty($image)) {
                    ImageHelpers::storePublicImage($image, $s->image_path . '/cover.jpg');
                    $nsfwImage = $image->pixelate(config('images.nsfw.pixelate', 10))->blur(config('images.nsfw.blur', 5))->encode('jpg');
                    ImageHelpers::storePublicImage($nsfwImage, $s->image_path . '/cover_sfw.jpg');
                }
                Log::info("Updating volumes for {$s->name}...");
                $seriesService->updateVolumes($s);
            } catch (Exception $exception) {
                Log::error('Error while updating series via API', ['exception' => $exception]);
            }
        }
        Log::info('Finished updating series metadata');
    }
}
