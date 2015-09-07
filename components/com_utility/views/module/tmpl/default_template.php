<div class="grid-stack-item  show-grid-stack-item" data-block-id="<?php echo $positionItem->id ?>" data-position="<?php echo $position ?>" data-screensize="<?php echo $screensize ?>"
     data-position-id="<?php echo $id ? $id : '0' ?>"
     data-gs-x="<?php echo $gs_x ?>" data-gs-y="<?php echo $gs_y ?>"
     data-gs-width="<?php echo $width ?>" data-gs-height="<?php echo $height ?>">

    <div data-original-title="" class="grid-stack-item-content edit-style allow-edit-style">
        <div class="item-row">column</div>
        <span class="drag label label-default "><i class="glyphicon glyphicon-move "></i> drag</span>
        <a class="remove label label-danger remove-column" href="#close"><i class="glyphicon-remove glyphicon"></i> remove</a>
        <a class="add label label-danger add-row" href="#close"><i class="glyphicon glyphicon-plus"></i> add row</a>

        <div class="position-content ">
            <?php echo  $debugScreen?"position:$position id:$id gs_x:$gs_x gs_y:$gs_y width:$width height:$height":''; ?>
            <?php if($position){ ?>
            <jdoc:include type="modules" sreensize="<?php echo $screensize ?>" name="<?php echo $position ?>" style="none"/>
            <?php } ?>
        </div>
    </div>
</div>
