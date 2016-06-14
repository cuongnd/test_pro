package com.vantinviet.bho88.libraries.joomla.form.fields;

import android.view.View;
import android.widget.Button;

import com.vantinviet.bho88.libraries.joomla.form.JFormField;

/**
 * Created by cuongnd on 6/11/2016.
 */
public class JFormFieldButton extends JFormField{
    public JFormFieldButton(){

    }
    @Override
    protected View getInput() {
        Button button= new Button(this.context);
        button.setText(this.label);
        button.setPadding(20, 10, 20, 10);
        button.setTextSize(23);
        return button;
    }

}
