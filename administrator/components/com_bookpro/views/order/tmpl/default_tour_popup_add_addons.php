<!-- Modal -->
<div class="modal fade" id="popup-add_addons" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <div role="tabpanel">

                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#pre-night" aria-controls="home" role="tab" data-toggle="tab"><?php echo JText::_('PRE NIGHT'); ?></a></li>
                        <li role="presentation"><a href="#post-night" aria-controls="profile" role="tab" data-toggle="tab"><?php echo JText::_('POST NIGHT'); ?></a></li>
                        <li role="presentation"><a href="#pre-transfer" aria-controls="messages" role="tab" data-toggle="tab"><?php echo JText::_('PRE-TRANSFER'); ?></a></li>
                        <li role="presentation"><a href="#post-transfer" aria-controls="settings" role="tab" data-toggle="tab"><?php echo JText::_('POST TRANSFER'); ?></a></li>
                        <li role="presentation"><a href="#excursion" aria-controls="settings" role="tab" data-toggle="tab"><?php echo JText::_('EXCURSION'); ?></a></li>
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active add-addons" id="pre-night">
                            <?php
                            echo $this->loadTemplate('tour_popup_add_addons_tab_pre_transfer');
                            ?>
                        </div>
                        <div role="tabpanel" class="tab-pane add-addons" id="post-night">
                            <?php
                            echo $this->loadTemplate(strtolower('tour_popup_add_addons_tab_post_transfer'));
                            ?>
                        </div>
                        <div role="tabpanel" class="tab-pane add-addons" id="pre-transfer"><?php
                            echo $this->loadTemplate(strtolower('tour_popup_add_addons_tab_pre_night'));
                            ?></div>
                        <div role="tabpanel" class="tab-pane add-addons" id="post-transfer"><?php
                            echo $this->loadTemplate(strtolower('tour_popup_add_addons_tab_post_night'));
                            ?></div>
                        <div role="tabpanel" class="tab-pane add-addons" id="excursion"><?php
                            echo $this->loadTemplate(strtolower('tour_popup_add_addons_tab_excursion'));
                            ?></div>
                    </div>

                </div>

            </div>

        </div>
    </div>
</div>

