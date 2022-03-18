<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
            <li class="breadcrumb-item">{{ __('Administration') }}</a></li>
            <li class="breadcrumb-item active">{{ __('Genres') }}</li>
        </ol>
    </nav>
    <div class="row bg-white shadow-sm rounded p-3">
        <div>
            <h2 style="display: inline;">{{ __('Genres') }}</h2>
        </div>
        <div class="table-responsive my-2" style="width: 100%;">
            <table class="table table-hover">
                <thead class="table-dark">
                    <tr>
                        {{-- <th scope="col" class="text-center" style="width: 2rem; min-width: 2rem;"></th> --}}
                        <th>{{ __('Name') }}</th>
                        <th class="text-center" style="width: 7rem; min-width: 7rem;">{{ __('Type') }}</th>
                        <th class="text-end" style="width: 7rem; min-width: 7rem;">{{ __('SeriesPlural') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($genres as $genre)
                        <tr>
                            {{-- <td class="text-center"><a href="{{ route('genres.edit', [$genre]) }}"><span class="fa fa-edit"></span></a></td> --}}
                            <td>{{ $genre->name }}</td>
                            <td class="text-center" style="width: 7rem; min-width: 7rem;"><span class="{{ $genre->type_class }}">{{ $genre->type_name }}</span></td>
                            <td class="text-end" style="width: 7rem; min-width: 7rem;">{{ $genre->series->count() }}</span></td>
                        </tr>
                    @endforeach
                    @if ($genres->count() == 0)
                        <tr>
                            <td colspan="5" style="text-align: center;">{{ __('No data') }}</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
