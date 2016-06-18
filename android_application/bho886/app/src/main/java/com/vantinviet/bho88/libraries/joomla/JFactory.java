package com.vantinviet.bho88.libraries.joomla;

import android.content.Context;
import android.content.pm.ApplicationInfo;
import android.content.pm.PackageManager;

import com.vantinviet.bho88.configuration.JConfig;
import com.vantinviet.bho88.configuration.JConfig_countdown;
import com.vantinviet.bho88.libraries.cms.application.JApplicationSite;
import com.vantinviet.bho88.libraries.cms.menu.JMenu;
import com.vantinviet.bho88.libraries.joomla.session.JSession;
import com.vantinviet.bho88.libraries.joomla.uri.JUri;
import com.vantinviet.bho88.libraries.joomla.user.JUser;
import com.vantinviet.bho88.libraries.legacy.application.JApplication;
import com.vantinviet.bho88.libraries.legacy.request.JRequest;

/**
 * Created by cuongnd on 6/8/2016.
 */
public class JFactory {

    private static Object config;
    private static Context context;
    private static JApplication application;
    private static JUser user;
    public static String language;

    public static JMenu getMenu() {
        return JMenu.getInstance();
    }

    public static JUri getUri(String link) {
        return JUri.getInstance(link);

    }

    public static JConfig getConfig() {
        Context context= JFactory.getContext();
        String app_name= JFactory.getAppLable(context);
        JConfig config;
        switch (app_name) {
            case "countdown":
                config= JConfig_countdown.getInstance();
                break;
            default:
                config= JConfig.getInstance();
                break;
        }
        return config;
    }

    public static void setContext(Context context) {
        JFactory.context = context;
    }

    public static Context getContext() {
        return JFactory.context;
    }

    public static String getAppLable(Context context) {
        PackageManager packageManager = context.getPackageManager();
        ApplicationInfo applicationInfo = null;
        try {
            applicationInfo = packageManager.getApplicationInfo(context.getApplicationInfo().packageName, 0);
        } catch (final PackageManager.NameNotFoundException e) {
        }
        return (String) (applicationInfo != null ? packageManager.getApplicationLabel(applicationInfo) : "Unknown");
    }

    public static JRequest getRequest() {
        return JRequest.getInstance();
    }

    public static JApplication getApplication() {
        return JApplication.getInstance();
    }
    public static JApplicationSite getApplication(String client) {
        return JApplicationSite.getInstance(client);
    }
    public static JSession getSession() {
        return JSession.getInstance();
    }


    public static JUser getUser() {
        return JUser.getInstance();
    }
    public static JUser getUser(int id) {
        return JUser.getInstance(id);
    }
}
