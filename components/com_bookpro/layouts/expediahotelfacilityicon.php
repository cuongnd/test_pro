<?php
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.tooltip');

$config = AFactory::getConfig(); ?>
<ul class="facilitiesicon">
    <?php foreach ($displayData as $facility) { ?>
                  <li>
            <span class="editlinktip hasTip" title="::<?php echo $facility->
    title ?>" >
                <img src="<?php echo JUri::root() . $facility->image ?>" border="0"/>
            </span>
        </li>
        <?php } ?>
</ul>
<style type="text/css">
    .facilitiesicon
    {
        list-style: none;
        padding: 10px;
        margin: 0px;
    }
    .facilitiesicon li span
    {
        padding-right: 10px;
    }
    .facilitiesicon li
    {
        float: left;
    }
</style>
