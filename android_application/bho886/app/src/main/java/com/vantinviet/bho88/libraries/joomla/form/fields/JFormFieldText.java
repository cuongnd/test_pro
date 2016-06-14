package com.vantinviet.bho88.libraries.joomla.form.fields;

import android.view.View;
import android.widget.LinearLayout;

import com.beardedhen.androidbootstrap.BootstrapEditText;
import com.beardedhen.androidbootstrap.BootstrapLabel;
import com.beardedhen.androidbootstrap.api.defaults.DefaultBootstrapBrand;
import com.vantinviet.bho88.libraries.joomla.form.JFormField;

/**
 * Created by cuongnd on 6/11/2016.
 */
public class JFormFieldText extends JFormField{
    public JFormFieldText(){

    }
    @Override

    protected View getInput() {
        LinearLayout linear_layout = new LinearLayout(context);
        BootstrapLabel label_text = new BootstrapLabel(context);
        label_text.setText(this.label);
        BootstrapEditText edit_text = new BootstrapEditText(context);
        edit_text.setBootstrapBrand(DefaultBootstrapBrand.PRIMARY);
        edit_text.setText(this.value);
        edit_text.setWidth(200);
        edit_text.setPadding(2,2,2,2);
        ((LinearLayout) linear_layout).addView(label_text);
        ((LinearLayout) linear_layout).addView(edit_text);
        linear_layout.setGravity(LinearLayout.TEXT_ALIGNMENT_GRAVITY);
        return (View)linear_layout;
    }

}
