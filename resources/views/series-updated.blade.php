@component('mail::message')
# {{ __('Series Changes') }}

@foreach ($changes as $series => $seriesChanges)
@if (!empty($seriesChanges['series']) || !empty($seriesChanges['volumes']))
<h2>{{ $series }}</h2>
@endif
@if (!empty($seriesChanges['series']))
<h3>{{ __('Series Metadata') }}</h3>
<ul>
@foreach ($seriesChanges['series'] as $seriesChange)
<li>{{ $seriesChange }}</li>
@endforeach
</ul>
@endif
@if (!empty($seriesChanges['volumes']))
@foreach ($seriesChanges['volumes'] as $volume => $volumeChanges)
<h3>{{ $volume }}</h3>
<ul>
@if (is_array($volumeChanges))
@foreach ($volumeChanges as $volumeChange)
<li>{{ $volumeChange }}</li>
@endforeach
@else
<li>{{ $volumeChanges }}</li>
@endif
</ul>
@endforeach
@endif
@endforeach
@endcomponent
