<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_product
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$doc = JFactory::getDocument();
/*JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');*/
JHtml::_('jquery.framework');
$app = JFactory::getApplication();
$user = JFactory::getUser();
$userId = $user->get('id');
$doc->addScript(JUri::root() . '/media/MarineObservationsDemo-master/webGraphicEditor/src/mustache.js');
$doc->addScript(JUri::root() . '/media/MarineObservationsDemo-master/webGraphicEditor/src/jszip-2.4.0-3.js');
$doc->addScript(JUri::root() . '/media/MarineObservationsDemo-master/webGraphicEditor/src/FileSaver.js');
$doc->addScript(JUri::root() . '/media/MarineObservationsDemo-master/webGraphicEditor/src/Blob.js');

$doc->addScript(JUri::root() . '/media/MarineObservationsDemo-master/dist/joint.js');
$doc->addScript(JUri::root() . '/media/MarineObservationsDemo-master/dist/joint.all.js');
$doc->addScript(JUri::root() . '/media/MarineObservationsDemo-master/webGraphicEditor/lib/keyboard.js');
$doc->addScript(JUri::root() . '/media/MarineObservationsDemo-master/webGraphicEditor/src/inspector.js');
$doc->addScript(JUri::root() . '/media/MarineObservationsDemo-master/webGraphicEditor/src/stencil.js');
$doc->addScript(JUri::root() . '/components/com_phpmyadmin/views/umldrawer/tmpl/assets/js/jquery.umldrawer.js');

$doc->addStyleSheet(JUri::root().'/media/MarineObservationsDemo-master/dist/joint.all.css');
$doc->addStyleSheet(JUri::root().'/media/MarineObservationsDemo-master/webGraphicEditor/css/layout.css');
$doc->addStyleSheet(JUri::root().'/media/MarineObservationsDemo-master/webGraphicEditor/css/paper.css');
$doc->addStyleSheet(JUri::root().'/media/MarineObservationsDemo-master/webGraphicEditor/css/inspector.css');
$doc->addStyleSheet(JUri::root().'/media/MarineObservationsDemo-master/webGraphicEditor/css/navigator.css');
$doc->addStyleSheet(JUri::root().'/media/MarineObservationsDemo-master/webGraphicEditor/css/stencil.css');
$doc->addStyleSheet(JUri::root().'/media/MarineObservationsDemo-master/webGraphicEditor/css/halo.css');
$doc->addStyleSheet(JUri::root().'/media/MarineObservationsDemo-master/webGraphicEditor/css/selection.css');
$doc->addStyleSheet(JUri::root().'/media/MarineObservationsDemo-master/webGraphicEditor/css/toolbar.css');
$doc->addStyleSheet(JUri::root().'/media/MarineObservationsDemo-master/webGraphicEditor/css/statusbar.css');
$doc->addStyleSheet(JUri::root().'/media/MarineObservationsDemo-master/webGraphicEditor/css/freetransform.css');
$doc->addStyleSheet(JUri::root().'/media/MarineObservationsDemo-master/webGraphicEditor/css/style.css');

//end get list style
$scriptId = "script_umldrawer";
ob_start();
?>
<script type="text/javascript">
    jQuery(document).ready(function ($) {


    });
</script>
<?php
$script = ob_get_clean();
$script = JUtility::remove_string_javascript($script);
$doc->addScriptDeclaration($script, "text/javascript", $scriptId);


?>
<div id="umldrawer">
    <h1> Marine Observations Demo </h1>

    <div class="toolbar-container">
        <button id="btn-open" class="btn" data - tooltip="Load a project from a valid json file"><span> Load</span>
        </button>
        <button id="btn-save" class="btn" data - tooltip="Save project in zip file"><span> Save</span></button>
        <button id="btn-exportJSON" class="btn" data - tooltip="Export graph as JSON"> export as JSON</button>
        <button id="btn-undo" class="btn" data - tooltip="Undo"><img src="<?php echo JUri::root() ?>/media/MarineObservationsDemo-master/webGraphicEditor/img/undo.png" alt="Undo"/></button>
        <button id="btn-redo" class="btn" data - tooltip="Redo"><img src="<?php echo JUri::root() ?>/media/MarineObservationsDemo-master/webGraphicEditor/img/redo.png" alt="Redo"/></button>
        <button id="btn-clear" class="btn" data - tooltip="Clear Paper"><img src="<?php echo JUri::root() ?>/media/MarineObservationsDemo-master/webGraphicEditor/img/clear.png" alt="Clear"/>
        </button>
        <button id="btn-svg" class="btn" data - tooltip="Open as SVG in a New Window"> export as SVG</button>
        <button id="btn-png" class="btn" data - tooltip="Open as PNG in a New Window"> export as PNG</button>
        <button id="btn-print" class="btn" data - tooltip="Open a Print Dialog"><img src="<?php echo JUri::root() ?>/media/MarineObservationsDemo-master/webGraphicEditor/img/print.png" alt="Print"/>
        </button>
        <button id="btn-zoom-in" class="btn" data - tooltip="Zoom In"><img src="<?php echo JUri::root() ?>/media/MarineObservationsDemo-master/webGraphicEditor/img/zoomin.png" alt="Zoom in"/>
        </button>
        <button id="btn-zoom-out" class="btn" data - tooltip="Zoom Out"><img src="<?php echo JUri::root() ?>/media/MarineObservationsDemo-master/webGraphicEditor/img/zoomout.png" alt="Zoom out"/>
        </button>
        <input id="fileName" type="text" value="MarineObservation-1"/>
        <input type="file" id="file" name="file" single/>

        <div class="panel">
            <span id="zoom-level"> 100</span>
            <span>%</span>
        </div>
        <button id="btn-zoom-to-fit" class="btn" data - tooltip="Zoom To Fit"><img src="<?php echo JUri::root() ?>/media/MarineObservationsDemo-master/webGraphicEditor/img/zoomtofit.png"
                                                                                   alt="Zoom To Fit"/></button>
        <button id="btn-fullscreen" class="btn" data - tooltip="Toggle Fullscreen Mode"><img src="<?php echo JUri::root() ?>/media/MarineObservationsDemo-master/webGraphicEditor/img/fullscreen.png"
                                                                                             alt="Fullscreen"/></button>
        <!--  <button id = "btn-to-front" class="btn" data - tooltip = "Bring Object to Front" > to front </button >
        <button id = "btn-to-back" class="btn" data - tooltip = "Send Object to Back" > to back </button > -->
        <button id="btn-layout" class="btn" data - tooltip="Auto-layout Graph"> layout</button>
        <label data - tooltip="Change Grid Size"> Grid size:</label>
        <input type="range" value="10" min="1" max="50" step="1" id="input-gridsize"/>
        <output id="output-gridsize"> 10</output>
        <label data - tooltip="Enable/Disable Snaplines"> Snaplines:</label>
        <input type="checkbox" id="snapline-switch" checked/>
    </div>
    <div class="stencil-container">
        <label> Palette</label>
        <button class="btn-expand" title="Expand all"> +</button>
        <button class="btn-collapse" title="Collapse all"> -</button>
    </div>
    <div class="paper-container"></div>
    <div class="inspector-container"></div>
    <div class="navigator-container"></div>
    <div class="statusbar-container">
        <div class="status"></div>
        <span class="rt-colab"></span>


    </div>
</div>


