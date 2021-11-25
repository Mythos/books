<div class="col-sm-12 col-md-6 col-lg-3 d-flex align-self-stretch">
    <div class="card shadow-sm mb-4" style="width: 30rem;">
        <img src="{{ $series->image }}" alt="{{ $series->image }}" class="card-img-top" style="max-height: 400px; object-fit: contain;">
        <div class="card-body d-flex flex-column">
            <h5 class="card-title text-uppercase">{{ $series->name }}</h5>
            <div class="mt-auto">
                <div class="float-left">
                    <span class="{{ $series->status_class }}">{{ $series->status_name }}</span>
                </div>
                <div class="float-right">
                    <span class="{{ $series->completion_status_class }}">{{ $series->completion_status_name }}</span>
                </div>
            </div>
            <a href="{{ route('series.show', [$category, $series]) }}" class="stretched-link"></a>
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
