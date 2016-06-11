package com.vantinviet.bho88.libraries.joomla.form;

import android.content.Context;
import android.view.View;
import android.widget.TextView;


import org.json.JSONObject;

/**
 * Created by cuongnd on 6/11/2016.
 */
public abstract class JFormField {
    protected String fieldName;
    protected String type;
    protected String label;
    protected JSONObject option;
    protected JSONObject value;
    protected String group;


    public View renderField(JSONObject option, String type, String fieldName, String group, String label) {
        this.fieldName=fieldName;
        this.type=type;
        this.label=label;
        this.option=option;
        this.group=group;
        JFormField field=getFormField(fieldName,group,option);
        View view_field=field.getInput();
        return view_field;
    }


    public static JFormField getFormField( String name, String group, JSONObject value) {
        return JForm.getField(name,group,value);
    }

    protected abstract View getInput();
}
