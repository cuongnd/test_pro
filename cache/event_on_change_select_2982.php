<?php
ob_start();
?>
<script type="text/javascript">
    $( 'button[name="save_tour_buid_price"]' ).trigger( "click" );
</script>
<?php
$content=ob_get_clean();
$content=JUtility::remove_string_javascript($content);
return $content;
?>