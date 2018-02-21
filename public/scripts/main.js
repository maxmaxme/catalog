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
        $container = $('.container .goods'),
        $loadingBlock = $('#loading');

    if ($container[0]) {
        $(window).scroll(function () {

            if (!inProgress && $(window).scrollTop() + $(window).height() >= $(document).height() - 400) {

                inProgress = true;

                $loadingBlock.show();

                api('getGoods', {
                    page: ++params['page'],
                    sorting: params['sorting'],
                    sorting_type: params['sorting_type']
                }, function (goods) {

                    $loadingBlock.hide();


                    $.each(goods['items'], function (i, good) {

                        good['Price'] = getPrice(good['Price']);

                        $container.append(getTemplate('goods_item', good));
                    });

                    if (goods['more']) {
                        inProgress = false;
                    }

                });

            }

        });
    }
}