<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Livewire\Component;

class Version extends Component
{
    public ?string $latestVersion;

    public function render()
    {
        $this->latestVersion = $this->getLatestVersion();
        $this->showVersionCheckNotification();
        return view('livewire.version');
    }

    private function getLatestVersion()
    {
        return Cache::remember('github_latest_version', 43200, function () {
            $response = Http::get('https://api.github.com/repos/Mythos/books/releases/latest');
            if (!$response->successful()) {
                return null;
            }
            return $response['tag_name'];
        });
    }

    private function isNewerVersionAvailable()
    {
        return version_compare(config('app.version'), $this->latestVersion) < 0;
    }

    private function showVersionCheckNotification()
    {
        $lastShown = session('version_check_shown');
        if (empty($lastShown)) {
            return true;
        }

        $difference = $lastShown->diffInMinutes(Carbon::now());
        if ($difference < 60) {
            return;
        }

        if ($this->latestVersion != null && $this->isNewerVersionAvailable($this->latestVersion)) {
            toastr()->addInfo(__('Version :version is available', ['version' => $this->latestVersion]));
        } else if ($this->latestVersion == null) {
            toastr()->addWarning(__('Version check failed'));
        }
        session(['version_check_shown' => Carbon::now()]);
    }
}
