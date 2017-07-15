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

function prepare_post(sel, plus)
{
    sel = sel || "";
    plus = plus || {};

    if (sel == "")
        var mypost = [];
    else
    {
        _jqtmp2 = $(sel).filter("input[type=hidden]");
        _jqtmp = $(sel).filter(":input").filter(":enabled").add(_jqtmp2);
        _jqtmp3 = $(sel).find("*").filter("input[type=hidden]");
        var tmp = $(sel).find("*").filter(":input").filter(":enabled").add(_jqtmp3).add(_jqtmp).filter("input[placeholder],textarea[placeholder]");
        var surcouche_placeholder = false;
        tmp.each(function () {
            if (($(this).attr("placeholder") != "") && ($(this).attr("placeholder") == $(this).val()))
            {
                surcouche_placeholder = true;
                $(this).val("");
            }
        });
        var mypost = $(sel).find("*").filter(":input").filter(":enabled").add(_jqtmp3).add(_jqtmp).serializeArray();
        if (surcouche_placeholder)
        {
            tmp.each(function () {
                if (($(this).attr("placeholder") != "") && ($(this).val() == ""))
                {
                    $(this).val($(this).attr("placeholder"));
                }
            });
        }
    }

    var et = new Array();
    var j = 0;
    for (i in plus)
    {
        if ($.isArray(plus[i]))
        {
            for (k in plus[i])
            {
                et[j] = {name: i + "[]", value: plus[i][k]};
                j = j + 1;
            }
        } else
        {
            et[j] = {name: i, value: plus[i]};
            j = j + 1;
        }
    }

    $.merge(mypost, et);

    return mypost;
}