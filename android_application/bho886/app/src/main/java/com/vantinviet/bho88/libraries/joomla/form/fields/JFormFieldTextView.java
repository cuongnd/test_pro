package com.vantinviet.bho88.libraries.joomla.form.fields;

import android.content.Context;
import android.view.View;
import android.widget.TextView;

import com.vantinviet.bho88.R;
import com.vantinviet.bho88.libraries.joomla.JFactory;
import com.vantinviet.bho88.libraries.joomla.application.JApplication;
import com.vantinviet.bho88.libraries.joomla.form.JFormField;

/**
 * Created by cuongnd on 6/11/2016.
 */
public class JFormFieldTextView extends JFormField {
    public Context context;

    @Override
    protected View getInput() {
        JApplication app= JFactory.getApplication();

        TextView textView = new TextView(app.context);
        textView.setText("sdfsdfsdfsd");
        textView.setPadding(20, 10, 20, 10);
        textView.setTextSize(23);
        return textView;
    }

}
