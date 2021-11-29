<div class="col-sm-12 col-md-6 col-lg-3 d-flex align-self-stretch">
    <div class="card shadow-sm mb-4" style="width: 30rem;">
        <img src="{{ $item->image }}" alt="{{ $item->image }}" class="card-img-top" style="max-height: 400px; object-fit: contain;">
        <div class="card-body d-flex flex-column">
            <h5 class="card-title text-uppercase">{{ $item->name }}</h5>
            <div class="mt-auto">
                <div class="float-left">
                    <span class="{{ $item->status_class }}">{{ $item->status_name }}</span>
                </div>
                <div class="float-right">
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
