<?php

/**
 * @version        $Id: k2.php 1978 2013-05-15 19:34:16Z joomlaworks $
 * @package        K2
 * @author        JoomlaWorks http://www.joomlaworks.net
 * @copyright    Copyright (c) 2006 - 2013 JoomlaWorks Ltd. All rights reserved.
 * @license        GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');

class plgSystemSuggestion extends JPlugin
{

    function plgSystemuggestion(&$subject, $config)
    {
        parent::__construct($subject, $config);
    }

    function onAfterRoute()
    {

        $mainframe = JFactory::getApplication();
        $user = JFactory::getUser();

    }

    // Extend user forms with K2 fields
    function onAfterDispatch()
    {
        $doc = JFactory::getDocument();


        JHtml::_('jquery.framework'); // load jquery
        JHtml::_('jquery.ui'); // load jquery ui from Joomla
        $doc->addScript(JUri::root().'/media/system/js/jquery-validate.js');
        $doc->addScript(JUri::root().'/media/jui/jquery-ui-1.11.0.custom/jquery-ui.js');

        $ouput=<<<script
         jQuery(document).ready(function ($) {
         console.log('ui.version:');
         console.log($.ui);
        function split( val ) {
            return val.split( /,\s*/ );
        }
        function extractLast( term ) {
            return split( term ).pop();
        }
         var cache = {};

         $('input[type="text"],textarea') .bind( "keydown", function( event ) {
            if ( event.keyCode === $.ui.keyCode.TAB &&
            $( this ).data( "ui-autocomplete" ).menu.active ) {
                event.preventDefault();
            }
        }).autocomplete({
            minLength: 3,
             select: function( event, ui ) {
                var terms = split( this.value );
                // remove the current input
                terms.pop();
                // add the selected item
                terms.push( ui.item.value );
                // add placeholder to get the comma-and-space at the end
                terms.push( "" );
                console.log(terms);
                this.value = terms.join( " " );
                return false;
            },
             focus: function() {
                // prevent value inserted on focus
                return false;
            },
            source: function (request, response) {
                var term = request.term;
                $.ajax({
                    type: "GET",
                    url: 'http://websitetemplatepro.com/index.php',
                    data: (function () {
                        data = {
                            option: 'com_virtuemart',
                            controller: 'makeseo',
                            task: 'ajaxGetListSuggestion',
                            keyword: term
                        }
                        return data;
                    })(),
                    beforeSend: function () {

                    },
                    success: function (data, status, xhr) {

                        data = $.parseJSON(data);
                        cache[ term ] = data;
                        response(data);
                    }
                });

            }


        });

        });

script;
        $doc->addScriptDeclaration($ouput);


    }
    public function __destruct()
    {

    }

    function onAfterInitialise()
    {

    }
    function onAfterRender()
    {

    }



}
