<a class="text-muted" href="https://github.com/Mythos/books/releases" target="_blank">
    {{ __('Version') }} {{ config('app.version') }} @if (!empty($latestVersion)){{ __('(Latest: :version)', ['version' => $latestVersion]) }}@endif
</a>
