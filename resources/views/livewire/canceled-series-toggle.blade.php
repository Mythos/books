<div>
    @if ($show_canceled_series)
        <a class="dropdown-item" href="#" wire:click='toggle'>{{ __('Hide canceled Series') }}</a>
    @else
        <a class="dropdown-item" href="#" wire:click='toggle'>{{ __('Show canceled Series') }}</a>
    @endif
</div>
