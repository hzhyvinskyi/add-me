$(document).ready(function ($) {

    $('select.language').on('change', function () {
        $('#site-language-form').submit();
	});

    /*====== BACK TO TOP ======*/
    $('.back-to-top-page').each(function () {
        $('.back-to-top').on('click', function (event) {
            event.preventDefault();
            $('html, body').animate({scrollTop: 0}, 1500);
            return false;
        });
    });

});
