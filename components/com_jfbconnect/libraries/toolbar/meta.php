<?php
/**
 * @package         JFBConnect
 * @copyright (c)   2009-@CURRENT_YEAR@ by SourceCoast - All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @version         Release v@VERSION@
 * @build-date      @DATE@
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

class JFBConnectToolbarMeta
{
    var $displayName = "Social Meta Tags";
    var $systemName = "meta";

    public function getHtml()
    {
        $openGraphLibrary = OpenGraphLibrary::getInstance();
        $ogGroups = array("Inserted", "Skipped", "Removed");

        $html = '
            <div id="social-meta" style="position:fixed; bottom:45px; display:none;">';
        foreach ($ogGroups as $group)
        {
            $groupName = 'tags' . $group;
            $tags = $this->getOGTagList("Inserted", $openGraphLibrary->$groupName, true);
            $html .=
<<<EOT
                <h3>{$group}</h3>
                <div>
                    <p>
                        {$tags}
                    </p>
                </div>
EOT;
        }
        $html .= "</div>";
        return $html;
    }

    private function getOGTagList($type, $tags, $showValues)
    {
        $html = "None";
        if (count($tags))
        {
            $html = '<table class="table table-striped" width="90%">
            <thead><tr><th width="10%">' . $type . ' Tag</th>';
            if ($showValues)
                $html .= '<th width="70%">Value</th>';
            $html .= '<th width="20%">Origin</th></tr></thead><tbody>';

            foreach ($tags as $ogTag)
            {
                foreach ($ogTag->value as $ogTagValue)
                {
                    $html .= '<tr><td>' . $ogTag->name . '</td>';
                    if ($showValues)
                        $html .= '<td>' . $ogTagValue . '</td>';
                    $html .= '<td>' . $ogTag->origin . '</td></tr>';
                }
            }
            $html .= "</tbody></table>";
        }
        return $html;
    }

}