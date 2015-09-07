
<?php

    defined('_JEXEC') or die('Restricted access');
    $config=AFactory::getConfig();?>

    <?php if(count($displayData))foreach ($displayData as $facility){?>
        <label > <?php echo $facility['description'] ?></label>
        <?php }?>

<style type="text/css">
    .facilities
    {
        list-style: none;
        padding: 10px;
    }

    .facilities li span
    {
        padding-right: 10px;
    }

</style>
