$(function () {

    initLoadMoreBtn();
    initAutoLoadMore();

});


function initLoadMoreBtn() {
    $('.goods_more_button').click(function () {

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


function initAutoLoadMore() {
    window.onscroll = function() {
        if(window.pageYOffset > document.body.offsetHeight - 1000) {
            $('.goods_more_button').click();
        }
    }
}