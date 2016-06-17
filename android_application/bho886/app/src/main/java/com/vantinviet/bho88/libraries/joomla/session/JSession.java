package com.vantinviet.bho88.libraries.joomla.session;

/**
 * Created by cuongnd on 6/8/2016.
 */
public  class JSession {
    private static JSession instance;
    private static String session_id;
    public static JSession getInstance() {
       return instance;
    }
    public  void setId(String session_id) {
        this.session_id = session_id;
    }

    public static String getId() {
        return session_id;
    }


}
