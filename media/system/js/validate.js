var JFormValidator = function () {
    var $, handlers, inputEmail, custom, setHandler = function (name, fn, en) {
        en = en === "" ? true : en;
        handlers[name] = {enabled: en, exec: fn}
    }, findLabel = function (id, form) {
        var $label, $form = jQuery(form);
        if (!id) {
            return false
        }
        $label = $form.find("#" + id + "-lbl");
        if ($label.length) {
            return $label
        }
        $label = $form.find('label[for="' + id + '"]');
        if ($label.length) {
            return $label
        }
        return false
    }, handleResponse = function (state, $el) {
        var $label = $el.data("label");
        if (state === false) {
            $el.addClass("invalid").attr("aria-invalid", "true");
            if ($label) {
                $label.addClass("invalid").attr("aria-invalid", "true")
            }
        } else {
            $el.removeClass("invalid").attr("aria-invalid", "false");
            if ($label) {
                $label.removeClass("invalid").attr("aria-invalid", "false")
            }
        }
    }, validate = function (el) {
        var $el = jQuery(el), tagName, handler;
        if ($el.attr("disabled")) {
            handleResponse(true, $el);
            return true
        }
        if ($el.attr("required") || $el.hasClass("required")) {
            tagName = $el.prop("tagName").toLowerCase();
            if (tagName === "fieldset" && ($el.hasClass("radio") || $el.hasClass("checkboxes"))) {
                if (!$el.find("input:checked").length) {
                    handleResponse(false, $el);
                    return false
                }
            } else if (!$el.val() || $el.hasClass("placeholder") || $el.attr("type") === "checkbox" && !$el.is(":checked")) {
                handleResponse(false, $el);
                return false
            }
        }
        handler = $el.attr("class") && $el.attr("class").match(/validate-([a-zA-Z0-9\_\-]+)/) ? $el.attr("class").match(/validate-([a-zA-Z0-9\_\-]+)/)[1] : "";
        if (handler === "") {
            handleResponse(true, $el);
            return true
        }
        if (handler && handler !== "none" && handlers[handler] && $el.val()) {
            if (handlers[handler].exec($el.val()) !== true) {
                handleResponse(false, $el);
                return false
            }
        }
        handleResponse(true, $el);
        return true
    }, isValid = function (form) {
        var valid = true, i, message, errors, error, label;
        jQuery.each(jQuery(form).find("input, textarea, select, fieldset, button"), function (index, el) {
            if (validate(el) === false) {
                valid = false
            }
        });
        jQuery.each(custom, function (key, validator) {
            if (validator.exec() !== true) {
                valid = false
            }
        });
        if (!valid) {
            message = Joomla.JText._("JLIB_FORM_FIELD_INVALID");
            errors = jQuery("input.invalid, textarea.invalid, select.invalid, fieldset.invalid, button.invalid");
            error = {};
            error.error = [];
            for (i = 0; i < errors.length; i++) {
                label = jQuery("label[for=" + errors[i].id + "]").text();
                if (label !== "undefined") {
                    error.error[i] = message + label.replace("*", "")
                }
            }
            Joomla.renderMessages(error)
        }
        return valid
    }, attachToForm = function (form) {
        var inputFields = [];
        jQuery(form).find("input, textarea, select, fieldset, button").each(function () {
            var $el = $(this), id = $el.attr("id"), tagName = $el.prop("tagName").toLowerCase();
            if ($el.hasClass("required")) {
                $el.attr("aria-required", "true").attr("required", "required")
            }
            if ((tagName === "input" || tagName === "button") && $el.attr("type") === "submit") {
                if ($el.hasClass("validate")) {
                    $el.on("click", function () {
                        return isValid(form)
                    })
                }
            } else {
                if (tagName !== "fieldset") {
                    $el.on("blur", function () {
                        return validate(this)
                    });
                    if ($el.hasClass("validate-email") && inputEmail) {
                        $el.get(0).type = "email"
                    }
                }
                $el.data("label", findLabel(id, form));
                inputFields.push($el)
            }
        });
        $(form).data("inputfields", inputFields)
    }, initialize = function () {
        $ = jQuery.noConflict();
        handlers = {};
        custom = custom || {};
        inputEmail = function () {
            var input = document.createElement("input");
            input.setAttribute("type", "email");
            return input.type !== "text"
        }();
        setHandler("username", function (value) {
            regex = new RegExp("[<|>|\"|'|%|;|(|)|&]", "i");
            return !regex.test(value)
        });
        setHandler("password", function (value) {
            regex = /^\S[\S ]{2,98}\S$/;
            return regex.test(value)
        });
        setHandler("numeric", function (value) {
            regex = /^(\d|-)?(\d|,)*\.?\d*$/;
            return regex.test(value)
        });
        setHandler("email", function (value) {
            value = punycode.toASCII(value);
            regex = /^[a-zA-Z0-9.!#$%&‚Äô*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
            return regex.test(value)
        });
        jQuery("form.form-validate").each(function () {
            attachToForm(this)
        })
    };
    initialize();
    return {isValid: isValid, validate: validate, setHandler: setHandler, attachToForm: attachToForm, custom: custom}
};
document.formvalidator = null;
jQuery(function () {
    document.formvalidator = new JFormValidator
});