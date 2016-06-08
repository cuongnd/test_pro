package com.vantinviet.bho88.libraries.joomla.session;

/**
 * Created by cuongnd on 6/8/2016.
 */
public class session {
    private static session ourInstance = new session();

    public static session getInstance() {
        return ourInstance;
    }

    private session() {
    }
}
