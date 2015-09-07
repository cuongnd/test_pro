<?php
/**
* @version 2.0.0
* @package RSTickets! Pro 2.0.0
* @copyright (C) 2010 www.rsjoomla.com
* @license GPL, http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('_JEXEC') or die('Restricted access');

class BookProTableCrons extends JTable
{
	public $id = null;
	
	public $name = '';
	public $server = '';
	public $protocol = 'pop3';
	public $port = 110;
	public $security = '';
	public $validate = 0;
	public $username = '';
	public $password = '';
	public $type = 0;
	public $last_check = 0;
	public $check_interval = 5;
	public $accept = 2;
	public $department_id = 0;
	public $priority_id = 0;
	public $blacklist = null;
	public $published = 1;
	public $ordering = null;
	
	public function __construct(& $db) {
		parent::__construct('#__bookpro_email_accounts', 'id', $db);
	}
}