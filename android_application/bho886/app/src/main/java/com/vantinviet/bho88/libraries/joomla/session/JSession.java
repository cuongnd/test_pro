package com.vantinviet.bho88.libraries.joomla.session;

/**
 * Created by cuongnd on 6/8/2016.
 */
public  class JSession {
    private static JSession instance;
    public String android_ses_id ="";
    public static JSession getInstance() {
        if(instance==null)
        {
            instance=new JSession();
        }
       return instance;
    }
    public  void setId(String android_ses_id) {
        this.android_ses_id = android_ses_id;
    }

    public String getId() {
        return this.android_ses_id;
    }


}
