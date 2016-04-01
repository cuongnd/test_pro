<?php
$doc=JFactory::getDocument();
$doc->addStyleSheet(JUri::root().'/components/com_virtuemart/assets/css/view-makeseo.css');
?>
<div class="result">
    <div class="category">none</div>
    <div class="category_id">none</div>
    <div class="listKeyword">none</div>
</div>
<div class="widgetbookpro-loading"></div>
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        makeSeo();
       function makeSeo()
       {
           $.ajax({
               type: "GET",
               url: 'index.php',
               dataType: 'json',
               data: (function() {
                   $data = {
                       option: 'com_virtuemart',
                       controller: 'makeseo',
                       task: 'setMetaRobotByGoogleSearch'
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
                   $('.category').html(data[0].category);
                   $('.category_id').html(data[0].category_id);
                   $('.listKeyword').html(data[0].listKeyword);
                   makeSeo();
               }
           });


;
       }
    });
</script>