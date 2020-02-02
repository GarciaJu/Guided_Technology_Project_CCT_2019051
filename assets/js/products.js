function changeToPage(page) {
    var ph = $(".page-holder");
    ph.each(function() {
        var t = $(this);

        if(t.attr("data-pid") != page) {
            t.addClass("d-none");
            $("#selector-" + t.attr("data-pid")).parent().removeClass("active");
        } else {
            t.removeClass("d-none");
            $("#selector-" + page).parent().addClass("active");
        }
    })
}