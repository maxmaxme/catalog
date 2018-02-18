$(function () {

    initLoadMoreBtn();


});


function api(method, params, successCallback, errorCallback) {

    var ajaxTime= new Date().getTime();

    $.post('/API/' + method, params)
        .done(function(data) {

            var totalTime = new Date().getTime()-ajaxTime;

            $('#page_load_time').append('<br>' + totalTime + 'ms');

            if (data['success']) {
                if (successCallback)
                    successCallback(data['result']);
            } else {
                if (errorCallback)
                    errorCallback(data['error']);
                else
                    alert('Ошибка: ' + data['error']);
            }
        });

}



function initLoadMoreBtn() {
    $('.good_more_button').click(function () {

        var $btn = $(this),
            page = $btn.attr('data-next-page'),
            sorting = $btn.data('sorting'),
            sorting_type = $btn.data('sorting-type');

        if (!$btn.attr('disabled')) {

            $btn.attr('disabled', true);


            api('getGoods', {
                page: page,
                sorting: sorting,
                sorting_type: sorting_type
            }, function (goods) {

                $btn.attr('disabled', false);

                $.each(goods['items'], function (i, good) {
                    $btn.before(getTemplate('goods_item', good));
                });

                if (goods['more']) {
                    $btn.attr('data-next-page', ++page);
                } else {
                    $btn.remove();
                }

            });

        }


    });
}


function getTemplate(template, data) {
    return templates[template].replace(/{{([A-z0-9]+)}}/g, function(original, val) {
        return data[val] ? data[val] : '';
    })
}