$(function () {
    $("body").on("click", ".toggle-password", function () { // Toggle Password Script
        $(this).toggleClass("fa-eye fa-eye-slash");
        var input = $("#" + $(this).data("id"));
        if (input.attr("type") === "password") {
            input.attr("type", "text");
        } else {
            input.attr("type", "password");
        }
    });
});
