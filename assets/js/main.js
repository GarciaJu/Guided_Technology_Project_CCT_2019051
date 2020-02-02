// quick function to write less code
function changePage(from, to) {
    $(from).fadeOut("fast", function() {
        $(to).fadeIn("fast");
    })
}

$('.link-scroll').click(function(e){
    e.preventDefault();
    var h = $(this).attr('href');
    $('html,body').animate({
        scrollTop: $(h).offset().top
    }, 1000);
});