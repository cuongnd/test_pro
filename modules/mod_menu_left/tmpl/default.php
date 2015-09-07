<?php
$doc=JFactory::getDocument();
$doc->addScript(JUri::root().'/modules/mod_menu_left/assets/js/mod_menu_left.js');
$firstItemList=reset($list);
?>
<script src="<?php echo  JUri::root().'/modules/mod_menu_left/assets/js/mod_menu_left.js' ?>"></script>
<table>
    <thead>
    <tr>
        <?php foreach($firstItemList as $key=>$value){ ?>
        <th><?php echo $key ?></th>
        <?php } ?>
    </tr>
    </thead>
    <tbody>
    <?php foreach($list as $item){ ?>
        <tr>
            <?php foreach($item as $key_item=>$valueItem){ ?>
            <td>
                <?php echo $valueItem ?>
            </td>
            <?php } ?>
        </tr>
    <?php } ?>
    </tbody>
</table>