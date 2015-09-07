
<?php
    defined('_JEXEC') or die('Restricted access');
    $config=AFactory::getConfig();?>
<ul class="facilities">
    <?php if(count($displayData))foreach ($displayData as $facility){?>
        <li><?php echo $facility->amenity ?></li>
        <?php }?>
</ul>
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
