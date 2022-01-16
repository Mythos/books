<div>
    @if ($show_nsfw)
        <a class="dropdown-item" href="#" wire:click='toggle'>{{ __('Hide NSFW') }}</a>
    @else
        <a class="dropdown-item" href="#" wire:click='toggle'>{{ __('Show NSFW') }}</a>
    @endif
</div>
