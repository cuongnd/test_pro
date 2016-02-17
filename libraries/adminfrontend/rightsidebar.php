<?php
/**
 * Created by PhpStorm.
 * User: cuongnd
 * Date: 12/3/2015
 * Time: 3:04
 */
$website=JFactory::getWebsite();
$db = JFactory::getDbo();
$query = $db->getQuery(true);
$query->from('#__menu As menu');
$query->select('menu.parent_id, menu.id,menu.title,menu.link,menu.icon');
$query->leftJoin('#__menu_types AS menuType ON menuType.id=menu.menu_type_id');
$query->select('menuType.title as menu_type');
$query->where('menuType.website_id=' . (int)$website->website_id);
$query->where('menuType.client_id=0');
$query->order('menuType.id,menu.ordering');
$listMenu = $db->setQuery($query)->loadObjectList();
$a_listMenu = array();
foreach ($listMenu as $menu) {
    $a_listMenu[$menu->menu_type][] = $menu;
}
?>
<div id=right-sidebar class=hide-sidebar>
    <!-- Start .sidebar-inner -->
    <div class=sidebar-inner>
        <div class="sidebar-panel mt0">
            <div class="sidebar-panel-content fullwidth pt0">
                <div class=chat-user-list>
                    <form class="form-horizontal chat-search" role=form>
                        <div class=form-group>
                            <input class=form-control placeholder="Search for user...">
                            <button type=submit><i class="ec-search s16"></i></button>
                        </div>
                        <!-- End .form-group  -->
                    </form>
                    <ul class="chat-ui bsAccordion">
                        <li><a href=#>Solution Explore <span class="notification teal">4</span><i class=en-arrow-down5></i></a>
                            <ul class="nav sub">

                                <?php foreach ($a_listMenu as $menu_type => $menus) { ?>
                                    <li><a href="javascript:void(0)"><i
                                                class=st-files></i> <?php echo JString::sub_string($menu_type, 30) ?></a>

                                        <?php
                                        $menu_root=new stdClass();
                                        foreach ($menus as $key=>$item)
                                        {
                                            if($item->parent_id==$item->id)
                                            {
                                                $menu_root=$item;
                                                unset($menus[$key]);
                                                break;
                                            }
                                        }

                                        $children = array();

                                        // First pass - collect children
                                        foreach ($menus as $v)
                                        {
                                            $pt = $v->parent_id;
                                            $list = @$children[$pt] ? $children[$pt] : array();
                                            array_push($list, $v);
                                            $children[$pt] = $list;
                                        }

                                        //$menus= treerecurse($menu_root->id,array(),$children,99,0);
                                        create_html_list_right_side_bar($menus,$menuItemIdActive);

                                        ?>

                                    </li>
                                <?php } ?>
                                <li><a href="javascript:void(0)"><i class=st-files></i> system pages</a>
                                    <ul class="nav sub">
                                        <li><a href=timeline.html><i class=ec-clock></i> Timeline page</a></li>
                                        <li><a href=invoice.html><i class=st-file></i> Invoice</a></li>
                                        <li><a href=profile.html><i class=ec-user></i> Profile page</a></li>
                                        <li><a href=search.html><i class=ec-search></i> Search page</a></li>
                                        <li><a href=blank.html><i class=im-file4></i> Blank page</a></li>
                                        <li><a href=login.html><i class=ec-locked></i> Login page</a></li>
                                        <li><a href=lockscreen.html><i class=ec-locked></i> Lock screen</a></li>
                                    </ul>
                                </li>
                                <li><a href="javascript:void(0)"><i class=st-files></i> Error pages</a>
                                    <ul class="nav sub">
                                        <li><a href=400.html><i class=st-file-broken></i> Error 400</a></li>
                                        <li><a href=401.html><i class=st-file-broken></i> Error 401</a></li>
                                        <li><a href=403.html><i class=st-file-broken></i> Error 403</a></li>
                                        <li><a href=404.html><i class=st-file-broken></i> Error 404</a></li>
                                        <li><a href=405.html><i class=st-file-broken></i> Error 405</a></li>
                                        <li><a href=500.html><i class=st-file-broken></i> Error 500</a></li>
                                        <li><a href=503.html><i class=st-file-broken></i> Error 503</a></li>
                                        <li><a href=offline.html><i class=st-window></i> Offline</a></li>
                                    </ul>
                                </li>
                            </ul>

                        </li>
                        <li><a href=#>Online <span class="notification green">3</span><i class=en-arrow-down5></i></a>
                            <ul class=in>
                                <li><a href=# class=chat-name><img class=chat-avatar src=<?php echo Juri::root() ?>/templates/<?php echo $this->template ?>/assets/img/avatars/51.jpg alt=@kolage>Eric Hofman</a> <span class="status online"><i class=en-dot></i></span></li>
                                <li><a href=# class=chat-name><img class=chat-avatar src=<?php echo Juri::root() ?>/templates/<?php echo $this->template ?>/assets/img/avatars/55.jpg alt=@mikebeecham>Mike Beecham</a> <span class="status online"><i class=en-dot></i></span></li>
                                <li><a href=# class=chat-name><img class=chat-avatar src=<?php echo Juri::root() ?>/templates/<?php echo $this->template ?>/assets/img/avatars/53.jpg alt=@derekebradley>Darek Bradly</a> <span class="status online"><i class=en-dot></i></span></li>
                            </ul>
                        </li>
                        <li><a href=#>Offline <span class="notification red">5</span><i class=en-arrow-down5></i></a>
                            <ul>
                                <li><a href=# class=chat-name><img class=chat-avatar src=<?php echo Juri::root() ?>/templates/<?php echo $this->template ?>/assets/img/avatars/56.jpg alt=@laurengray>Lauren Grey</a> <span class="status offline"><i class=en-dot></i></span></li>
                                <li><a href=# class=chat-name><img class=chat-avatar src=<?php echo Juri::root() ?>/templates/<?php echo $this->template ?>/assets/img/avatars/49.jpg alt=@chadengle>Chad Engle</a> <span class="status offline"><i class=en-dot></i></span></li>
                                <li><a href=# class=chat-name><img class=chat-avatar src=<?php echo Juri::root() ?>/templates/<?php echo $this->template ?>/assets/img/avatars/58.jpg alt=@frankiefreesbie>Frankie Freesibie</a> <span class="status offline"><i class=en-dot></i></span></li>
                                <li><a href=# class=chat-name><img class=chat-avatar src=<?php echo Juri::root() ?>/templates/<?php echo $this->template ?>/assets/img/avatars/57.jpg alt=@joannefournier>Joane Fornier</a> <span class="status offline"><i class=en-dot></i></span></li>
                                <li><a href=# class=chat-name><img class=chat-avatar src=<?php echo Juri::root() ?>/templates/<?php echo $this->template ?>/assets/img/avatars/59.jpg alt=@aiiaiiaii>Alia Alien</a> <span class="status offline"><i class=en-dot></i></span></li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <div class=chat-box>
                    <h5>Chad Engle</h5>
                    <a id=close-user-chat href=# class="btn btn-xs btn-primary"><i class=en-arrow-left4></i></a>
                    <ul class="chat-ui chat-messages">
                        <li class=chat-user>
                            <p class=avatar><img src=<?php echo Juri::root() ?>/templates/<?php echo $this->template ?>/assets/img/avatars/49.jpg alt=@chadengle></p>
                            <p class=chat-name>Chad Engle <span class=chat-time>15 seconds ago</span></p>
                            <span class="status online"><i class=en-dot></i></span>
                            <p class=chat-txt>Hello Sugge check out the last order.</p>
                        </li>
                        <li class=chat-me>
                            <p class=avatar><img src=<?php echo Juri::root() ?>/templates/<?php echo $this->template ?>/assets/img/avatars/48.jpg alt=SuggeElson></p>
                            <p class=chat-name>SuggeElson <span class=chat-time>10 seconds ago</span></p>
                            <span class="status online"><i class=en-dot></i></span>
                            <p class=chat-txt>Ok i will check it out.</p>
                        </li>
                        <li class=chat-user>
                            <p class=avatar><img src=<?php echo Juri::root() ?>/templates/<?php echo $this->template ?>/assets/img/avatars/49.jpg alt=@chadengle></p>
                            <p class=chat-name>Chad Engle <span class=chat-time>now</span></p>
                            <span class="status online"><i class=en-dot></i></span>
                            <p class=chat-txt>Thank you, have a nice day</p>
                        </li>
                    </ul>
                    <div class=chat-write>
                        <form action=# class=form-horizontal role=form>
                            <div class=form-group>
                                <textarea name=sendmsg id=sendMsg class="form-control elastic" rows=1></textarea>
                                <a role=button class=btn id=attach_photo_btn><i class="fa-picture s20"></i></a>
                                <input type=file name=attach_photo id=attach_photo>
                            </div>
                            <!-- End .form-group  -->
                        </form>
                    </div>
                </div>
            </div>
            <div class="sidebar-panel-content fullwidth pt0">
                <div class=chat-user-list>
                    <form class="form-horizontal chat-search" role=form>
                        <div class=form-group>
                            <input class=form-control placeholder="Search for user...">
                            <button type=submit><i class="ec-search s16"></i></button>
                        </div>
                        <!-- End .form-group  -->
                    </form>
                    <ul class="chat-ui bsAccordion">
                        <li><a href=#>Favorites <span class="notification teal">4</span><i class=en-arrow-down5></i></a>
                            <ul class=in>
                                <li><a href=# class=chat-name><img class=chat-avatar src=<?php echo Juri::root() ?>/templates/<?php echo $this->template ?>/assets/img/avatars/49.jpg alt=@chadengle>Chad Engle <span class=has-message><i class=im-pencil></i></span></a> <span class="status online"><i class=en-dot></i></span></li>
                                <li><a href=# class=chat-name><img class=chat-avatar src=<?php echo Juri::root() ?>/templates/<?php echo $this->template ?>/assets/img/avatars/54.jpg alt=@alagoon>Anthony Lagoon</a> <span class="status offline"><i class=en-dot></i></span></li>
                                <li><a href=# class=chat-name><img class=chat-avatar src=<?php echo Juri::root() ?>/templates/<?php echo $this->template ?>/assets/img/avatars/52.jpg alt=@koridhandy>Kory Handy</a> <span class=status><i class=en-dot></i></span></li>
                                <li><a href=# class=chat-name><img class=chat-avatar src=<?php echo Juri::root() ?>/templates/<?php echo $this->template ?>/assets/img/avatars/50.jpg alt=@divya>Divia Manyan</a> <span class=status><i class=en-dot></i></span></li>
                            </ul>
                        </li>
                        <li><a href=#>Online <span class="notification green">3</span><i class=en-arrow-down5></i></a>
                            <ul class=in>
                                <li><a href=# class=chat-name><img class=chat-avatar src=<?php echo Juri::root() ?>/templates/<?php echo $this->template ?>/assets/img/avatars/51.jpg alt=@kolage>Eric Hofman</a> <span class="status online"><i class=en-dot></i></span></li>
                                <li><a href=# class=chat-name><img class=chat-avatar src=<?php echo Juri::root() ?>/templates/<?php echo $this->template ?>/assets/img/avatars/55.jpg alt=@mikebeecham>Mike Beecham</a> <span class="status online"><i class=en-dot></i></span></li>
                                <li><a href=# class=chat-name><img class=chat-avatar src=<?php echo Juri::root() ?>/templates/<?php echo $this->template ?>/assets/img/avatars/53.jpg alt=@derekebradley>Darek Bradly</a> <span class="status online"><i class=en-dot></i></span></li>
                            </ul>
                        </li>
                        <li><a href=#>Offline <span class="notification red">5</span><i class=en-arrow-down5></i></a>
                            <ul>
                                <li><a href=# class=chat-name><img class=chat-avatar src=<?php echo Juri::root() ?>/templates/<?php echo $this->template ?>/assets/img/avatars/56.jpg alt=@laurengray>Lauren Grey</a> <span class="status offline"><i class=en-dot></i></span></li>
                                <li><a href=# class=chat-name><img class=chat-avatar src=<?php echo Juri::root() ?>/templates/<?php echo $this->template ?>/assets/img/avatars/49.jpg alt=@chadengle>Chad Engle</a> <span class="status offline"><i class=en-dot></i></span></li>
                                <li><a href=# class=chat-name><img class=chat-avatar src=<?php echo Juri::root() ?>/templates/<?php echo $this->template ?>/assets/img/avatars/58.jpg alt=@frankiefreesbie>Frankie Freesibie</a> <span class="status offline"><i class=en-dot></i></span></li>
                                <li><a href=# class=chat-name><img class=chat-avatar src=<?php echo Juri::root() ?>/templates/<?php echo $this->template ?>/assets/img/avatars/57.jpg alt=@joannefournier>Joane Fornier</a> <span class="status offline"><i class=en-dot></i></span></li>
                                <li><a href=# class=chat-name><img class=chat-avatar src=<?php echo Juri::root() ?>/templates/<?php echo $this->template ?>/assets/img/avatars/59.jpg alt=@aiiaiiaii>Alia Alien</a> <span class="status offline"><i class=en-dot></i></span></li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <div class=chat-box>
                    <h5>Chad Engle</h5>
                    <a id=close-user-chat href=# class="btn btn-xs btn-primary"><i class=en-arrow-left4></i></a>
                    <ul class="chat-ui chat-messages">
                        <li class=chat-user>
                            <p class=avatar><img src=<?php echo Juri::root() ?>/templates/<?php echo $this->template ?>/assets/img/avatars/49.jpg alt=@chadengle></p>
                            <p class=chat-name>Chad Engle <span class=chat-time>15 seconds ago</span></p>
                            <span class="status online"><i class=en-dot></i></span>
                            <p class=chat-txt>Hello Sugge check out the last order.</p>
                        </li>
                        <li class=chat-me>
                            <p class=avatar><img src=<?php echo Juri::root() ?>/templates/<?php echo $this->template ?>/assets/img/avatars/48.jpg alt=SuggeElson></p>
                            <p class=chat-name>SuggeElson <span class=chat-time>10 seconds ago</span></p>
                            <span class="status online"><i class=en-dot></i></span>
                            <p class=chat-txt>Ok i will check it out.</p>
                        </li>
                        <li class=chat-user>
                            <p class=avatar><img src=<?php echo Juri::root() ?>/templates/<?php echo $this->template ?>/assets/img/avatars/49.jpg alt=@chadengle></p>
                            <p class=chat-name>Chad Engle <span class=chat-time>now</span></p>
                            <span class="status online"><i class=en-dot></i></span>
                            <p class=chat-txt>Thank you, have a nice day</p>
                        </li>
                    </ul>
                    <div class=chat-write>
                        <form action=# class=form-horizontal role=form>
                            <div class=form-group>
                                <textarea name=sendmsg id=sendMsg class="form-control elastic" rows=1></textarea>
                                <a role=button class=btn id=attach_photo_btn><i class="fa-picture s20"></i></a>
                                <input type=file name=attach_photo id=attach_photo>
                            </div>
                            <!-- End .form-group  -->
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End .sidebar-inner -->
</div>
<?php
function create_html_list_right_side_bar($nodes,$menuItemIdActive)
{
    echo '<ul class="nav sub">';

    foreach ($nodes as $menu) {
        $childNodes = $menu->children;
        ob_start();


        ?>
    <li class="<?php echo $menuItemIdActive == $menu->id ? ' menu-active ' : '' ?>">
        <a
            href="javascript:void(0)"><i
                class="<?php echo $menu->icon ?>"></i> <?php echo $menu->title ?></a>
        <ul class="nav sub">
            <li><a onclick="Joomla.design_website.load_php_content(this,<?php echo $menu->id ?>)" href="javascript:void(0)"><i class="im-file3"></i> <?php echo $menu->title.'.php' ?></a> </li>
            <li><a href="javascript:void(0)"><i class="im-file-css"></i> <?php echo $menu->title.'.less' ?></a> </li>
            <li><a href="javascript:void(0)"><i class="en-bolt"></i> <?php echo $menu->title.'.js' ?></a> </li>
        </ul>
        <?php
        echo ob_get_clean();
        if (count($childNodes) > 0) {
            create_html_list_right_side_bar($childNodes, $menuItemIdActive);
        }
        echo "</li>";
    }
    echo '</ul>';
}

?>