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

    //Pagination
    $(document).on('click', '.pagine-num', function () {
        _change_page_pagine($(this).attr('data-num'));
    });
    $(document).on('click', '#pagine-prev', function () {
        if (!$(this).hasClass('disabled')) {
            _change_page_pagine(_paginePageActuel - 1);
        }
    });
    $(document).on('click', '#pagine-next', function () {
        if (!$(this).hasClass('disabled')) {
            _change_page_pagine(_paginePageActuel + 1);
        }
    });

    //Dialog
    $('#dialog').on('click', '.close-dialog', function () {
        dialog();
    });

});

var _paginePageActuel = 1;
var _pagineElement = null;
var _pagineNbPage = 0;
var _pagineCallback = null;
function pagine(nbPage, element, callback = function(page) {}) {
    _pagineElement = '#' + element;
    _pagineNbPage = nbPage;
    _pagineCallback = callback;
    var html = '<ul class="pagination"><li><span id="pagine-prev" class="btn btn-default disabled">«</span></li>';
    //Si il y a plus ou moins de 10 pages
    if (nbPage < 10) {
        for (i = 0; i < nbPage; i++) {
            if (i === 0) {
                html += '<li><span id="pagine_page_' + (i + 1) + '" class="btn btn-default active pagine-num" data-num="' + (i + 1) + '">' + (i + 1) + '</span></li>';
            } else {
                html += '<li><span id="pagine_page_' + (i + 1) + '" class="btn btn-default pagine-num" data-num="' + (i + 1) + '">' + (i + 1) + '</span></li>';
            }
        }
    } else {
        html += '<li><span id="pagine_page_1" class="btn btn-default active pagine-num" data-num="1">1</span></li>';
        html += '<li><span id="pagine_page_2" class="btn btn-default pagine-num" data-num="2">2</span></li>';
        html += '<li><span id="pagine_page_3" class="btn btn-default pagine-num" data-num="3">3</span></li>';
        html += '<li><span class="btn btn-default disabled">...</span></li>';
        html += '<li><span id="pagine_page_' + (nbPage - 2) + '" class="btn btn-default pagine-num" data-num="' + (nbPage - 2) + '">' + (nbPage - 2) + '</span></li>';
        html += '<li><span id="pagine_page_' + (nbPage - 1) + '" class="btn btn-default pagine-num" data-num="' + (nbPage - 1) + '">' + (nbPage - 1) + '</span></li>';
        html += '<li><span id="pagine_page_' + (nbPage) + '" class="btn btn-default pagine-num" data-num="' + (nbPage) + '">' + (nbPage) + '</span></li>';
    }
    //Si il y a qu'une page on disable aussi le next
    if (nbPage == 1) {
        html += '<li><span id="pagine-next" class="btn btn-default disabled">»</span></li></ul>';
    } else {
        html += '<li><span id="pagine-next" class="btn btn-default">»</span></li></ul>';
    }
    $('#' + element).html(html);
}

function _change_page_pagine(newPage) {
    //Si il y a plus ou moins de 10 pages
    if (_pagineNbPage < 10) {
        //Si on a pagine sans coupure on retire la class active sur le btn actuel et on le met sur le nouveau
        $('#pagine_page_' + _paginePageActuel).removeClass('active');
        $('#pagine_page_' + newPage).addClass('active');
        _paginePageActuel = newPage;
    } else {
        var html = '<ul class="pagination"><li><span id="pagine-prev" class="btn btn-default">«</span></li>';
        if (['1', '2', '3', '' + (_pagineNbPage - 2), '' + (_pagineNbPage - 1), '' + (_pagineNbPage)].indexOf('' + newPage) !== -1) {
            //Code Html
            html += '<li><span id="pagine_page_1" class="btn btn-default pagine-num" data-num="1">1</span></li>';
            html += '<li><span id="pagine_page_2" class="btn btn-default pagine-num" data-num="2">2</span></li>';
            html += '<li><span id="pagine_page_3" class="btn btn-default pagine-num" data-num="3">3</span></li>';
            html += '<li><span class="btn btn-default disabled">...</span></li>';
            html += '<li><span id="pagine_page_' + (_pagineNbPage - 2) + '" class="btn btn-default pagine-num" data-num="' + (_pagineNbPage - 2) + '">' + (_pagineNbPage - 2) + '</span></li>';
            html += '<li><span id="pagine_page_' + (_pagineNbPage - 1) + '" class="btn btn-default pagine-num" data-num="' + (_pagineNbPage - 1) + '">' + (_pagineNbPage - 1) + '</span></li>';
            html += '<li><span id="pagine_page_' + (_pagineNbPage) + '" class="btn btn-default pagine-num" data-num="' + (_pagineNbPage) + '">' + (_pagineNbPage) + '</span></li>';
        } else if (['4', '5'].indexOf('' + newPage) !== -1) {
            //Code Html
            html += '<li><span id="pagine_page_1" class="btn btn-default pagine-num" data-num="1">1</span></li>';
            html += '<li><span id="pagine_page_2" class="btn btn-default pagine-num" data-num="2">2</span></li>';
            html += '<li><span id="pagine_page_3" class="btn btn-default pagine-num" data-num="3">3</span></li>';
            html += '<li><span id="pagine_page_4" class="btn btn-default pagine-num" data-num="4">4</span></li>';
            html += '<li><span id="pagine_page_5" class="btn btn-default pagine-num" data-num="5">5</span></li>';
            html += '<li><span class="btn btn-default disabled">...</span></li>';
            html += '<li><span id="pagine_page_' + (_pagineNbPage) + '" class="btn btn-default pagine-num" data-num="' + (_pagineNbPage) + '">' + (_pagineNbPage) + '</span></li>';
        } else if (['' + (_pagineNbPage - 4), '' + (_pagineNbPage - 3)].indexOf('' + newPage) !== -1) {
            //Code Html
            html += '<li><span id="pagine_page_1" class="btn btn-default pagine-num" data-num="1">1</span></li>';
            html += '<li><span class="btn btn-default disabled">...</span></li>';
            html += '<li><span id="pagine_page_' + (_pagineNbPage - 4) + '" class="btn btn-default pagine-num" data-num="' + (_pagineNbPage - 4) + '">' + (_pagineNbPage - 4) + '</span></li>';
            html += '<li><span id="pagine_page_' + (_pagineNbPage - 3) + '" class="btn btn-default pagine-num" data-num="' + (_pagineNbPage - 3) + '">' + (_pagineNbPage - 3) + '</span></li>';
            html += '<li><span id="pagine_page_' + (_pagineNbPage - 2) + '" class="btn btn-default pagine-num" data-num="' + (_pagineNbPage - 2) + '">' + (_pagineNbPage - 2) + '</span></li>';
            html += '<li><span id="pagine_page_' + (_pagineNbPage - 1) + '" class="btn btn-default pagine-num" data-num="' + (_pagineNbPage - 1) + '">' + (_pagineNbPage - 1) + '</span></li>';
            html += '<li><span id="pagine_page_' + (_pagineNbPage) + '" class="btn btn-default pagine-num" data-num="' + (_pagineNbPage) + '">' + (_pagineNbPage) + '</span></li>';
        } else {
            html += '<li><span id="pagine_page_1" class="btn btn-default pagine-num" data-num="1">1</span></li>';
            html += '<li><span class="btn btn-default disabled">...</span></li>';
            html += '<li><span id="pagine_page_' + (newPage - 1) + '" class="btn btn-default pagine-num" data-num="' + (newPage - 1) + '">' + (newPage - 1) + '</span></li>';
            html += '<li><span id="pagine_page_' + (newPage) + '" class="btn btn-default pagine-num" data-num="' + (newPage) + '">' + (newPage) + '</span></li>';
            html += '<li><span id="pagine_page_' + (newPage + 1) + '" class="btn btn-default pagine-num" data-num="' + (newPage + 1) + '">' + (newPage + 1) + '</span></li>';
            html += '<li><span class="btn btn-default disabled">...</span></li>';
            html += '<li><span id="pagine_page_' + (_pagineNbPage) + '" class="btn btn-default pagine-num" data-num="' + (_pagineNbPage) + '">' + (_pagineNbPage) + '</span></li>';
        }
        html += '<li><span id="pagine-next" class="btn btn-default">»</span></li></ul>';
        //Insertion de l'html
        $(_pagineElement).html(html);
        //Ajout de la class active
        $('#pagine_page_' + newPage).addClass('active');
        //Mise ajour du pagine actuel
        _paginePageActuel = newPage;
    }
    //si on est sur la 1er on derniere page on disabled les bon btn
    if (_pagineNbPage == 1) {
        $('#pagine-prev').addClass('disabled');
        $('#pagine-next').addClass('disabled');
    } else if (newPage == 1) {
        $('#pagine-prev').addClass('disabled');
        $('#pagine-next').removeClass('disabled');
    } else if (newPage == _pagineNbPage) {
        $('#pagine-next').addClass('disabled');
        $('#pagine-prev').removeClass('disabled');
    } else {
        $('#pagine-prev').removeClass('disabled');
        $('#pagine-next').removeClass('disabled');
    }
    _pagineCallback(newPage);
}

function dialog(content = '') {
    content = '' + content;
    if (content.trim() != '') {
        $('#dialog').css('height', $(document).height() + 'px');
        $('#dialog_content').html(content);
        $('#dialog').removeClass('hide');
    } else {
        $('#dialog').addClass('hide');
        $('#dialog_content').html('');
}
}

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