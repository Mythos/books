<?php

namespace App\Http\Livewire;

use DateTime;
use Illuminate\Support\Facades\Http;
use Livewire\Component;

class VersionCheck extends Component
{
    public function render()
    {
        $shown = session('version_update_shown', []);
        if (empty($shown['date']) || $shown['date']->diff(new DateTime())->days > 0) {
            $response = Http::get('https://api.github.com/repos/Mythos/books/releases/latest');
            if (!$response->successful()) {
                return view('livewire.version-check');
            }
            $latestVersion = $response['tag_name'];
            if (version_compare(config('app.version'), $latestVersion) < 0) {
                toastr()->addWarning(__('Version :version is available', ['version' => $latestVersion]));
                session()->put('version_update_shown', ['version' => $latestVersion, 'date' => new DateTime()]);
            }
        }
        return view('livewire.version-check');
    }
}
