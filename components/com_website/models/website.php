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
     * @param   integer $value The new category.
     * @param   array $pks An array of row IDs.
     * @param   array $contexts An array of item contexts.
     *
     * @return  mixed  An array of new IDs on success, boolean false on failure.
     *
     * @since   11.1
     */
    public function createBasicInfoWebsite($domain = '')
    {
        $website=JFactory::getWebsite();
        $website_template_id = websiteHelperFrontEnd::getOneTemplateWebsite();
        $app=JFactory::getApplication();
        $user = JFactory::getUser();
        $session_website = JModelLegacy::getInstance('session_website');
        $session_website->load();
        $table_website = $this->getTable();
        $table_website->id = 0;
        $table_website->title = $domain;
        $table_website->name = $session_website->sub_domain;
        $table_website->alias = $domain;
        $table_website->created_by_website_id = $website->website_id;
        $table_website->introtext = $domain;
        if (!$user->id) {
            $this->setError('please login again<a href="index.php?option=com_users&view=login">(click here)</a>');
            return false;
        }
        $table_website->created_by = $user->id;
        $table_website->copy_from = $website_template_id;
        $table_website->created = JFactory::getDate()->format('Y-m-d h:i:m');
        $ok=$table_website->check();
        if(!$ok)
        {
            $this->setError($table_website->getError());

            return false;
        }
        $ok=$table_website->store();
        if (!$ok) {
            $this->setError($table_website->getError());

            return false;
        }
        return $table_website->id;


    }

    function getProgressBarSuccess($layout)
    {
        $steps = $this->getListStep();
        $currentKey = 0;
        foreach ($steps as $key => $step) {

            if (strtolower($step) == strtolower($layout)) {
                $currentKey = $key;
                break;
            }
        }
        $success = (100 / count($steps) + 1) * $currentKey;
        return $success;
    }

    function getListStep()
    {
        $steps = array();
        $steps[] = 'formBase';
        $steps[] = 'createBasicInfoWebsite';
        $steps[] = 'insertDomainToWebsite';
        $steps[] = 'createConfiguration';
        $steps[] = 'createGroupUser';
        $steps[] = 'createViewAccessLevels';
        $steps[] = 'createSupperAdmin';
        $steps[] = 'createComponents';
        $steps[] = 'createModules';
        $steps[] = 'createPlugins';
        $steps[] = 'createStyles';
        $steps[] = 'createMenus';
        $steps[] = 'createControl';
        $steps[] = 'changeParams';
        $steps[] = 'createContentCategory';
        $session = JFactory::getSession();
        $website_id = $session->get('website_id', 0);
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
        $steps[] = 'finish';
        return $steps;
    }

    public function CheckcreateControl(&$layout){
        $session = JFactory::getSession();
        $website_id = $session->get('website_id', 0);
        if (!$website_id) {
            $this->setError('Can not found website');
        }
        require_once JPATH_ROOT . '/components/com_templates/helpers/styles.php';
        $listStyle = StylesHelper::getStylesByWebsiteId($website_id);
        if (!count((array)$listStyle)) {
            $layout = $this->getPrevLayoutByLayout('createcontrol');
            $this->setError('there are no style in website');
            return false;

        }

        return true;
    }
    public function CheckFormBase(&$layout)
    {
        return true;
    }

    public function CheckCreateBasicInfoWebsite(&$layout)
    {
        $session = JFactory::getSession();
        $sub_domain = $session->get('sub_domain', '');
        if ($sub_domain != '') {
            $layout = 'formbase';
        } else {
            $this->setError('there is no domain to setup');
            return false;
        }
        $website_id = $session->get('website_id', '');
        if ($website_id != 0)
            $layout = 'createbasicinfowebsite';

        return true;
    }

    function createStyles($website_id = 0, $website_template_id = 0)
    {
        if (!$website_template_id) {
            //copy from website template
            $website_template_id = websiteHelperFrontEnd::getOneTemplateWebsite();
        }
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->clear()
            ->select('*')
            ->from('#__extensions')
            ->where('website_id=' . (int)$website_id);
        $list_extensions = $db->setQuery($query)->loadObjectList();
        $list_older_extension = array();
        foreach ($list_extensions AS $extensions) {
            $list_older_extension[$extensions->copy_from] = $extensions->id;
        }
        $query->clear()
            ->select('template_styles.*')
            ->from('#__template_styles AS template_styles')
            ->leftJoin('#__extensions AS extensions ON extensions.id=template_styles.extension_id')
            ->where('extensions.website_id=' . (int)$website_template_id);
        $list_template = $db->setQuery($query)->loadObjectList();
        $table_template = JTable::getInstance('template');
        foreach ($list_template AS $template) {
            $table_template->bind((array)$template);
            $table_template->id = 0;
            $table_template->copy_from = $template->id;
            if ($extension_id = $list_older_extension[$template->extension_id]) {
                $table_template->extension_id = $extension_id;
            }
            $ok = $table_template->store();
            if (!$ok) {
                throw new Exception($table_template->getError());
            }
        }

        return true;

    }

    //TODO create user supper admin

    function createSupperAdmin($website_id = 0, $website_template_id = 0)
    {
        $session = JFactory::getSession();
        $email = $session->get('email', '');
        if (!$website_template_id) {
            //copy from website template
            $website_template_id = websiteHelperFrontEnd::getOneTemplateWebsite();
        }
        require_once JPATH_ROOT . '/libraries/joomla/table/user.php';
        $table_user = JTable::getInstance('User', 'JTable');
        $table_user->id = 0;
        $table_user->email = $email;
        $table_user->block = 0;
        $table_user->issystem = 1;
        $table_user->name = 'admin';
        $table_user->username = 'admin';
        $table_user->website_id = $website_id;
        //$password=JUserHelper::genRandomPassword(8);
        $password = '123456';
        $table_user->password = md5($password);
        if (!$table_user->store()) {
            $this->setError($table_user->getError());
        }
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->clear()
            ->select('usergroups.id,usergroups.parent_id,user_group_id_website_id.website_id AS website_id')
            ->from('#__usergroups AS usergroups')
            ->leftJoin('#__user_group_id_website_id AS user_group_id_website_id ON user_group_id_website_id.user_group_id=usergroups.id')
        ;
        $list_user_group=$db->setQuery($query)->loadObjectList();
        $children_user_group = array();
        foreach ($list_user_group as $v) {
            $pt = $v->parent_id;
            $pt = ($pt == '' || $pt == $v->id) ? 'list_root' : $pt;
            $list = @$children_user_group[$pt] ? $children_user_group[$pt] : array();
            array_push($list, $v);
            $children_user_group[$pt] = $list;
        }
        $list_root_user_group = $children_user_group['list_root'];
        unset($children_user_group['list_root']);

        $list_user_group_of_website=array();
        foreach($list_root_user_group AS $root_user_group)
        {
            if($root_user_group->website_id==$website_id)
            {
                $list_user_group_of_website[]=$root_user_group->id;
                $get_list_user_group_of_website=function($function_call_back, $user_group_id=0, &$list_user_group_of_website, $children_user_group, $level=0, $max_level=999){
                    foreach($children_user_group[$user_group_id] as $user_group)
                    {
                        $user_group_id_1=$user_group->id;
                        $list_user_group_of_website[]=$user_group_id_1;
                        $function_call_back($function_call_back,$user_group_id_1, $list_user_group_of_website,$children_user_group);
                    }
                };
                $get_list_user_group_of_website($get_list_user_group_of_website,$root_user_group->id,$list_user_group_of_website,$children_user_group);
            }
        }

        $table_user->groups = $list_user_group_of_website;

        if (!$table_user->store()) {
            $this->setError($table_user->getError());
            return false;
        }
        return true;

    }


    public function createViewAccessLevels($website_id = 0, $website_template_id = 0)
    {
        if (!$website_template_id) {
            //copy from website template
            $website_template_id = websiteHelperFrontEnd::getOneTemplateWebsite();
        }

        $db = $this->_db;
        $query = $db->getQuery(true)
            ->select('user_group_id_website_id.user_group_id')
            ->from('#__user_group_id_website_id AS user_group_id_website_id')
            ->where('website_id=' . (int)$website_id);
        $root_user_group_id = $db->setQuery($query)->loadResult();

        $query = $db->getQuery(true);
        $query->select('usergroups.*')
            ->from('#__usergroups As usergroups ')
            ->order('usergroups.ordering');
        $db->setQuery($query);
        $list_rows = $db->loadObjectList('id');
        $children = array();
        $list_id = array();

// First pass - collect children
        foreach ($list_rows as $v) {
            $pt = $v->parent_id;
            $list = @$children[$pt] ? $children[$pt] : array();
            if ($v->id != $v->parent_id) {
                array_push($list, $v);
            } elseif ($root_user_group_id == $v->id && $v->id == $v->parent_id) {
                $list_id[$v->copy_from] = $v->id;
            }
            $children[$pt] = $list;
        }
        if(!function_exists('get_array_older_id')) {
            function get_array_older_id($children, $root_id, &$list_id = array())
            {

                if (@$children[$root_id]) {
                    foreach ($children[$root_id] as $v) {
                        $root_id = $v->id;
                        $list_id[$v->copy_from] = $v->id;
                        get_array_older_id($children, $root_id, $list_id);
                    }
                }
            }
        }
        $query->clear()
            ->select('viewlevels.*')
            ->from('#__viewlevels AS viewlevels')
            ->where('website_id=' . (int)$website_template_id);
        $list_view_levels = $db->setQuery($query)->loadObjectList();
        $table_view_levels = JTable::getInstance('viewlevel');
        foreach ($list_view_levels AS $view_levels) {
            $table_view_levels->bind((array)$view_levels);
            $table_view_levels->id = 0;
            $table_view_levels->copy_from = $view_levels->id;
            $rules = $view_levels->rules;
            $rules = json_decode($rules);
            foreach ($rules AS $key => $rule) {
                if ($list_id[$rule]) {
                    $rules[$key] = $list_id[$rule];
                }
            }
            $table_view_levels->rules = json_encode($rules);
            $table_view_levels->website_id = $website_id;
            $ok = $table_view_levels->store();
            if (!$ok) {
                throw new Exception($table_view_levels->getError());
            }
        }
        return true;

    }

    public function CheckInsertDomainToWebsite(&$layout)
    {
        $session = JFactory::getSession();
        $website_id = $session->get('website_id', 0);
        if ($website_id) {
            $tableWebsite = $this->getTable();
            if ($tableWebsite->load($website_id)) {
                $layout = $this->getPrevLayoutByLayout('insertdomaintowebsite');
            } else {
                $this->setError($tableWebsite->getError());
                return false;
            }
        }

        return true;
    }

    public function CheckCreateConfiguration(&$layout)
    {
        $session = JFactory::getSession();
        $website_id = $session->get('website_id', 0);
        if ($website_id) {
            $tableWebsite = $this->getTable();
            if ($tableWebsite->load($website_id)) {
                $layout = $this->getPrevLayoutByLayout('createconfiguration');
            } else {
                $this->setError($tableWebsite->getError());
                return false;
            }
            //check file webstore

        }
        if (!JFile::exists(JPATH_ROOT . '/configuration/configuration_' . $website_id . '.php')) {
            //
            $this->setError("file configuration_.$website_id.php not exits");
            return false;
        }

        $listDomainWebsite = $this->getListDomainByWebsiteId($website_id);
        if (!count($listDomainWebsite)) {
            $this->setError("table domain_website not have website:$website_id ");
            return false;
        }
        $existsFileWebstore = false;
        foreach ($listDomainWebsite as $domainWebsite) {
            $filePathConfiguration = JPATH_ROOT . '/webstore/' . $domainWebsite->domain . '.ini';
            if (JFile::exists($filePathConfiguration)) {
                $existsFileWebstore = true;
            } else {


                $this->setError("file  {$domainWebsite->domain}.ini not exists");
            }
            if ($existsFileWebstore)
                break;
        }
        if (!$existsFileWebstore) {
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
    public function createGroupUser($website_id = 0, $website_template_id = 0)
    {

        if (!$website_template_id) {
            //copy from website template
            $website_template_id = websiteHelperFrontEnd::getOneTemplateWebsite();
        }
        $db = $this->_db;
        $query = $db->getQuery(true)
            ->select('user_group_id_website_id.user_group_id')
            ->from('#__user_group_id_website_id AS user_group_id_website_id')
            ->where('website_id=' . (int)$website_template_id);
        $old_root_user_group_id = $db->setQuery($query)->loadResult();
        if (!$old_root_user_group_id) {
            throw new Exception('there are no exists user group in website template');
        }
        $table_user_group = JTable::getInstance('usergroup', 'Jtable');
        $table_user_group->load($old_root_user_group_id);
        $table_user_group->id = 0;
        $table_user_group->copy_from = $old_root_user_group_id;
        $ok = $table_user_group->parent_store();
        if (!$ok) {
            throw new Exception($table_user_group->getError());
        }
        $new_parent_id = $table_user_group->id;
        $table_user_group->parent_id = $new_parent_id;
        $ok = $table_user_group->parent_store();
        if (!$ok) {
            throw new Exception($table_user_group->getError());
        }
        $user_group_website = JTable::getInstance('usergroupwebsite');
        $user_group_website->user_group_id = $new_parent_id;
        $user_group_website->website_id = $website_id;
        $ok = $user_group_website->store();
        if (!$ok) {
            throw new Exception($user_group_website->getError());
        }

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('usergroups.*')
            ->from('#__usergroups As usergroups ')
            ->order('usergroups.ordering');
        $db->setQuery($query);
        $list_user_group = $db->loadObjectList('id');
        $children = array();

// First pass - collect children
        foreach ($list_user_group as $v) {
            $pt = $v->parent_id;
            $list = @$children[$pt] ? $children[$pt] : array();
            if ($v->id != $v->parent_id) {
                array_push($list, $v);
            }
            $children[$pt] = $list;
        }

        function execute_copy_rows_table_group_user($website_id, JTable $table_user_group, $old_parent_id = 0, $new_parent_id, $children)
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
                    execute_copy_rows_table_group_user($website_id, $table_user_group, $old_parent_id, $new_parent_id, $children);
                }
            }
        }

        execute_copy_rows_table_group_user($website_id, $table_user_group, $old_root_user_group_id, $new_parent_id, $children);
        return true;
    }

    public function getListDomainByWebsiteId($website_id)
    {
        $db = $this->getDbo();
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from('#__domain_website');
        $query->where('website_id=' . (int)$website_id);
        $db->setQuery($query);
        return $db->loadObjectList();
    }

    public function CheckCreateGroupUser(&$layout)
    {
        $session = JFactory::getSession();
        $website_id = $session->get('website_id', 0);
        if (!$website_id) {
            $this->setError('Can not found website');
        }
        require_once JPATH_ROOT . '/components/com_users/helpers/groups.php';
        $listRootUserGroup = GroupsHelper::getRootUserGroupByWebsiteId($website_id);
        if (!count($listRootUserGroup)) {
            $layout = $this->getPrevLayoutByLayout('creategroupuser');
            $this->setError('there are no user group in website');
            return false;

        }
        return true;
    }

    public function CheckCreateSupperAdmin(&$layout)
    {
        $session = JFactory::getSession();
        $website_id = $session->get('website_id', 0);
        if (!$website_id) {
            $this->setError('Can not found website');
        }

        require_once JPATH_ROOT . '/components/com_users/helpers/users.php';
        $listRootUserGroup = UsersHelper::getTotalUserByWebsiteId($website_id);
        if (!count($listRootUserGroup)) {
            $layout = $this->getPrevLayoutByLayout('createsupperadmin');
            $this->setError('there are no user  in website');
            return false;

        }
        return true;
    }

    public function CheckCreateViewAccessLevels(&$layout)
    {
        $session = JFactory::getSession();
        $website_id = $session->get('website_id', 0);
        if (!$website_id) {
            $this->setError('Can not found website');
        }
        require_once JPATH_ROOT . '/components/com_users/helpers/levels.php';
        $listUserLevel = levelsHelper::getListUserLevelByWebsiteId($website_id);
        if (!count($listUserLevel)) {
            $layout = $this->getPrevLayoutByLayout('createviewaccesslevels');
            $this->setError('there are no user level in website');
            return false;

        }
        return true;
    }

    function CreateComponents($website_id = 0, $website_template_id = 0)
    {
        if (!$website_template_id) {
            //copy from website template
            $website_template_id = websiteHelperFrontEnd::getOneTemplateWebsite();
        }

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->clear()
            ->select('*')
            ->from('#__extensions')
            ->where('website_id=' . (int)$website_template_id)
        ;
        $list_extensions = $db->setQuery($query)->loadObjectList();
        $table_extension = JTable::getInstance('extension');
        $list_older_extension = array();
        foreach ($list_extensions AS $extensions) {
            $table_extension->bind((array)$extensions);
            $table_extension->id = 0;
            $table_extension->copy_from = $extensions->id;
            $table_extension->website_id = $website_id;
            $ok = $table_extension->store();
            if (!$ok) {
                throw new Exception($table_extension->getError());
            }
            $list_older_extension[$extensions->id] = $table_extension->id;
        }
        $query->clear()
            ->select('*')
            ->from('#__components AS components')
            ->leftJoin('#__extensions AS extensions ON extensions.id=components.extension_id')
            ->where('extensions.website_id=' . (int)$website_template_id);
        $list_components = $db->setQuery($query)->loadObjectList();
        $table_component = JTable::getInstance('component');
        $website_name=websiteHelperFrontEnd::get_website_name_by_website_id($website_id);
        $website_template_name=websiteHelperFrontEnd::get_website_name_by_website_id($website_template_id);

        foreach ($list_components AS $component) {
            $table_component->bind((array)$component);
            $table_component->id = 0;
            $table_component->copy_from = $component->id;
            if ($extension_id = $list_older_extension[$component->extension_id]) {
                $table_component->extension_id = $extension_id;
            }
            $ok = $table_component->store();
            if (!$ok) {
                throw new Exception($table_component->getError());
            }
            $source_component_path="components/website/website_$website_template_name/$component->name";
            if(JFolder::exists(JPATH_ROOT.DS.$source_component_path))
            {
                $new_destination_component_path="components/website/website_$website_name/$component->name";
                $ok=JFolder::copy(JPATH_ROOT.DS.$source_component_path,JPATH_ROOT.DS.$new_destination_component_path,'',true);
                if (!$ok) {
                    throw new Exception('copy component error');
                }
            }
        }
        return true;
    }

    function createControl($website_id = 0, $website_template_id = 0){
        if (!$website_template_id) {
            //copy from website template
            $website_template_id = websiteHelperFrontEnd::getOneTemplateWebsite();
        }
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->clear()
            ->select('control.*')
            ->from('#__control AS control')
            ->where('control.website_id=' . (int)$website_template_id);
        $list_control = $db->setQuery($query)->loadObjectList();
        $list_older_control = array();
        $website_name=websiteHelperFrontEnd::get_website_name_by_website_id($website_id);
        $website_name_template=websiteHelperFrontEnd::get_website_name_by_website_id($website_template_id);
        $table_control=JTable::getInstance('control');
        foreach ($list_control AS $control) {
            $table_control->bind($control);
            $table_control->id=0;
            $element_path=$control->element_path;
            $type=$table_control->type;
            if($type=='module')
            {
                $element_path=str_replace("website_$website_name_template","website_$website_name",$element_path);
            }
            $table_control->element_path= $element_path;
            $table_control->website_id=$website_id;
            $ok = $table_control->store();
            if (!$ok) {
                throw new Exception($table_control->getError());
            }
            $list_older_control[$control->id] = $table_control->id;
        }
        return true;
    }
    function createModules($website_id = 0, $website_template_id = 0)
    {
        if (!$website_template_id) {
            //copy from website template
            $website_template_id = websiteHelperFrontEnd::getOneTemplateWebsite();
        }
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->clear()
            ->select('*')
            ->from('#__extensions')
            ->where('website_id=' . (int)$website_id);
        $list_extensions = $db->setQuery($query)->loadObjectList();
        $list_older_extension = array();
        foreach ($list_extensions AS $extensions) {
            $list_older_extension[$extensions->copy_from] = $extensions->id;
        }
        $query->clear()
            ->select('modules.*')
            ->from('#__modules AS modules')
            ->leftJoin('#__extensions AS extensions ON extensions.id=modules.extension_id')
            ->where('extensions.website_id=' . (int)$website_template_id);
        $list_modules = $db->setQuery($query)->loadObjectList();
        $table_module = JTable::getInstance('module');
        jimport('joomla.filesystem.folder');
        $website_name=websiteHelperFrontEnd::get_website_name_by_website_id($website_id);
        $website_template_name=websiteHelperFrontEnd::get_website_name_by_website_id($website_template_id);
        foreach ($list_modules AS $module) {
            $table_module->bind((array)$module);
            $table_module->id = 0;
            $table_module->copy_from = $module->id;
            $extension_id = $module->extension_id;
            $extension_id = $list_older_extension[$extension_id];
            $table_module->extension_id = $extension_id;
            $ok = $table_module->store();
            if (!$ok) {
                throw new Exception($table_module->getError());
            }
            $module_path="modules/website/website_$website_template_name/$module->module";
            if(JFolder::exists(JPATH_ROOT.DS.$module_path))
            {
                $new_module_path="modules/website/website_$website_name/$module->module";
                $ok=JFolder::copy(JPATH_ROOT.DS.$module_path,JPATH_ROOT.DS.$new_module_path,'',true);
                if (!$ok) {
                    throw new Exception('copy module error');
                }
            }

        }

        return true;
    }

    public function createPlugins($website_id = 0, $website_template_id = 0)
    {
        if (!$website_template_id) {
            //copy from website template
            $website_template_id = websiteHelperFrontEnd::getOneTemplateWebsite();
        }
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->clear()
            ->select('*')
            ->from('#__extensions')
            ->where('website_id=' . (int)$website_id);
        $list_extensions = $db->setQuery($query)->loadObjectList();
        $list_older_extension = array();
        foreach ($list_extensions AS $extension) {
            $list_older_extension[$extension->copy_from] = $extension->id;
        }

        $query->clear()
            ->select('plugins.*,extensions.website_id')
            ->from('#__plugins AS plugins')
            ->innerJoin('#__extensions AS extensions ON extensions.id=plugins.extension_id')
            ->where('extensions.website_id=' . (int)$website_template_id)
            ->group('plugins.name')
        ;

        $list_plugins = $db->setQuery($query)->loadObjectList();

        $table_plugin = JTable::getInstance('plugin');
        foreach ($list_plugins AS $plugins) {
            $table_plugin->bind((array)$plugins);
            $table_plugin->id = 0;
            $table_plugin->copy_from = $plugins->id;
            $extension_id = $plugins->extension_id;
            $table_plugin->extension_id = $list_older_extension[$extension_id];
            $ok = $table_plugin->store();
            if (!$ok) {
                throw new Exception($table_plugin->getError());
            }
        }
        return true;
    }

    function createMenus($website_id = 0, $website_template_id = 0)
    {

        if (!$website_template_id) {
            //copy from website template
            $website_template_id = websiteHelperFrontEnd::getOneTemplateWebsite();
        }

        //copy menu type
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        //get template menu type
        $query->clear()
            ->select('menu_types.*')
            ->from('#__menu_types AS menu_types')
            ->where('menu_types.website_id=' . (int)$website_template_id);
        $list_older_menu_type = array();
        $list_menu_type = $db->setQuery($query)->loadObjectList();
        require_once JPATH_ROOT . '/libraries/legacy/table/menu/type.php';
        $table_menu_type = JTable::getInstance('menutype', 'JTable');
        foreach ($list_menu_type AS $menu_type) {
            $table_menu_type->bind((array)$menu_type);
            $table_menu_type->id = 0;
            $table_menu_type->copy_from = $menu_type->id;
            $table_menu_type->website_id = $website_id;
            $ok = $table_menu_type->store();
            if (!$ok) {
                throw new Exception($table_menu_type->getError());
            }
            $list_older_menu_type[$menu_type->id] = $table_menu_type->id;
        }
        //end copy menu type
        //get root menu item from template
        $list_root_menu=MenusHelperFrontEnd::get_list_root_menu_item_by_website_id($website_template_id);
        if(!count($list_root_menu))
        {
            throw new Exception('there are no menu item');
        }
        $list_root_menu=JArrayHelper::pivot($list_root_menu,'id');
        $table_menu = JTable::getInstance('menu');
        $table_menu_item_menu_type = JTable::getInstance('menuitemmenutype');
        foreach ($list_root_menu AS $menu) {
            //create root menu item
            $table_menu->bind((array)$menu);
            $table_menu->id = 0;
            $table_menu->copy_from = $menu->id;
            $ok = $table_menu->check();
            $ok = $table_menu->parent_store();
            if (!$ok) {
                throw new Exception($table_menu->getError());
            }
            $new_menu_item=$table_menu->id;
            //end create root memnu item
            //store old  menu item
            $list_older_root_menu_item[$menu->id] = $new_menu_item;
            //create link root menu item width menu type
            $table_menu_item_menu_type->id=0;
            $table_menu_item_menu_type->menu_type_id = $list_older_menu_type[$menu->menu_type_id];
            $table_menu_item_menu_type->menu_id =  $new_menu_item;
            $ok = $table_menu_item_menu_type->store();
            if (!$ok) {
                throw new Exception($table_menu_item_menu_type->getError());
            }
            //end get link root menu item width menu type
        }
        require_once JPATH_ROOT . '/components/com_menus/helpers/menus.php';
        $a_list_older_menu_item1 = array();
        foreach ($list_older_root_menu_item AS $old_root_menu_item_id => $new_root_menu_item_id) {
            $list_older_menu_item1 = array();
            $list_older_menu_item1[$old_root_menu_item_id] = $new_root_menu_item_id;
            $list_children_menu_item_of_root_menu_item = MenusHelperFrontEnd::get_children_menu_item_by_menu_item_id($old_root_menu_item_id);
            $children_menu_item_of_root_menu_item = array();
            // First pass - collect children
            foreach ($list_children_menu_item_of_root_menu_item as $v) {
                $pt = $v->parent_id;
                $pt=($pt==''||$pt==$v->id)?'list_root':$pt;
                $list = @$children_menu_item_of_root_menu_item[$pt] ? $children_menu_item_of_root_menu_item[$pt] : array();
                array_push($list, $v);
                $children_menu_item_of_root_menu_item[$pt] = $list;
            }
            if (!function_exists('sub_execute_copy_rows_table_menu')) {
                function sub_execute_copy_rows_table_menu(JTable $table_menu, &$list_old_menu_item_id = array(), $old_menu_item_id = 0, $new_menu_item_id, $children_menu_item_of_root_menu_item)
                {
                    if ($children_menu_item_of_root_menu_item[$old_menu_item_id]) {
                        foreach ($children_menu_item_of_root_menu_item[$old_menu_item_id] as $v) {
                            $table_menu->bind((array)$v);
                            $table_menu->id = 0;
                            $table_menu->copy_from = $v->id;
                            $table_menu->parent_id = $new_menu_item_id;
                            $table_menu->getDbo()->rebuild_action = 1;
                            $ok = $table_menu->parent_store();
                            if (!$ok) {
                                throw new Exception($table_menu->getError());
                            }
                            $new_menu_item_id1 = $table_menu->id;
                            $old_menu_item_id1 = $v->id;
                            $list_old_menu_item_id[$old_menu_item_id1] = $new_menu_item_id1;
                            sub_execute_copy_rows_table_menu($table_menu, $list_old_menu_item_id, $old_menu_item_id1, $new_menu_item_id1, $children_menu_item_of_root_menu_item);
                        }
                    }
                }
            }
            unset($children_menu_item_of_root_menu_item['list_root']);
            sub_execute_copy_rows_table_menu($table_menu, $list_older_menu_item1, $old_root_menu_item_id, $new_root_menu_item_id, $children_menu_item_of_root_menu_item);
            $a_list_older_menu_item1 = $list_older_menu_item1 + $a_list_older_menu_item1;
        }
        $query->clear()
            ->select('position_config.id,position_config.parent_id,position_config.menu_item_id,root_position_id_website_id.website_id,position_config.screen_size_id')
            ->from('#__position_config AS position_config')
            ->leftJoin('#__root_position_id_website_id AS root_position_id_website_id ON root_position_id_website_id.position_id=position_config.id')
        ;

        $db->setQuery($query);
        $list_position_config = $db->loadObjectList();
        $children_position = array();
        foreach ($list_position_config as $v) {
            $pt = $v->parent_id;
            $pt = ($pt == '' || $pt == $v->id) ? 'list_root' : $pt;
            $list = @$children_position[$pt] ? $children_position[$pt] : array();
            array_push($list, $v);
            $children_position[$pt] = $list;
        }
        $list_root_position = $children_position['list_root'];
        unset($children_position['list_root']);
        if (!function_exists('sub_execute_copy_rows_table_position')) {
            function sub_execute_copy_rows_table_position(JTable $table_position, &$list_old_position_config,$list_older_menu_item, $old_position_id = 0, $new_position_id, $children,$level=0,$max_level=999)
            {
                if ($children[$old_position_id]&&$level<=$max_level) {
                    $level1=$level+1;

                    foreach ($children[$old_position_id] as $v) {
                        $table_position->load($v->id);
                        $table_position->id = 0;
                        $table_position->website_id = null;
                        $table_position->copy_from = $v->id;
                        $table_position->level = $level1;
                        $menu_item_id=$v->menu_item_id;
                        $menu_item_id=$list_older_menu_item[$menu_item_id];
                        if($menu_item_id)
                        {
                            $table_position->menu_item_id = $menu_item_id;
                        }
                        $table_position->parent_id = $new_position_id;
                        $table_position->getDbo()->rebuild_action = 1;
                        $ok = $table_position->parent_store();
                        if (!$ok) {
                            throw new Exception($table_position->getError());
                        }
                        $new_position_id1 = $table_position->id;
                        $old_position_id1 = $v->id;
                        $list_old_position_config[$old_position_id1]=$new_position_id1;
                        sub_execute_copy_rows_table_position($table_position,$list_old_position_config, $list_older_menu_item, $old_position_id1, $new_position_id1, $children,$level1,$max_level);
                    }
                }
            }
        }
        $table_position = JTable::getInstance('positionnested');
        $list_old_position_config=array();
        foreach ($list_root_position as $position) {
            if ($position->website_id == $website_template_id) {
                $table_position->load($position->id);
                $table_position->id = 0;
                $table_position->copy_from = $position->id;
                $table_position->menu_item_id = $a_list_older_menu_item1[$position->menu_item_id];
                $table_position->parent_id = null;
                $table_position->screen_size_id = $position->screen_size_id;
                $table_position->website_id = $website_id;
                $table_position->getDbo()->rebuild_action = 1;
                $ok = $table_position->parent_store();
                if (!$ok) {
                    throw new Exception($table_position->getError());
                }
                $table_root_position_id_website_id=JTable::getInstance('root_position_id_website_id');
                $table_root_position_id_website_id->position_id=$table_position->id;
                $table_root_position_id_website_id->website_id=$website_id;
                $table_root_position_id_website_id->screen_size_id=$position->screen_size_id;
                $ok = $table_root_position_id_website_id->store();
                if (!$ok) {
                    throw new Exception($table_root_position_id_website_id->getError());
                }
                $list_old_position_config[$position->id]=$table_position->id;
                sub_execute_copy_rows_table_position($table_position,$list_old_position_config, $a_list_older_menu_item1, $position->id, $table_position->id, $children_position);

            }
        }

        $query->clear()
            ->select('menu_item_id_position_id_ordering.*')
            ->from('#__menu_item_id_position_id_ordering AS menu_item_id_position_id_ordering')
            ->where('menu_item_id_position_id_ordering.website_id='.(int)$website_template_id)
        ;
        $db->setQuery($query);
        $list_menu_item_id_position_id = $db->loadObjectList();
        $table_menu_item_id_position_id_ordering = JTable::getInstance('menu_item_id_position_id_ordering');
        foreach ($list_menu_item_id_position_id as $menu_position) {
            $table_menu_item_id_position_id_ordering->bind($menu_position);
            $table_menu_item_id_position_id_ordering->menu_item_id=$a_list_older_menu_item1[$menu_position->menu_item_id];
            $table_menu_item_id_position_id_ordering->position_id=$list_old_position_config[$menu_position->position_id];
            $table_menu_item_id_position_id_ordering->id=0;
            $table_menu_item_id_position_id_ordering->website_id=$website_id;
            if($table_menu_item_id_position_id_ordering->website_id&&$table_menu_item_id_position_id_ordering->menu_item_id&&$table_menu_item_id_position_id_ordering->position_id) {
                $ok = $table_menu_item_id_position_id_ordering->store();
                if (!$ok) {
                    throw new Exception($table_menu_item_id_position_id_ordering->getError());
                }
            }
        }
        $query->clear()
            ->select('modules.id')
            ->from('#__modules AS modules')
            ->leftJoin('#__extensions AS extensions ON extensions.id=modules.extension_id')
            ->where('extensions.website_id='.(int)$website_id)
        ;
        $list_module_id=$db->setQuery($query)->loadColumn();

        $table_module_menu=JTable::getInstance('module');
        foreach($list_module_id as $module_id)
        {
            $table_module_menu->load($module_id);
            $position_id=$table_module_menu->position_id;
            $position_id=$list_old_position_config[$position_id];
            $table_module_menu->position_id=$position_id;
            $table_module_menu->position="position-$position_id";
            $ok = $table_module_menu->store();
            if (!$ok) {
                throw new Exception($table_module_menu->getError());
            }
        }
        //copy table module_menu
        $query->clear()
            ->select('modules_menu.*')
            ->from('#__modules_menu AS modules_menu')
            ->leftJoin('#__modules AS modules ON modules.id=modules_menu.moduleid')
            ->leftJoin('#__extensions AS extensions ON extensions.id=modules.extension_id')
            ->where('extensions.website_id='.(int)$website_template_id)
        ;
        $list_module_menu=$db->setQuery($query)->loadObjectList();

        $query->clear()
            ->select('modules.id,modules.copy_from')
            ->from('#__modules AS modules')
            ->leftJoin('#__extensions AS extensions ON extensions.id=modules.extension_id')
            ->where('extensions.website_id='.(int)$website_id)
        ;
        $list_new_module=$db->setQuery($query)->loadObjectList('copy_from');
        $table_module_menu=JTable::getInstance('modulemenu');
        foreach($list_module_menu as $module_menu)
        {
            $table_module_menu->id=0;
            $table_module_menu->moduleid=$list_new_module[$module_menu->moduleid]->id;
            $menuid=$module_menu->menuid;
            $menuid=$a_list_older_menu_item1[$menuid];
            $menuid=$menuid?$menuid:null;
            $table_module_menu->menuid=$menuid;
            $ok = $table_module_menu->store();
            if (!$ok) {
                throw new Exception($table_module_menu->getError());
            }
        }

        // First pass - collect children
        return true;
    }

    public function CheckCreateComponents(&$layout)
    {
        $session = JFactory::getSession();
        $website_id = $session->get('website_id', 0);
        if (!$website_id) {
            $this->setError('Can not found website');
        }
        require_once JPATH_ROOT . '/components/com_components/helpers/components.php';
        $listComponent = componentsHelper::getComponentByWebsiteId($website_id);
        if (!count($listComponent)) {
            $layout = $this->getPrevLayoutByLayout('createcomponents');
            $this->setError('there are no components in website');
            return false;

        }
        return true;
    }

    public function CheckCreateModules(&$layout)
    {
        $session = JFactory::getSession();
        $website_id = $session->get('website_id', 0);
        if (!$website_id) {
            $this->setError('Can not found website');
        }
        require_once JPATH_ROOT . '/components/com_modules/helpers/modules.php';
        $listModule = ModulesHelper::getModulesByWebsiteId($website_id);
        if (!count($listModule)) {
            $layout = $this->getPrevLayoutByLayout('createmodules');
            $this->setError('there are no module in website');
            return false;

        }
        return true;
    }

    public function CheckCreatePlugins(&$layout)
    {
        $session = JFactory::getSession();
        $website_id = $session->get('website_id', 0);
        if (!$website_id) {
            $this->setError('Can not found website');
            return false;
        }
        require_once JPATH_ROOT . '/components/com_plugins/helpers/plugins.php';
        $listPlugin = PluginsHelper::getPluginsByWebsiteId($website_id);
        if (!count($listPlugin)) {
            $layout = $this->getPrevLayoutByLayout('createplugins');
            $this->setError('there are no plugin in website');
            return false;

        }
        return true;
    }

    public function CheckCreateStyles(&$layout)
    {
        $session = JFactory::getSession();
        $website_id = $session->get('website_id', 0);
        if (!$website_id) {
            $this->setError('Can not found website');
        }
        require_once JPATH_ROOT . '/components/com_templates/helpers/styles.php';
        $listStyle = StylesHelper::getStylesByWebsiteId($website_id);
        if (!count((array)$listStyle)) {
            $layout = $this->getPrevLayoutByLayout('createstyles');
            $this->setError('there are no style in website');
            return false;

        }
        return true;
    }

    public function CheckCreateMenus(&$layout)
    {
        $session = JFactory::getSession();
        $website_id = $session->get('website_id', 0);
        if (!$website_id) {
            $this->setError('Can not found website');
        }
        require_once JPATH_ROOT . '/administrator/components/com_menus/helpers/menus.php';
        $listMenuItem = MenusHelper::getTotalMenuItemByWebsiteId($website_id);
        if (!count((array)$listMenuItem)) {
            $layout = $this->getPrevLayoutByLayout('createmenus');
            $this->setError('there are no menu item in website');
            return false;

        }
        $menuItemDefault = MenusHelper::getMenuItemDefault($website_id);
        if (!$menuItemDefault->id) {
            $layout = $this->getPrevLayoutByLayout('createmenus');
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

    public function changeParams($website_id,$website_template_id = 0)
    {
        if (!$website_template_id) {
            //copy from website template
            $website_template_id = websiteHelperFrontEnd::getOneTemplateWebsite();
        }
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->clear()
            ->select('menu.id,menu.parent_id,menu.copy_from')
            ->from('#__menu AS menu');
        $db->setQuery($query);
        $list_menu_item = $db->loadObjectList();
        $children_menu_item = array();
        foreach ($list_menu_item as $v) {
            $pt = $v->parent_id;
            $pt = ($pt == '' || $pt == $v->id) ? 'list_root' : $pt;
            $list = @$children_menu_item[$pt] ? $children_menu_item[$pt] : array();
            array_push($list, $v);
            $children_menu_item[$pt] = $list;
        }

        $list_root_menu_item = $children_menu_item['list_root'];
        unset($children_menu_item['list_root']);
        //get root position of current website
        $query=$db->getQuery(true);
        $query->clear()
            ->select('menu_type_id_menu_id.menu_id')
            ->from('#__menu_type_id_menu_id AS menu_type_id_menu_id')
            ->innerJoin('#__menu_types AS menu_types ON menu_types.id=menu_type_id_menu_id.menu_type_id')
            ->where('menu_types.website_id='.(int)$website_id)
        ;
        $db->setQuery($query);
        $list_root_menu_item_id = $db->loadColumn();

        $get_menu_item_of_website=function($function_call_back, $menu_item_id=0, &$list_menu_item_of_website, $children_menu_item=array(), $level=0, $max_level=999){
            if ($children_menu_item[$menu_item_id]) {
                $level1=$level+1;
                foreach ($children_menu_item[$menu_item_id] as $menu_item) {
                    $menu_item_id_1 = $menu_item->id;
                    $list_menu_item_of_website[]=$menu_item;
                    $function_call_back($function_call_back, $menu_item_id_1, $list_menu_item_of_website, $children_menu_item, $level1, $max_level);
                }
            }
        };
        $list_menu_item_of_website=array();
        foreach($list_root_menu_item_id AS $root_menu_item_id)
        {
            $get_menu_item_of_website($get_menu_item_of_website,$root_menu_item_id,$list_menu_item_of_website,$children_menu_item);
        }





        $table_menu = JTable::getInstance('menu');
        JModelLegacy::addIncludePath(JPATH_ROOT.'/components/com_menus/models');
        $menu_model = JModelLegacy::getInstance('uitem','MenusModel');
        JForm::addFormPath(JPATH_ROOT.'/components/com_menus/models/forms');
        $app=JFactory::getApplication();
        $list_update_query=array();
        foreach($list_menu_item_of_website AS $menu_item)
        {

            $app->input->set('id',$menu_item->id);
            $menu_model->setState('item.id',$menu_item->id);
            $item=$menu_model->getItem($menu_item->id);
            $form=$menu_model->getForm((array)$item,true);
            $field_sets = $form->getFieldset();
            foreach ($field_sets as $field) {
                $field_name = $field->__get('fieldname');
                $group = $field->__get('group');
                $function='get_new_value_by_old_value';
                if(method_exists($field,$function)) {

                    $new_value = $field->get_new_value_by_old_value($website_id);
                    $form->setValue($field_name, $group, $new_value);
                }

            }

            $item =clone $form->getData();
            $item = $item->toObject();
            $params = new JRegistry;
            $params->loadObject($item->params);
            $item->params = $params->toString();

            $table_menu->bind($item);
            $table_menu->id=$menu_item->id;
            $query=$table_menu->getQueryStore();
            $list_update_query[]=$query;

        }
        $query=$db->getQuery(true);
        $query->clear();
        $query->setQuery(implode(";\r\n",$list_update_query));
        $ok = $db->execute();
        if (!$ok) {
            throw new Exception($db->getErrorMsg());
            return false;
        }
        //update param module
        $app=JFactory::getApplication();
        $list_module=JModuleHelper::get_list_module_by_website_id($website_id);
        JModelLegacy::addIncludePath(JPATH_ROOT.'/components/com_modules/models');
        $module_model = JModelLegacy::getInstance('umodule','ModulesModel');
        JForm::addFormPath(JPATH_ROOT.'/components/com_modules/models/forms');

        $table_module=JTable::getInstance('module');
        $main_table_control = JTable::getInstance('control');
        require_once JPATH_ROOT.'/components/com_modules/helpers/module.php';
        $main_table_control->load(
            array(
                "element_path" => module_helper::MODULE_ROOT_NAME,
                "type" =>module_helper::ELEMENT_TYPE
            )
        );
        $list_update_query=array();
        foreach($list_module AS $module)
        {

            $app->input->set('id',$module->id);
            $module_model->setState('module.id',$module->id);
            $item=$module_model->getItem($module->id);
            $form=$module_model->getForm(array(),false);
            $form->bind($item);
            $module_control=JControlHelper::get_control_module_by_module_id($item->id);
            JModuleHelper::change_property_module_by_fields($website_id,$form,$module_control->fields,$main_table_control->fields);
            $item=clone $form->getData();
            $item=$item->toObject();
            $params = new JRegistry;
            $params->loadObject($item->params);
            $item->params = $params->toString();
            $table_module->bind((array)$item);
            $table_module->id=$module->id;

            $query=$table_module->getQueryStore();
            $list_update_query[]=$query;

        }
        $query=$db->getQuery(true);
        $query->clear();
        $query->setQuery(implode(";\r\n",$list_update_query));
        $ok = $db->execute();
        if (!$ok) {

            throw new Exception($db->getErrorMsg());
            return false;
        }
        //update params blocks
        //update params component
        //update params plugins

        return true;
    }

    public function createContentCategory($website_id, $website_template_id = 0)
    {
        if (!$website_template_id) {
            //copy from website template
            $website_template_id = websiteHelperFrontEnd::getOneTemplateWebsite();
        }
        require_once JPATH_ROOT . '/components/com_categories/helpers/categories.php';
        $ok = CategoriesHelper::copy_categories($website_template_id, $website_id);
        return $ok;

    }

    public function CheckCreateContentCategory(&$layout)
    {
        $session = JFactory::getSession();
        $website_id = $session->get('website_id', 0);
        if (!$website_id) {
            $this->setError('Can not found website');
        }
        require_once JPATH_ROOT . '/administrator/components/com_categories/helpers/categories.php';
        $listCategory = CategoriesHelper::getListCategoryByWebsiteId($website_id);
        if (!count((array)$listCategory)) {
            $layout = $this->getPrevLayoutByLayout('createcontentcategory');
            $this->setError('there are no category item in website');
            return false;

        }
        require_once JPATH_ROOT . '/administrator/components/com_content/helpers/articles.php';
        $listArticle = ArticlesHelper::getListArticleByWebsiteId($website_id);
        if (!count((array)$listArticle)) {
            $layout = $this->getPrevLayoutByLayout('createcontentcategory');
            $this->setError('there are no article item in website');
            return false;

        }
        return true;
    }


    public function CheckFinish(&$layout)
    {
        $session=JFactory::getSession();
        $website_id=$session->get('website_id',0);
        $table_website=$this->getTable();
        $table_website->load($website_id);
        $table_website->setup_finish=1;
        $table_website->supper_admin_request_update=1;
        $ok=$table_website->store();
        if(!$ok)
        {
            $this->setError($table_website->getError());
            return false;
        }
        $session_website = JModelLegacy::getInstance('session_website');
        $session_website->load();
        $session_website->clear();
        $layout = 'finish';
        require_once JPATH_ROOT.'/components/com_utility/helper/block_helper.php';
        require_once JPATH_ROOT.'/components/com_menus/helpers/menus.php';
        MenusHelperFrontEnd::remove_all_menu_not_exists_menu_type_by_website_id($website_id);
        block_helper::remove_all_block_not_exists_menu_item_by_website_id($website_id);
        require_once JPATH_ROOT.'/components/com_utility/controllers/block.php';
        UtilityControllerBlock::fix_screen_size_by_website_id($website_id);
        UtilityControllerBlock::rebuild_website_by_website_id($website_id);
        return true;
    }

    public function getLayoutOfCurrentStep()
    {
        $listStep = $this->getListStep();
        $this->setError('Process running again');
        $layout = 'formbase';

        foreach ($listStep as $step) {
            if (!strpos($step, ':')) {
                $step = 'check' . $step;
                $ok = call_user_func_array(array($this, $step), array(&$layout));
                if (!$ok) {
                    break;
                }
            } else {
                //cau truc cho cac file setup
                //checkcreate:class:class
                $step = explode(':', $step);
                $objStep = json_decode($step[1]);
                require_once $objStep->fileName;
                $ok = call_user_func_array(array($objStep->className, $objStep->functiongetLayoutCurrentStep), array(&$layout));
                if (!$ok)
                    return $layout;
                //
            }
        }
        return $layout;
    }

    public function getPrevLayoutByLayout($layout)
    {
        $steps = $this->getListStep();
        $currentKey = 0;
        foreach ($steps as $key => $step) {

            if (strtolower($step) == strtolower($layout)) {
                $currentKey = $key;
                break;
            }
        }
        return $steps[$currentKey - 1];

    }

    public function createConfiguration($website_id = 0, $website_template_id = 0)
    {
        $db = $this->getDbo();
        $query = $db->getQuery(true);
        $query->select('website.id AS website_id,website.name AS website_name,domain_website.domain')
            ->from('#__domain_website AS domain_website')
            ->where('website_id=' . (int)$website_id)
            ->leftJoin('#__website AS website ON website.id = domain_website.website_id')
        ;
        $db->setQuery($query);
        $listDomainWebsite = $db->loadObjectList();
        if (!count($listDomainWebsite)) {
            $this->setError('there are no domain point to this website');
            return false;
        }
        $templateConfigurationFilePath = JPATH_ROOT . '/configuration/';
        if (!$website_template_id) {
            //copy from website template
            $website_template_id = websiteHelperFrontEnd::getOneTemplateWebsite();
        }
        $website_template_name=websiteHelperFrontEnd::get_website_name_by_website_id($website_template_id);
        $templateConfigurationFile = 'configuration_' . $website_template_name . '.php';
        if (!JFile::exists($templateConfigurationFilePath . $templateConfigurationFile)) {
            $this->setError("File template configuration not exists");
            return false;
        }
        $pathFolderWebStore = JPATH_ROOT . '/webstore/';
        foreach ($listDomainWebsite as $domainWebsite) {
            //create file configuration
            $newFileConfiguration = "configuration_{$domainWebsite->website_name}.php";
            if (!JFile::exists($templateConfigurationFilePath . $newFileConfiguration)) {
                if (!JFile::copy($templateConfigurationFilePath . $templateConfigurationFile, $templateConfigurationFilePath . $newFileConfiguration)) {
                    $this->setError("Cannot copy file configuration");
                }
            }
            $fileWebStore =strtolower($domainWebsite->domain . '.ini');
            $content="$domainWebsite->website_id:$domainWebsite->website_name";
            if (!JFile::write($pathFolderWebStore . $fileWebStore,$content )) {
                $this->setError("can not create and write file webstore");
            }
        }

    }

    public function insertDomainToWebsite($domain = '', $website_id = 0)
    {
        $db = $this->getDbo();
        $query = $db->getQuery(true);
        $query->from('#__domain_website');
        $query->select('*');
        $query->where(
            array(
                'domain=' . $query->q($domain)
            )
        );
        $db->setQuery($query);
        $listDomainWebsite = $db->loadObjectList();
        if (count($listDomainWebsite)) {
            $this->setError('Existed this domain in our system');
            return false;
        }
        $table_domain_website=JTable::getInstance('domainwebsite');
        $table_domain_website->id=0;
        $table_domain_website->domain=$domain;
        $table_domain_website->website_id=$website_id;

        $ok=$table_domain_website->check();
        if(!$ok)
        {
            $this->setError($table_domain_website->getError());

            return false;
        }

        $ok=$table_domain_website->store();
        if(!$ok)
        {
            $this->setError($table_domain_website->getError());
        }
        $table_domain_website->id=0;
        $table_domain_website->domain="admin.$domain";
        $table_domain_website->website_id=$website_id;

        $ok=$table_domain_website->check();
        if(!$ok)
        {
            $this->setError($table_domain_website->getError());

            return false;
        }



        $ok=$table_domain_website->store();
        if(!$ok)
        {
            $this->setError($table_domain_website->getError());
        }




        return true;
    }

    public function checkExistsDomain($domain = '')
    {
        if (!$domain)
            return true;
        $domain = trim($domain);
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->from('#__domain_website AS dw');
        $query->select('COUNT(*)');
        $query->where('domain=' . $query->q($domain));
        $db->setQuery($query);
        return $db->loadResult();

    }

    public function checkStateSetupWebsite($domain = '')
    {
        if (!$domain)
            return false;
        if ($domain != '' && !$this->checkExistsDomain($domain))
            return false;
        $website = $this->getWebsiteByDomain($domain);

        if (!$website->id)
            return false;
        $components = $this->getComponentsByWebsiteId($website->id);

        if (!count($components))
            return false;
        foreach ($components as $component) {
            $componentName = $component->element;
            $fileCheckSetupWebsite = "setupwebsite.php";
            $pathFileCheckSetupWebsite = JPATH_ADMINISTRATOR . '/components/' . $component->element . '/' . $fileCheckSetupWebsite;
            if (file_exists($pathFileCheckSetupWebsite)) {
                require_once $pathFileCheckSetupWebsite;
                $className = "WebsiteSetup$componentName";
                $return = call_user_func_array(array($className, 'checkSetupWebsite'), array($website->id));
                if ($return == false)
                    return false;
            }
        }
        $modules = $this->getModulesByWebsiteId($website->id);

        if (!count($modules))
            return false;
        foreach ($modules as $module) {
            $moduleName = $module->module;
            $fileCheckSetupWebsite = "setupwebsite.php";
            if ($module->client_id)
                $pathFileCheckSetupWebsite = JPATH_ADMINISTRATOR . '/modules/' . $module->module . '/' . $fileCheckSetupWebsite;
            else
                $pathFileCheckSetupWebsite = JPATH_ROOT . '/modules/' . $module->module . '/' . $fileCheckSetupWebsite;
            if (file_exists($pathFileCheckSetupWebsite)) {
                require_once $pathFileCheckSetupWebsite;
                $className = "WebsiteSetup$moduleName";
                $return = call_user_func_array(array($className, 'checkSetupWebsite'), array($website->id));
                if ($return == false)
                    return false;
            }
        }
        $plugins = $this->getPluginsByWebsiteId($website->id);
        if (!count($plugins))
            return false;
        foreach ($plugins as $plugin) {
            $fileCheckSetupWebsite = "setupwebsite.php";
            $pathFileCheckSetupWebsite = JPATH_ROOT . '/plugins/' . $plugin->folder . '/' . $plugin->element . '/' . $fileCheckSetupWebsite;
            if (file_exists($pathFileCheckSetupWebsite)) {
                require_once $pathFileCheckSetupWebsite;
                $className = "WebsiteSetup{$plugin->element}";
                $return = call_user_func_array(array($className, 'checkSetupWebsite'), array($website->id));
                if ($return == false)
                    return false;
            }
        }
        return true;

    }

    public function sendEmailAlertSetupWebsite($email = '')
    {
        jimport('joomla.mail.helper');
        if (!JMailHelper::isEmailAddress($email))
            return false;
        require_once JPATH_ROOT . '/components/com_website/controllers/website.php';
        $control = new WebsiteControllerWebsite();
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
        if ($send !== true) {
            return false;
        } else {
            return true;
        }


    }

    public function getComponentsByWebsiteId($website_id = 0)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('components.*');
        $query->from('#__components AS components')
            ->leftJoin('#__extensions AS extensions.id=components.extension_id')
            ->where('extensions.website_id=' . (int)$website_id)
        ;
        $db->setQuery($query);
        $components = $db->loadObjectList();

        return $components;
    }

    public function getModulesByWebsiteId($website_id = 0)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('m.*');
        $query->from('#__modules AS m');
        $query->where('m.website_id=' . (int)$website_id);
        $db->setQuery($query);
        return $db->loadObjectList();
    }

    public function getPluginsByWebsiteId($website_id = 0)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('p.*');
        $query->from('#__plugins AS p');
        $query->where('p.website_id=' . (int)$website_id);
        $db->setQuery($query);
        return $db->loadObjectList();
    }

    public function getWebsiteByDomain($domain)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('website.*');
        $query->from('#__website AS website');
        $query->leftJoin('#__domain_website AS dw ON dw.website_id=website.id');
        $query->where('dw.domain=' . $query->q($domain));
        $db->setQuery($query);
        $website = $db->loadObject();
        return $website;
    }

    public function getWebsiteByUserId($user_id = 0)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
    }

    protected function batchCopy($value, $pks, $contexts)
    {
        $categoryId = (int)$value;

        $i = 0;

        if (!parent::checkCategoryId($categoryId)) {
            return false;
        }

        // Parent exists so we let's proceed
        while (!empty($pks)) {
            // Pop the first ID off the stack
            $pk = array_shift($pks);

            $this->table->reset();

            // Check that the row actually exists
            if (!$this->table->load($pk)) {
                if ($error = $this->table->getError()) {
                    // Fatal error
                    $this->setError($error);

                    return false;
                } else {
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
            if (!$this->table->check()) {
                $this->setError($this->table->getError());
                return false;
            }

            parent::createTagsHelper($this->tagsObserver, $this->type, $pk, $this->typeAlias, $this->table);

            // Store the row.
            if (!$this->table->store()) {
                $this->setError($this->table->getError());
                return false;
            }

            // Get the new item ID
            $newId = $this->table->get('id');

            // Add the new ID to the array
            $newIds[$i] = $newId;
            $i++;

            // Check if the website was featured and update the #__website_frontpage table
            if ($featured == 1) {
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
     * @param   object $record A record object.
     *
     * @return  boolean  True if allowed to delete the record. Defaults to the permission set in the component.
     * @since   1.6
     */
    protected function canDelete($record)
    {
        if (!empty($record->id)) {
            if ($record->state != -2) {
                return;
            }
            $user = JFactory::getUser();
            return $user->authorise('core.delete', 'com_website.website.' . (int)$record->id);
        }
    }

    /**
     * Method to test whether a record can have its state edited.
     *
     * @param   object $record A record object.
     *
     * @return  boolean  True if allowed to change the state of the record. Defaults to the permission set in the component.
     * @since   1.6
     */
    protected function canEditState($record)
    {
        $user = JFactory::getUser();

        // Check for existing website.
        if (!empty($record->id)) {
            return $user->authorise('core.edit.state', 'com_website.website.' . (int)$record->id);
        } // New website, so check against the category.
        elseif (!empty($record->catid)) {
            return $user->authorise('core.edit.state', 'com_website.category.' . (int)$record->catid);
        } // Default to component settings if neither website nor category known.
        else {
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
        if ($table->state == 1 && (int)$table->publish_up == 0) {
            $table->publish_up = JFactory::getDate()->toSql();
        }

        if ($table->state == 1 && intval($table->publish_down) == 0) {
            $table->publish_down = $db->getNullDate();
        }

        // Increment the website version number.
        $table->version++;

        // Reorder the websites within the category so the new website is first
        if (empty($table->id)) {
            $table->reorder('catid = ' . (int)$table->catid . ' AND state >= 0');
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
        $website = JFactory::getWebsite();
        $pk = $pk ? $pk : $website->website_id;
        $this->setState('website.id', $pk);
        // Load the parameters.
        $params = JComponentHelper::getParams('com_website');
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
        $pk = (!empty($pk)) ? (int)$pk : (int)$this->state->get('website.id');
        if ($item = parent::getItem($pk)) {
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

            if (!empty($item->id)) {
                $item->tags = new JHelperTags;
                $item->tags->getTagIds($item->id, 'com_website.website');
            }
        }
        // Load associated website items
        $app = JFactory::getApplication();
        $assoc = JLanguageAssociations::isEnabled();

        if ($assoc) {
            $item->associations = array();

            if ($item->id != null) {
                $associations = JLanguageAssociations::getAssociations('com_website', '#__website', 'com_website.item', $item->id);

                foreach ($associations as $tag => $association) {
                    $item->associations[$tag] = $association->id;
                }
            }
        }

        return $item;
    }

    /**
     * Method to get the record form.
     *
     * @param   array $data Data for the form.
     * @param   boolean $loadData True if the form is to load its own data (default case), false if not.
     *
     * @return  mixed  A JForm object on success, false on failure
     * @since   1.6
     */
    public function getForm($data = array(), $loadData = true)
    {
        // The folder and element vars are passed when saving the form.
        if (empty($data)) {

            $item = $this->getItem();

            $clientId = $item->client_id;
            $id = $item->id;
        } else {
            $clientId = JArrayHelper::getValue($data, 'client_id');
            $id = JArrayHelper::getValue($data, 'id');
        }

        // These variables are used to add data from the plugin XML files.
        $this->setState('item.client_id', $clientId);

        // Get the form.
        $form = $this->loadForm('com_website.website', 'website', array('control' => 'jform', 'load_data' => $loadData));
        if (empty($form)) {
            echo $this->getError();
            return false;
        }

        $form->setFieldAttribute('position', 'client', $this->getState('item.client_id') == 0 ? 'site' : 'administrator');

        $user = JFactory::getUser();

        // Check for existing module
        // Modify the form based on Edit State access controls.
        if ($id != 0 && (!$user->authorise('core.edit.state', 'com_website.module.' . (int)$id))
            || ($id == 0 && !$user->authorise('core.edit.state', 'com_website'))
        ) {
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
        if (isset($this->_forms[$hash]) && !$clear) {
            return $this->_forms[$hash];
        }
        // Get the form.
        JForm::addFormPath(__DIR__ . '/forms');
        JForm::addFieldPath(__DIR__ . '/fields');

        try {
            $form = JForm::getInstance($name, $source, $options, false, $xpath);

            if (isset($options['load_data']) && $options['load_data']) {
                // Get the data for the form.
                $data = $this->loadFormData();
            } else {
                $data = array();
            }

            // Allow for additional modification of the form, and events to be triggered.
            // We pass the data because plugins may require it.

            $this->preprocessForm($form, $data);

            // Load the data into the form after the plugins have operated.
            $form->bind($data);

        } catch (Exception $e) {

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

        if (empty($data)) {
            $data = $this->getItem();

            // Prime some default values.
            if ($this->getState('website.id') == 0) {
                $filters = (array)$app->getUserState('com_website.websites.filter');
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

        if (isset($data['images']) && is_array($data['images'])) {
            $registry = new JRegistry;
            $registry->loadArray($data['images']);
            $data['images'] = (string)$registry;
        }

        if (isset($data['urls']) && is_array($data['urls'])) {

            foreach ($data['urls'] as $i => $url) {
                if ($url != false && ($i == 'urla' || $i == 'urlb' || $i == 'urlc')) {
                    $data['urls'][$i] = JStringPunycode::urlToPunycode($url);
                }

            }
            $registry = new JRegistry;
            $registry->loadArray($data['urls']);
            $data['urls'] = (string)$registry;
        }

        // Alter the title for save as copy
        if ($app->input->get('task') == 'save2copy') {
            list($title, $alias) = $this->generateNewTitle($data['catid'], $data['alias'], $data['title']);
            $data['title'] = $title;
            $data['alias'] = $alias;
            $data['state'] = 0;
        }

        if (parent::save($data)) {

            if (isset($data['featured'])) {
                $this->featured($this->getState($this->getName() . '.id'), $data['featured']);
            }

            $assoc = JLanguageAssociations::isEnabled();
            if ($assoc) {
                $id = (int)$this->getState($this->getName() . '.id');
                $item = $this->getItem($id);

                // Adding self to the association
                $associations = $data['associations'];

                foreach ($associations as $tag => $id) {
                    if (empty($id)) {
                        unset($associations[$tag]);
                    }
                }

                // Detecting all item menus
                $all_language = $item->language == '*';

                if ($all_language && !empty($associations)) {
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

                if ($error = $db->getErrorMsg()) {
                    $this->setError($error);
                    return false;
                }

                if (!$all_language && count($associations)) {
                    // Adding new association for these items
                    $key = md5(json_encode($associations));
                    $query->clear()
                        ->insert('#__associations');

                    foreach ($associations as $id) {
                        $query->values($id . ',' . $db->quote('com_website.item') . ',' . $db->quote($key));
                    }

                    $db->setQuery($query);
                    $db->execute();

                    if ($error = $db->getErrorMsg()) {
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
        $pks = (array)$pks;
        JArrayHelper::toInteger($pks);

        if (empty($pks)) {
            $this->setError(JText::_('com_website_NO_ITEM_SELECTED'));
            return false;
        }

        $table = $this->getTable('Featured', 'websiteTable');

        try {
            $db = $this->getDbo();
            $query = $db->getQuery(true)
                ->update($db->quoteName('#__website'))
                ->set('featured = ' . (int)$value)
                ->where('id IN (' . implode(',', $pks) . ')');
            $db->setQuery($query);
            $db->execute();

            if ((int)$value == 0) {
                // Adjust the mapping table.
                // Clear the existing features settings.
                $query = $db->getQuery(true)
                    ->delete($db->quoteName('#__website_frontpage'))
                    ->where('website_id IN (' . implode(',', $pks) . ')');
                $db->setQuery($query);
                $db->execute();
            } else {
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
                foreach ($new_featured as $pk) {
                    $tuples[] = $pk . ', 0';
                }
                if (count($tuples)) {
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
        } catch (Exception $e) {
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
        $condition[] = 'catid = ' . (int)$table->catid;
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
        if ($assoc) {
            $languages = JLanguageHelper::getLanguages('lang_code');

            // force to array (perhaps move to $this->loadFormData())
            $data = (array)$data;

            $addform = new SimpleXMLElement('<form />');
            $fields = $addform->addChild('fields');
            $fields->addAttribute('name', 'associations');
            $fieldset = $fields->addChild('fieldset');
            $fieldset->addAttribute('name', 'item_associations');
            $fieldset->addAttribute('description', 'com_website_ITEM_ASSOCIATIONS_FIELDSET_DESC');
            $add = false;
            foreach ($languages as $tag => $language) {
                if (empty($data['language']) || $tag != $data['language']) {
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
            if ($add) {
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
