package com.vantinviet.vtv.libraries.validator.validator;

import android.content.Context;
import android.graphics.drawable.Drawable;
import android.text.TextUtils;

import com.vantinviet.vtv.R;
import com.vantinviet.vtv.libraries.validator.AbstractValidator;


public class NotEmptyValidator extends AbstractValidator {

    private static final int DEFAULT_ERROR_MESSAGE_RESOURCE = R.string.validator_empty;

    public NotEmptyValidator(Context c) {
        super(c, DEFAULT_ERROR_MESSAGE_RESOURCE);
    }

    public NotEmptyValidator(Context c, int errorMessage) {
        super(c, errorMessage);
    }

    public NotEmptyValidator(Context c, int errorMessageRes, Drawable errorDrawable) {
        super(c, errorMessageRes, errorDrawable);
    }

    @Override
    public boolean isValid(String text) {
        return !TextUtils.isEmpty(text);
    }
}
