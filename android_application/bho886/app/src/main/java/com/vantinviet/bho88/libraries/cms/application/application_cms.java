package com.vantinviet.bho88.libraries.cms.application;

import android.content.Context;
import android.text.Html;
import android.text.Spanned;
import android.view.View;
import android.widget.EditText;
import android.widget.LinearLayout;
import android.widget.TextView;

import org.json.JSONException;


/**
 * Created by cuongnd on 12/17/2015.
 */
public class application_cms {
    public static Context main_context;

    public static void execute_component(final Context context, View linear_layout, String host, String content) {
        TextView myTextview = new TextView(context);
        Spanned sp = Html.fromHtml(content);
        myTextview.setText(sp);
        ((LinearLayout) linear_layout).addView(myTextview);
    }
}
