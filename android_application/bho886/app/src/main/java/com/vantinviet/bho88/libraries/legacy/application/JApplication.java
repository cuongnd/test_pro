package com.vantinviet.bho88.libraries.legacy.application;

import com.vantinviet.bho88.libraries.cms.menu.JMenu;

/**
 * Created by cuongnd on 6/7/2016.
 */
public class JApplication {
    public JMenu getMenu() {
        JMenu menu = JMenu.getInstance( );
        return  menu;
    }
}
