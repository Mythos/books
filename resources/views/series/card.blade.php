<div class="col-sm-12 col-md-6 col-lg-3 d-flex align-self-stretch">
    <div class="card shadow-sm mb-4" style="width: 30rem;">
        @livewire('series.image', [$item])
        <div class="card-body d-flex flex-column">
            <h5 class="card-title text-uppercase">{{ $item->name }}</h5>
            <div class="mt-auto">
                <div class="float-start">
                    <span class="{{ $item->status_class }}">{{ $item->status_name }}</span>
                </div>
                <div class="float-end">
                    <span class="{{ $item->completion_status_class }}">{{ $item->completion_status_name }}</span>
                </div>
            </div>
            <a href="{{ route('series.show', [$category, $item]) }}" class="stretched-link"></a>
        </div>
    </div>
</div>

<style>
    .new {
        color: red;
    }

    .ordered {
        color: orange;
    }

    .delivered {
        color: green;
    }

</style>
