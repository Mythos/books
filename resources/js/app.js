require("./bootstrap");

const tooltipTriggerList = [].slice.call(
    document.querySelectorAll('[data-bs-toggle="tooltip"]')
);
const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
});

const volumeCoverElements = [].slice.call(document.querySelectorAll('.volume-cover'));
volumeCoverElements.map(function (popoverTriggerEl) {
    return new bootstrap.Popover(popoverTriggerEl, {
        html: true,
        trigger: 'hover',
        container: 'body',
        content: function () {
            return '<img src="' + popoverTriggerEl.src + '" alt="' + popoverTriggerEl.alt + '" class="img-fluid" />';
        }
    })
});
