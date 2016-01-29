/**
 * @package     Joomla.Administrator
 * @subpackage  Templates.isis
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @since       3.0
 */

function jfbcMakePrettyRadioButtons() {
    jfbcJQuery('*[rel=tooltip]').tooltip()

    // Turn radios into btn-group
    jfbcJQuery('.radio.btn-group label').addClass('btn');
    jfbcJQuery(".btn-group label:not(.active)").click(function () {
        var label = jfbcJQuery(this);
        var input = jfbcJQuery('#' + label.attr('for'));

        if (!input.prop('checked')) {
            label.closest('.btn-group').find("label").removeClass('active btn-success btn-danger btn-primary');
            if (input.val() == '') {
                label.addClass('active btn-primary');
            } else if (input.val() == 0) {
                label.addClass('active btn-danger');
            } else {
                label.addClass('active btn-success');
            }
            input.prop('checked', true);
        }
    });
    jfbcJQuery(".btn-group input[checked=checked]").each(function () {
        var input = jfbcJQuery(this);

        if (input.prop('checked') == true) {
            if (jfbcJQuery(this).val() == '') {
                jfbcJQuery("label[for=" + jfbcJQuery(this).attr('id') + "]").addClass('active btn-primary');
            } else if (jfbcJQuery(this).val() == 0) {
                jfbcJQuery("label[for=" + jfbcJQuery(this).attr('id') + "]").addClass('active btn-danger');
            } else {
                jfbcJQuery("label[for=" + jfbcJQuery(this).attr('id') + "]").addClass('active btn-success');
            }
        }
    });
}