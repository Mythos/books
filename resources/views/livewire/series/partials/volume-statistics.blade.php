<div class="row">
    <div class="col-sm-12 col-md-12 col-lg-6" style="max-height: 200px;">
        <canvas id="volumeStatistics"></canvas>
    </div>
    <div id="volumeStatistics-legend" class="col-sm-12 col-md-12 col-lg-6 chart-legend" style="max-height: 200px; overflow-y: auto;"></div>
</div>

<script>
    let chart = null;
    document.addEventListener('livewire:load', function() {
        chart = initializeChart();
    });
    document.addEventListener('livewire:update', function() {
        chart.destroy();
        chart = initializeChart();
    });

    function initializeChart() {
        const labelSet = [
            '{{ __('New') }}',
            '{{ __('Ordered') }}',
            '{{ __('Shipped') }}',
            '{{ __('Delivered') }}',
            '{{ __('Read') }}',
        ];
        const valueSet = [
            @this.new,
            @this.ordered,
            @this.shipped,
            @this.delivered,
            @this.read,
        ];

        const colors = [
            '#dc3545',
            '#ffc107',
            '#0dcaf0',
            '#0d6efd',
            '#198754',
        ];
        return PieChart.create('volumeStatistics', labelSet, valueSet, colors);

    }
</script>
