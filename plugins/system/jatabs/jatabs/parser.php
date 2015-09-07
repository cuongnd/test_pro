<?php
/**
 * ------------------------------------------------------------------------
 * JA Tabs Plugin for J25 & J3.2
 * ------------------------------------------------------------------------
 * Copyright (C) 2004-2011 J.O.O.M Solutions Co., Ltd. All Rights Reserved.
 * @license - GNU/GPL, http://www.gnu.org/licenses/gpl.html
 * Author: J.O.O.M Solutions Co., Ltd
 * Websites: http://www.joomlart.com - http://www.joomlancers.com
 * ------------------------------------------------------------------------
 */
defined('_JEXEC') or die();
if (!class_exists('ReplaceCallbackParserTabs')) {
    if (!defined('_OPEN_TAG')) {
		define('_OPEN_TAG', 1);
	}
	if (!defined('_CLOSE_TAG')) {
		define('_CLOSE_TAG', 2);
	}
	if (!defined('_FULL_TAG')) {
		define('_FULL_TAG', 3);
	}
    /**
     *
     * Parser Class
     * @author JoomlArt
     *
     */
    class ReplaceCallbackParserTabs
    {
        var $_source = '';
        var $_tagname = '';
        var $_open = '{';
        var $_close = '}';
        var $_callback = '';


        /**
         * Constructor
         *
         * For php4 compatability we must not use the __constructor as a constructor for plugins
         * because func_get_args ( void ) returns a copy of all passed arguments, NOT references.
         * This causes problems with cross-referencing necessary for the observer design pattern.
         *
         * @param	string	$tagName The tag name
         * @param	string 	$tagAttr  The tag open
         * @param	string 	$tagClose  The tag close
         */
        function ReplaceCallbackParserTabs($tagName, $tagAttr = '{', $tagClose = '}')
        {
            $this->_tagname = $tagName;
            $this->_open = $tagAttr;
            $this->_close = $tagClose;
        }


        /**
         *
         * Parse string
         * @param string $strinput string input into parser
         * @param string $callback function callback name
         * @return string
         */
        function parse($strinput, $callback)
        {
            $this->_source = $strinput;
            $this->_callback = $callback;
            //Build delimiter
            $regex = '/(' . $this->_open . '[\/]?' . $this->_tagname . '[^}]*' . $this->_close . ')/';
            $arr = preg_split($regex, $this->_source, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);

            $empty = true;
            $matches = array();
            $tagAttr = '';
            $isOpened = false;
            $tagContent = '';
            $stroutput = '';
            $tagContent = '';
            foreach ($arr as $item) {
                $tagtype = $this->parseTag($item);
                if ($tagtype == _OPEN_TAG) {
                    if ($isOpened) {
                        $stroutput .= $this->callBack($tagAttr, $tagContent);
                        $isOpened = false;
                    }
                    $tagAttr = substr($item, strlen($this->_open) + strlen($this->_tagname), strlen($item) - strlen($this->_tagname) - strlen($this->_close) - strlen($this->_open));
                    $tagContent = '';
                    $isOpened = true;

                    continue;
                }
                if ($tagtype == _FULL_TAG) {
                    if ($isOpened) {
                        $stroutput .= $this->callBack($tagAttr, $tagContent);
                        $isOpened = false;
                    }
                    $tagAttr = substr($item, strlen($this->_open) + strlen($this->_tagname), strlen($item) - strlen($this->_close) - strlen($this->_tagname) - strlen($this->_open) - 1);
                    $tagContent = '';
                    $stroutput .= $this->callBack($tagAttr, $tagContent);
                    continue;
                }
                if ($tagtype == _CLOSE_TAG) {
                    $stroutput .= $this->callBack($tagAttr, $tagContent);
                    $isOpened = false;
                    continue;
                }

                if ($isOpened) {
                    $tagContent .= $item;
                } else {
                    $stroutput .= $item;
                }
            }
            if ($isOpened) {
                $stroutput .= $this->callBack($tagAttr, $tagContent);
                $isOpened = false;
            }

            return $stroutput;
        }


        /**
         *
         * Parse tag in string
         * @param string $tag
         * @return string
         */
        function parseTag($tag)
        {
            $arr = preg_split('/' . $this->_tagname . '/', $tag);
            if (count($arr) < 2)
                return 0;

            if ($arr[0] == $this->_open) {
                if (substr($arr[1], -(strlen($this->_close) + 1)) == '/' . $this->_close)
                    return _FULL_TAG;
                else
                    return _OPEN_TAG;
            }
            if ($arr[0] == $this->_open . '/')
                return _CLOSE_TAG;
            return 0;
        }


        /**
         *
         * Callback function
         * @param string $tagAttr
         * @param string $tagContent
         * @return string
         */
        function callBack($tagAttr, $tagContent)
        {
            if (is_array($this->_callback) && count($this->_callback) >= 2) {
                $callbackobj = $this->_callback[0];
                $callbackmethod = $this->_callback[1];
                return $callbackobj->$callbackmethod($tagAttr, $tagContent);
            } else {
                if (function_exists($this->_callback)) {
                    $callback = $this->_callback;
                    return $callback($tagAttr, $tagContent);
                }
            }
        }
    }
}