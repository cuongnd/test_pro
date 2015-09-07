/**
 * Created by cuongnd on 5/14/14.
 */
jQuery(document).ready(function ($) {
    //console.log($('#index h2').length);
    //34
    refreshIntervalId = setInterval(function () {

        $.ajax({
            type: "GET",
            url: 'index.php',
            cache: false,
            data: (function () {
                $data = {
                    option: 'com_virtuemart',
                    controller: 'category',
                    task: 'getproduct'

                }

                return $data;
            })(),
            beforeSend: function () {
                $('.widgetbookpro-loading').css({
                    display: "none",
                    position: "fixed",
                    "z-index": 1000,
                    top: 0,
                    left: 0,
                    height: "100%",
                    width: "100%"
                });
                // $('.loading').popup();
            },
            success: function ($result) {
                //$result=$.parseJSON($result);
                $('.listings-all').html($result);
                //getproduct($result.cat_id);
            }
        });
        //clearInterval(refreshIntervalId);
    }, 6000);
    function getproduct($cat_id) {
        $('.listing-summary > h3 > a').each(function () {
            $product_name = $(this).html().replace('&amp;', '');
            ;
            $product_link = $(this).attr('href');

            $.ajax({
                type: "GET",
                url: 'index.php',
                cache: false,
                data: (function () {
                    $data = {
                        option: 'com_virtuemart',
                        controller: 'category',
                        task: 'saveproduct',
                        product_name: $product_name,
                        category_id: $cat_id,
                        product_link: $product_link
                    }
                    console.log($data);
                    return $data;
                })(),
                beforeSend: function () {
                    $('.widgetbookpro-loading').css({
                        display: "none",
                        position: "fixed",
                        "z-index": 1000,
                        top: 0,
                        left: 0,
                        height: "100%",
                        width: "100%"
                    });
                    // $('.loading').popup();
                },
                success: function ($result) {

                    console.log($result);
                }
            });


        });
    }


});