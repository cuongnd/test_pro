<?php
$doc=JFactory::getDocument();
$doc->addStyleSheet(JUri::root().'/components/com_virtuemart/assets/css/view-makeseo.css');
?>
<div class="result">
    <div class="tasking">none</div>
    <div class="category">none</div>
    <div class="category_id">none</div>
    <span>Before filter :<span class="beforeFilter"></span></span>
    <span>After filter :<span class="afterFilter"></span></span>

    <div>
        <span>da kiem tra  :<span class="dakiemtra"></span></span>
        <span>chua kiem tra :<span class="chuakiemtra"></span></span>
    </div>
    <div>listDownup<div class="listDownUp">none</div></div>
    <div class="listKeyword">none</div>

</div>
<div class="widgetbookpro-loading"></div>
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        virtuemart_category_id=0;
        subTask='getCategory';
        loctrunglap(virtuemart_category_id,subTask);
        function loctrunglap(virtuemart_category_id,subTask)
        {
            $.ajax({
                type: "GET",
                url: 'index.php',
                dataType: 'json',
                data: (function() {
                    $data = {
                        option: 'com_virtuemart',
                        controller: 'makeseo',
                        task: 'loctrunglaptrongtoandatabase',
                        virtuemart_category_id:virtuemart_category_id,
                        subTask:subTask
                    }

                    return $data;
                })(),
                beforeSend: function() {
                    $('.widgetbookpro-loading').css({
                        display: "block",
                        position: "fixed",
                        "z-index": 1000,
                        top: 0,
                        left: 0,
                        height: "100%",
                        width: "100%"
                    });
                },
                success: function(data) {
                    $('.widgetbookpro-loading').css({
                        display: "none"
                    });
                    $('.tasking').html(data.subTask);
                    $('.category').html(data.category.category_name);
                    $('.category_id').html(data.category.virtuemart_category_id);
                    $('.listKeyword').html(data.category.metakey);
                    $('.beforeFilter').html(data.beforeFilter);
                    $('.afterFilter').html(data.afterFilter);
                    if(data.listDownUp.length>1)
                    {
                        $('.listDownUp').html(data.listDownUp.join(', '));
                    }
                    $('.dakiemtra').html(data.dakiemtra);
                    $('.chuakiemtra').html(data.chuakiemtra);

                    loctrunglap(data.category.virtuemart_category_id,data.subTask);
                }
            });


            ;
        }
    });
</script>
<style>
    .listKeyword
    {
        text-align: justify;
    }
</style>