jQuery(document).ready(function ($) {
    var array_text = Array();
    i = 0;
    $('.e-change-lang').each(function () {
        text = $(this).text().trim();
        if (text != '')
        {
            $(this).attr('data-index-lang', i);
            array_text.push(text);
            i++;
        }
    });

    if (global_language_id != primaryLanguage)
    {
        changeLanguage(global_language_id);
    };


    function changeLanguage(language_id)
    {
        if (language_id == primaryLanguage)
        {
            i = 0;
            $('.e-change-lang').each(function () {
                $(this).html(array_text[i]);
                i++;
            });
            $('.btn-change-language').removeClass($('.btn-change-language').attr('data-class'));
            $('.btn-change-language').addClass('en-language');
            $('.btn-change-language').attr('data-class', 'en-language');
            $('.btn-change-language').html('English');
            $.ajax({
                type: "GET",
                url: 'index.php',
                data: (function () {
                    $data = {
                        option: 'com_utility',
                        task: 'utility.setSectionLanguage',
                        language_id: language_id
                    };
                    return $data;
                })(),
                beforeSend: function () {
                    $('.widget-change-language-loading').html('Loading...');
                    $('.widget-change-language-loading').css({
                        background: 'none repeat scroll 0 0 #fff'
                        , display: 'block'
                        , margin: '0 auto'
                        , position: 'fixed'
                        , 'text-align': 'center'
                        , 'z-index': 1000
                        , top: 0
                        , left: 0

                    });
                },
                success: function (result) {

                    $('.widget-change-language-loading').css({
                        display: "none"
                    });
                }
            });

            return;
        };
        $.ajax({
            type: "GET",
            url: 'index.php',
            data: (function () {
                $data = {
                    option: 'com_utility',
                    task: 'utility.switch_language',
                    language_id: language_id,
                    array_text: array_text
                };
                return $data;
            })(),
            beforeSend: function () {
                $('.widget-change-language-loading').html('Loading...');
                $('.widget-change-language-loading').css({
                    background: 'none repeat scroll 0 0 #fff'
                    , display: 'block'
                    , margin: '0 auto'
                    , position: 'fixed'
                    , 'text-align': 'center'
                    , 'z-index': 1000
                    , top: 0
                    , left: 0

                });
                // $('.loading').popup();
            },
            success: function (result) {
                result = $.parseJSON(result);
                tolang = result.tolang;
                translations = result.translations;

                $('.widget-change-language-loading').css({
                    display: "none"
                });
                if (translations.length)
                {
                    i = 0;
                    $('.e-change-lang').each(function () {
                        data_index_lang = $(this).attr('data-index-lang');
                        if (data_index_lang == i)
                        {
                            $(this).html(translations[i]);
                            i++;
                        }
                    });

                    $('.btn-change-language').removeClass($('.btn-change-language').attr('data-class'));
                    $('.btn-change-language').addClass(tolang.iso639code + '-language');
                    $('.btn-change-language').attr('data-class', tolang.iso639code + '-language');
                    $('.btn-change-language').html(tolang.title);
                }
            }
        });
    }
    $(document).on('click', '.div-change-language a.list-group-item', function () {

        $('.btn-change-language').popover('toggle');
        language_id = $(this).attr('data-lang');
        changeLanguage(language_id);

    });
});