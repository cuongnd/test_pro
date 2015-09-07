<div class="grid-stack "   >
    
    <?php

    foreach($listPositionsSetting as $positionItem)
    {
        if($positionItem->position=='component-position')
            continue;
        $position=$positionItem->position;
        $id = $positionItem->id;
        $gs_x = $positionItem->gs_x;
        $gs_y = $positionItem->gs_y;
        $width = $positionItem->width;
        $height = $positionItem->height;
        include JPATH_ROOT . '/components/com_utility/views/module/tmpl/default.php';


    }
    $position='component-position';
    $id=(int)$listPositionsSetting[$position]->id;
    $gs_x=(int)$listPositionsSetting[$position]->gs_x;
    $gs_y=(int)$listPositionsSetting[$position]->gs_y;
    $width=(int)$listPositionsSetting[$position]->width;
    $height=(int)$listPositionsSetting[$position]->height;
    ?>

    <div class="grid-stack-item"
         data-gs-x="<?php echo $gs_x ?>" data-gs-y="<?php echo $gs_y ?>" data-position="<?php echo $position ?>" data-position-id="<?php echo $id?$id:'0'  ?>"
         data-gs-width="<?php echo $width ?>" data-gs-height="<?php echo $height ?>">
        <div class="grid-stack-item-content"><jdoc:include type="component" /></div>
    </div>

</div>
