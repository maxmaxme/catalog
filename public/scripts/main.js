$(function () {

    initLoadMoreBtn();


});


function api(method, params, successCallback, errorCallback) {

    $.post('/api.php?method=' + method, params)
        .done(function(data) {
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


        api('getGoods', {
            page: page,
            sorting: sorting,
            sorting_type: sorting_type
        }, function (goods) {
            $.each(goods['items'], function (i, good) {

                // todo переделать это на что-то адекватное
                $btn.before('<div class="good_item"><div class="photo"><img src="' + good['PhotoURL'] + '">\n' +
                    '</div><div class="name"><a href="/show.php?id=' + good['ID'] + '" target="_blank">' +
                    good['Name'] + '</a></div><div class="price">' + good['Price'] + '₽</div></div>');


            });

            if (goods['more']) {
                $btn.attr('data-next-page', ++page);
            } else {
                $btn.remove();
            }

        });


    });
}