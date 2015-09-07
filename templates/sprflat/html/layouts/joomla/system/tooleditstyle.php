<?php
$doc = JFactory::getDocument();
$doc->addScript(JUri::root() . '/templates/sprflat/js/jquery.tooleditstyle.js');
/**
 * Created by PhpStorm.
 * User: cuongnd
 * Date: 3/3/2015
 * Time: 10:30 PM
 */
?>
<div class="tool-edit-style panel panel-primary panel-teal  toggle  ">
    <div class="panel-heading">
        <h4 class="panel-title"><i class="en-tools"></i>Tool</h4>
    </div>
    <div class="panel-body">
        <ul class="list-icon-edit nav nav-pills nav-stacked show-arrows">
            <li><a href="javascript:void(0)" class="pointer"><i title="pointer" class="br-pointer"></i></a></li>
            <li>
                <a href="javascript:void(0)" class="pointer screen-size"><i title="pointer" class="en-screen"></i></a>
                <div class="list-screen">
                    <ul class="nav">
                    <?php foreach($listScreenSize as $item){ ?>
                        <li>
                            <a href="javascript:void(0)" class="pointer"><i title="pointer" screen-size="<?php echo $item ?>" class="en-screen <?php echo $item==$currentScreenSize?'selected':''; ?>"></i></a>
                        </li>
                    <?php } ?>
                    </ul>
                </div>
            </li>
            <li><a href="javascript:void(0)"><i class="st-type"></i></a></li>
            <li class="font-paragraph">
                <a href="javascript:void(0)"><i class="fa-font"></i></a>

                <div class="controller-font-paragraph tab-edit-tool hide">
                    <?php
                    // Start Tabs

                    echo JHtml::_('bootstrap.startTabSet', 'tab_group_font_paragraph', array('active' => 'tabs_font'));
                    // Tab 1
                    echo JHtml::_('bootstrap.addTab', 'tab_group_font_paragraph', 'tabs_font', 'Character');
                    echo <<<HTML
<div class="row">
    <div class="col-xs-6">font</div>
    <div class="col-xs-6">regular</div>
</div>
<div class="row">
    <div class="col-xs-6">font size</div>
    <div class="col-xs-6">leading</div>
</div>
<div class="row">
    <div class="col-xs-6"></div>
    <div class="col-xs-6"></div>
</div>
<div class="row">
    <div class="col-xs-6"></div>
    <div class="col-xs-6"></div>
</div>
<div class="row">
    <div class="col-xs-6"></div>
    <div class="col-xs-6"></div>
</div>
HTML;

                    echo JHtml::_('bootstrap.endTab');
                    // Tab 2
                    echo JHtml::_('bootstrap.addTab', 'tab_group_font_paragraph', 'paragraph', 'Paragraph');
                    echo <<<HTML
Paragraph
HTML;

                    echo JHtml::_('bootstrap.endTab');

                    echo JHtml::_('bootstrap.endTabSet');

                    ?>
                </div>
            </li>
            <li><a href="javascript:void(0)"><i class="im-paragraph-justify"></i></a></li>
            <li><a href="javascript:void(0)"><i class="im-paragraph-justify"></i></a></li>
            <li><a href="javascript:void(0)"><i class="im-paragraph-justify"></i></a></li>
            <li><a href="javascript:void(0)"><i class="im-paragraph-justify"></i></a></li>
            <li><a href="javascript:void(0)"><i class="im-paragraph-justify"></i></a></li>
            <li><a href="javascript:void(0)"><i class="im-paragraph-justify"></i></a></li>
        </ul>
    </div>
    <div class="panel-footer teal-bg"></div>
</div>