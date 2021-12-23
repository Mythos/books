<div>
    @if ($show_nsfw)
        <a class="nav-link" href="#" wire:click='toggle'>{{ __('Hide NSFW') }}</a>
    @else
        <a class="nav-link" href="#" wire:click='toggle'>{{ __('Show NSFW') }}</a>
    @endif
</div>
