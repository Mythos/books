require("./bootstrap");

$(document).ready(() => {
    $('[data-toggle="tooltip"]').tooltip();
});

$('a[data-type="book-set-status"]').click(function (e) {
    e.preventDefault();
    let $this = $(this);
    let token = $('meta[name="csrf-token"]').attr("content");
    $.post($this.attr("href"), {
        _token: token,
    }).done(function () {
        location.reload();
            return;
    });
});
