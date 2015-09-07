<?php
/**
* @package		EasySocial
* @copyright	Copyright (C) 2010 - 2012 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasySocial is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );

/**
 * EmoticonHook
 *
 * Converts smiley faces into emoticon images.
 *
 * @author      Miles Johnson - http://milesj.me
 * @copyright   Copyright 2006-2011, Miles Johnson, Inc.
 * @license     http://opensource.org/licenses/mit-license.php - Licensed under The MIT License
 * @link        http://milesj.me/code/php/decoda
 */

class EmoticonHook extends DecodaHook {

	/**
	 * Configuration.
	 *
	 * @access protected
	 * @var array
	 */
	protected $_config = array(
		'path' => ''
	);

	/**
	 * Mapping of emoticons and smilies.
	 *
	 * @access protected
	 * @var array
	 */
	protected $_emoticons = array();

	/**
	 * Map of smilies to emoticons.
	 *
	 * @access protected
	 * @var array
	 */
	protected $_map = array();

	/**
	 * Load the emoticons from the JSON file.
	 *
	 * @access public
	 * @param array $config
	 */
	public function __construct(array $config = array()) {
		parent::__construct($config);

		$path = DECODA_CONFIG . 'emoticons.json';

		// Set the path to our own directory
		$this->_config[ 'path' ]	= '/media/com_easysocial/images/emoticons/';

		if (file_exists($path)) {
			$this->_emoticons = json_decode(file_get_contents($path), true);

			foreach ($this->_emoticons as $emoticon => $smilies) {
				foreach ($smilies as $smile) {
					$this->_map[$smile] = $emoticon;
				}
			}

			if (empty($this->_config['path'])) {
				$this->_config['path'] = str_replace(array(realpath($_SERVER['DOCUMENT_ROOT']), '\\', '/'), array('', '/', '/'), DECODA_EMOTICONS);
			}
		}
	}

	/**
	 * Parse out the emoticons and replace with images.
	 *
	 * @access public
	 * @param string $content
	 * @return string
	 */
	public function beforeParse($content) {
		if ($this->getParser()->getFilter('Image') && !empty($this->_emoticons)) {
			foreach ($this->_emoticons as $smilies) {
				foreach ($smilies as $smile) {
					$content = preg_replace_callback('/(\s)?' . preg_quote($smile, '/') . '(\s)?/is', array($this, '_emoticonCallback'), $content);
				}
			}
		}

		return $content;
	}

	/**
	 * Callback for smiley processing.
	 *
	 * @access protected
	 * @param array $matches
	 * @return string
	 */
	protected function _emoticonCallback($matches) {
		$smiley = trim($matches[0]);

		if( count( $matches ) === 1 && isset( $this->_map[ $smiley ] ) )
		{
			$image = $this->getParser()->getFilter('Image')->parse(array(
				'tag' => 'img',
				'attributes' => array()
			), $this->_config['path'] . $this->_map[$smiley] . '.png');

			return $image;
		}
		
		if (count($matches) === 1 || !isset($this->_map[$smiley])) {
			return $matches[0];
		}

		$l = isset($matches[1]) ? $matches[1] : '';
		$r = isset($matches[2]) ? $matches[2] : '';

		$image = $this->getParser()->getFilter('Image')->parse(array(
			'tag' => 'img',
			'attributes' => array()
		), $this->_config['path'] . $this->_map[$smiley] . '.png');

		return $l . $image . $r;
	}

}
