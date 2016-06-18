package com.vantinviet.bho88.libraries.validator.validator;

import android.content.Context;
import android.graphics.drawable.Drawable;
import android.util.Patterns;

import com.vantinviet.bho88.R;
import com.vantinviet.bho88.libraries.validator.AbstractValidator;
import com.vantinviet.bho88.libraries.validator.ValidatorException;

import java.util.regex.Pattern;


/**
 * Validator to check if Phone number is correct.
 * Created by throrin19 on 13/06/13.
 */
public class PhoneValidator extends AbstractValidator {

    private static final Pattern PHONE_PATTERN = Patterns.PHONE;
    private static final int DEFAULT_ERROR_MESSAGE_RESOURCE = R.string.validator_phone;

    public PhoneValidator(Context c) {
        super(c, DEFAULT_ERROR_MESSAGE_RESOURCE);
    }

    public PhoneValidator(Context c, int errorMessageRes) {
        super(c, errorMessageRes);
    }

    public PhoneValidator(Context c, int errorMessageRes, Drawable errorDrawable) {
        super(c, errorMessageRes, errorDrawable);
    }

    @Override
    public boolean isValid(String phone) throws ValidatorException {
        return PHONE_PATTERN.matcher(phone).matches();
    }
}
