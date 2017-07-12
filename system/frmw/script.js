$(document).ready(function () {

    var out = false
    $('.btn-mobile-nav').on('click', function () {
        if (out) {
            out = false;
            animate('#mobile-nav', 'slideOutRight', function () {
                $(this).removeClass().addClass('hide');
            });
        } else {
            out = true;
            $('#mobile-nav').height($(window).height());
            $(window).height($('#mobile-nav').height());
            animate('#mobile-nav', 'slideInRight');
        }
    });
    $(window).on('resize', function () {
        if (out) {
            out = false;
            animate('#mobile-nav', 'slideOutRight', function () {
                $(this).removeClass().addClass('hide');
            });
        }
    });

});

function animate(jqueryId, animType, callBack = function() {$(this).removeClass(); }) {
    $(jqueryId).removeClass().addClass(animType + ' animated').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', callBack);
}