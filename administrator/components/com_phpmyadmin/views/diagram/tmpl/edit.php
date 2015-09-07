<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_product
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
$doc = JFactory::getDocument();
/*JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');*/
JHtml::_('jquery.framework');
$app = JFactory::getApplication();
$user = JFactory::getUser();
$userId = $user->get('id');

$js='
var tablesAndField='.json_encode($this->tablesAndField,true);

$doc->addScriptDeclaration($js);


/*
$doc->addScript(JUri::root() . '/media/jui/jquery-ui-1.11.0.custom/jquery-ui.js');
$doc->addScript(JUri::root() . '/media/jui/jquery-ui-1.11.1/ui/draggable.js');
$doc->addStyleSheet(JUri::root() . '/media/jui/jquery-ui-1.11.1/themes/base/all.css');
$doc->addScript(JUri::root() . '/media/system/js/fabric.js');*/
//$doc->addStyleSheet(JUri::root().'/media/jui/jquery-ui-1.11.0.custom/jquery-ui.css');

//$doc->addScript(JUri::root().'/media/system/js/jquery.svg.package-1.5.0/jquery.svg.js');
//$doc->addScript(JUri::root().'/media/system/js/jquery.connectingLine.js');


require_once JPATH_ROOT . '/administrator/components/com_bookpro/helpers/bookpro.php';

$lessInput = JPATH_ROOT . '/administrator/components/com_phpmyadmin/assets/less/view-diagram-default.less';
$cssOutput = JPATH_ROOT . '/administrator/components/com_phpmyadmin/assets/css/view-diagram-default.css';
BookProHelper::compileLess($lessInput, $cssOutput);
$doc->addStyleSheet(JUri::root() . '/administrator/components/com_phpmyadmin/assets/css/view-diagram-default.css');


$doc->addScript(JUri::root() . '/media/MarineObservationsDemo-master/webGraphicEditor/src/mustache.js');
$doc->addScript(JUri::root() . '/media/MarineObservationsDemo-master/webGraphicEditor/src/jszip-2.4.0-3.js');
$doc->addScript(JUri::root() . '/media/MarineObservationsDemo-master/webGraphicEditor/src/FileSaver.js');
$doc->addScript(JUri::root() . '/media/MarineObservationsDemo-master/webGraphicEditor/src/Blob.js');
$doc->addScript(JUri::root() . '/media/MarineObservationsDemo-master/dist/joint.js');
//$doc->addScript(JUri::root() . '/media/MarineObservationsDemo-master/dist/joint.all.js');

$doc->addScript(JUri::root() . '/media/MarineObservationsDemo-master/dist/joint.ui.paperScroller.js');
$doc->addScript(JUri::root() . '/media/MarineObservationsDemo-master/dist/joint.ui.snaplines.js');
$doc->addScript(JUri::root() . '/media/MarineObservationsDemo-master/dist/joint.shapes.devs.js');
$doc->addScript(JUri::root() . '/media/MarineObservationsDemo-master/dist/joint.ui.stencil.js');
$doc->addScript(JUri::root() . '/media/MarineObservationsDemo-master/dist/joint.shapes.fsa.js');
$doc->addScript(JUri::root() . '/media/MarineObservationsDemo-master/dist/joint.shapes.pn.js');
$doc->addScript(JUri::root() . '/media/MarineObservationsDemo-master/dist/joint.shapes.org.js');
$doc->addScript(JUri::root() . '/media/MarineObservationsDemo-master/plugins/layout/GridLayout/joint.layout.GridLayout.js');
$doc->addScript(JUri::root() . '/media/MarineObservationsDemo-master/dist/joint.ui.tooltip.js');
$doc->addScript(JUri::root() . '/media/MarineObservationsDemo-master/dist/joint.ui.selectionView.js');
$doc->addScript(JUri::root() . '/media/MarineObservationsDemo-master/dist/joint.ui.navigator.js');
$doc->addScript(JUri::root() . '/media/MarineObservationsDemo-master/plugins/ui/Clipboard/joint.ui.Clipboard.js');
$doc->addScript(JUri::root() . '/media/MarineObservationsDemo-master/dist/joint.dia.command.js');
$doc->addScript(JUri::root() . '/media/MarineObservationsDemo-master/dist/joint.ui.inspector.js');
$doc->addScript(JUri::root() . '/media/MarineObservationsDemo-master/dist/joint.ui.freeTransform.js');
$doc->addScript(JUri::root() . '/media/MarineObservationsDemo-master/dist/joint.ui.halo.js');
$doc->addScript(JUri::root() . '/media/MarineObservationsDemo-master/dist/joint.shapes.uml.js');
$doc->addScript(JUri::root() . '/media/MarineObservationsDemo-master/dist/joint.format.raster.js');
$doc->addScript(JUri::root() . '/media/MarineObservationsDemo-master/dist/joint.format.svg.js');
$doc->addScript(JUri::root() . '/media/MarineObservationsDemo-master/dist/joint.format.print.js');
$doc->addScript(JUri::root() . '/media/MarineObservationsDemo-master/dist/joint.shapes.bpmn.js');

$doc->addScript(JUri::root() . '/media/MarineObservationsDemo-master/dist/joint.shapes.logic.js');
$doc->addScript(JUri::root() . '/media/MarineObservationsDemo-master/dist/joint.shapes.IO.js');
$doc->addScript(JUri::root() . '/media/MarineObservationsDemo-master/dist/joint.shapes.erd.js');
$doc->addScript(JUri::root() . '/media/MarineObservationsDemo-master/dist/joint.shapes.chart.js');
$doc->addScript(JUri::root() . '/media/MarineObservationsDemo-master/dist/joint.ui.textEditor.js');

$doc->addScript(JUri::root() . '/media/MarineObservationsDemo-master/webGraphicEditor/lib/keyboard.js');
$doc->addScript(JUri::root() . '/media/MarineObservationsDemo-master/webGraphicEditor/src/inspector.js');
//$doc->addScript(JUri::root() . '/administrator/components/com_phpmyadmin/assets/js/view-diagram-default2.js');
//$doc->addScript(JUri::root() . '/media/MarineObservationsDemo-master/webGraphicEditor/src/main.js');
$doc->addScript(JUri::root() . '/media/MarineObservationsDemo-master/webGraphicEditor/lib/base64.js');

$doc->addScript(JUri::root() . '/administrator/components/com_phpmyadmin/assets/js/view-diagram-default.js');
$doc->addScript(JUri::root() . '/administrator/components/com_phpmyadmin/assets/js/jquery.windowscroll.js');


$doc->addStyleSheet(JUri::root() . '/media/MarineObservationsDemo-master/dist/joint.all.css');
$doc->addStyleSheet(JUri::root() . '/media/MarineObservationsDemo-master/webGraphicEditor/css/layout.css');
$doc->addStyleSheet(JUri::root() . '/media/MarineObservationsDemo-master/webGraphicEditor/css/paper.css');
$doc->addStyleSheet(JUri::root() . '/media/MarineObservationsDemo-master/webGraphicEditor/css/inspector.css');
$doc->addStyleSheet(JUri::root() . '/media/MarineObservationsDemo-master/webGraphicEditor/css/navigator.css');
$doc->addStyleSheet(JUri::root() . '/media/MarineObservationsDemo-master/webGraphicEditor/css/stencil.css');
$doc->addStyleSheet(JUri::root() . '/media/MarineObservationsDemo-master/webGraphicEditor/css/halo.css');
$doc->addStyleSheet(JUri::root() . '/media/MarineObservationsDemo-master/webGraphicEditor/css/selection.css');
$doc->addStyleSheet(JUri::root() . '/media/MarineObservationsDemo-master/webGraphicEditor/css/toolbar.css');
$doc->addStyleSheet(JUri::root() . '/media/MarineObservationsDemo-master/webGraphicEditor/css/statusbar.css');
$doc->addStyleSheet(JUri::root() . '/media/MarineObservationsDemo-master/webGraphicEditor/css/freetransform.css');
$doc->addStyleSheet(JUri::root() . '/media/MarineObservationsDemo-master/webGraphicEditor/css/style.css');

//$doc->addStyleSheet(JUri::root().'/media/system/js/jquery.svg.package-1.5.0/jquery.svg.css');
?>
<form action="<?php echo JRoute::_('index.php?option=com_phpmyadmin&view=diagram&id=' . (int)$this->item->id); ?>"
      method="post" name="adminForm" id="adminForm" class="form-validate">
    <div class="container-fluid container-main">


        <div class="container-fluid">
            <div class="form-horizontal">
                <div class="control-group">
                    <div class="control-label">
                        <?php echo $this->form->getLabel('title'); ?>
                    </div>
                    <div class="controls">
                        <?php echo $this->form->getInput('title'); ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="toolbar-container" style="display: none">
                <button id="btn-open" class="btn" data-tooltip="Load a project from a valid json file"><span>Load</span>
                </button>
                <button id="btn-save" class="btn" data-tooltip="Save project in zip file"><span>Save</span></button>
                <button id="btn-exportJSON" class="btn" data-tooltip="Export graph as JSON"> export as JSON</button>
                <button id="btn-undo" class="btn" data-tooltip="Undo"><img src="./img/undo.png" alt="Undo"/></button>
                <button id="btn-redo" class="btn" data-tooltip="Redo"><img src="./img/redo.png" alt="Redo"/></button>
                <button id="btn-clear" class="btn" data-tooltip="Clear Paper"><img src="./img/clear.png" alt="Clear"/>
                </button>
                <button id="btn-svg" class="btn" data-tooltip="Open as SVG in a New Window">export as SVG</button>
                <button id="btn-png" class="btn" data-tooltip="Open as PNG in a New Window">export as PNG</button>
                <button id="btn-print" class="btn" data-tooltip="Open a Print Dialog"><img src="./img/print.png"
                                                                                           alt="Print"/>
                </button>
                <button id="btn-zoom-in" class="btn" data-tooltip="Zoom In"><img src="./img/zoomin.png" alt="Zoom in"/>
                </button>
                <button id="btn-zoom-out" class="btn" data-tooltip="Zoom Out"><img src="./img/zoomout.png"
                                                                                   alt="Zoom out"/>
                </button>
                <input id="fileName" type="text" value="MarineObservation-1"/>
                <input type="file" id="file" name="file" single/>

                <div class="panel">
                    <span id="zoom-level">100</span>
                    <span>%</span>
                </div>
                <button id="btn-zoom-to-fit" class="btn" data-tooltip="Zoom To Fit"><img src="./img/zoomtofit.png"
                                                                                         alt="Zoom To Fit"/></button>
                <button id="btn-fullscreen" class="btn" data-tooltip="Toggle Fullscreen Mode"><img
                        src="./img/fullscreen.png"
                        alt="Fullscreen"/></button>
                <!--  <button id="btn-to-front" class="btn" data-tooltip="Bring Object to Front">to front</button>
                <button id="btn-to-back" class="btn" data-tooltip="Send Object to Back">to back</button>-->
                <button id="btn-layout" class="btn" data-tooltip="Auto-layout Graph">layout</button>
                <label data-tooltip="Change Grid Size">Grid size:</label>
                <input type="range" value="10" min="1" max="50" step="1" id="input-gridsize"/>
                <output id="output-gridsize">10</output>
                <label data-tooltip="Enable/Disable Snaplines">Snaplines:</label>
                <input type="checkbox" id="snapline-switch" checked/>
            </div>

        </div>
        <div class="container-fluid main-diagram-design">
            <div class="pull-left main-stencil">
                <div class="stencil-container">
                    <label>Palette</label>
                    <button class="btn-expand" title="Expand all">+</button>
                    <button class="btn-collapse" title="Collapse all">-</button>
                </div>
            </div>
            <div class="pull-left main-paper-container">
                <div class="row-fluid"><span class="icon-cog"></span><span></span></div>
                <div class="paper-container"></div>
            </div>
            <div class="pull-left main-inspector-navigator">
                <div class="inspector-container"></div>
                <div class="navigator-container"></div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="statusbar-container">
                <div class="status"></div>
                <span class="rt-colab"></span>


            </div>

        </div>



    </div>
    <?php echo $this->form->getInput('json'); ?>
    <?php echo $this->form->getInput('data_png_image'); ?>
    <input type="hidden" name="controller" value="diagram"/>
    <input type="hidden" name="task" value=""/>
    <input type="hidden" name="option" value="com_phpmyadmin"/>

    <?php echo JHtml::_('form.token'); ?>
</form>





