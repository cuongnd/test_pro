<div class="air-table" data-table-name="<?php echo $this->table->name ?>">
    <ul class="item-table">
        <li class="field span12 table-title">
            <span class="pull-left"><?php echo $this->table->name ?></span><i class="icon-cog config pull-right"></i>
        </li>
        <?php foreach($this->table->columns as $key=>$columnType){ ?>
            <li class="field"data-field="<?php echo $key ?>">
                <div class="key_type span3"><i class="icon-ok"></i></div>
                <div class="field_name span9"><?php echo $key ?>(<?php echo $columnType ?>)</div>
            </li>
        <?php } ?>


    </ul>
</div>