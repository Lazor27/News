$(document).ready(function () {

        // Сюда указываем id поля поиска
        $('.input-search').bind('input', function () {

            var input_search = $(".input-search").val();

            if (input_search.length >= 1 && input_search.length < 150) {

                $.ajax({
                    type: "POST",
                    url: "/requests/search_tags/", // Обработчик.
                    data: "search=" + input_search, // В переменной <strong>q</strong> отправляем ключевое слово в обработчик.
                    dataType: "html",
                    cache: false,
                    success: function (data) {

                        $(".block-search-result").show();
                        $(".list-search-result").html(data);
                    }
                })
            } else {
                $(".block-search-result").hide();
            }
        })
    })