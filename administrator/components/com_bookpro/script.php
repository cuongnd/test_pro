<?php
/**
 * @version $Id: script.php 10 2012-06-26 12:37:51Z quannv $
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
 

class com_bookproInstallerScript
{
	/**
	 * method to install the component
	 *
	 * @return void
	 */
	function install($parent) 
	{
		// $parent is the class calling this method
		$parent->getParent()->setRedirectURL('index.php?option=com_bookpro');
	}
 
	/**
	 * method to uninstall the component
	 *
	 * @return void
	 */
	function uninstall($parent) 
	{
		$db = JFactory::getDBO();
		$status = new stdClass;
		$status->modules = array();
		$status->plugins = array();
		$manifest = $parent->getParent()->manifest;
		$plugins = $manifest->xpath('plugins/plugin');
		foreach ($plugins as $plugin)
		{
			$name = (string)$plugin->attributes()->plugin;
			$group = (string)$plugin->attributes()->group;
			$query = "SELECT `extension_id` FROM #__extensions WHERE `type`='plugin' AND element = ".$db->Quote($name)." AND folder = ".$db->Quote($group);
			$db->setQuery($query);
			$extensions = $db->loadColumn();
			if (count($extensions))
			{
				foreach ($extensions as $id)
				{
					$installer = new JInstaller();
					$result = $installer->uninstall('plugin', $id);
				}
				$status->plugins[] = array('name' => $name, 'group' => $group, 'result' => $result);
			}

		}
	}
 
	/**
	 * method to update the component
	 *
	 * @return void
	 */
	function update($parent) 
	{
		// $parent is the class calling this method
		echo '<p>' . JText::_('Update successful') . '</p>';
	}
 
	/**
	 * method to run before an install/update/uninstall method
	 *
	 * @return void
	 */
	function preflight($type, $parent) 
	{
		if(in_array($type, array('install','discover_install'))) {
			$this->_bugfixDBFunctionReturnedNoError();
		} else {
			$this->_bugfixCantBuildAdminMenus();
		}
	}
 
	/**
	 * method to run after an install/update/uninstall method
	 *
	 * @return void
	 */
	function postflight($type, $parent) 
	{
		$db = JFactory::getDBO();
		$app = JFactory::getApplication('site');
		$status = new stdClass;
		$status->plugins = array();
		$src = $parent->getParent()->getPath('source');
		$manifest = $parent->getParent()->manifest;
		$plugins = $manifest->xpath('plugins/plugin');
		
		foreach ($plugins as $plugin)
		{
			$name = (string)$plugin->attributes()->plugin;
			$group = (string)$plugin->attributes()->group;
			$path = $src.'/plugins/'.$group;
			
			if (JFolder::exists($src.'/plugins/'.$group.'/'.$name))
			{
				$path = $src.'/plugins/'.$group.'/'.$name;
			}
			$installer = new JInstaller();
			$result = $installer->install($path);
			if($result){
				$query = "UPDATE #__extensions SET enabled=1 WHERE type='plugin' AND element=".$db->Quote($name)." AND folder=".$db->Quote($group);
				$db->setQuery($query);
				$db->query();
			}
			else {
				echo '<p>Can not install plugin, can install later manually</p>';
			}
			$status->plugins[] = array('name' => $name, 'group' => $group, 'result' => $result);
		}
		
		$this->installationResults($status);
		
		
		echo '<p>' . JText::_('COM_BOOKPRO_POSTFLIGHT_' . $type . '_TEXT') . '</p>';
	}
	
	private function _bugfixCantBuildAdminMenus()
	{
		$db = JFactory::getDbo();
	
		// If there are multiple #__extensions record, keep one of them
		$query = $db->getQuery(true);
		$query->select('extension_id')
		->from('#__extensions')
		->where($db->qn('element').' = '.$db->q($this->_extension_name));
		$db->setQuery($query);
		$ids = $db->loadColumn();
		if(count($ids) > 1) {
			asort($ids);
			$extension_id = array_shift($ids); // Keep the oldest id
	
			foreach($ids as $id) {
				$query = $db->getQuery(true);
				$query->delete('#__extensions')
				->where($db->qn('extension_id').' = '.$db->q($id));
				$db->setQuery($query);
				$db->query();
			}
		}
	
		// @todo
	
		// If there are multiple assets records, delete all except the oldest one
		$query = $db->getQuery(true);
		$query->select('id')
		->from('#__assets')
		->where($db->qn('name').' = '.$db->q($this->_extension_name));
		$db->setQuery($query);
		$ids = $db->loadObjectList();
		if(count($ids) > 1) {
			asort($ids);
			$asset_id = array_shift($ids); // Keep the oldest id
	
			foreach($ids as $id) {
				$query = $db->getQuery(true);
				$query->delete('#__assets')
				->where($db->qn('id').' = '.$db->q($id));
				$db->setQuery($query);
				$db->query();
			}
		}
	
		// Remove #__menu records for good measure!
		$query = $db->getQuery(true);
		$query->select('id')
		->from('#__menu')
		->where($db->qn('type').' = '.$db->q('component'))
		->where($db->qn('menutype').' = '.$db->q('main'))
		->where($db->qn('link').' LIKE '.$db->q('index.php?option='.$this->_extension_name));
		$db->setQuery($query);
		$ids1 = $db->loadColumn();
		if(empty($ids1)) $ids1 = array();
		$query = $db->getQuery(true);
		$query->select('id')
		->from('#__menu')
		->where($db->qn('type').' = '.$db->q('component'))
		->where($db->qn('menutype').' = '.$db->q('main'))
		->where($db->qn('link').' LIKE '.$db->q('index.php?option='.$this->_extension_name.'&%'));
		$db->setQuery($query);
		$ids2 = $db->loadColumn();
		if(empty($ids2)) $ids2 = array();
		$ids = array_merge($ids1, $ids2);
		if(!empty($ids)) foreach($ids as $id) {
			$query = $db->getQuery(true);
			$query->delete('#__menu')
			->where($db->qn('id').' = '.$db->q($id));
			$db->setQuery($query);
			$db->query();
		}
	}
	private function _bugfixDBFunctionReturnedNoError()
	{
		$db = JFactory::getDbo();
	
		// Fix broken #__assets records
		$query = $db->getQuery(true);
		$query->select('id')
		->from('#__assets')
		->where($db->qn('name').' = '.$db->q($this->_extension_name));
		$db->setQuery($query);
		$ids = $db->loadColumn();
		if(!empty($ids)) foreach($ids as $id) {
			$query = $db->getQuery(true);
			$query->delete('#__assets')
			->where($db->qn('id').' = '.$db->q($id));
			$db->setQuery($query);
			$db->query();
		}
	
		// Fix broken #__extensions records
		$query = $db->getQuery(true);
		$query->select('extension_id')
		->from('#__extensions')
		->where($db->qn('element').' = '.$db->q($this->_extension_name));
		$db->setQuery($query);
		$ids = $db->loadColumn();
		if(!empty($ids)) foreach($ids as $id) {
			$query = $db->getQuery(true);
			$query->delete('#__extensions')
			->where($db->qn('extension_id').' = '.$db->q($id));
			$db->setQuery($query);
			$db->query();
		}
	
		// Fix broken #__menu records
		$query = $db->getQuery(true);
		$query->select('id')
		->from('#__menu')
		->where($db->qn('type').' = '.$db->q('component'))
		->where($db->qn('menutype').' = '.$db->q('main'))
		->where($db->qn('link').' LIKE '.$db->q('index.php?option='.$this->_extension_name));
		$db->setQuery($query);
		$ids = $db->loadColumn();
		if(!empty($ids)) foreach($ids as $id) {
			$query = $db->getQuery(true);
			$query->delete('#__menu')
			->where($db->qn('id').' = '.$db->q($id));
			$db->setQuery($query);
			$db->query();
		}
	}
	private function installationResults($status)
	{
			?>
	               <table>
	                <?php if (count($status->plugins)): ?>
	                <tr>
	                    <th><?php echo JText::_('Plugin'); ?></th>
	                    <th><?php echo JText::_('Group'); ?></th>
	                    <th></th>
	                </tr>
	                <?php foreach ($status->plugins as $plugin): ?>
	                <tr class="row<?php echo(++$rows % 2); ?>">
	                    <td class="key"><?php echo ucfirst($plugin['name']); ?></td>
	                    <td class="key"><?php echo ucfirst($plugin['group']); ?></td>
	                    <td><strong><?php echo ($plugin['result'])?JText::_('Installed'):JText::_('Not Installed'); ?></strong></td>
	                </tr>
	                <?php endforeach; ?>
	                <?php endif; ?>
	        	</table>
				    <?php
				    }
}