@section('title')
    {{ $category->name }}
@endsection

<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
            <li class="breadcrumb-item active">{{ $category->name }}</li>
        </ol>
    </nav>
    @if ($category->type == 0)
        @livewire('series.gallery', [$category])
    @elseif($category->type == 1)
        @livewire('articles.gallery', [$category])
    @endif
</div>
