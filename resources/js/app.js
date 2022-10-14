require("./bootstrap");

let tooltipList = [];
function initializeTooltips() {
    const tooltipTriggerList = [].slice.call(
        document.querySelectorAll('[data-bs-toggle="tooltip"]')
    );
    tooltipList.map(function (tooltip) {
        tooltip.hide();
    });
    tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
}

function initializeVolumePopover() {
    const volumeCoverElements = [].slice.call(document.querySelectorAll('.volume-cover'));
    volumeCoverElements.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl, {
            html: true,
            trigger: 'hover',
            container: 'body',
            content: function () {
                return '<img src="' + popoverTriggerEl.dataset.imageUrl + '" alt="' + popoverTriggerEl.alt + '" class="img-fluid" />';
            }
        })
    });
}

document.addEventListener('livewire:load', function () {
    initializeTooltips();
    initializeVolumePopover();
});
document.addEventListener('livewire:update', function () {
    initializeTooltips();
    initializeVolumePopover();
});

