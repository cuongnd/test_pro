<?php
defined('_JEXEC') or die('Restricted access');

JHtml::_('behavior.calendar');
JHtmlBehavior::modal('a.jbmodal');
JHtml::_('behavior.formvalidation');

$lessInput = JPATH_ROOT . '/administrator/components/com_bookpro/assets/less/view-tour-default.less';
$cssOutput = JPATH_ROOT . '/administrator/components/com_bookpro/assets/css/view-tour-default.css';
BookProHelper::compileLess($lessInput, $cssOutput);
$doc = JFactory::getDocument();
$doc->addStyleSheet(JUri::root() . '/administrator/components/com_bookpro/assets/css/view-tour-default.css');
JToolBarHelper::title(JText::_('Edit tour'));
//JToolBarHelper::save();
//JToolBarHelper::reset();
JToolBarHelper::apply();
//JToolBarHelper::cancel();
JHtml::_('jquery.framework');
JHtml::_('jquery.ui');
?>


<div class="view-tour-default">


    <form action="index.php" method="post" name="adminForm" id="adminForm" class="form-validate">


        <div class="row main-page-title">
            <div class="pull-right page-tile general-build">
                General Build
            </div>
        </div>
        <div class="main-subhead">

        </div>
        <div class="form-view-tour-default">


            <h4><i class="im-arrow-down" style="margin:0px 10px"></i><?php echo JText::_('COM_BOOKPRO_TOUR_BUILD_INFORMATION')?></h4>
            <div class="build-form build_tour_information">

                <?php echo $this->loadTemplate('build_tour_information') ?>
            </div>
            <h4><i class="im-arrow-down" style="margin:0px 10px"></i><?php echo JText::_('COM_BOOKPRO_TOUR_CHOOSE_ACTIVITIES')?></h4>
            <div class="build-form choose_tour_activities">

                <?php echo $this->loadTemplate('choose_tour_activities') ?>
            </div>
            <h4><i class="im-arrow-down" style="margin:0px 10px"></i><?php echo JText::_('COM_BOOKPRO_TOUR_CHOOSE_GROUP_SIZE_COST')?></h4>
            <div class="build-form choose_group_size_cost">

                <?php echo $this->loadTemplate('choose_group_size_cost') ?>
            </div>
            <h4><i class="im-arrow-down" style="margin:0px 10px"></i><?php echo JText::_('COM_BOOKPRO_TOUR_BUILD_ADDITION_INFORMATION')?></h4>
            <div class="build-form build_addition_information">

                <?php echo $this->loadTemplate('build_addition_information') ?>
            </div>

            <?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'general')); ?>
            <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'general', JText::_('General')); ?>


            <?php echo JHtml::_('bootstrap.endTab'); ?>
            <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'tabac', JText::_('COM_BOOKPRO_TOUR_ACTIVITIES')); ?>

            <div class="control-group">
                <label class="control-label" for="pickup"> <?php echo JText::_('COM_BOOKPRO_TOUR_ACTIVITY') ?>
                </label>

                <div class="controls">
                    <?php //echo $this->hotelfacility ?>
                </div>
            </div>

            <?php echo JHtml::_('bootstrap.endTab'); ?>
            <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'tab2', JText::_('COM_BOOKPRO_TOUR_GALLERY')); ?>
            <div class="form-horizontal">
                <!--
		<div class="control-group">
			<label class="control-label" for="pickup"> <?php echo JText::_('COM_BOOKPRO_TOUR_GALLERY') ?>
			</label>
			<div class="controls">
			<?php //AImporter::tpl('images', $this->_layout, 'images'); ?>
			</div>
		</div>
 -->
                <div class="control-group">
                    <label class="control-label" for="pickup"> <?php echo JText::_('COM_BOOKPRO_TOUR_MAPIMAGE') ?>
                    </label>

                    <div class="controls">
                        <?php AImporter::tpl('ajaximage', 'form', 'image', SITE_VIEWS); ?>
                        <?php
                        //$this->fieldname = 'mapimage';
                        //AImporter::tpl('images', $this->_layout, 'img');
                        ?>
                    </div>
                </div>

                <div class="control-group">
                    <label class="control-label" for="pickup"> <?php echo JText::_('COM_BOOKPRO_TOUR_FILES') ?>
                    </label>

                    <div class="controls">
                        <?php AImporter::tpl('ajaxfile', 'form', 'file', SITE_VIEWS); ?>
                        <?php //AImporter::tpl('files', $this->_layout, 'files');   ?>
                    </div>
                </div>
            </div>

            <?php echo JHtml::_('bootstrap.endTab'); ?>
            <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'tab3', JText::_('Detail')); ?>

            <div class="form-horizontal">

                <!--
        <div class="control-group">
        <label class="control-label" for="pickup"> <?php echo JText::_('COM_BOOKPRO_TOUR_START_TIME') ?>
        </label>
        <div class="controls">
        <input class="text_area" type="text" name="start_time"
        id="start_time" size="10" maxlength="255"
        value="<?php echo $this->obj->start_time; ?>" />(01:23)
        </div>
        </div>
        -->

                <div class="control-group">
                    <label class="control-label"
                           for="short_desc"> <?php echo JText::_('COM_BOOKPRO_TOUR_SHORT_DESCRIPTION') ?>
                    </label>

                    <div class="controls">
                        <?php
                        $editor = JFactory::getEditor();
                        echo $editor->display('short_desc', $this->obj->short_desc, '100%', '300', '50', '20', true);
                        ?>
                    </div>
                </div>

                <div class="control-group">
                    <label class="control-label" for="pickup"> <?php echo JText::_('COM_BOOKPRO_TOUR_DESCRIPTION') ?>
                    </label>

                    <div class="controls">
                        <?php
                        $editor = JFactory::getEditor();
                        echo $editor->display('description', $this->obj->description, '100%', '300', '50', '20', true);
                        ?>
                    </div>
                </div>

                <div class="control-group">
                    <label class="control-label" for="pickup"> <?php echo JText::_('COM_BOOKPRO_TOUR_CONDITION') ?>
                    </label>

                    <div class="controls">
                        <?php
                        $editor = JFactory::getEditor();
                        echo $editor->display('condition', $this->obj->condition, '100%', '300', '40', '20', true);
                        ?>
                    </div>
                </div>

                <div class="control-group">
                    <label class="control-label" for="pickup"> <?php echo JText::_('COM_BOOKPRO_TOUR_INCLUDE') ?>
                    </label>

                    <div class="controls">
                        <?php
                        $editor = &JFactory::getEditor();
                        echo $editor->display('include', $this->obj->include, '100%', '300', '30', '10', true);
                        ?>
                    </div>
                </div>

                <div class="control-group">
                    <label class="control-label" for="pickup"> <?php echo JText::_('COM_BOOKPRO_TOUR_EXCLUDE') ?>
                    </label>

                    <div class="controls">
                        <?php
                        $editor = &JFactory::getEditor();
                        echo $editor->display('exclude', $this->obj->exclude, '100%', '300', '30', '10', false);
                        ?>
                    </div>
                </div>
            </div>
            <?php echo JHtml::_('bootstrap.endTab'); ?>
            <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'tab_fee', JText::_('Payment')); ?>
            <div class="form-horizontal">
                <div class="control-group">
                    <label class="control-label"
                           for="deposit_amount"> <?php echo JText::_('COM_BOOKPRO_TOUR_AGENT_DISCOUNT') ?>
                    </label>

                    <div class="controls">
                        <input class="text_area" type="text" name="agent_discount"
                               id="agent_discount" size="10" maxlength="255"
                               value="<?php echo $this->obj->agent_discount; ?>"/>

                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="deposit"> <?php echo JText::_('COM_BOOKPRO_TOUR_DEPOSIT') ?>
                    </label>

                    <div class="form-inline">
                        <?php echo JHtmlSelect::booleanlist('deposit', '', $this->obj->deposit) ?>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label"
                           for="deposit_amount"> <?php echo JText::_('COM_BOOKPRO_TOUR_DEPOSIT_AMOUNT') ?>
                    </label>

                    <div class="controls">
                        <input class="text_area" type="text" name="deposit_amount"
                               id="deposit_amount" size="10" maxlength="255"
                               value="<?php echo $this->obj->deposit_amount; ?>"/>

                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label"
                           for="deposit_expired"> <?php echo JText::_('COM_BOOKPRO_TOUR_DEPOSIT_EXPIRED') ?>
                    </label>

                    <div class="controls">
                        <input class="text_area" type="text" name="deposit_expired"
                               id="deposit_expired" size="10" maxlength="255"
                               value="<?php echo $this->obj->deposit_expired; ?>"/>

                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="pickup"> <?php echo JText::_('COM_BOOKPRO_TOUR_CANCEL_DAY') ?>
                    </label>

                    <div class="controls">
                        <?php echo JHtmlSelect::integerlist(0, 60, 1, 'cancel_day', null, $this->obj->cancel_day) ?>
                    </div>
                </div>
            </div>

            <?php echo JHtml::_('bootstrap.endTab'); ?>

            <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'tab4', JText::_('Meta')); ?>

            <div class="form-horizontal">

                <div class="control-group">
                    <label class="control-label" for="pickup"> <?php echo JText::_('COM_BOOKPRO_TOUR_META') ?>
                    </label>

                    <div class="controls">
				<textarea class="text_area" name="metadesc" id="metadesc" rows='3'
                          cols='40'>
					<?php echo $this->obj->metadesc; ?>
                </textarea>
                    </div>
                </div>

                <div class="control-group">
                    <label class="control-label" for="pickup"> <?php echo JText::_('COM_BOOKPRO_TOUR_KEYWORD') ?>
                    </label>

                    <div class="controls">
				<textarea class="text_area" name="metakey" id="metakey" rows='2'
                          cols='40'>
					<?php echo $this->obj->metakey; ?>
                </textarea>
                    </div>
                </div>

            </div>

            <?php echo JHtml::_('bootstrap.endTab'); ?>
            <?php echo JHtml::_('bootstrap.endTabSet'); ?>

            <input type="hidden" name="option" value="<?php echo OPTION; ?>"/>
            <input type="hidden" name="controller" value="<?php echo CONTROLLER_TOUR; ?>"/>
            <input type="hidden" name="task" value="save"/>
            <input type="hidden" name="boxchecked" value="1"/>
            <input type="hidden" name="cid[]" value="<?php echo $this->obj->id; ?>" id="cid"/>

            <?php echo JHTML::_('form.token'); ?>
        </div>

    </form>
</div>
<script type="text/javascript">
    jQuery(document).ready(function () {
        CheckDaytrip();
        jQuery("input[name='daytrip']").click(function () {
            CheckDaytrip();
        });
    });

    function CheckDaytrip() {
        if (jQuery("#daytrip0").is(":checked")) {
            jQuery("#days_daytrip").hide();
            jQuery("#days").show();
            jQuery("#days_daytrip").attr('name', '');
            jQuery("#days").attr('name', 'days');
        }
        if (jQuery("#daytrip1").is(":checked")) {
            jQuery("#days_daytrip").show();
            jQuery("#days").hide();
            jQuery("#days_daytrip").attr('name', 'days');
            jQuery("#days").attr('name', '');
        }
    }

</script>


