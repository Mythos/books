<div class="col-sm-12 col-md-6 col-lg-4">
    <h2>{{ __('Volumes per genre') }}</h2>
    <div class="row py-3 px-1">
        <div class="col-sm-12 col-md-12 col-lg-6" style="max-height: 200px;">
            <canvas id="volumesByGenreStatistics"></canvas>
        </div>
        <div id="volumesByGenreStatistics-legend" class="col-sm-12 col-md-12 col-lg-6 chart-legend" style="max-height: 200px; overflow-y: auto;"></div>
    </div>
    <script>
        let volumesByGenreStatistics = null;
        document.addEventListener('livewire:load', function() {
            volumesByGenreStatistics = volumesByGenreChart();
        });
        document.addEventListener('livewire:update', function() {
            volumesByGenreStatistics.destroy();
            volumesByGenreStatistics = volumesByGenreChart();
        });

        function volumesByGenreChart() {
            const dataSet = @this.volumesByGenreStatistics;
            const labelSet = Object.keys(dataSet);
            const valueSet = Object.values(dataSet);
            const colors = [
                '#0d6efd',
                '#6610f2',
                '#6f42c1',
                '#d63384',
                '#dc3545',
                '#fd7e14',
                '#ffc107',
                '#198754',
                '#20c997',
                '#0dcaf0',
            ];

            return PieChart.create('volumesByGenreStatistics', labelSet, valueSet, colors);
        }
    </script>
</div>
