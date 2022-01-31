<div class="col-sm-12 col-md-12 col-lg-6" style="max-height: 200px;">
    <canvas id="volumesByStatusStatistics"></canvas>
</div>
<div id="volumesByStatusStatistics-legend" class="col-sm-12 col-md-12 col-lg-6 chart-legend" style="max-height: 200px; overflow-y: auto;"></div>

<script>
    let volumesByStatusStatistics = null;
    document.addEventListener('livewire:load', function() {
        volumesByStatusStatistics = volumesByStatusChart();
    });
    document.addEventListener('livewire:update', function() {
        volumesByStatusStatistics.destroy();
        volumesByStatusStatistics = volumesByStatusChart();
    });

    function volumesByStatusChart() {
        const dataSet = @this.volumesByStatusStatistics;
        const labelSet = Object.keys(dataSet);
        const valueSet = Object.values(dataSet);
        const colors = [
            '#dc3545',
            '#ffc107',
            '#0dcaf0',
            '#0d6efd',
            '#198754',
        ];
        return PieChart.create('volumesByStatusStatistics', labelSet, valueSet, colors);
    }
</script>
