<div class="col-sm-12 col-md-6 col-lg-4">
    <h2>{{ __('Series per status') }}</h2>
    <div class="row py-3 px-1">
        <div class="col-sm-12 col-md-12 col-lg-6" style="max-height: 200px;">
            <canvas id="seriesByStatusStatistics"></canvas>
        </div>
        <div id="seriesByStatusStatistics-legend" class="col-sm-12 col-md-12 col-lg-6 chart-legend" style="max-height: 200px; overflow-y: auto;"></div>
    </div>
    <script>
        let seriesByStatusStatistics = null;
        document.addEventListener('livewire:load', function() {
            seriesByStatusStatistics = seriesByStatusChart();
        });
        document.addEventListener('livewire:update', function() {
            seriesByStatusStatistics.destroy();
            seriesByStatusStatistics = seriesByStatusChart();
        });

        function seriesByStatusChart() {
            const dataSet = @this.seriesByStatusStatistics;
            const labelSet = Object.keys(dataSet);
            const valueSet = Object.values(dataSet);
            const colors = [
                '#6c757d',
                '#0d6efd',
                '#198754',
                '#dc3545',
                '#ffc107',
            ];
            return PieChart.create('seriesByStatusStatistics', labelSet, valueSet, colors);
        }
    </script>
</div>
