<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_website
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JLoader::register('websiteHelper', JPATH_ADMINISTRATOR . '/components/com_website/helpers/website.php');

/**
 * Item Model for an website.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_website
 * @since       1.6
 */
class WebsiteModelWebsite extends JModelAdmin
{
	/**
	 * @var        string    The prefix to use with controller messages.
	 * @since   1.6
	 */
	protected $text_prefix = 'com_website';

	/**
	 * The type alias for this website type (for example, 'com_website.website').
	 *
	 * @var      string
	 * @since    3.2
	 */
	public $typeAlias = 'com_website.website';

	/**
	 * Batch copy items to a new category or current.
	 *
	 * @param   integer  $value     The new category.
	 * @param   array    $pks       An array of row IDs.
	 * @param   array    $contexts  An array of item contexts.
	 *
	 * @return  mixed  An array of new IDs on success, boolean false on failure.
	 *
	 * @since   11.1
	 */
    public  function createBasicInfoWebsite($domain='')
    {

        $user=JFactory::getUser();
        $tableWebsite=$this->getTable();
        $tableWebsite->id=0;
        $tableWebsite->title=$domain;
        $tableWebsite->alias=$domain;
        $tableWebsite->introtext=$domain;
        if(!$user->id)
        {
            throw new  Exception('website no created by, please login again');
        }
        $tableWebsite->created_by=$user->id;
        $tableWebsite->created=JFactory::getDate()->format('Y-m-d h:i:m');
        if(!$tableWebsite->store())
        {
            if ($error = $tableWebsite->getError())
            {
                // Fatal error
                $this->setError($error);

                return false;
            }
        }
        return $tableWebsite->id;


    }
    function getProgressBarSuccess($layout)
    {
        $steps=$this->getListStep();
        $currentKey=0;
        foreach($steps as $key=>$step)
        {

            if(strtolower($step)==strtolower($layout))
            {
                $currentKey=$key;
                break;
            }
        }
        $success=(100/count($steps)+1)*$currentKey;
        return $success;
    }
    function getListStep()
    {
        $steps=array();
        $steps[]='formBase';
        $steps[]='createBasicInfoWebsite';
        $steps[]='insertDomainToWebsite';
        $steps[]='createConfiguration';
        $steps[]='createGroupUser';
        $steps[]='createViewAccessLevels';
        $steps[]='createSupperAdmin';
        $steps[]='createComponents';
        $steps[]='createModules';
        $steps[]='createPlugins';
        $steps[]='createStyles';
        $steps[]='createMenus';
        $steps[]='changeParams';
        $steps[]='createContentCategory';
        $session=JFactory::getSession();
        $website_id=$session->get('website_id',0);
      /*  if($website_id) {
            $db = $this->getDbo();
            $query = $db->getQuery(true);
            $query->select('c.element AS element,"administrator/components" AS path');
            $query->from('#__components AS c')->where('c.website_id=' . (int)$website_id);
            $query1 = $db->getQuery(true);
            $query1->select('p.element AS element, CONCAT("plugins/",p.folder,"/",p.element) AS path');
            $query1->from('#__plugins AS p')->where('p.website_id=' . (int)$website_id);
            $query2 = $db->getQuery(true);
            $query2->select('m.module AS element, IF(m.client_id=0, "modules", "administrator/modules") AS path');
            $query2->from('#__modules AS m')->where('m.website_id=' . (int)$website_id);
            $query->union(array($query1, $query2));
            $db->setQuery($query);
            $listComModPlug=$db->loadObjectList();
            if(count($listComModPlug))
            {
                foreach($listComModPlug as $item)
                {
                    $fileSetupWebsite='setupwebsite.php';
                    $pathFileSetupWebsite=JPATH_ROOT.'/'.$item->path.'/'.$item->element.'/'.$fileSetupWebsite;
                    if(JFile::exists($pathFileSetupWebsite))
                    {
                        require_once $pathFileSetupWebsite;
                        $className='WebsiteSetup'.$item->element;
                        if(method_exists($className,'addStepSetupWebsite'))
                            call_user_func_array(array($className, 'addStepSetupWebsite'), array($steps));
                        else {
                            //nen lam pham ma loi de setup
                            $this->setError('Setup error:');
                        }
                    }
                }
            }
        }*/
        $steps[]='finish';
        return $steps;
    }
    public function CheckFormBase(&$layout)
    {
        return true;
    }
    public function CheckCreateBasicInfoWebsite(&$layout)
    {
        $session=JFactory::getSession();
        $sub_domain=$session->get('sub_domain','');
        if($sub_domain!='') {
            $layout = 'formbase';
        }
        else
        {
            $this->setError('there is no domain to setup');
            return false;
        }
        $website_id=$session->get('website_id','');
        if($website_id!=0)
            $layout='createbasicinfowebsite';

        return true;
    }
    function createStyles($website_id=0,$website_template_id=0)
    {
        if(!$website_template_id)
        {
            //copy from website template
            $website_template_id=websiteHelperFrontEnd::getOneTemplateWebsite();
        }
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->clear()
            ->select('*')
            ->from('#__extensions')
            ->where('website_id='.(int)$website_id)
        ;
        $list_extensions=$db->setQuery($query)->loadObjectList();
        $list_older_extension=array();
        foreach($list_extensions AS $extensions)
        {
            $list_older_extension[$extensions->copy_from]=$extensions->id;
        }
        $query->clear()
            ->select('template_styles.*')
            ->from('#__template_styles AS template_styles')
            ->leftJoin('#__extensions AS extensions ON extensions.id=template_styles.extension_id')
            ->where('extensions.website_id='.(int)$website_template_id)
        ;
        $list_modules=$db->setQuery($query)->loadObjectList();
        $table_template=JTable::getInstance('template');
        foreach($list_modules AS $template)
        {
            $table_template->bind((array)$template);
            $table_template->id=0;
            $table_template->copy_from=$template->id;
            if($extension_id=$list_older_extension[$template->extension_id])
            {
                $table_template->extension_id=$extension_id;
            }
            $ok=$table_template->store();
            if(!$ok){
                throw new Exception($table_template->getError());
            }
        }
        return true;

    }
    //TODO create user supper admin

    function createSupperAdmin($website_id=0,$website_template_id=0)
    {
        $session=JFactory::getSession();
        $email=$session->get('email','');
        if(!$website_template_id)
        {
            //copy from website template
            $website_template_id=websiteHelperFrontEnd::getOneTemplateWebsite();
        }
        require_once JPATH_ROOT.'/libraries/joomla/table/user.php';
        $tableUser=JTable::getInstance('User','JTable');
        $tableUser->id=0;
        $tableUser->email=$email;
        $tableUser->block=0;
        $tableUser->issystem=1;
        $tableUser->name='admin';
        $tableUser->username='admin';
        //$password=JUserHelper::genRandomPassword(8);
        $password='123456';
        $tableUser->password=md5($password);
        if(!$tableUser->store())
        {
            $this->setError($tableUser->getError());
        }
        require_once JPATH_ROOT.'/components/com_users/helpers/groups.php';
        $listGroup=GroupsHelper::get_group_id_by_website_id($website_id);
        $tableUser->groups = $listGroup;

        if(!$tableUser->store())
        {
            $this->setError($tableUser->getError());
            return false;
        }
        return true;

    }
    public function createViewAccessLevels($website_id=0,$website_template_id=0)
    {
        if(!$website_template_id)
        {
            //copy from website template
            $website_template_id=websiteHelperFrontEnd::getOneTemplateWebsite();
        }

        $db=$this->_db;
        $query=$db->getQuery(true)
            ->select('user_group_id_website_id.user_group_id')
            ->from('#__user_group_id_website_id AS user_group_id_website_id')
            ->where('website_id='.(int)$website_id)
        ;
        $root_id=$db->setQuery($query)->loadResult();

        $query = $db->getQuery(true);
        $query->select('usergroups.*')
            ->from('#__usergroups As usergroups ')
            ->order('usergroups.ordering');
        $db->setQuery($query);
        $list_rows = $db->loadObjectList('id');
        $children = array();
        $list_id=array();

// First pass - collect children
        foreach ($list_rows as $v) {
            $pt = $v->parent_id;
            $list = @$children[$pt] ? $children[$pt] : array();
            if ($v->id != $v->parent_id) {
                array_push($list, $v);
            }elseif ($root_id==$v->id&&$v->id == $v->parent_id) {
                $list_id[$v->copy_from]=$v->id;
            }
            $children[$pt] = $list;
        }

        function get_array_older_id($children,$root_id,&$list_id=array())
        {

            if (@$children[$root_id]) {
                foreach ($children[$root_id] as $v) {
                    $root_id = $v->id;
                    $list_id[$v->copy_from]= $v->id;
                    get_array_older_id($children,$root_id,$list_id);
                }
            }
        }
        $query->clear()
            ->select('*')
            ->from('#__viewlevels')
            ->where('website_id='.(int)$website_template_id)
        ;
        $list_viewlevels=$db->setQuery($query)->loadObjectList();
        $table_viewlevels=JTable::getInstance('viewlevel');
        foreach($list_viewlevels AS $viewlevels)
        {
            $table_viewlevels->bind((array)$viewlevels);
            $table_viewlevels->id=0;
            $table_viewlevels->copy_from=$viewlevels->id;
            $rules=$viewlevels->rules;
            $rules=json_decode($rules);
            foreach($rules AS $key=>$rule)
            {
                if($list_id[$rule])
                {
                    $rules[$key]=$list_id[$rule];
                }
            }
            $table_viewlevels->rules=json_encode($rules);
            $table_viewlevels->website_id=$website_id;
            $ok=$table_viewlevels->store();
            if(!$ok){
                throw new Exception($table_viewlevels->getError());
            }
        }
        return true;

    }
    public function CheckInsertDomainToWebsite(&$layout)
    {
        $session=JFactory::getSession();
        $website_id=$session->get('website_id',0);
        if($website_id)
        {
            $tableWebsite=$this->getTable();
            if($tableWebsite->load($website_id))
            {
                $layout=$this->getPrevLayoutByLayout('insertdomaintowebsite');
            }else
            {
                $this->setError($tableWebsite->getError());
                return false;
            }
        }

        return true;
    }
    public function CheckCreateConfiguration(&$layout)
    {
        $session=JFactory::getSession();
        $website_id=$session->get('website_id',0);
        if($website_id)
        {
            $tableWebsite=$this->getTable();
            if($tableWebsite->load($website_id))
            {
                $layout=$this->getPrevLayoutByLayout('createconfiguration');
            }else
            {
                $this->setError($tableWebsite->getError());
                return false;
            }
            //check file webstore

        }
        if(!JFile::exists(JPATH_ROOT.'/configuration/configuration_'.$website_id.'.php')) {
            //
            $this->setError("file configuration_.$website_id.php not exits");
            return false;
        }

        $listDomainWebsite=$this->getListDomainByWebsiteId($website_id);
        if(!count($listDomainWebsite))
        {
            $this->setError("table domain_website not have website:$website_id ");
            return false;
        }
        $existsFileWebstore=false;
        foreach ($listDomainWebsite as $domainWebsite) {
            $filePathConfiguration=JPATH_ROOT.'/webstore/'.$domainWebsite->domain.'.ini';
            if(JFile::exists($filePathConfiguration))
            {
                $existsFileWebstore=true;
            }
            else
            {


                $this->setError("file  {$domainWebsite->domain}.ini not exists");
            }
            if($existsFileWebstore)
                break;
        }
        if(!$existsFileWebstore)
        {
            $this->setError('file website store not exists');
            return false;
        }
        return true;
    }

    /**
     * @param int $website_id
     * @param int $website_template_id
     * @return bool
     * @throws Exception
     */
    public function createGroupUser($website_id=0, $website_template_id=0)
    {

        if(!$website_template_id)
        {
            //copy from website template
            $website_template_id=websiteHelperFrontEnd::getOneTemplateWebsite();
        }
        $db=$this->_db;
        $query=$db->getQuery(true)
            ->select('user_group_id_website_id.user_group_id')
            ->from('#__user_group_id_website_id AS user_group_id_website_id')
            ->where('website_id='.(int)$website_template_id)
            ;
        $old_parent_id=$db->setQuery($query)->loadResult();
        if(!$old_parent_id)
        {
            throw new Exception('there are no exists user group in website template');
        }
        $table_user_group=JTable::getInstance('usergroup','Jtable');
        $table_user_group->load($old_parent_id);
        $table_user_group->id=0;
        $table_user_group->copy_from=$old_parent_id;
        $ok=$table_user_group->parent_store();
        if(!$ok)
        {
            throw new Exception($table_user_group->getError());
        }
        $new_parent_id=$table_user_group->id;
        $table_user_group->parent_id=$new_parent_id;
        $ok=$table_user_group->parent_store();
        if(!$ok)
        {
            throw new Exception($table_user_group->getError());
        }
        $user_group_website=JTable::getInstance('usergroupwebsite');
        $user_group_website->user_group_id=$new_parent_id;
        $user_group_website->website_id=$website_id;
        $ok=$user_group_website->store();
        if(!$ok)
        {
            throw new Exception($user_group_website->getError());
        }

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('usergroups.*')
            ->from('#__usergroups As usergroups ')
            ->order('usergroups.ordering');
        $db->setQuery($query);
        $list_rows = $db->loadObjectList('id');
        $children = array();

// First pass - collect children
        foreach ($list_rows as $v) {
            $pt = $v->parent_id;
            $list = @$children[$pt] ? $children[$pt] : array();
            if ($v->id != $v->parent_id) {
                array_push($list, $v);
            }
            $children[$pt] = $list;
        }

        function execute_copy_rows_table($website_id, JTable $table_user_group, $old_parent_id = 0, $new_parent_id, $children)
        {
            if ($children[$old_parent_id]) {
                foreach ($children[$old_parent_id] as $v) {
                    $table_user_group->bind((array)$v);
                    $table_user_group->id = 0;
                    $table_user_group->copy_from = $v->id;
                    $table_user_group->parent_id = $new_parent_id;
                    $ok = $table_user_group->parent_store();
                    if (!$ok) {
                        throw new Exception($table_user_group->getError());
                    }
                    $new_parent_id = $table_user_group->id;
                    $old_parent_id = $v->id;
                    execute_copy_rows_table($website_id, $table_user_group,  $old_parent_id,$new_parent_id, $children);
                }
            }
        }

        execute_copy_rows_table($website_id,$table_user_group,$old_parent_id,$new_parent_id,$children);
        return true;
    }
    public  function getListDomainByWebsiteId($website_id){
        $db=$this->getDbo();
        $query=$db->getQuery(true);
        $query->select('*');
        $query->from('#__domain_website');
        $query->where('website_id='.(int)$website_id);
        $db->setQuery($query);
        return $db->loadObjectList();
    }
    public function CheckCreateGroupUser(&$layout)
    {
        $session=JFactory::getSession();
        $website_id=$session->get('website_id',0);
        if(!$website_id)
        {
            $this->setError('Can not found website');
        }
        require_once JPATH_ROOT.'/administrator/components/com_users/helpers/groups.php';
        $listRootUserGroup=GroupsHelper::getRootUserGroupByWebsiteId($website_id);
        if(!count($listRootUserGroup))
        {
            $layout=$this->getPrevLayoutByLayout('creategroupuser');
            $this->setError('there are no user group in website');
            return false;

        }
        return true;
    }

    public function CheckCreateSupperAdmin(&$layout)
    {
        $session=JFactory::getSession();
        $website_id=$session->get('website_id',0);
        if(!$website_id)
        {
            $this->setError('Can not found website');
        }

        require_once JPATH_ROOT.'/administrator/components/com_users/helpers/users.php';
        $listRootUserGroup=UsersHelper::getTotalUserByWebsiteId($website_id);
        if(!count($listRootUserGroup))
        {
            $layout=$this->getPrevLayoutByLayout('createsupperadmin');
            $this->setError('there are no user  in website');
            return false;

        }
        return true;
    }
    public function CheckCreateViewAccessLevels(&$layout)
    {
        $session=JFactory::getSession();
        $website_id=$session->get('website_id',0);
        if(!$website_id)
        {
            $this->setError('Can not found website');
        }
        require_once JPATH_ROOT.'/administrator/components/com_users/helpers/levels.php';
        $listUserLevel=levelsHelper::getListUserLevelByWebsiteId($website_id);
        if(!count($listUserLevel))
        {
            $layout=$this->getPrevLayoutByLayout('createviewaccesslevels');
            $this->setError('there are no user level in website');
            return false;

        }
        return true;
    }
    function CreateComponents($website_id=0,$website_template_id=0)
    {
        if(!$website_template_id)
        {
            //copy from website template
            $website_template_id=websiteHelperFrontEnd::getOneTemplateWebsite();
        }

        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->clear()
            ->select('*')
            ->from('#__extensions')
            ->where('website_id='.(int)$website_template_id)
        ;
        $list_extensions=$db->setQuery($query)->loadObjectList();
        $table_extension=JTable::getInstance('extension');
        $list_older_extension=array();
        foreach($list_extensions AS $extensions)
        {
            $table_extension->bind((array)$extensions);
            $table_extension->id=0;
            $table_extension->copy_from=$extensions->id;
            $table_extension->website_id=$website_id;
            $ok=$table_extension->store();
            if(!$ok){
                throw new Exception($table_extension->getError());
            }
            $list_older_extension[$extensions->id]=$table_extension->id;
        }
        $query->clear()
            ->select('*')
            ->from('#__components AS components')
            ->leftJoin('#__extensions AS extensions ON extensions.id=components.extension_id')
            ->where('extensions.website_id='.(int)$website_template_id)
        ;
        $list_components=$db->setQuery($query)->loadObjectList();
        $table_component=JTable::getInstance('component');
        foreach($list_components AS $component)
        {
            $table_component->bind((array)$component);
            $table_component->id=0;
            $table_component->copy_from=$component->id;
            if($extension_id=$list_older_extension[$component->extension_id])
            {
                $table_component->extension_id=$extension_id;
            }
            $ok=$table_component->store();
            if(!$ok){
                throw new Exception($table_component->getError());
            }
        }
        return true;
    }
    function createModules($website_id=0,$website_template_id=0)
    {
        if(!$website_template_id)
        {
            //copy from website template
            $website_template_id=websiteHelperFrontEnd::getOneTemplateWebsite();
        }
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->clear()
            ->select('*')
            ->from('#__extensions')
            ->where('website_id='.(int)$website_id)
        ;
        $list_extensions=$db->setQuery($query)->loadObjectList();
        $list_older_extension=array();
        foreach($list_extensions AS $extensions)
        {
            $list_older_extension[$extensions->copy_from]=$extensions->id;
        }
        $query->clear()
            ->select('*')
            ->from('#__modules AS modules')
            ->leftJoin('#__extensions AS extensions ON extensions.id=modules.extension_id')
            ->where('extensions.website_id='.(int)$website_template_id)
        ;
        $list_modules=$db->setQuery($query)->loadObjectList();
        $table_module=JTable::getInstance('module');
        foreach($list_modules AS $module)
        {
            $table_module->bind((array)$module);
            $table_module->id=0;
            $table_module->copy_from=$module->id;
            if($extension_id=$list_older_extension[$module->extension_id])
            {
                $table_module->extension_id=$extension_id;
            }
            $ok=$table_module->store();
            if(!$ok){
                throw new Exception($table_module->getError());
            }
        }
        return true;
    }
    public function createPlugins($website_id=0,$website_template_id=0)
    {
        if(!$website_template_id)
        {
            //copy from website template
            $website_template_id=websiteHelperFrontEnd::getOneTemplateWebsite();
        }
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->clear()
            ->select('*')
            ->from('#__extensions')
            ->where('website_id='.(int)$website_id)
        ;
        $list_extensions=$db->setQuery($query)->loadObjectList();
        $list_older_extension=array();
        foreach($list_extensions AS $extensions)
        {
            $list_older_extension[$extensions->copy_from]=$extensions->id;
        }
        $query->clear()
            ->select('*')
            ->from('#__plugins AS plugins')
            ->leftJoin('#__extensions AS extensions ON extensions.id=plugins.extension_id')
            ->where('extensions.website_id='.(int)$website_template_id)
        ;
        $list_plugins=$db->setQuery($query)->loadObjectList();
        $table_plugin=JTable::getInstance('plugin');
        foreach($list_plugins AS $plugins)
        {
            $table_plugin->bind((array)$plugins);
            $table_plugin->id=0;
            $table_plugin->copy_from=$plugins->id;
            if($extension_id=$list_older_extension[$plugins->extension_id])
            {
                $table_plugin->extension_id=$extension_id;
            }
            $ok=$table_plugin->store();
            if(!$ok){
                throw new Exception($table_plugin->getError());
            }
        }
        return true;
    }
    function createMenus($website_id=0,$website_template_id=0)
    {

        if(!$website_template_id)
        {
            //copy from website template
            $website_template_id=websiteHelperFrontEnd::getOneTemplateWebsite();
        }
        //copy menu type
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->clear()
            ->select('*')
            ->from('#__menu_types')
            ->where('website_id='.(int)$website_template_id)
        ;
        $list_older_menu_type=array();
        $list_menu_type=$db->setQuery($query)->loadObjectList();
        require_once JPATH_ROOT.'/libraries/legacy/table/menu/type.php';
        $table_menu_type=JTable::getInstance('menutype','JTable');
        foreach($list_menu_type AS $menu_type)
        {
            $table_menu_type->bind((array)$menu_type);
            $table_menu_type->id=0;
            $table_menu_type->copy_from=$menu_type->id;
            $table_menu_type->website_id=$website_id;
            $ok=$table_menu_type->store();
            if(!$ok){
                throw new Exception($table_menu_type->getError());
            }
            $list_older_menu_type[$menu_type->id]=$table_menu_type->id;
        }
        $query->clear()
            ->select('menu.*')
            ->from('#__menu AS menu')
            ->leftJoin('#__menu_type_id_menu_id AS menu_type_id_menu_id ON menu_type_id_menu_id.menu_id=menu.id')
            ->leftJoin('#__menu_types AS menu_types ON menu_types.id=menu_type_id_menu_id.menu_type_id')
            ->where('menu_types.website_id='.(int)$website_template_id)
        ;
        $list_root_menu=$db->setQuery($query)->loadObjectList('id');
        $table_menu=JTable::getInstance('menu');
        foreach($list_root_menu AS $menu)
        {
            $table_menu->bind((array)$menu);
            $table_menu->id=0;
            $table_menu->copy_from=$menu->id;
            $table_menu->menu_type_id=$list_older_menu_type[$menu->menu_type_id];
            $ok=$table_menu->store();
            if(!$ok){
                throw new Exception($table_menu->getError());
            }
            $list_older_menu_item[$menu->id]=$table_menu->id;
        }
        require_once JPATH_ROOT.'/components/com_menus/helpers/menus.php';
        foreach($list_older_menu_item AS $old_menu_item_id=>$new_menu_item_id)
        {
            $list_children_menu_item_id=MenusHelperFrontEnd::get_chilren_menu_item_id_by_menu_item_id($old_menu_item_id);
            $query->clear()
                ->select('*')
                ->from('#__menu AS menu')
                ->where('menu.id IN('.implode(',',$list_children_menu_item_id).')')
                ;
            $list_rows=$db->setQuery($query)->loadObjectList();
            $children = array();
            // First pass - collect children
            foreach ($list_rows as $v) {
                $pt = $v->parent_id;
                $list = @$children[$pt] ? $children[$pt] : array();
                if ($v->id != $v->parent_id || $v->parent_id!=null) {
                    array_push($list, $v);
                }
                $children[$pt] = $list;
            }
            if(!function_exists('sub_execute_copy_rows_table_menu')) {
                function sub_execute_copy_rows_table_menu(JTable $table_menu, $old_menu_item_id = 0, $new_menu_item_id, $children)
                {
                    if ($children[$old_menu_item_id]) {
                        foreach ($children[$old_menu_item_id] as $v) {
                            $table_menu->bind((array)$v);
                            $table_menu->id = 0;
                            $table_menu->copy_from = $v->id;
                            $table_menu->parent_id = $new_menu_item_id;
                            $table_menu->getDbo()->rebuild_action=1;
                            $ok = $table_menu->store();
                            if (!$ok) {
                                throw new Exception($table_menu->getError());
                            }
                            $new_menu_item_id = $table_menu->id;
                            $old_parent_id = $v->id;
                            sub_execute_copy_rows_table_menu($table_menu, $old_parent_id, $new_menu_item_id, $children);
                        }
                    }
                }
            }
        }
        return true;
    }
    public function CheckCreateComponents(&$layout)
    {
        $session=JFactory::getSession();
        $website_id=$session->get('website_id',0);
        if(!$website_id)
        {
            $this->setError('Can not found website');
        }
        require_once JPATH_ROOT.'/administrator/components/com_components/helpers/components.php';
        $listComponent=componentsHelper::getComponentByWebsiteId($website_id);
        if(!count($listComponent))
        {
            $layout=$this->getPrevLayoutByLayout('createcomponents');
            $this->setError('there are no components in website');
            return false;

        }
        return true;
    }
    public function CheckCreateModules(&$layout)
    {
        $session=JFactory::getSession();
        $website_id=$session->get('website_id',0);
        if(!$website_id)
        {
            $this->setError('Can not found website');
        }
        require_once JPATH_ROOT.'/administrator/components/com_website/helpers/modules.php';
        $listModule=ModulesHelper::getModulesByWebsiteId($website_id);
        if(!count($listModule))
        {
            $layout=$this->getPrevLayoutByLayout('createmodules');
            $this->setError('there are no module in website');
            return false;

        }
        return true;
    }
    public function CheckCreatePlugins(&$layout)
    {
        $session=JFactory::getSession();
        $website_id=$session->get('website_id',0);
        if(!$website_id)
        {
            $this->setError('Can not found website');
            return false;
        }
        require_once JPATH_ROOT.'/administrator/components/com_plugins/helpers/plugins.php';
        $listPlugin=PluginsHelper::getPluginsByWebsiteId($website_id);
        if(!count($listPlugin))
        {
            $layout=$this->getPrevLayoutByLayout('createplugins');
            $this->setError('there are no plugin in website');
            return false;

        }
        return true;
    }
    public function CheckCreateStyles(&$layout)
    {
        $session=JFactory::getSession();
        $website_id=$session->get('website_id',0);
        if(!$website_id)
        {
            $this->setError('Can not found website');
        }
        require_once JPATH_ROOT.'/administrator/components/com_templates/helpers/styles.php';
        $listStyle=StylesHelper::getStylesByWebsiteId($website_id);
        if(!count((array)$listStyle))
        {
            $layout=$this->getPrevLayoutByLayout('createstyles');
            $this->setError('there are no style in website');
            return false;

        }
        return true;
    }
    public function CheckCreateMenus(&$layout)
    {
        $session=JFactory::getSession();
        $website_id=$session->get('website_id',0);
        if(!$website_id)
        {
            $this->setError('Can not found website');
        }
        require_once JPATH_ROOT.'/administrator/components/com_menus/helpers/menus.php';
        $listMenuItem=MenusHelper::getTotalMenuItemByWebsiteId($website_id);
        if(!count((array)$listMenuItem))
        {
            $layout=$this->getPrevLayoutByLayout('createmenus');
            $this->setError('there are no menu item in website');
            return false;

        }
        $menuItemDefault=MenusHelper::getMenuItemDefault($website_id);
        if(!$menuItemDefault->id)
        {
            $layout=$this->getPrevLayoutByLayout('createmenus');
            $this->setError('there is no menu default in website');
            return false;

        }
        return true;
    }



    public function checkChangeParams(&$layout)
    {
        //$layout=$this->getPrevLayoutByLayout('changeparams');
        return true;
    }
    public function changeParams($website_id)
    {
        return true;
    }
    public function createContentCategory($website_id,$website_template_id=0)
    {
        if(!$website_template_id)
        {
            //copy from website template
            $website_template_id=websiteHelperFrontEnd::getOneTemplateWebsite();
        }
        require_once JPATH_ROOT.'/administrator/components/com_categories/helpers/categories.php';
        $rootId=CategoriesHelper::createRootCategory($website_id);
        if(!$rootId)
        {
            $this->setError('There is not root category');
            return false;
        }
        //copy category
        $listCategory=CategoriesHelper::getListCategoryByWebsiteId($website_template_id);
        if(!count($listCategory))
        {
            $this->setError('There is not category to copy');
            return false;
        }
        $pks=array();
        foreach($listCategory as $category)
        {
            $pks[]=$category->id;
        }
        JModelLegacy::addIncludePath(JPATH_ROOT.'/administrator/components/com_categories/models');
        $modelCategory=JModelLegacy::getInstance('Category','CategoriesModel');
        $state= $modelCategory->batchCopy($rootId,$pks);
        if(!$state)
        {
            $this->setError($modelCategory->getError());
            return false;

        }


        $listCategory=CategoriesHelper::getListCategoryByWebsiteId($website_id);

        $pks1=array();
        foreach($listCategory as $category)
        {
            $pks1[]=$category->id;
        }


        JModelLegacy::addIncludePath(JPATH_ROOT.'/administrator/components/com_content/models');
        $modelArticle=JModelLegacy::getInstance('Article','ContentModel');
        $state= $modelArticle->batchCopyAllArticleOfWebsiteToOtherWebsite($website_id);
        if(!$state)
        {
            $this->setError($modelArticle->getError());
            return false;

        }


        return true;
    }

    public function CheckCreateContentCategory(&$layout)
    {
        $session=JFactory::getSession();
        $website_id=$session->get('website_id',0);
        if(!$website_id)
        {
            $this->setError('Can not found website');
        }
        require_once JPATH_ROOT.'/administrator/components/com_categories/helpers/categories.php';
        $listCategory=CategoriesHelper::getListCategoryByWebsiteId($website_id);
        if(!count((array)$listCategory))
        {
            $layout=$this->getPrevLayoutByLayout('createcontentcategory');
            $this->setError('there are no category item in website');
            return false;

        }
        require_once JPATH_ROOT.'/administrator/components/com_content/helpers/articles.php';
        $listArticle=ArticlesHelper::getListArticleByWebsiteId($website_id);
        if(!count((array)$listArticle))
        {
            $layout=$this->getPrevLayoutByLayout('createcontentcategory');
            $this->setError('there are no article item in website');
            return false;

        }
        return true;
    }




    public function CheckFinish(&$layout)
    {

        $layout='finish';
        return true;
    }

    public  function getLayoutOfCurrentStep()
    {
        $listStep=$this->getListStep();
        $this->setError('Process running again');
        $layout='formbase';

        foreach($listStep as $step)
        {
            if(!strpos($step,':'))
            {
                $step='check'.$step;
                $ok= call_user_func_array(array($this, $step), array(&$layout));
                if(!$ok)
                {
                    break;
                }
            }
            else{
                //cau truc cho cac file setup
                //checkcreate:class:class
                $step=explode(':',$step);
                $objStep=json_decode($step[1]);
                require_once $objStep->fileName;
                $ok=call_user_func_array(array($objStep->className, $objStep->functiongetLayoutCurrentStep), array(&$layout));
                if(!$ok)
                    return $layout;
                //
            }
        }
        return $layout;
    }
    public function  getPrevLayoutByLayout($layout)
    {
        $steps=$this->getListStep();
        $currentKey=0;
        foreach($steps as $key=>$step)
        {

            if(strtolower($step)==strtolower($layout))
            {
                $currentKey=$key;
                break;
            }
        }
        return $steps[$currentKey-1];

    }
    public function createConfiguration($website_id=0,$website_template_id=0)
    {
        $db=$this->getDbo();
        $query=$db->getQuery(true);
        $query->select('*');
        $query->from('#__domain_website');
        $query->where('website_id='.(int)$website_id);
        $db->setQuery($query);
        $listDomainWebsite=$db->loadObjectList();
        if(!count($listDomainWebsite))
        {
            $this->setError('there are no domain point to this website');
            return false;
        }
        $templateConfigurationFilePath=JPATH_ROOT.'/configuration/';
        if(!$website_template_id)
        {
            //copy from website template
            $website_template_id=websiteHelperFrontEnd::getOneTemplateWebsite();
        }
        $templateConfigurationFile='configuration_'.$website_template_id.'.php';
        if(!JFile::exists($templateConfigurationFilePath.$templateConfigurationFile))
        {
            $this->setError("File template configuration not exists");
            return false;
        }
        $pathFolderWebStore=JPATH_ROOT.'/webstore/';
        foreach($listDomainWebsite as $domainWebsite)
        {
            //create file configuration
            $newFileConfiguration="configuration_{$domainWebsite->website_id}.php";
            if(!JFile::exists($templateConfigurationFilePath.$newFileConfiguration))
            {
                if(!JFile::copy($templateConfigurationFilePath.$templateConfigurationFile,$templateConfigurationFilePath.$newFileConfiguration))
                {
                    $this->setError("Cannot copy file configuration");
                }
            }
            $fileWebStore=$domainWebsite->domain.'.ini';
            if(!JFile::write($pathFolderWebStore.$fileWebStore,$domainWebsite->website_id))
            {
                $this->setError("can not create and write file webstore");
            }
        }

    }
    public function insertDomainToWebsite($domain='',$website_id=0)
    {
        $db=$this->getDbo();
        $query=$db->getQuery(true);
        $query->from('#__domain_website');
        $query->select('*');
        $query->where(
            array(
                'domain='.$query->q($domain),
                'website_id='.(int)$website_id
            )
        );
        $db->setQuery($query);
        $listDomainWebsite=$db->loadObjectList();
        if(count($listDomainWebsite))
        {
            $this->setError('Existed this domain in our system');
            return false;
        }
        $query->clear();
        //$query->insert('#__a')->columns('id, title')->values('1,2')->values('3,4');
        $query->insert('#__domain_website')->columns('domain,website_id')->values($query->q($domain).','.$website_id);
        $db->setQuery($query);
        if(!$db->execute())
        {
            $this->setError($db->getErrorMsg());
        }
        return true;
    }
    public  function  checkExistsDomain($domain='')
    {
        if(!$domain)
            return true;
        $domain=trim($domain);
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->from('#__domain_website AS dw');
        $query->select('COUNT(*)');
        $query->where('domain='.$query->q($domain));
        $db->setQuery($query);
        return $db->loadResult();

    }

    public function checkStateSetupWebsite($domain='')
    {
        if(!$domain)
            return false;
        if($domain!=''&&!$this->checkExistsDomain($domain))
            return false;
        $website=$this->getWebsiteByDomain($domain);

        if(!$website->id)
            return false;
        $components=$this->getComponentsByWebsiteId($website->id);

        if(!count($components))
            return false;
        foreach($components as $component)
        {
            $componentName=$component->element;
            $fileCheckSetupWebsite="setupwebsite.php";
            $pathFileCheckSetupWebsite=JPATH_ADMINISTRATOR.'/components/'.$component->element.'/'.$fileCheckSetupWebsite;
            if(file_exists($pathFileCheckSetupWebsite))
            {
                require_once $pathFileCheckSetupWebsite;
                $className="WebsiteSetup$componentName";
                $return=call_user_func_array(array($className, 'checkSetupWebsite'), array($website->id));
                if($return==false)
                    return false;
            }
        }
        $modules=$this->getModulesByWebsiteId($website->id);

        if(!count($modules))
            return false;
        foreach($modules as $module)
        {
            $moduleName=$module->module;
            $fileCheckSetupWebsite="setupwebsite.php";
            if($module->client_id)
                $pathFileCheckSetupWebsite=JPATH_ADMINISTRATOR.'/modules/'.$module->module.'/'.$fileCheckSetupWebsite;
            else
                $pathFileCheckSetupWebsite=JPATH_ROOT.'/modules/'.$module->module.'/'.$fileCheckSetupWebsite;
            if(file_exists($pathFileCheckSetupWebsite))
            {
                require_once $pathFileCheckSetupWebsite;
                $className="WebsiteSetup$moduleName";
                $return=call_user_func_array(array($className, 'checkSetupWebsite'), array($website->id));
                if($return==false)
                    return false;
            }
        }
        $plugins=$this->getPluginsByWebsiteId($website->id);
        if(!count($plugins))
            return false;
        foreach($plugins as $plugin)
        {
            $fileCheckSetupWebsite="setupwebsite.php";
            $pathFileCheckSetupWebsite=JPATH_ROOT.'/plugins/'.$plugin->folder.'/'.$plugin->element.'/'.$fileCheckSetupWebsite;
            if(file_exists($pathFileCheckSetupWebsite))
            {
                require_once $pathFileCheckSetupWebsite;
                $className="WebsiteSetup{$plugin->element}";
                $return=call_user_func_array(array($className, 'checkSetupWebsite'), array($website->id));
                if($return==false)
                    return false;
            }
        }
        return true;

    }
    public function sendEmailAlertSetupWebsite($email='')
    {
        jimport('joomla.mail.helper');
        if(!JMailHelper::isEmailAddress($email))
            return false;
        require_once JPATH_ROOT.'/components/com_website/controllers/website.php';
        $control=new WebsiteControllerWebsite();
        $view = $control->getView('website', 'html', 'WebsiteView');
        $view->setLayout('email_alert_setup_website');
        $view->parentDisPlay();
        $contents = ob_get_contents();
        ob_end_clean(); // get the callback function
        $config = JFactory::getConfig();
        $mailer = JFactory::getMailer();
        $sender = array(
            $config->mailfrom,
            $config->fromname);

        $mailer->setSender($sender);
        $mailer->addRecipient($email);
        $mailer->setSubject('Your subject string');
        $mailer->setBody($contents);
        $mailer->isHTML(true);
        $mailer->Encoding = 'base64';

        $send = $mailer->Send();
        if ( $send !== true ) {
            return false;
        } else {
            return true;
        }


    }
    public function  getComponentsByWebsiteId($website_id=0)
    {
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('c.*');
        $query->from('#__components AS c');
        $query->where('c.website_id='.(int)$website_id);
        $db->setQuery($query);
        $components=$db->loadObjectList();

        return $components;
    }
    public function  getModulesByWebsiteId($website_id=0)
    {
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('m.*');
        $query->from('#__modules AS m');
        $query->where('m.website_id='.(int)$website_id);
        $db->setQuery($query);
        return $db->loadObjectList();
    }
    public function  getPluginsByWebsiteId($website_id=0)
    {
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('p.*');
        $query->from('#__plugins AS p');
        $query->where('p.website_id='.(int)$website_id);
        $db->setQuery($query);
        return $db->loadObjectList();
    }
    public function getWebsiteByDomain($domain)
    {
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('website.*');
        $query->from('#__website AS website');
        $query->leftJoin('#__domain_website AS dw ON dw.website_id=website.id');
        $query->where('dw.domain='.$query->q($domain));
        $db->setQuery($query);
        $website=$db->loadObject();
        return $website;
    }
    public  function getWebsiteByUserId($user_id=0)
    {
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
    }
	protected function batchCopy($value, $pks, $contexts)
	{
		$categoryId = (int) $value;

		$i = 0;

		if (!parent::checkCategoryId($categoryId))
		{
			return false;
		}

		// Parent exists so we let's proceed
		while (!empty($pks))
		{
			// Pop the first ID off the stack
			$pk = array_shift($pks);

			$this->table->reset();

			// Check that the row actually exists
			if (!$this->table->load($pk))
			{
				if ($error = $this->table->getError())
				{
					// Fatal error
					$this->setError($error);

					return false;
				}
				else
				{
					// Not fatal error
					$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_BATCH_MOVE_ROW_NOT_FOUND', $pk));
					continue;
				}
			}

			// Alter the title & alias
			$data = $this->generateNewTitle($categoryId, $this->table->alias, $this->table->title);
			$this->table->title = $data['0'];
			$this->table->alias = $data['1'];

			// Reset the ID because we are making a copy
			$this->table->id = 0;

			// New category ID
			$this->table->catid = $categoryId;

			// TODO: Deal with ordering?
			//$table->ordering	= 1;

			// Get the featured state
			$featured = $this->table->featured;

			// Check the row.
			if (!$this->table->check())
			{
				$this->setError($this->table->getError());
				return false;
			}

			parent::createTagsHelper($this->tagsObserver, $this->type, $pk, $this->typeAlias, $this->table);

			// Store the row.
			if (!$this->table->store())
			{
				$this->setError($this->table->getError());
				return false;
			}

			// Get the new item ID
			$newId = $this->table->get('id');

			// Add the new ID to the array
			$newIds[$i] = $newId;
			$i++;

			// Check if the website was featured and update the #__website_frontpage table
			if ($featured == 1)
			{
				$db = $this->getDbo();
				$query = $db->getQuery(true)
					->insert($db->quoteName('#__website_frontpage'))
					->values($newId . ', 0');
				$db->setQuery($query);
				$db->execute();
			}
		}

		// Clean the cache
		$this->cleanCache();

		return $newIds;
	}

	/**
	 * Method to test whether a record can be deleted.
	 *
	 * @param   object    $record    A record object.
	 *
	 * @return  boolean  True if allowed to delete the record. Defaults to the permission set in the component.
	 * @since   1.6
	 */
	protected function canDelete($record)
	{
		if (!empty($record->id))
		{
			if ($record->state != -2)
			{
				return;
			}
			$user = JFactory::getUser();
			return $user->authorise('core.delete', 'com_website.website.' . (int) $record->id);
		}
	}

	/**
	 * Method to test whether a record can have its state edited.
	 *
	 * @param   object    $record    A record object.
	 *
	 * @return  boolean  True if allowed to change the state of the record. Defaults to the permission set in the component.
	 * @since   1.6
	 */
	protected function canEditState($record)
	{
		$user = JFactory::getUser();

		// Check for existing website.
		if (!empty($record->id))
		{
			return $user->authorise('core.edit.state', 'com_website.website.' . (int) $record->id);
		}
		// New website, so check against the category.
		elseif (!empty($record->catid))
		{
			return $user->authorise('core.edit.state', 'com_website.category.' . (int) $record->catid);
		}
		// Default to component settings if neither website nor category known.
		else
		{
			return parent::canEditState('com_website');
		}
	}

	/**
	 * Prepare and sanitise the table data prior to saving.
	 *
	 * @param   JTable    A JTable object.
	 *
	 * @return  void
	 * @since   1.6
	 */
	protected function prepareTable($table)
	{
		// Set the publish date to now
		$db = $this->getDbo();
		if ($table->state == 1 && (int) $table->publish_up == 0)
		{
			$table->publish_up = JFactory::getDate()->toSql();
		}

		if ($table->state == 1 && intval($table->publish_down) == 0)
		{
			$table->publish_down = $db->getNullDate();
		}

		// Increment the website version number.
		$table->version++;

		// Reorder the websites within the category so the new website is first
		if (empty($table->id))
		{
			$table->reorder('catid = ' . (int) $table->catid . ' AND state >= 0');
		}
	}

	/**
	 * Returns a Table object, always creating it.
	 *
	 * @param   type      The table type to instantiate
	 * @param   string    A prefix for the table class name. Optional.
	 * @param   array     Configuration array for model. Optional.
	 *
	 * @return  JTable    A database object
	 */
	public function getTable($type = 'website', $prefix = 'JTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}
    protected function populateState()
    {
        $app = JFactory::getApplication('site');

        // Load the User state.
        $pk = $app->input->getInt('id');
        $website=JFactory::getWebsite();
        $pk=$pk?$pk:$website->website_id;
        $this->setState('website.id', $pk);
        // Load the parameters.
        $params	= JComponentHelper::getParams('com_website');
        $this->setState('params', $params);
    }

    /**
	 * Method to get a single record.
	 *
	 * @param   integer    The id of the primary key.
	 *
	 * @return  mixed  Object on success, false on failure.
	 */
	public function getItem($pk = null)
	{
        $pk = (!empty($pk)) ? (int) $pk : (int) $this->state->get('website.id');
        if ($item = parent::getItem($pk))
		{
			// Convert the params field to an array.
			$registry = new JRegistry;
			$registry->loadString($item->attribs);
			$item->attribs = $registry->toArray();

			// Convert the metadata field to an array.
			$registry = new JRegistry;
			$registry->loadString($item->metadata);
			$item->metadata = $registry->toArray();

			// Convert the images field to an array.
			$registry = new JRegistry;
			$registry->loadString($item->images);
			$item->images = $registry->toArray();
            // Convert the style field to an array.
            $registry = new JRegistry;
            $registry->loadString($item->style);
            $item->style = $registry->toArray();
			// Convert the urls field to an array.
			$registry = new JRegistry;
			$registry->loadString($item->urls);
			$item->urls = $registry->toArray();

			$item->websitetext = trim($item->fulltext) != '' ? $item->introtext . "<hr id=\"system-readmore\" />" . $item->fulltext : $item->introtext;

			if (!empty($item->id))
			{
				$item->tags = new JHelperTags;
				$item->tags->getTagIds($item->id, 'com_website.website');
			}
		}
		// Load associated website items
		$app = JFactory::getApplication();
		$assoc = JLanguageAssociations::isEnabled();

		if ($assoc)
		{
			$item->associations = array();

			if ($item->id != null)
			{
				$associations = JLanguageAssociations::getAssociations('com_website', '#__website', 'com_website.item', $item->id);

				foreach ($associations as $tag => $association)
				{
					$item->associations[$tag] = $association->id;
				}
			}
		}

		return $item;
	}

	/**
	 * Method to get the record form.
	 *
	 * @param   array      $data        Data for the form.
	 * @param   boolean    $loadData    True if the form is to load its own data (default case), false if not.
	 *
	 * @return  mixed  A JForm object on success, false on failure
	 * @since   1.6
	 */
	public function getForm($data = array(), $loadData = true)
	{
        // The folder and element vars are passed when saving the form.
        if (empty($data))
        {

            $item		= $this->getItem();

            $clientId	= $item->client_id;
            $id			= $item->id;
        }
        else
        {
            $clientId	= JArrayHelper::getValue($data, 'client_id');
            $id			= JArrayHelper::getValue($data, 'id');
        }

        // These variables are used to add data from the plugin XML files.
        $this->setState('item.client_id', $clientId);

        // Get the form.
        $form = $this->loadForm('com_website.website', 'website', array('control' => 'jform', 'load_data' => $loadData));
        if (empty($form))
        {
            echo $this->getError();
            return false;
        }

        $form->setFieldAttribute('position', 'client', $this->getState('item.client_id') == 0 ? 'site' : 'administrator');

        $user = JFactory::getUser();

        // Check for existing module
        // Modify the form based on Edit State access controls.
        if ($id != 0 && (!$user->authorise('core.edit.state', 'com_website.module.'.(int) $id))
            || ($id == 0 && !$user->authorise('core.edit.state', 'com_website'))
        )
        {
            // Disable fields for display.
            $form->setFieldAttribute('ordering', 'disabled', 'true');
            $form->setFieldAttribute('published', 'disabled', 'true');
            $form->setFieldAttribute('publish_up', 'disabled', 'true');
            $form->setFieldAttribute('publish_down', 'disabled', 'true');

            // Disable fields while saving.
            // The controller has already verified this is a record you can edit.
            $form->setFieldAttribute('ordering', 'filter', 'unset');
            $form->setFieldAttribute('published', 'filter', 'unset');
            $form->setFieldAttribute('publish_up', 'filter', 'unset');
            $form->setFieldAttribute('publish_down', 'filter', 'unset');
        }

        return $form;
	}

    protected function loadForm($name, $source = null, $options = array(), $clear = false, $xpath = false)
    {
        // Handle the optional arguments.
        $options['control'] = JArrayHelper::getValue($options, 'control', false);

        // Create a signature hash.
        $hash = md5($source . serialize($options));

        // Check if we can use a previously loaded form.
        if (isset($this->_forms[$hash]) && !$clear)
        {
            return $this->_forms[$hash];
        }
        // Get the form.
        JForm::addFormPath(__DIR__ . '/forms');
        JForm::addFieldPath(__DIR__  . '/fields');

        try
        {
            $form = JForm::getInstance($name, $source, $options, false, $xpath);

            if (isset($options['load_data']) && $options['load_data'])
            {
                // Get the data for the form.
                $data = $this->loadFormData();
            }
            else
            {
                $data = array();
            }

            // Allow for additional modification of the form, and events to be triggered.
            // We pass the data because plugins may require it.

            $this->preprocessForm($form, $data);

            // Load the data into the form after the plugins have operated.
            $form->bind($data);

        }
        catch (Exception $e)
        {

            $this->setError($e->getMessage());
            return false;
        }

        // Store the form for later.
        $this->_forms[$hash] = $form;
        return $form;
    }

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return  mixed  The data for the form.
	 * @since   1.6
	 */
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$app = JFactory::getApplication();
		$data = $app->getUserState('com_website.edit.website.data', array());

		if (empty($data))
		{
			$data = $this->getItem();

			// Prime some default values.
			if ($this->getState('website.id') == 0)
			{
				$filters = (array) $app->getUserState('com_website.websites.filter');
				$filterCatId = isset($filters['category_id']) ? $filters['category_id'] : null;

				$data->set('catid', $app->input->getInt('catid', $filterCatId));
			}
		}

		$this->preprocessData('com_website.website', $data);

		return $data;
	}

	/**
	 * Method to save the form data.
	 *
	 * @param   array  The form data.
	 *
	 * @return  boolean  True on success.
	 * @since   1.6
	 */
	public function save($data)
	{
		$app = JFactory::getApplication();

		if (isset($data['images']) && is_array($data['images']))
		{
			$registry = new JRegistry;
			$registry->loadArray($data['images']);
			$data['images'] = (string) $registry;
		}

		if (isset($data['urls']) && is_array($data['urls']))
		{

			foreach ($data['urls'] as $i => $url)
			{
				if ($url != false && ($i == 'urla' || $i == 'urlb' || $i == 'urlc'))
				{
					$data['urls'][$i] = JStringPunycode::urlToPunycode($url);
				}

			}
			$registry = new JRegistry;
			$registry->loadArray($data['urls']);
			$data['urls'] = (string) $registry;
		}

		// Alter the title for save as copy
		if ($app->input->get('task') == 'save2copy')
		{
			list($title, $alias) = $this->generateNewTitle($data['catid'], $data['alias'], $data['title']);
			$data['title'] = $title;
			$data['alias'] = $alias;
			$data['state'] = 0;
		}

		if (parent::save($data))
		{

			if (isset($data['featured']))
			{
				$this->featured($this->getState($this->getName() . '.id'), $data['featured']);
			}

			$assoc = JLanguageAssociations::isEnabled();
			if ($assoc)
			{
				$id = (int) $this->getState($this->getName() . '.id');
				$item = $this->getItem($id);

				// Adding self to the association
				$associations = $data['associations'];

				foreach ($associations as $tag => $id)
				{
					if (empty($id))
					{
						unset($associations[$tag]);
					}
				}

				// Detecting all item menus
				$all_language = $item->language == '*';

				if ($all_language && !empty($associations))
				{
					JError::raiseNotice(403, JText::_('com_website_ERROR_ALL_LANGUAGE_ASSOCIATED'));
				}

				$associations[$item->language] = $item->id;

				// Deleting old association for these items
				$db = JFactory::getDbo();
				$query = $db->getQuery(true)
					->delete('#__associations')
					->where('context=' . $db->quote('com_website.item'))
					->where('id IN (' . implode(',', $associations) . ')');
				$db->setQuery($query);
				$db->execute();

				if ($error = $db->getErrorMsg())
				{
					$this->setError($error);
					return false;
				}

				if (!$all_language && count($associations))
				{
					// Adding new association for these items
					$key = md5(json_encode($associations));
					$query->clear()
						->insert('#__associations');

					foreach ($associations as $id)
					{
						$query->values($id . ',' . $db->quote('com_website.item') . ',' . $db->quote($key));
					}

					$db->setQuery($query);
					$db->execute();

					if ($error = $db->getErrorMsg())
					{
						$this->setError($error);
						return false;
					}
				}
			}

			return true;
		}

		return false;
	}

	/**
	 * Method to toggle the featured setting of websites.
	 *
	 * @param   array    The ids of the items to toggle.
	 * @param   integer  The value to toggle to.
	 *
	 * @return  boolean  True on success.
	 */
	public function featured($pks, $value = 0)
	{
		// Sanitize the ids.
		$pks = (array) $pks;
		JArrayHelper::toInteger($pks);

		if (empty($pks))
		{
			$this->setError(JText::_('com_website_NO_ITEM_SELECTED'));
			return false;
		}

		$table = $this->getTable('Featured', 'websiteTable');

		try
		{
			$db = $this->getDbo();
			$query = $db->getQuery(true)
						->update($db->quoteName('#__website'))
						->set('featured = ' . (int) $value)
						->where('id IN (' . implode(',', $pks) . ')');
			$db->setQuery($query);
			$db->execute();

			if ((int) $value == 0)
			{
				// Adjust the mapping table.
				// Clear the existing features settings.
				$query = $db->getQuery(true)
							->delete($db->quoteName('#__website_frontpage'))
							->where('website_id IN (' . implode(',', $pks) . ')');
				$db->setQuery($query);
				$db->execute();
			}
			else
			{
				// first, we find out which of our new featured websites are already featured.
				$query = $db->getQuery(true)
					->select('f.website_id')
					->from('#__website_frontpage AS f')
					->where('website_id IN (' . implode(',', $pks) . ')');
				//echo $query;
				$db->setQuery($query);

				$old_featured = $db->loadColumn();

				// we diff the arrays to get a list of the websites that are newly featured
				$new_featured = array_diff($pks, $old_featured);

				// Featuring.
				$tuples = array();
				foreach ($new_featured as $pk)
				{
					$tuples[] = $pk . ', 0';
				}
				if (count($tuples))
				{
					$db = $this->getDbo();
					$columns = array('website_id', 'ordering');
					$query = $db->getQuery(true)
						->insert($db->quoteName('#__website_frontpage'))
						->columns($db->quoteName($columns))
						->values($tuples);
					$db->setQuery($query);
					$db->execute();
				}
			}
		}
		catch (Exception $e)
		{
			$this->setError($e->getMessage());
			return false;
		}

		$table->reorder();

		$this->cleanCache();

		return true;
	}

	/**
	 * A protected method to get a set of ordering conditions.
	 *
	 * @param   object    A record object.
	 *
	 * @return  array  An array of conditions to add to add to ordering queries.
	 * @since   1.6
	 */
	protected function getReorderConditions($table)
	{
		$condition = array();
		$condition[] = 'catid = ' . (int) $table->catid;
		return $condition;
	}

	/**
	 * Auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @return  void
	 * @since    3.0
	 */
	protected function preprocessForm(JForm $form, $data, $group = 'website')
	{
		// Association website items
		$app = JFactory::getApplication();
		$assoc = JLanguageAssociations::isEnabled();
		if ($assoc)
		{
			$languages = JLanguageHelper::getLanguages('lang_code');

			// force to array (perhaps move to $this->loadFormData())
			$data = (array) $data;

			$addform = new SimpleXMLElement('<form />');
			$fields = $addform->addChild('fields');
			$fields->addAttribute('name', 'associations');
			$fieldset = $fields->addChild('fieldset');
			$fieldset->addAttribute('name', 'item_associations');
			$fieldset->addAttribute('description', 'com_website_ITEM_ASSOCIATIONS_FIELDSET_DESC');
			$add = false;
			foreach ($languages as $tag => $language)
			{
				if (empty($data['language']) || $tag != $data['language'])
				{
					$add = true;
					$field = $fieldset->addChild('field');
					$field->addAttribute('name', $tag);
					$field->addAttribute('type', 'modal_website');
					$field->addAttribute('language', $tag);
					$field->addAttribute('label', $language->title);
					$field->addAttribute('translate_label', 'false');
					$field->addAttribute('edit', 'true');
					$field->addAttribute('clear', 'true');
				}
			}
			if ($add)
			{
				$form->load($addform, false);
			}
		}

		parent::preprocessForm($form, $data, $group);
	}

	/**
	 * Custom clean the cache of com_website and website modules
	 *
	 * @since   1.6
	 */
	protected function cleanCache($group = null, $client_id = 0)
	{
		parent::cleanCache('com_website');
		parent::cleanCache('mod_websites_archive');
		parent::cleanCache('mod_websites_categories');
		parent::cleanCache('mod_websites_category');
		parent::cleanCache('mod_websites_latest');
		parent::cleanCache('mod_websites_news');
		parent::cleanCache('mod_websites_popular');
	}
}
