/**
* DISCLAIMER
*
* Do not edit or add to this file.
* You are not authorized to modify, copy or redistribute this file.
* Permissions are reserved by Satoshi Brasileiro.
*
*  @author    Satoshi Brasileiro
*  @copyright Satoshi Brasileiro 2021
*  @license   Single domain
*/

var Designer = {

    selector: null,

    image_type: ['image/png', 'image/jpg', 'image/jpeg'],

    runScript: function() {
        cpd_group.init();
        Designer.triggerActions();
    },

    triggerActions: function() {
        $(document)
        .on('click', '#cpd_add', cpd_group.getDesignPanel)
        .on('click', '#cpd_goto_premades', cpd_group.openPremades)
        .on('click', '#cpd_close_premades', cpd_group.closePremades)
        .on('click', '.cpd_select_image', Designer.setDesignImage)
        .on('click', '.image_up', Designer.triggerClick)
        .on('change', '.cpd_image_upload', Designer.changeImage)
        .on('click', '.cpd_image_layer_tag', cpd_group.addTag)
        .on('click', '.cpd_tag_layer_tag', cpd_group.addTag)
        .on('click', '.cpd_window_layer_tag', cpd_group.addWindowLayer)
        .on('click', '.cpd_remove_img', cpd_group.removeTag)
        .on('click', '.cpd_remove_workplace', cpd_group.removeWorkplace)
        .on('click', '.remove_layer', cpd_group.removeDesignLayer)
        .on('click', '.delete_cpd_template', cpd_group.removePremadeTemplate)
        .on('click', '.status_layer', cpd_group.changeDesignStatus)
        .on('click', '.design_title', cpd_group.updateDesignTitle)
        .on('click', '.tag_price', cpd_group.updateTag)
        .on('click', '.form-horizontal button[name=submitDesignTitle]', cpd_group.ajaxSubmitName)
        .on('click', '.form-horizontal button[name=submitUpdateTag]', cpd_group.ajaxUpdateTag)
    },

    triggerClick: function() {
        $(this).siblings('.cpd_image_upload').click();
    },

    setDesignImage: function(selector) {
        $(this).closest('.cpd_layer').find('.image_loader').show();
        $('.selected_image').hide();

        var img_container = $(selector.target);
        var id_image = img_container.data('id');
        var image_dir = img_container.data('dir');
        var cpd_image = $(this).closest('.cpd_layer').find('.cpd_layer_image');
       
        var data = {
            type: 'url',
            source: image_dir,
            action: 'saveDesignCover',
            id_design: $(this).data('id'),
        };
        cpd_image.attr('src', image_dir);
        img_container.siblings('.selected_image').show();
        //cpd_group.actionDraggable(cpd_group.dragable_wrapper + cpd_image.data('id'), '#cpd_design_preview_' + cpd_image.data('id'));
        //cpd_group.actionResizable(cpd_group.resizable_wrapper + cpd_image.data('id'), '#cpd_design_preview_' + cpd_image.data('id') + ', #' + cpd_image.attr('id'));
        // ajax save design cover image
        cpd_group.saveDesignCover(data);
        $(this).closest('.cpd_layer').find('.image_loader').fadeOut("slow");
    },

    changeImage: function(selector) {
        $(this).closest('.cpd_layer').find('.image_loader').show();
        var files = selector.target.files;
        var cpd_image = $(this).closest('.cpd_layer').find('.cpd_layer_image');

        var data = {
            type: 'base64',
            action: 'saveDesignCover',
            id_design: $(this).data('id'),
        };
        if (files && files[0]) {
            if (jQuery.inArray(files[0].type, Designer.image_type) === -1) {
                showErrorMessage(labels.image_type_error + labels.types);
            } else if (parseFloat(files[0].size) > allowed_sized) {
                showErrorMessage(labels.image_size_error);
            } else {
                var reader = new FileReader();
                reader.onload = function (e) {
                    data.source = e.target.result;
                    cpd_group.saveDesignCover(data);
                    cpd_image.attr('src', e.target.result);
                    cpd_group.actionResizable(cpd_group.resizable_wrapper + cpd_image.data('id'), '#cpd_design_preview_' + cpd_image.data('id') + ', #' + cpd_image.attr('id'));
                }
                reader.readAsDataURL(files[0]);
                cpd_group.actionDraggable(cpd_group.dragable_wrapper + cpd_image.data('id'), '#cpd_design_preview_' + cpd_image.data('id'));
                // ajax save design cover image
            }
        }
        $(this).closest('.cpd_layer').find('.image_loader').fadeOut("slow");
    },
};
