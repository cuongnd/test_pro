package com.vantinviet.bho88.libraries.cms.application;

import android.content.Context;
import android.text.Html;
import android.text.Spanned;
import android.view.View;
import android.widget.LinearLayout;
import android.widget.TextView;

import com.vantinviet.bho88.libraries.cms.menu.JMenu;
import com.vantinviet.bho88.libraries.legacy.JFactory;
import com.vantinviet.bho88.libraries.legacy.application.JApplication;

import org.json.JSONObject;


/**
 * Created by cuongnd on 12/17/2015.
 */
public class application_cms {
    public static Context main_context;

    public static void execute_component(final Context context, View linear_layout, String host, String content) {

        JApplication app=JFactory.getApplication();
        JMenu menu=JMenu.getInstance();
        JSONObject menu_active=menu.getMenuActive();
        System.out.println(menu_active);
        TextView myTextview = new TextView(context);
        Spanned sp = Html.fromHtml(content);
        myTextview.setText(sp);
        linear_layout.setPadding(10, 10, 10, 10);
        ((LinearLayout) linear_layout).addView(myTextview);
    }
}
