$(document).ready(function () {
    $(".deleteLink").click(function () {
        if (!confirm("Really delete this subscription ?")) {
            return false;
        }
    });
});