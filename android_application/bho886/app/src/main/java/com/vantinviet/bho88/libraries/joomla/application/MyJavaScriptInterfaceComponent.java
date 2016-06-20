package com.vantinviet.bho88.libraries.joomla.application;

import android.content.Context;
import android.webkit.JavascriptInterface;

import com.vantinviet.bho88.libraries.joomla.JFactory;
import com.vantinviet.bho88.libraries.legacy.application.JApplication;

import java.util.regex.Matcher;
import java.util.regex.Pattern;

/**
 * Created by cuongnd on 6/20/2016.
 */
public class MyJavaScriptInterfaceComponent {
    private Context ctx;

    public MyJavaScriptInterfaceComponent(Context ctx) {
        this.ctx = ctx;
    }

    @JavascriptInterface
    public void showHTML(String html) {

        final Pattern pattern = Pattern.compile("<android_response>(.+?)</android_response>");
        final Matcher matcher = pattern.matcher(html);
        matcher.find();
        System.out.println("html:" + html);
        System.out.println(matcher.group(1)); // Prints String I want to extract
        html=matcher.group(1);
        System.out.println("html:"+html);
        JApplication app= JFactory.getApplication();
        app.execute_component(html);
    }
}
