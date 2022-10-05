<?php

namespace App\Jobs;

use App\Constants\SeriesStatus;
use App\Mail\SeriesUpdated;
use App\Models\Series;
use App\Models\User;
use App\Models\Volume;
use App\Services\SeriesService;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
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
        $statusesToSkip = [];
        if (!config('jobs.mangapassion_updater.include_completed')) {
            $statusesToSkip[] = SeriesStatus::FINISHED;
        }
        if (!config('jobs.mangapassion_updater.include_canceled')) {
            $statusesToSkip[] = SeriesStatus::CANCELED;
        }
        Log::info('Started updating series metadata');
        $changeLog = [];
        $originalSeries = Series::all();
        $originalVolumes = Volume::all();
        $series = Series::whereNotNull('mangapassion_id');
        if (count($statusesToSkip) > 0) {
            $series = $series->whereNotIn('status', $statusesToSkip);
        }
        $series = $series->orderBy('name')->get();
        foreach ($series as $s) {
            try {
                Log::info("Updating series metadata for {$s->name}...");
                $originalS = $originalSeries->where('id', $s->id)->first();
                $originalV = $originalVolumes->where('series_id', $s->id);

                $seriesService->refreshMetadata($s);

                Log::info("Updating volumes for {$s->name}...");
                $volumes = $seriesService->updateVolumes($s);
                $changes = $this->check_for_changes($originalS, $s, $originalV, $volumes);
                if (!empty($changes) && (!empty($changes['series']) || !empty($changes['volumes']))) {
                    if (!empty($changes['series'])) {
                        $changeLog[$s->name]['series'] = $changes['series'];
                    }
                    if (!empty($changes['volumes'])) {
                        $changeLog[$s->name]['volumes'] = $changes['volumes'];
                    }
                }
            } catch (Exception $exception) {
                Log::error('Error while updating series via API', ['exception' => $exception]);
            }
        }
        $this->sendEmail($changeLog);
        Log::info('Finished updating series metadata');
    }

    private function check_for_changes($originalSeries, $newSeries, $originalVolumes, $newVolumes) : ?array
    {
        $seriesChanges = [];
        $volumeChanges = [];
        $original = $originalSeries->toArray();
        $new = $newSeries->toArray();
        $differences = array_diff_assoc($new, $original);
        if (!empty($differences)) {
            foreach ($differences as $key => $value) {
                $name = $this->getChangedName($key);
                if (empty($name)) {
                    continue;
                }
                $oldValue = $original[$key] ?? 'NULL';
                $newValue = $new[$key] ?? 'NULL';
                if (is_numeric($oldValue) && is_numeric($newValue)) {
                    if (intval($oldValue) == intval($newValue) && floatval($oldValue) == floatval($newValue)) {
                        continue;
                    }
                }
                $seriesChanges[] = __(':name changed from :old to :new', ['name' => $name, 'old' => $oldValue, 'new' => $newValue]);
            }
        }
        foreach ($newVolumes as $newVolume) {
            $originalVolume = $originalVolumes->where('id', $newVolume->id)->first();
            $new = $newVolume->toArray();
            if (empty($originalVolume)) {
                $volumeChanges[__('Volume :number', ['number' => $new['number']])] = __('New Volume :number (ISBN: :isbn, Publish Date: :publish_date, Price: :price)', ['number' => $new['number'] ?? 'NULL', 'isbn' => $new['isbn'] ?? 'NULL', 'publish_date' => $new['publish_date'] ?? 'NULL', 'price' => $new['price'] ?? 'NULL']);
            } else {
                $original = $originalVolume->toArray();
                $differences = array_diff_assoc($new, $original);
                if (!empty($differences)) {
                    foreach ($differences as $key => $value) {
                        $name = $this->getChangedName($key);
                        if (empty($name)) {
                            continue;
                        }
                        $oldValue = $original[$key] ?? 'NULL';
                        $newValue = $new[$key] ?? 'NULL';
                        if (is_numeric($oldValue) && is_numeric($newValue)) {
                            if (intval($oldValue) == intval($newValue) && floatval($oldValue) == floatval($newValue)) {
                                continue;
                            }
                        }
                        $volumeChanges[__('Volume :number', ['number' => $new['number']])][] = __(':name changed from :old to :new', ['name' => $name, 'old' => $oldValue ?? 'NULL', 'new' => $newValue ?? 'NULL']);
                    }
                }
            }
        }

        return ['series' => $seriesChanges, 'volumes' => $volumeChanges];
    }

    private function getChangedName($key)
    {
        switch ($key) {
            case 'name': return __('Name');
            case 'status': return __('Status');
            case 'total': return __('Total');
            case 'default_prices': return __('Default price');
            case 'description': return __('Description');
            case 'number': return __('Number');
            case 'publish_date': return __('Publish Date');
            case 'isbn': return __('ISBN');
            case 'price': return __('Price');
            case 'image_url': return __('Image URL');
            case 'source_status': return __('Status (Source)');
            case 'source_name': return __('Original Title');
            case 'source_name_romaji': return __('Original Title (Romaji)');
            case 'pages': return __('Pages');
            default: return null;
        }
    }

    private function sendEmail($changes): void
    {
        if (empty($changes)) {
            return;
        }
        $recipients = User::all(['email']);
        foreach ($recipients as $recipient) {
            Mail::to($recipient)->send(new SeriesUpdated($changes));
        }
    }
}
