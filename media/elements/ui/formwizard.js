jQuery(document).ready(function ($) {

    elementuiformwizard = {

        innitFormwizard: function () {


        },
        add_form_wizard: function (self) {

            object_id = self.closest('.properties.block').attr('data-object-id');
            formwizard = $('.form-wizard[data-block-id="' + object_id + '"]');
            ajaxInsertElement = $.ajax({
                type: "GET",
                url: this_host + '/index.php',

                data: (function () {

                    dataPost = {
                        option: 'com_utility',
                        task: 'utility.aJaxInsertElement',
                        parentColumnId: object_id,
                        menuItemActiveId: menuItemActiveId,
                        ajaxgetcontent: 1,
                        pathElement: 'media/elements/ui/formwizardcontent.php'

                    };
                    return dataPost;
                })(),
                beforeSend: function () {


                    // $('.loading').popup();
                },
                success: function (response) {

                    response = $.parseJSON(response);
                    html = $(response.html);
                    html = $(html);
                    block_id = response.blockId;
                    block_parent_id = html.attr('data-block-parent-id');
                    href = 'formwizard_content' + block_id;
                    html.attr('id', href);
                    li = $('<li role="presentation">' +
                    '<a href="javascript:void(0)" data-block-parent-id="' + block_parent_id + '" data-block-id="' + block_id + '" class="remove-formwizard-content "><i class="glyphicon-remove glyphicon "></i></a>' +
                    '<a href="javascript:void(0)" data-block-parent-id="' + block_parent_id + '" data-block-id="' + block_id + '" class="more-formwizard-content config-block"><i class="im-menu2 "></i></a>' +
                    '<a aria-controls="' + href + '" href="#' + href + '" role="formwizard" data-toggle="formwizard" >formwizard-content-' + block_id + '</a>' +
                    '</li>');

                    formwizard.find('.nav-FormWizard').append(li);
                    formwizard.find('.formwizard-content').append(html);
                    html.find('.row-content[data-block-parent-id="' + block_id + '"]').css({
                        display: "block"
                    });
                    html.find('.grid-stack[data-block-parent-id="' + block_id + '"]').gridstackDivRow(optionsGridIndex);

                }
            });
        },
        prev_formwizard_content: function (self) {
            form_wizard = self.closest('.element-form-wizard');
            block_id=form_wizard.attr('data-block-id');
            form_wizard_content_visible= $('.element-form-wizard-content[data-block-parent-id="'+block_id+'"]:visible');
            index_form_wizard_content_visible= form_wizard_content_visible.index();
            form_wizard_content_visible.hide();
            prev_form_wizard_content_visible=form_wizard_content_visible.prev();
            prev_form_wizard_content_visible.show();
            console.log(prev_form_wizard_content_visible.prev().length);
            $('.next-from-wizard-content[data-block-id="'+block_id+'"]').removeAttr('disabled');
            if(!prev_form_wizard_content_visible.prev().length)
           {
               self.attr('disabled','disabled');
           }
        },
        next_formwizard_content: function (self) {
            form_wizard = self.closest('.element-form-wizard');
            block_id=form_wizard.attr('data-block-id');
            form_wizard_content_visible= $('.element-form-wizard-content[data-block-parent-id="'+block_id+'"]:visible');
            index_form_wizard_content_visible= form_wizard_content_visible.index();
            form_wizard_content_visible.hide();
            next_form_wizard_content_visible=form_wizard_content_visible.next();
            next_form_wizard_content_visible.show();
            $('.prev-from-wizard-content[data-block-id="'+block_id+'"]').removeAttr('disabled');
            if(!next_form_wizard_content_visible.next().length)
            {
                self.attr('disabled','disabled');
            }
        },
        //self button delete
        remove_formwizard: function (self) {
            block_id = self.attr('data-block-id');
            block_parent_id = self.attr('data-block-parent-id');
            formwizard = $('.FormWizard[data-block-id="' + block_parent_id + '"]');
            ajaxInsertElement = $.ajax({
                type: "GET",
                url: this_host + '/index.php',
                data: (function () {

                    dataPost = {
                        option: 'com_utility',
                        task: 'utility.aJaxRemoveElement',
                        block_id: block_id

                    };
                    return dataPost;
                })(),
                beforeSend: function () {

                    // $('.loading').popup();
                },
                success: function (response) {
                    formwizard.find('.nav-FormWizard li[data-block-id="' + block_id + '"]').remove();
                    formwizard.find('.formwizard-content div.formwizard-pane[data-block-id="' + block_id + '"]').remove();


                }
            });
        }
    };
    //$('.formwizard_ui .remove-formwizard-content').click(function(){
    $(document).delegate(".formwizard_ui .remove-formwizard-content", "click", function (e) {
        elementuiformwizard.remove_formwizard($(this));
    });
    $(document).delegate(".prev-from-wizard-content", "click", function (e) {
        elementuiformwizard.prev_formwizard_content($(this));
    });
    $(document).delegate(".next-from-wizard-content", "click", function (e) {
        elementuiformwizard.next_formwizard_content($(this));
    });


});