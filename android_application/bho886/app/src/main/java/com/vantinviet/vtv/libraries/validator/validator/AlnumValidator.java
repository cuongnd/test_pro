package com.vantinviet.vtv.libraries.validator.validator;

import android.content.Context;
import android.graphics.drawable.Drawable;
import android.text.TextUtils;

import com.vantinviet.vtv.R;
import com.vantinviet.vtv.libraries.validator.AbstractValidator;


/**
 * Validator to check if a field contains only numbers and letters.
 * Avoids having special characters like accents.
 */
public class AlnumValidator extends AbstractValidator {

    private static final int DEFAULT_ERROR_MESSAGE_RESOURCE = R.string.validator_alnum;

    public AlnumValidator(Context c) {
        super(c, DEFAULT_ERROR_MESSAGE_RESOURCE);
    }

    public AlnumValidator(Context c, int errorMessageRes) {
        super(c, errorMessageRes);
    }

    public AlnumValidator(Context c, int errorMessageRes, Drawable errorDrawable) {
        super(c, errorMessageRes, errorDrawable);
    }

    @Override
    public boolean isValid(String text) {
        return TextUtils.isDigitsOnly(text);
    }
}
