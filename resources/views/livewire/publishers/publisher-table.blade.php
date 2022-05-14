@section('title')
    {{ __('Publishers') }}
@endsection

<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
            <li class="breadcrumb-item">{{ __('Administration') }}</a></li>
            <li class="breadcrumb-item active">{{ __('Publishers') }}</li>
        </ol>
    </nav>
    <div class="row bg-white shadow-sm rounded p-3">
        <div>
            <h2 style="display: inline;">{{ __('Publishers') }}</h2>
            <div class="float-end" style="display: inline;">
                <a href="{{ route('publishers.create') }}" class="btn btn-link"><span class="fas fa-plus-circle"></span></a>
            </div>
        </div>
        <div class="table-responsive my-2" style="width: 100%;">
            <table class="table table-hover">
                <thead class="table-dark">
                    <tr>
                        <th scope="col" class="text-center" style="width: 2rem; min-width: 2rem;"></th>
                        <th>{{ __('Name') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($publishers as $publisher)
                        <tr>
                            <td class="text-center"><a href="{{ route('publishers.edit', [$publisher]) }}"><span class="fa fa-edit"></span></a></td>
                            <td>{{ $publisher->name }}</td>
                        </tr>
                    @endforeach
                    @if ($publishers->count() == 0)
                        <tr>
                            <td colspan="5" style="text-align: center;">{{ __('No data') }}</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
