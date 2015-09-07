<div class="row-fluid vehicles">
    <div class="header row-fluid">
        <div class="warped-content">
            <div class="vehicles pull-left"><?php echo JText::_('Vehicles') ?>:</div>
            <div class="select-all-clear  pull-right"><span class="title"><?php echo JText::_('Vehicles') ?></span>:<span><a class="select-all-vehicle" href="javascript:void(0)"><?php echo JText::_('All') ?></a></span><span class="space">|</span><span><a class="select-clear-all-vehicle" href="javascript:void(0)"><?php echo JText::_('Clear') ?></a></span></div>
        </div>
    </div>
    <div class="row-fluid body">
        <div class="warped-content">
            <?php
            $app=JFactory::getApplication();
            $vehicles=$app->getUserState('bustrip_filter_vehicles');
            $vehicles=explode(',',$vehicles);
            ?>
            <?php for($i=0;$i<count($this->listVehicle);$i=$i+2){ ?>
                <div class="row-fluid">
                    <?php for($j=0;$j<2;$j++){ ?>
                        <?php
                        $item=$this->listVehicle[$i+$j];

                        ?>
                        <?php if($item){ ?>
                        <div class="span6 "><label class="checkbox-inline"><input <?php echo in_array($item->id,$vehicles)?'checked':'' ?> class="input-vehicle" value="<?php echo  $item->id?>" type="checkbox"> <?php echo $item->title ?></label></div>
                        <?php } ?>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
