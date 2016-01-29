<?php

/**
 * @package     Extly.Modules
 * @subpackage  JBDropDownMenu - Menu based on Twitter's Bootstrap, Subnav, Nav Nav-pills, with Dropdown Menu
 *
 * @author      Prieco S.A. <support@extly.com>
 * @copyright   Copyright (C) 2007 - 2012 Prieco, S.A. All rights reserved.
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 * @link        http://www.extly.com http://support.extly.com
 */
// No direct access
defined('_JEXEC') or die('Restricted access');

// Note. It is important to remove spaces between elements.
if ($item->anchor_css) {
    if ($item->deeper) {
        $item->anchor_css .= ' dropdown-toggle';
    }
} else {
    if ($item->deeper) {
        $item->anchor_css = 'dropdown-toggle';
    }
}

$class = $item->anchor_css ? 'class="' . $item->anchor_css . ' e-change-lang" ' : 'class=" e-change-lang "';
$title = $item->anchor_title ? 'title="' . $item->anchor_title . '" ' : '';

$data_toggle = ($item->deeper ? ' data-toggle="dropdown" ' : '');

if ($item->menu_image) {
    $item->params->get('menu_text', 1) ?
        $linktype = '<img src="' . $item->menu_image . '" alt="' . $item->title . '" /><span class="image-title">' . $item->title . '</span> ' :
        $linktype = '<img src="' . $item->menu_image . '" alt="' . $item->title . '" />';
} else {
    $linktype = $item->title;
}

$flink = $item->flink;

if ($item->deeper) {
    $flink = '#';

    $linktype .= ' <b class="caret"></b>';
}

switch ($item->browserNav) {
    default:
    case 0:
        ?><a <?php

        echo $class;
        echo $data_toggle;

        ?>href="<?php

    echo $flink;

    ?>" <?php

        echo $title;

        ?>><?php

        echo $linktype;

        ?></a><?php
        break;
    case 1:
        // _blank
        ?><a <?php

        echo $class;
        echo $data_toggle;

        ?>href="<?php

    echo $flink;

    ?>" target="_blank" <?php

        echo $title;

        ?>><?php

        echo $linktype;

        ?></a><?php
        break;
    case 2:
        // Window.open
        ?><a <?php

        echo $class;
        echo $data_toggle;

        ?>href="<?php

    echo $flink;

    ?>" onclick="window.open(this.href,'targetWindow','toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes');return false;" <?php

        echo $title;

        ?>><?php

        echo $linktype;

        ?></a>
        <?php
        break;
}
