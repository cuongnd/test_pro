Finish
<input type="hidden" name="currentStep" value="Finish">
<?php
$input=JFactory::getApplication()->input;
$session=JFactory::getSession();
$sub_domain=$session->get('sub_domain','');
$action=$input->getString('action','');
?>
<div class="row-fluid">
    Thanks for using our product
    <br/>
    you can login your website by this link below to edit your website
    <br/>
    <a href="http://admin.<?php echo $sub_domain ?>/">http://admin.<?php echo $sub_domain ?></a>
    <br/>
    or you can click this link below to view your website
    <br/>
    <a href="http://<?php echo $sub_domain ?>">http://<?php echo $sub_domain ?></a>

</div>
<input type="hidden" name="website" value="<?php echo $sub_domain ?>">
<script type="text/javascript">
    jQuery(document).ready(function($){
        $=jQuery;
        $('.setup button.back').remove();
        $('.setup button.cancel').remove();
        $('.autosetup').remove();
        $('.setup button.next').html('Finish');
        <?php if($action=='auto'){ ?>
            window.location.href = "index.php?option=com_website&view=website&action=<?php echo $action ?>";
        <?php } ?>
    });

</script>