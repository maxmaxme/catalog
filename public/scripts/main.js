var params = [];

$(function () {

    initParams();
    initAutoLoadMore();

});

function initParams() {

    var $paramsBlock = $('#params');

    params['page'] = 1;
    params['sorting'] = $paramsBlock.data('sorting');
    params['sorting_type'] = $paramsBlock.data('sorting_type');
}


function initAutoLoadMore() {

    var inProgress = false,
        $container = $('.container .goods');

    $(window).scroll(function() {

        if(!inProgress && $(window).scrollTop() + $(window).height() >= $(document).height() - 400) {

            inProgress = true;

            api('getGoods', {
                page: params['page'],
                sorting: params['sorting'],
                sorting_type: params['sorting_type']
            }, function (goods) {

                params['page']++;

                $.each(goods['items'], function (i, good) {

                    good['Price'] = getPrice(good['Price']);

                    $container.append(getTemplate('goods_item', good));
                });

                inProgress = false;

            }, function () {
                // ничего не найдено
            });

        }

    });
}