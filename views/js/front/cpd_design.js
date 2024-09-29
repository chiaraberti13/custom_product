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

$(document).ready(function() {
    $('input#cpd_dynamic_layer_count').val(0);
    $('textarea.cpd_tag').val('');
    $.fn.mColorPicker.defaults.imageFolder = cpd_img_path;
    initDesigner();
    initCpdLeftMenu();
    $( 'a.design_gallery' ).fancybox();
    //$('#cpd-tools-box-container').accordion({
    //    active:false,
    //    collapsible: true,
    //    icons:false,
    //    heightStyle: "content",
    //    header: '.DesignPanel > .DesignPanelTab',
    //});

    // tag rotation
    $( "#rotate" ).slider({
        value: 0,
        min: -20,
        max: 20,
        slide: function (event, ui) {
            var angle = ui.value;
            var prev_angle = $(this).attr('data-angle');
            var selectedTag = $('#cpd_layers_section_content li.selected');
            //console.log(ui);
            //if (typeof selectedTag[0] !== 'undefined' && selectedTag[0]) {
                var id = $( selectedTag ).attr( 'data-id-tag' );
                var type = $( selectedTag ).attr( 'data-type' );
                var id_design = $( selectedTag ).attr( 'data-id-design' );
                var parent = $( '#cpd_layer_top_' + id_design );
                if ( type == 'image' || type == 'text' ) {
                    var idSelector = '#tag_' + type + '_' + id;
                    
                    //select editing side preview
                    if ($( '#mini_thumb_' + id_design ).hasClass( 'selected' ) == false) {
                        $( '#mini_thumb_' + id_design ).trigger('click');   
                    }
                    if (angle > prev_angle || (angle < 0 && prev_angle < 0 && Math.abs(prev_angle) > Math.abs(angle))) {
                        rotateClockwise( idSelector, angle );
                    } else {
                        rotateAnticlockwise( idSelector, angle );
                    }
                    $(this).attr('data-angle', angle);
                }
            //}
        }
    });

    $('#materialDropdown').ddslick({
        width: 200,
        data: ['price'],
        imagePosition: "left",
        selectText: printmaterial_label,
        onSelected: function(data) {
            if (data) {
                var __cpd_material_val = data.selectedData.value;
                $('#cpd_material_active').val(__cpd_material_val);
                $( '#selected_print_material' ).val( __cpd_material_val );
                var id = $( '#cpd-parts-bar' ).find( '.selected' ).data( 'id' );
                $('#cpd_material_' + id).val( data.selectedData.value ).attr( 'data-price', data.selectedData.price );
                cpd_update_price();
            }
        }   
    });
    initTouch();
    $(".cpd_layers_section_sortable").sortable({
        opacity: 0.6,
        cursor: "move",
        update: function() { cpdSortZindex();}
    });
});

// tools icon
$(document).on( 'click', '.setting_tag, .layer_tag', function(e) {
    openLayerPanel($(this).attr('data-id-tag'), $(this).attr('data-type'));
    //$('#cpd_toolset').html('');
    //if ($('#main-design').find('.tooltipster-base').length) {
    //    $('.tooltipster-base').tooltipster().tooltipster('destroy');
    //}
    getToolBox('#' + $(this).attr('id'), $(this).attr('data-type'));
});

$(document).on('click', '.my-designs', function(e) {
    var id = $(this).data('cpd');
    if ($('#mini_thumb_' + id).hasClass('selected') == false) {
        $('#mini_thumb_' + id).trigger('click');
    }
});

// handle ajax url image upload
$(document).on('click', '#logo-upload-url button[name=upload_url_image]', ajaxUploadImage);

// trigger generating design preview
$(document).on('click', 'button#preview-design', generate_design_preview);

// add designs to design tab
$(document).on('click', 'button.add_more_design', add_design);

// trigger add to cart button
$(document).on('click', '#add_custom_product_to_cart', addDesignsToCart);

// close tooltip
$(document).on('click', '.close-tooltip', function(){
    $('.tooltipstered').tooltipster('close');
});

$(document).on('click', '.cpd_attribute_radio', function() {
    $('.cpd_attribute_selection').removeClass('selected_attribute');
    if ($( this ).is(':checked')) {
        $(this).closest('.cpd_attribute_selection').addClass('selected_attribute');
    }
});

$(document).on('change', '.cpd_attribute_select', function(e) {
    cpdCombination();
});

$(document).on('click', '.cpd_attribute_radio', function(e) {
    cpdCombination();
});

// customization button
$(document).on('click', '#customize_product', function() {
    $('.cpd_main_loader').fadeIn(100);
});

// click on left tags
$(document).on( 'click', '.cpd_ui_layer', function() {
	$('.cpd_ui_layer').removeClass('selected');
    $('.tags').removeClass('cpd_marked');
	$(this).addClass( 'selected' );
    $( '#cpd_tag_' + $(this).attr('data-id-tag') ).addClass('cpd_marked');
    //getToolBox('#setting-tag-' + $(this).attr('data-id-tag'), $('#setting-tag-' + $(this).attr('data-id-tag')).attr('data-type'));
    if ( $(this).attr('data-type') == 'image' ) {
        $( '.logo_container' ).find( '.selected_logo' ).trigger( 'click' );
    }
    cpd_update_price();
    if ( $(this).attr('data-type') == 'text' ) {
        setTimeout( function() {
            $('.cpd_ui_layer.selected textarea').focus();
        }, 1000 );
    }
});

// focus in tags
$(document).on( 'mouseenter, mouseover', '.tags', function() {
    var id_tag = $(this).data('id-tag');
    $( this ).css( 'z-index', 999);
    $( this ).addClass('cpd_marked');
    
    //mobile devices fix for resizable
    var __viewport_width = $(window).width();
    __viewport_width = parseInt(__viewport_width);
    
    if ($( this ).find( '.cpd_icon' ).children().length > 0) {
        $( this ).find( '.rotation_wrapper' ).show();
        $( this ).find( '.bottom_right').show();
    } else {
        $( this ).find( '.rotation_wrapper' ).hide();
        $( this ).find( '.bottom_right').hide();
    }
    
    if (typeof id_tag !== 'undefined' && id_tag) {
        if ($(this).attr('data-draggable') == 1) {
            $('#cpd_tag_' + id_tag).draggable({
                containment: 'parent',
                scroll: false,
                cursor: 'move',
                start: function (event, ui) {
                    if (__viewport_width <= 580) {
                        $('body').css('overflow','hidden');
                    }
                },
                stop: function (event, ui) {
                    var parent  = $(this).parent();
                    var left    = parseFloat($(this).css('left'))/parent.width()*100;
                    var top     = parseFloat($(this).css('top'))/parent.height()*100;

                    $(this).css({
                        left: left + "%",
                        top: top + "%",
                        position: 'absolute'
                    });
                    if (__viewport_width <= 580) {
                        $('body').css('overflow','auto');
                    }
                }
            });
        }
        
        if ($(this).attr('data-resizable') == 1) {
            options = {
                autoHide: true,
                containment: 'parent',
                alsoResize: $('#cpd_tag_' + id_tag).find('#tag_image_' + id_tag),
                handles: "se",
                /*handles: {
                    se: '.bottom_right',
                },*/
                stop: function(e, ui) {
                    var parent  = ui.element.parent();
                    var width   = ui.size.width/parent.width()*100;
                    var height  = ui.size.height/parent.height()*100;
                    var left    = parseFloat(ui.element.css('left'))/parent.width()*100;
                    var top     = parseFloat(ui.element.css('top'))/parent.height()*100;

                    ui.element.css({
                        left: left + "%",
                        top: top + "%",
                        width: width + "%",
                        height: height + "%",
                        position: 'absolute'
                   });
                    $('.ui-resizable-se').hide();
                }
            };
            $( '#cpd_tag_' + id_tag ).resizable(options);
        }
        //Fix to disable resizable
        if ($(this).attr('data-resizable') <= 0) {
            options = {
                autoHide: true,
                containment: 'parent',
                alsoResize: $('#cpd_tag_' + id_tag).find('#tag_image_' + id_tag),
                handles: "se",
                disabled: true,
                /*handles: {
                    se: '.bottom_right',
                },*/
                stop: function(e, ui) {
                    var parent  = ui.element.parent();
                    var width   = ui.size.width/parent.width()*100;
                    var height  = ui.size.height/parent.height()*100;
                    var left    = parseFloat(ui.element.css('left'))/parent.width()*100;
                    var top     = parseFloat(ui.element.css('top'))/parent.height()*100;

                    ui.element.css({
                        left: left + "%",
                        top: top + "%",
                        width: width + "%",
                        height: height + "%",
                        position: 'absolute'
                   });
                    $('.ui-resizable-se').hide();
                }
            };
            $( '#cpd_tag_' + id_tag ).resizable(options);
        }
    }
});

// fouc out tags
$(document).on( 'mouseleave, mouseout', '.tags', function() {
    $( this ).find( '.rotation_wrapper' ).hide();
    $( this ).find( '.bottom_right').hide();
    $( this ).css( 'z-index', '');
    $( this ).removeClass('cpd_marked');
});

// click on design tags
$( document ).on( 'click' , '.tags' , function() {
    var id_tag = $(this).data('id-tag');
    $( this ).find( '.bottom_right').show();
    $('.tags').removeClass('cpd_marked');
    if (typeof id_tag !== 'undefined' && id_tag) {
        //if ($('#left-tag-' + id_tag).closest( '.DesignPanelContent' ).prev('.DesignPanelTab').hasClass('ui-accordion-header-active ui-state-active ui-corner-top') === false) {
        //    $('#left-tag-' + id_tag).closest( '.DesignPanelContent' ).prev('.DesignPanelTab').trigger('click');
        //}
        $('#cpd_layers_section_content li#cpd_ui_layer_' + id_tag).trigger( 'click' ).focus();
        $('#cpd_tag_' + id_tag ).addClass('cpd_marked');
        
        //mobile devices fix for resizable
        var __viewport_width = $(window).width();
        __viewport_width = parseInt(__viewport_width);
        if (__viewport_width <= 580) {
            $(this).find('.ui-resizable-se').show();
        }
    }
    cpd_update_price();
});

//start typing on text tags
$( document ).on( 'keyup', '.cpd_tag', function() {
    var id = $( this ).data( 'id' );
    if (!id || id <= 0) {
        var id = $( this ).attr('data-id').val();
    }
    var type = $( this ).data( 'type' );
    var id_design = $( this ).data( 'design' );
    var parent = $( '#cpd_layer_top_' + id_design );
    if ( type == 'text' ) {
        var innerText = $.trim( $( this ).val() );
        var price = ( innerText && innerText != '' )? parseFloat($( parent ).find( '#cpd_tag_' + id ).data('price')) : 0;
        var newTag = createTextTag( id, innerText, price );
        
        //select editing side preview
        if ($( '#mini_thumb_' + id_design ).hasClass( 'selected' ) == false) {
            $( '#mini_thumb_' + id_design ).trigger('click');   
        }

        //busy loader
        if ($('.cpd_busy').css('display') == 'none') {
            $('.cpd_busy').fadeIn();
        }

        setTimeout( function() {
            $( parent ).find( '#cpd_tag_' + id ).children('.cpd_icon').html( newTag );
            $('.cpd_busy').fadeOut();
            cpd_update_price();
            if ($(".cpd_layers_section_sortable").length) {
                var _innerText_count = parseInt(innerText.length);
                if (_innerText_count <= 15) {
                    $('.cpd_layers_section_sortable li#cpd_ui_layer_'+id+' span').text(innerText);
                }
            }
        }, 500 );
    }
});

$(document).on('click', '#browse_logo', function(){
    $('#logo').trigger('click');
});

// on selecting image for image tag
$(document).on( 'click', '.logos', function() {
    var src = $(this).attr('src');
    var selectedImg = $('#cpd_layers_section_content li.selected');
    
    if ($(this).hasClass( 'selected_logo' )) {
        $(this).removeClass( 'selected_logo' );
        $(this).parent().removeClass( 'buttony' );
    } else {
        $( '.logo_container' ).removeClass( 'buttony' );
        $( '.logos' ).removeClass( 'selected_logo' );
        $(this).addClass( 'selected_logo' );
        $(this).parent().addClass( 'buttony' );
    }

    //if (typeof selectedImg[0] !== 'undefined' && selectedImg[0]) {
        var id = $(selectedImg).attr('data-id-tag');
        var type = $(selectedImg).attr('data-type');
        var id_design = $(selectedImg).attr('data-id-design');
        var parent = $('#cpd_layer_top_' + id_design);
        var price = parseFloat(parent.find('#cpd_tag_' + id ).attr('data-price'));
        if (type == 'image') {
            if(src && src != '') {
                var imgTag = createImageTag( id, src, price);

                //select editing side preview
                if ($('#mini_thumb_' + id_design).hasClass( 'selected' ) == false) {
                    $('#mini_thumb_' + id_design).trigger('click');   
                }

                //busy loader
                if ($('.cpd_busy').css('display') == 'none') {
                    $('.cpd_busy').fadeIn();
                }
//console.log('ID: '+id+' IDD: '+id_design);
                setTimeout( function() {
                    parent.find('#cpd_tag_' + id).children('.cpd_icon').html(imgTag);
                    $('.cpd_busy').fadeOut();
                    cpd_update_price();
                }, 500 );
            }
        }
   // }
});

// font slection
$(document).on( 'change', '.font_select', function() {
    var selector = $('#cpd_layers_section_content li.selected');
    var id = $(selector).attr('data-id-tag');
    var type = $(selector).attr('data-type');
    var id_design = $(selector).attr('data-id-design');
    var parent = $( '#cpd_layer_top_' + id_design );
    if ( type == 'text' ) {
        var fonts = $( this ).val();
        var innerText = $.trim($( selector ).val());
        if( innerText && innerText != '' ) {
            
            //select editing side preview
            if ($( '#mini_thumb_' + id_design ).hasClass( 'selected' ) == false) {
                $( '#mini_thumb_' + id_design ).trigger('click');   
            }

            //busy loader
            if ($('.cpd_busy').css('display') == 'none') {
                $('.cpd_busy').fadeIn();
            }

            setTimeout( function() {
                $( parent ).find( '#tag_text_' + id ).css( 'font-family', fonts );
                $('.cpd_busy').fadeOut();
            }, 300 );
        }
    }
});

//text color
$(document).on( 'click', '.set_colour', function() {
    setColor($(this));
    $( '.normal' ).each(function() {
        $( this ).removeClass( 'highlighted' );
    });
    $( this ).find( '.normal' ).addClass( 'highlighted' );
});

// text alignment
$(document).on( 'click', '.cpd_align', function() {
    var selector = $('#cpd_layers_section_content li.selected');
    var id = $(selector).attr('data-id-tag');
    var type = $(selector).attr('data-type');
    var id_design = $(selector).attr('data-id-design');
    var parent = $( '#cpd_layer_top_' + id_design );
    
    if ( type == 'text' ) {
        $('.cpd_align').removeClass('selected_align');
        var alignment = $( this ).data( 'align' );
        var innerText = $.trim($(selector).val());
        if( innerText && innerText != '' ) {
            
            //select editing side preview
            if ($( '#mini_thumb_' + id_design ).hasClass('selected') == false) {
                $( '#mini_thumb_' + id_design ).trigger('click');   
            }

            //busy loader
            if ($('.cpd_busy').css('display') == 'none') {
                $('.cpd_busy').fadeIn();
            }

            $(this).addClass('selected_align');
            setTimeout( function() {
                $( parent ).find( '#tag_text_' + id ).css( 'text-align', alignment );
                $('.cpd_busy').fadeOut();
            }, 300 );
        }
    }
});

// text style
$(document).on( 'click', '.cpd_fonts_style', function() {
    var selector = $('#cpd_layers_section_content li.selected');
    var id = $(selector).attr('data-id-tag');
    var type = $( selector ).attr('data-type');
    var id_design = $( selector ).attr('data-id-design');
    var parent = $( '#cpd_layer_top_' + id_design );
    
    if ( type == 'text' ) {
        var style = $( this ).data( 'style' );
        var checkbox = '#' + $( this ).data( 'parent' );

        var innerText = $.trim($( selector ).val());
        if( innerText && innerText != '' ) {
            
            //select editing side preview
            if ($( '#mini_thumb_' + id_design ).hasClass( 'selected' ) == false) {
                $( '#mini_thumb_' + id_design ).trigger('click');   
            }

            //busy loader
            if ($('.cpd_busy').css('display') == 'none') {
                $('.cpd_busy').fadeIn();
            }

            if ($( checkbox ).is(':checked') == false) {
                $(this).addClass('selected_tstyle');
            } else {
                $(this).removeClass('selected_tstyle');
            }
            setTimeout( function() {
                var type = $.trim( $( checkbox ).val() );
                if ($( checkbox ).is(':checked') == false) {
                    if ( type == 'underline') {
                        style = 'none';
                    } else {
                        style = 'normal';
                    }
                }
                switch ( type ) {
                    case 'bold':
                        $( parent ).find( '#tag_text_' + id ).css( 'font-weight', style );
                        break;
                    case 'italic':
                        $( parent ).find( '#tag_text_' + id ).css( 'font-style', style );
                        break;
                    case 'underline':
                        $( parent ).find( '#tag_text_' + id ).css( 'text-decoration', style );
                        break;
                }
                $('.cpd_busy').fadeOut();
            }, 300 );
        }
    }
});

// on click mini preview design images
$(document).on('click', '.mini_preview', function() {
    var id_design = $(this).data('id');
    $('ul.cpd_layers_section_sortable').hide();
    //display loader
    if ($('.working_loader').css('display') == 'none') {
        $('.working_loader').fadeIn();
    }

    // cpd_marked as selected
    $('.mini_preview').removeClass('selected');
    $(this).addClass('selected');

    if (typeof id_design !== 'undefined' && id_design) {
        $('ul#cpd_layers_section_sortable_'+id_design).show();
        //if ($('#my-designs-' + id_design).hasClass('ui-accordion-header-active ui-state-active ui-corner-top') == false) {
        //    $('#my-designs-' + id_design).trigger('click');
        //}
    }
    //show selected deisgn image
    setTimeout(function() {
        $('.working_loader').fadeOut();
        $('.design_container').hide();
        $('#design-view-' + id_design).show();
        cpd_update_price();
        $('#cpd_left_basic_nav ul li a.cpd_origin_text').click();
    }, 1000);
});

// set inverse curve direction
$(document).on( 'click', '#direction', function() {
    var selectedTag = $('#cpd_layers_section_content li.selected');
    if (typeof selectedTag[0] !== 'undefined' && selectedTag[0]) {
        var id = $(selectedTag).attr('data-id-tag');
        var type = $(selectedTag).attr('data-type');
        var id_design = $(selectedTag).attr('data-id-design');
        var parent = $( '#cpd_layer_top_' + id_design );
        if ( type == 'text' ) {
            var idSelector = '#tag_' + type + '_' + id;
        
            //select editing side preview
            if ($( '#mini_thumb_' + id_design ).hasClass( 'selected' ) == false) {
                $( '#mini_thumb_' + id_design ).trigger('click');
            }
            
            var direction = 1;
            var radius = $(this).attr('data-angle');
           if ($( '#direction' ).is(':checked')) {
                direction = -1;
            }
            $(idSelector).show().arctext({radius: radius});
            $(idSelector).arctext('set', {
                radius      : radius, 
                dir         : direction,
                animation   : {
                    speed   : 300,
                    easing  : 'ease-out'
                }
            });
        }
    }
});

// getting from for url image upload
$(document).on('click', '.link', function(event) {
    event.preventDefault();
    urlImage();
});

//delete tag
$(document).on( 'click', '.delete_tag', function() {
    var idTag = $( this ).data( 'tag' );
    idTag = idTag.replace ( /[^\d.]/g, '' );
    idTag = parseInt(idTag);
    //console.log('Delete this '+idTag);
    if (typeof idTag !== 'undefined' && idTag) {
        //var tag = $('#cpd_tag_' + idTag);
        var callback = function () {
            $('#design_preview').find('#cpd_tag_' + idTag).remove();
            $('.cpd_layers_section_sortable').find('#cpd_ui_layer_' + idTag).remove();
            $('#cpd_img_txt_blox_content').find('li#cpd_layer_' + idTag).remove();
            $('#cpd_left_basic_nav ul li a:first').click();
            setTimeout(function() {
                cpd_update_price();
            }, 500);
        };
        // confirmation
        alertAction(delete_label, delete_para, callback);
    }
});


// delete design from list
$(document).on('click', '.deleteDesign', function() {
    // confirmation
    var tag = '#' + $(this).data('id');
    var callback = function () {
       if ($(tag).remove()) {
            $('#design_content')
            .append('<div class="alert alert-success cpd_glow"> '+ delete_design_label +' </div>')
            .find('.cpd_glow')
            .fadeOut(6000, function() {
                $(this).remove();
            });
            var __children = $( '#selected-combinations' ).children().length;
            if (__children <= 0) {
                $('.cpd_abys').show();
            }
        }
    };
    alertAction(delete_label, delete_para, callback);
});

/*
* Reset customization
*/
$(document).on('click', '#reset-name-num', clear_customization);

/*
* Cancal customizarion
*/
$(document).on('click', '#cancel_customize_product', cancel_customization);

//print PDF
$(document).on('click', '.printPDF', function() {
    var text = watermark.text
    var design = $('#' + $(this).data('id')).find('.design_gallery_image');
    var imgData = design.attr('src');
    var file_name = 'custom_design_' + Date.now();
    var image = getImage(imgData);

    if (typeof cpd_watermark_active !== 'undefined' && cpd_watermark_active) {
        //watercpd_markeding
        var watermark_text = function(target) {
            if (typeof cpd_watermark_size == 'undefined' || !isNaN(cpd_watermark_size)) {
                cpd_watermark_size = 50;
            }
            if (typeof cpd_watermark_textfonts == 'undefined' || $.trim(cpd_watermark_textfonts) == '') {
                cpd_watermark_textfonts = 'Josefin Slab';
            }
            if (typeof cpd_watermark_textclr == 'undefined' || $.trim(cpd_watermark_textclr) == '') {
                cpd_watermark_textclr = '#000';
            }

            var context = target.getContext('2d');
            var text = cpd_watermark_text;
            var metrics = context.measureText(text);

            var x = (target.width / 2) - (metrics.width + 100);
            var y = (target.height / 2) + 48 * 2;

            context.translate(x, y);
            context.globalAlpha = 0.5;
            context.fillStyle = cpd_watermark_textclr;
            context.font =  cpd_watermark_size + 'px Josefin Slab';
            context.rotate(-45 * Math.PI / 180);
            context.fillText(text, 0, 0);
            return target;
        };
        watermark([imgData])
        .image(watermark_text)
        .then(function (img) {
            // create PDF
            createPDFObject(img, file_name, image.width, image.height, 'jpg', 'fast');
        });
    } else {
        createPDFObject(imgData, file_name, image.width, image.height, 'jpg', 'fast');
    }
});

function initDesigner() {
    var mode = getUrlParameter('cpd_mode');
    if (typeof mode !== 'undefined' && mode && mode === 'designer') {
        $('body').prepend('<div id="customization-wrapper"><div class="cpd_main_loader"></div></div>');
        $('body').fadeOut(1500, function() {
            if ($('#customization-wrapper').next().is('main')) {
                $('#customization-wrapper').next().fadeOut();//hide the other elements container
            }
            else {
                $('#customization-wrapper').siblings('main').fadeOut();//find and hide the other elements container
            }
            $('#personalization').appendTo('#customization-wrapper').fadeIn();
            $('body').fadeIn();
            $('.tag_text').css('color', DEFAULT_CUSTOM_COLOR).css('font-family', DEFAULT_CUSTOM_FONT);
            $('.cpd_main_loader').fadeOut();
        });
        $('#cpd_layers_section_content ul.cpd_layers_section_sortable').first().show();
    }
}

function getUrlParameter(name) {
    name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
    var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
    var results = regex.exec(location.search);
    return (results === null)? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
}

/** create <p> tag **/
function createTextTag(id, value, price) {
    var css = getTagCss(id);
    return text = $('<p>', {
        class: 'tag_text design',
        text: value,
        id: 'tag_text_' + id
    })
    .attr('style', css)
    .css('word-wrap', 'break-word')
    .css('white-space', 'pre-wrap')
    .attr('data-price', parseFloat(price))
    .attr('data-size', $('#tag_text_' + id).css('font-size').replace('px',''));
}

/** create <img> tag **/
function createImageTag(id, value, price) {
    var width = $('#cpd_tag_' + id)[0].style.width;
    var height = $('#cpd_tag_' + id)[0].style.height;
    if (typeof width === 'undefined' || !width) {
        width = '100%';
    }
    return text = $('<img />', {
        class: 'tag_image design',
        id: 'tag_image_' + id,
        src: value,
        width: '100%',
        height: '100%',
    }).attr('data-price', parseFloat(price));
}

function setFonts(size) {
    var selector = $('#cpd_layers_section_content li.selected');
    var id = $(selector).attr('data-id-tag');
    var type = $(selector).attr('data-type');
    var id_design = $(selector).attr('data-id-design');
    var parent = $( '#cpd_layer_top_' + id_design );
    if ( type == 'text' ) {
        var current_size = parseFloat( $( parent ).find( '#tag_text_' + id ).css( 'font-size') );

        if (typeof current_size === 'undefined' || current_size < 8) {
            current_size = 8;
        }

        current_size = size;
        var innerText = $.trim($( selector ).val());
        if( innerText && innerText != '' ) {
            //select editing side preview
            if ($( '#mini_thumb_' + id_design ).hasClass( 'selected' ) == false) {
                $( '#mini_thumb_' + id_design ).trigger('click');   
            }

            //busy loader
            if ($('.cpd_busy').css('display') == 'none') {
                $('.cpd_busy').fadeIn();
            }
            setTimeout( function() {
                $( parent ).find( '#tag_text_' + id ).css( 'font-size', current_size + 'px' );
                $('#tag_text_' + id).attr('data-size', current_size);
                $('.cpd_busy').fadeOut();
            }, 300 );
        }
    }
}
// setting up fonts color
function setColor(thisObject) {
    var selector = $('#cpd_layers_section_content li.selected');
    var id = $(selector).attr('data-id-tag');
    var type = $(selector).attr('data-type');
    var id_design = $(selector).attr('data-id-design');
    var parent = $( '#cpd_layer_top_' + id_design );

    if ( type == 'text' ) {
        var innerText = $.trim($( selector ).val());
        if( innerText && innerText != '' ) {
            
            //select editing side preview
            if ($( '#mini_thumb_' + id_design ).hasClass( 'selected' ) == false) {
                $( '#mini_thumb_' + id_design ).trigger('click');   
            }

            //busy loader
            if ($('.cpd_busy').css('display') == 'none') {
                $('.cpd_busy').fadeIn();
            }

            setTimeout( function() {
                var color = $( thisObject ).data('colorcode');
                if (color == '') {
                    color = $( thisObject ).attr('data-colorcode');
                }
                $( parent ).find( '#tag_text_' + id ).css( 'color', color );
                $('.cpd_busy').fadeOut();
            }, 300 );
        }
    }
}

// get url image upload form
function urlImage() {
    var id = 'uploads-panel_' + Date.now();
    var data = {
        action: 'getURLForm',
        id_product: parseInt($('#cpd_id_product').val()),
    };
    $('#url-image').qtip({
            id: id,
            content: {
                text: function(event, api) {
                    var ajaxData = {
                        url: design_handler,
                        data: data,
                        dataType: 'json',
                        type: 'get',
                        success: function(jsonData) {
                            if (jsonData.hasError) {
                                api.set('content.text', error_label + ': ' + jsonData.msg);
                            } else {
                                api.set('content.text', jsonData.html);
                            }
                        },
                        error: function(xhr, status, error) {
                            api.set('content.text', status + ': ' + error);
                        }

                    }
                    processAjax(ajaxData);
                    return loading_label;
                },
                title: {
                    button: true
                }
            },
            show: {
                event: 'click',
                modal: {
                    on: true,
                    blur: false,
                    escape: true,
                }
            },
            hide: false,
            position: {
                at: 'center center',
                my: 'center top',
                target: $('#url-image'),
                container: $('#custom-design-center-column'),
                adjust: {
                    mouse: true,
                    scroll: true
                }
            },
            style: { classes: 'qtip-bootstrap qtip_group_form'},
            events: {
                show: function(event, api) {
                    $( '#cpd_loader' ).addClass( 'cpd_fade' );
                    $('html, body').animate({
                        scrollTop: $('#' + $('body').attr('id')).offset().top
                    }, 10);
                },
                hide: function(event, api) {
                    $( '#cpd_loader' ).removeClass( 'cpd_fade' );
                    $( '#alert-1' ).show();
                }
            }
        }).qtip('show');
    return false;
}

function getToolBox(selector, tag) {
    var _toolset_inst = $('#cpd_toolset');
    var id = 'toolBox_' + Date.now();
    var id_tag = $(selector).attr('data-tag');
    var id_design = $(selector).attr('data-design');
    var data = {
        action: 'getToolBox',
        type: tag,
        id_tag: id_tag,
        id_product: parseInt($('#cpd_id_product').val()),
    };
    var ajaxData = {
                    url: design_handler,
                    data: data,
                    dataType: 'json',
                    type: 'get',
                    success: function(response) {
                        //console.log(response);
                        if (response.hasError) {
                            _toolset_inst.html(error_label + ': ' + response.msg);
                            //$origin.data('loaded', true);
                        } else {
                            _toolset_inst.html(response.html);
                            //$origin.data('loaded', true);
                        }
                    },
                    error: function(xhr, status, error) {
                        _toolset_inst.html(status + ': ' + error);
                        //$origin.data('loaded', true);
                        //console.log(xhr);
                    }
                };
                processAjax(ajaxData);
    //$(selector)
    //.tooltipster(options)
    //.tooltipster('open', function(instance, helper) {
    //    var selectedTag = $( '#cpd_layers_section_content li.selected' );
    //    var id = $(selectedTag).attr('data-id-tag');
    //    var type = $(selectedTag).attr('data-type');
    //    var id_design = $(selectedTag).attr('data-id-design');
    //    //initSlider();
    //    //console.log('Tag: '+id+' Type: '+type+' Design '+id_design+' Opac: '+helper);
    //    $('.tooltipster-base').draggable({
    //        containment: '#main-design',
    //        handle: '.drag-toolbox',
    //    });
    //});
}

$(document).on('submit', 'form#logo-upload-front', function(e) {
    e.preventDefault();
    e.stopImmediatePropagation();

    $('#upload_logo_front').find('.alert').hide();
    $('.mini_loader').fadeIn();

    // submitting form data via ajax
    var formObj = $(this);
    var formURL = formObj.attr("action");
    if(window.FormData !== undefined)  // for HTML5 browsers
    {
        var formData = new FormData(this);
        $.ajax({
            url         : formURL,
            dataType    : "json",
            type        : "POST",
            data        : formData,
            contentType : false,
            cache       : false,
            processData : false,
            success: function(jsonData) {
                $('.mini_loader').fadeOut(500);
                $('.alert').hide();
                $('#alert-' + jsonData.logo_res).fadeIn();
                // logo_res
                if (jsonData.up_state)
                {
                    var src = jsonData.logo_path.toString();
                    var logo = $('<div logoname="'+ jsonData.logo_name +'" logoid="'+ jsonData.id_logo +'" class="logo_container boxlogosq">'
                        +'<img src="'+ jsonData.logo_path +'" class="logos" width="50px" height="50px" logo-name="'+ jsonData.logo_name +'">'
                        +'</div>');

                    $(logo).appendTo('#upload_logo_front');
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                $('.mini_loader').fadeOut();
                alert(errorThrown);
            }
       });
        // e.preventDefault();
   }
   else  //for olden browsers
   {
        //generate a random id
        var  iframeId = "unique" + (new Date().getTime());

        //create an empty iframe
        var iframe = $('<iframe src="javascript:false;" name="'+iframeId+'" />');

        //hide it
        iframe.hide();

        //set form target to iframe
        formObj.attr("target",iframeId);

        //Add iframe to body
        iframe.appendTo("body");
        iframe.load(function(e)
        {
            var doc = getDoc(iframe[0]);
            var docRoot = doc.body ? doc.body : doc.documentElement;
            var data = docRoot.innerHTML;
            //data return from server.
        });
    }
});

// ajax upload image from url
function ajaxUploadImage(event) {
    event.preventDefault();
    $( '.image_processing' ).show();
    var selector  = $(this);
    var formData = selector.closest('form').serialize();
    var data = stringToJSON(formData);
    data.action = 'ajaxUploadImage';
    var ajaxData = {
        url: design_handler,
        data: data,
        dataType: 'json',
        type: 'post',
        success: function(response) {
            if (response) {
                $( '#logo-upload-url' ).find( '.alert' ).hide();
                if (response.hasError) {
                    $( '#logo-upload-url' ).find( '#hasError-' + Number(response.hasError) ).html( response.msg ).fadeIn();
                } else {
                    $( '#logo-upload-url' ).find( '#hasError-' + Number(response.hasError) ).html( response.msg ).fadeIn();
                    var up_image = $('<div logoname="'
                        + response.name
                        + '" logoid="'
                        + response.id
                        + '" class="logo_container boxlogosq">'
                        + '<img src="'
                        + response.path.toString()
                        + '" class="logos" width="50px" height="50px" logo-name="'
                        + response.logo_name
                        + '" </div>');

                    $('#url-image').qtip('toggle', false);
                    $(up_image).appendTo('#upload_logo_front');
                }
            }
        },
        error: function(xhr, status, error) {
            $.alert(error_label + ': ' + status + ': ' + error);
        }
    }
    processAjax(ajaxData);
    setTimeout(function() {
        $( '.image_processing' ).fadeOut('slow');
    }, 800);
}

//text curvy - deprecated
function initSlider() {
var selector = $('#cpd_layers_section_content li.selected');
    var id = $(selector).attr('data-id-tag');
    var type = $(selector).attr('data-type');
    var id_design = $(selector).attr('data-id-design');
$('input#transparent').rangeslider({
            polyfill: false,
            rangeClass: 'font_rangeslider',
            disabledClass: 'font_rangeslider--disabled',
            horizontalClass: 'font_rangeslider--horizontal',
            fillClass: 'font_rangeslider__fill',
            handleClass: 'font_rangeslider__handle',
            onSlide: function(position, value) {
                    //console.log('Tag: '+id+' Type: '+type+' Design '+id_design+' Opac: '+value);
                    //var parent = $( '#cpd_layer_top_' + id_design );
                    //if ( type == 'image' || type == 'text' ) {
                        var idSelector = '#tag_' + type + '_' + id;
                        
                        //select editing side preview
                        if ($( '#mini_thumb_' + id_design ).hasClass( 'selected' ) == false) {
                            $( '#mini_thumb_' + id_design ).trigger('click');   
                        }
                        $( idSelector ).css( 'opacity', value );
                        $(this).attr('data-trans', value);
                    //}
            }
        });
$('#font-siz').rangeslider('update', true);
$('input#transparent').rangeslider('update', true);
    //$( "#curve" ).slider({
    //    value: 350,
    //    min: 0,
    //    max: 400,
    //    slide: function (event, ui) {
    //        var angle = ui.value;
    //        var prev_angle = $(this).attr('data-angle');
    //        var selectedTag = $( 'ul.cpd_layers_section_sortable:visible' ).find( 'li.selected' );
    //        console.log('some tag'+selectedTag);
    //        if (typeof selectedTag[0] !== 'undefined' && selectedTag[0]) {
    //            var id = $( selectedTag ).attr( 'data-id-tag' );
    //            var type = $( selectedTag ).attr( 'data-type' );
    //            var id_design = $( selectedTag ).attr( 'data-id-design' );
    //            var parent = $( '#cpd_layer_top_' + id_design );
    //            if ( type == 'text' ) {
    //                var idSelector = '#tag_' + type + '_' + id;
    //                $( idSelector ).show().arctext({radius: 0});
    //                
    //                //select editing side preview
    //                if ($( '#mini_thumb_' + id_design ).hasClass( 'selected' ) == false) {
    //                    $( '#mini_thumb_' + id_design ).trigger('click');   
    //                }
    //
    //                var direction = 1;
    //               if ($( '#direction' ).is(':checked')) {
    //                    direction = -1;
    //                }
    //
    //                $( idSelector ).arctext('set', {
    //                    radius : Math.abs(angle),
    //                    dir : direction,
    //                    nimation    : {
    //                        speed   : 300,
    //                        easing  : 'ease-out'
    //                    }
    //                });
    //                $(this).attr('data-angle', angle);
    //            }
    //        }
    //    }
    //});
}

// convert string to json format
function stringToJSON(string) {
    var pairs = string.split('&');
    var result = {};
    pairs.forEach(function(pair) {
        pair = pair.split('=');
        result[pair[0]] = decodeURIComponent(pair[1] || '').replace(/\+/g,' ');
    });
    return JSON.parse(JSON.stringify(result));
}

// attributes
function cpdCombination() {
    var choice = [];
    var requestData = {};
    var total_selector = $('#total-price');
    var radio_inputs = parseInt($('#cpd-variants input[type=radio]:checked').length);
    if (radio_inputs) {
        radio_inputs = '#cpd-variants input[type=radio]:checked';
    }

    $('#cpd-variants select,' + radio_inputs).each(function() {
        choice.push( $(this).attr('name') + '=' + parseInt($(this).val()) );
    });
    
    requestData = stringToJSON(choice.join('&'));
    if (typeof requestData !== 'undefined' && requestData) {
        requestData.action = 'getAttribute';
        requestData.cpd_product = parseInt($('#cpd_id_product').val());
        if ($('.price_update').css('display') == 'none') {
            total_selector.find('.price_update').fadeIn();
        }
        var ajaxData = {
            url: design_handler,
            data: requestData,
            dataType: 'json',
            type: 'post',
            success: function(response) {
                if (response.hasError) {
                    $.alert(error_label + ': ' + response.msg);
                    total_selector.find('.price_update').fadeOut('slow');
                } else {
                    total_selector.find('.cproduct_price').text(response.price);
                    total_selector.find('.cproduct_price').attr('data-price', response.price);
                    total_selector.find('.price_update').fadeOut('slow');
                }
            },
            error: function(xhr, status, error) {
                total_selector.find('.price_update').fadeOut('slow');
                $.alert(error_label + ': ' + status + ': ' + error);
            }
        }
        processAjax(ajaxData);
    }
    grandTotal();
}

// clear customization
function clear_customization() {
    // confirmation
    $.confirm({
        opacity: 0.5,
        escapeKey: true,
        type: 'red',
        animation: 'scale',
        closeAnimation: 'scale',
        icon: 'fa fa-warning',
        theme: 'modern',
        boxWidth: '80%',
        title: reset_label,
        content: reset_text_label,
        useBootstrap: false,
        buttons: {
            all: {
                text: all_label,
                btnClass: 'btn-red',
                action: function () {
                   alertAction(all_label, reset_all_label, all_callback);
                }
            },
            cancel: {
                text: cancel_label,
                btnClass: 'btn-blue',
                cancel: function () {
                    // on cancel do nothing
                }
            },
        }
    });
}

//cancel customization
function cancel_customization(e) {
    e.preventDefault();
    alertAction(all_label, reset_all_label, cancel_customization);
}

//cpd custom alert pop-up action
function alertAction(title, content, callback) {
    $.confirm({
        animation: 'scale',
        closeAnimation: 'scale',
        icon: 'fa fa-warning',
        theme: 'modern',
        title: title,
        content: content,
        useBootstrap: false,
        buttons: {
            confirm: {
                text: yes_label,
                btnClass: 'btn-red',
                action: callback
            },
            cancel: {
                text: no_label,
                btnClass: 'btn-blue',
                cancel: function () {
                    // on cancel do nothing
                }
            },
        }
    });
}

// clear current customization
var current_callback = function() {
    var id = $( '#cpd-parts-bar' ).find( '.selected' ).data( 'id' );
    var design = $( '#design_preview' ).find( '#design-view-' + id );
    if (design.find('.design').remove()) {
        cpd_update_price();
    }
};

//clear all customizations
var all_callback = function() {
    window.location.reload();
    //$( '#design_preview' ).find('.design').remove();
    //console.log('ALL CUSTOMIZATION GONE');
    //cpd_update_price();
};

var cancel_customization = function() {
    $('.cpd_main_loader').fadeIn();
    $( '#design_preview' ).find('.design').remove();
    cpd_update_price();
    $('#personalization').fadeOut(1000, function() {
        $('.cpd_main_loader').fadeOut();
        window.location = $('#cancel_customize_product').attr('href');
    });
}

/*************************************
 * Clockwise/Anticlockwise Rotation
 ************************************/
function rotateClockwise(id, angle) {
    $(id).rotate({angle: Math.abs(angle) });
    get(id);
    return false;
}

function rotateAnticlockwise(id, angle) {
    $(id).rotate({ angle: Math.abs(angle), direction: false });
    get(id);
    return false;
}

function reset(id) {
    $(id).clearRotation();
    get(id);
    return false;
}

function get(id) {
    var degs = $(id).getCurrentDegrees();
}

/*************************************
 * Price Update and right panel actions
 ************************************/

/*
 * Calculate total customization price
 */
function cpd_update_price() {
    var total = 0.0;
    var total_selector = $('#total-price');
    var id = $( '#cpd-parts-bar' ).find( '.selected' ).data( 'id' );
    var design = $( '#design_preview' ).find( '#design-view-' + id );
    //Make price zero if index is more then 1.
    var _index = parseInt(design.attr('data-index'));
    _base_product_price = $('span.cproduct_price').attr('data-price');
    if (_index > 1) {
        $('span.cproduct_price').text('0.00');
    }
    else {
        $('span.cproduct_price').text(_base_product_price);
    }
    design.find( '.design' ).each(function(e) {
        var price = $(this).attr('data-price').replace(/,/g, '.');
        if (price >= 0) {
            total += parseFloat(price);
        }
    });
    if (total >= 0) {
        var requestData = {
            action: 'getFormattedPrice',
            price: parseFloat(total)
        };
        if ($('.price_update').css('display') == 'none') {
            total_selector.find('.price_update').fadeIn();
        }
        var ajaxData = {
             url: design_handler,
             data: requestData,
             dataType: 'json',
             type: 'get',
             success: function(response) {
                if (response && response.success) {
                    total_selector.find('.plus_sign').show();
                    total_selector.find('.custom_price')
                    .text(response.total)
                    .trigger('change');
                    total_selector.find('.custom_price').attr('data-price', response.total);
                    total_selector.find('.price_update').fadeOut('slow');
                    grandTotal();
                } else {
                    total_selector.find('.plus_sign').hide();
                    total_selector.find('.custom_price').text('0');
                }
            },
            error: function(xhr, status, error) {
                $.alert(error_label + ': ' + status + ': ' + error);
                total_selector.find('.price_update').fadeOut('slow')
            }
        }
        processAjax(ajaxData);
    } else {
        total_selector.find('.plus_sign').hide();
        total_selector.find('.custom_price').text('0');
    }
    grandTotal();
}

/*
 * calculate total price
 */
function grandTotal() {
    var total = 0.0;
    var total_selector = $('#total-price');
    if ($('.price_update').css('display') == 'none') {
        total_selector.find('.price_update').fadeIn();
    }
    setTimeout(function() {
        var p = checkNaN(parseFloat(total_selector.find('.cproduct_price').text().replace(/,/g, '.').replace(/[^\d.]/g,''))),
            c = checkNaN(parseFloat(total_selector.find('.custom_price').text().replace(/,/g, '.').replace(/[^\d.]/g,'')));
        total = (parseFloat(p) + parseFloat(c)).toFixed(2);
        //total = checkNaN(parseFloat(total_selector.find('.cproduct_price').text().replace(/,/g, '.').replace(/[^\d.]/g,''))) + checkNaN(parseFloat(total_selector.find('.custom_price').text().replace(/,/g, '.').replace(/[^\d.]/g,'')));

        if (!isNaN(total)) {
            var requestData = {
                action: 'getFormattedPrice',
                price: parseFloat(total)
            };
            //Put in data-price for use without sign
            total_selector.find('.total_custom_price').attr('data-price', total);
            var ajaxData = {
                url: design_handler,
                data: requestData,
                dataType: 'json',
                type: 'get',
                success: function(response) {
                    if (response && response.success) {
                        total_selector.find('.total_custom_price').text(response.total);
                    }
                },
                error: function(xhr, status, error) {
                    total_selector.find('.price_update').fadeOut('slow');
                     $.alert(error_label + ': ' + status + ': ' + error);
                }
            }
            processAjax(ajaxData);
        }
        total_selector.find('.price_update').fadeOut('slow');
    }, 300);
}

// check if number is NaN
function checkNaN(number) {
    if (isNaN(number.toFixed(2))) {
        return 0;
    } else {
        return number.toFixed(2);
    }
}

/*
 * Generate design previews
 */
function generate_design_preview() {
    var id = $( '#cpd-parts-bar' ).find( '.selected' ).data( 'id' );
    //var design = $( '#design_preview' ).find( '#design-view-' + id );
    var design = $( '#design_preview' );
    $( '.design_loader' ).fadeIn();
    $('html, body').animate({
        scrollTop: $('#custom-design-center-column').offset().top
    }, 100);

    setTimeout(function() {
        $( '.design_loader' ).fadeOut( 2000, create_canvas(design, id));
    }, 800);
}

/*
 * Add customized design to list
 */
function add_design(byCart) {
    if (byCart !== true) {
        byCart = false;
    }
    var _base_design_added = false;
    var price = 0;
    var choice = [];
    var hiddenVars = [];
    var requestData = {};
    var rand_id = Date.now();
    var total_selector = $('#total-price');
    var has_attributes = false;
    var __index = 1;
    var cpd_id_product = parseInt($('#cpd_id_product').val());
    var id = $( '#cpd-parts-bar' ).find( '.selected' ).data( 'id' );
    var design = $( '#design_preview' ).find( '#design-view-' + id );
    var _customization_price = total_selector.find('.custom_price').attr('data-price');
    if (typeof _customization_price !== 'undefined') {
        _customization_price = _customization_price.replace(/,/g, '.').replace(/[^\d.]/g,'');
        _customization_price = parseFloat(_customization_price);    
    }
  
    //--------------------------------------------------------------------------
    //Base design is mandatory
    var _base_design_elem = $('#cpd-parts-bar').find('li:first img');
    var _base_design_id = _base_design_elem.attr('data-id');
    var _base_design_title = _base_design_elem.next('.cpd_thumbs_bottom_title').text();
    $('#selected-combinations tr').each(function() {
            if ($(this).attr('data-id') === _base_design_id) {
                _base_design_added = true;
            }
        });
    //Make price zero if index is more then 1.
    var _index = parseInt(design.attr('data-index'));
    if (_index > 1) {
        __index = _index;
    }
    price = _customization_price;//$('#cpd_material_' + id).attr('data-price');
    //design.find('.design').each(function(e) {//-----deprecated------
    //    price += parseFloat($(this).data('price'));
    //});
    
    var ___active_material = $('#cpd_material_active').val();
    //See if material is mandatory
    if (CPD_MATERIALS_MANDATORY > 0 && ___active_material <= 0) {
        material_req();
    }
    else if (!_base_design_added && id != _base_design_id) {//if first desing is not added yet
        basedesign_req(_base_design_title);
    }
    else if (typeof price !== 'undefined' && price >= 0) {
            // if product has combinations - get selected variants
            if ($('#cpd-variants').length) {
                has_attributes = true;
                var radio_inputs = parseInt($('#cpd-variants input[type=radio]:checked').length);
                if (radio_inputs) {
                    radio_inputs = '#cpd-variants input[type=radio]:checked';
                }
                $('#cpd-variants select,' + radio_inputs).each(function() {
                    var val = $(this).attr('name') + '=' + parseInt($(this).val());
                    choice.push( val );
                    hiddenVars.push( wrapAround($(this).attr('name')) + '=' + parseInt($(this).val()) );
                });
                requestData = stringToJSON(choice.join('&'));
            }
    
            design.find('.working_loader').show();
            requestData.price = parseFloat(price);
            requestData.index = parseInt(__index);
            requestData.action = 'getDesignPrice';
            requestData.qty = parseInt($('#cpd_qty_wanted').val());
            requestData.cpd_product = parseInt(cpd_id_product);
            requestData.has_attributes = has_attributes;
    
            var design_img = design.find('#cpd_layer_image_' + design.data('id'));
            var img = getImage(design_img.attr('src'));
    
            var minWidth = 650;
            var minHeight = 800;
            var width = $('#cpd_design_preview_' + id).width();
            var height = $('#cpd_design_preview_' + id).height();
    
            if (typeof width === 'undefined') {
                img.width;
            }
            if (typeof width === 'undefined') {
                img.height;
            }
    
            var scaleBy = 2;
            var options = {
                useCORS: true,
                allowTaint: true,
                logging: false,
                scale: scaleBy,
                width: width,
                height: height
            };
            var __viewport_width = $(window).width();
            __viewport_width = parseInt(__viewport_width);
            if (__viewport_width <= 580) {
                $('#cpd-tools-box-container .DesignPanel').hide();
            }
            $('html, body').animate({
                scrollTop: $('#custom-design-center-column').offset().top
            }, 100);
    
            var ajaxData = {
                url: design_handler,
                data: requestData,
                dataType: 'json',
                type: 'post',
                success: function(response) {
                    setTimeout(function() {
                        design.find('.working_loader').fadeOut(1500, function() {
                            if (response.success) {
                                var image = '';
                                design.show().removeClass('card');
                                html2canvas($('#design-view-' + id).get(0), options).then(function(canvas) {
                                    var material_price = 0;
                                    var source = canvas.toDataURL( 'image/png', 1 );
                                    if (design.find('#cpd_material_' + id).length) {
                                        material_price = design.find('#cpd_material_' + id).val();
                                    }
    
                                    if (source) {
                                        hiddenVars.push( '[price]=' + parseFloat(price) );
                                        hiddenVars = stringToJSON(hiddenVars.join('&'));
                                        var hidden_fields = '<td class="hiddenData">';
                                        if (typeof hiddenVars !== 'undefined' && hiddenVars) {
                                            for (var input in hiddenVars) {
                                                hidden_fields += '<input type="hidden" name="customized_design[' + rand_id + ']' + input + '" value="' + hiddenVars[input] + '">\n';
                                            }
                                        }
                                        if (typeof pdf === 'undefined') {
                                            pdf = 1;
                                        }
                                        hidden_fields += '<input type="hidden" name="customized_design[' + rand_id + '][design_image]" value="' + source + '">'
                                                      + '<input type="hidden" name="customized_design[' + rand_id + '][print_material]" value="' + material_price + '"></td>';
                                        var row = $('<tr id="customized_design_' + rand_id + '" data-id="'+id+'">' + hidden_fields
                                            + '<td><a class="design_gallery" href="' + source + '" rel="preview">'
                                            + '<img class="design_gallery_image" src="' + source + '" style="width:62px;"></a></td>'
                                            + '<td>' + response.price + '</td>'
                                            + '<td class="cpd_right_txt">' + ((pdf == 1)? '<a href="javascript:void(0);" class="printPDF" data-id="customized_design_' + rand_id + '"><i class="material-icons">print</i>' : '' ) + '</a>'
                                            + '<a href="javascript:void(0);" class="deleteDesign" data-id="customized_design_' + rand_id + '"><i class="material-icons">delete</i></a></td></tr>');
                                        $( '#selected-combinations' ).append(row);
                                    }
                                    design.addClass('card');
                                    if ($('#designs-panel').find('.DesignPanelTab').hasClass('ui-accordion-header-active ui-state-active ui-corner-top') === false) {
                                        $('#designs-panel').find('.DesignPanelTab').trigger('click');
                                        
                                        $('html, body').animate({
                                            scrollTop: $('#cpd-tools-box-container').offset().top
                                        }, 100);
                                        $('.cpd_abys').hide();
                                        //if direct cart button init, add to cart afterwards
                                        if (byCart) {
                                            addDesignsToCart();
                                        }
                                    }
                                });
                                $('#cpd_left_basic_nav ul li a.cpd_origin_mydesigns').click();
                            } else {
                                $.alert(error_label + ': ' + response.msg);
                            }
                        });
                     }, 800);
                },
                error: function(xhr, status, error) {
                    $.alert(error_label + ': ' + status + ': ' + error);
                }
            }
            processAjax(ajaxData);
        } else {
            $('#design_content').append('<div class="alert alert-danger cpd_glow"> '+ empty_design_label +' </div>').find('.cpd_glow').fadeOut(7000, function() {
                $(this).remove();
            });
        }
}

// create canvas
function create_canvas(design, id) {
    //var design = $( '#design_preview' ).find( '#design-view-' + id );
    var image = '';
    designView = design.find( '#design-view-' + id )
    designView.removeClass('card');
    var design_img = designView.find('#cpd_layer_image_' + designView.data('id'));
    var img = getImage(design_img.attr('src'));

    var minWidth = 650;
    var minHeight = 800;
    var width = $('#cpd_design_preview_' + id).width();
    var height = $('#cpd_design_preview_' + id).height();

    if (typeof width === 'undefined') {
        img.width;
    }
    if (typeof width === 'undefined') {
        img.height;
    }

    var scaleBy = 2;
    var options = {
        useCORS: true,
        allowTaint: true,
        logging: false,
        scale: scaleBy,
        width: width,
        height: height
    };

    html2canvas($('#design-view-' + id).get(0), options).then(function(canvas) {
        var source = canvas.toDataURL( 'image/png', 1 );
        image = $( '<img />', {
            src: source,
            class: 'imgm img-thumbnail'
        }).attr( 'rel', 'preview' );
            $.confirm({
                title: preview_label,
                content: image,
                animation: 'scale',
                animationClose: 'top',
                escapeKey: true,
                backgroundDismiss: true,
                boxWidth: '40%',
                useBootstrap: false,
                buttons: {
                    cancel: {
                        text: close_label,
                        btnClass: 'btn-blue',
                        cancel: function () {
                            // on cancel do nothing
                        }
                    },
                },
            });
        designView.addClass('card');
    });
}

// generate pdf
function createPDFObject(imgData, file_name, width, height, format, compress) {

    if (typeof pdf_orientation === 'undefined' || !pdf_orientation) {
        pdf_orientation = 'p';
    }
    if (typeof compress === 'undefined' || !compress) {
        compress = 'fast';
    }
    if (typeof format === 'undefined' || !format) {
        format = 'jpg';
    }
    var doc = new jsPDF(pdf_orientation, 'pt', 'a4', false);
    
    var pwidth = doc.internal.pageSize.width;    
    var pheight = doc.internal.pageSize.height;

    var maxWidth = 520;
    var maxHeight = 520;

    if (width > maxWidth) {
        ratio = maxWidth / width;
        height = height * ratio;
        width = width * ratio;
    } else if (height > maxHeight) {
        ratio = maxHeight / height;
        height = height * ratio;
        width = width * ratio;
    }
    
    var xAxis = 160;
    xAxis = (pwidth - width) - 50;
    if (xAxis > 160) {
        xAxis = 160;
    }

    doc.addImage(imgData, format, xAxis, 80, width, height, undefined, compress);
    //doc.addImage(imgData, format, 35, 10, 530, 790, undefined, compress);
    doc.save( file_name + '.pdf')
}

// trigger add to cart
function addDesignsToCart() {
    var children = $( '#selected-combinations' ).children().length;
    var formData = $( 'form#customized-data-form' ).serialize();
    var post_url = $( 'form#customized-data-form' ).attr( 'action' );
    
    if ( children >= 1 ) {
        $('#personalization').find('.cart_loader').show();
        ___qty = parseInt($('#cpd_qty_wanted').val());
        var ajaxData = {
            url: post_url,
            data: formData + '&cpd_qty_wanted=' + ___qty,
            dataType: 'json',
            type: 'post',
            success: function( response ) {
                $('#personalization').find('.cart_loader').fadeOut(2000, function() {
                    if (response.success) {
                        var pClass = (response.result <= 0) ? 'alert alert-danger error' : 'alert alert-success conf';
                        $.dialog({
                            title: add_to_cart_label,
                            columnClass: 'col-md-6',
                            content: '<div class="cart-content-btn"><p class="' + pClass + '">' + response.msg + '</p>'
                            + '<button data-dismiss="modal" class="btn btn-secondary c_p_d-button dissmiss" type="button">' + continue_label +'</button>'
                            + '<a class="btn btn-primary c_p_d-button proceed" href="' + cart_link + '">' + proceed_label + '</a>'
                            + '</div>',
                            animation: 'scale',
                            onOpen: function () {
                                var that = this;
                                this.$content.find('.dissmiss').click(function () {
                                    that.close();
                                });
                            }
                        });

                        // refresh cart - backward compatibility
                        if (ps_version < 1.7 && typeof ajaxCart !== 'undefined') {
                            ajaxCart.refresh();
                        }
                    } else {
                    $.alert( error_label + ': ' + response.msg );
                    }
                });
            },
            error: function(xhr, status, error) {
               $.alert( error_label + ': ' + status + ': ' + error );
            }
        }
        processAjax(ajaxData);
    } else {
        add_design(true);
        //$.alert( minimum_qty );
    }
}

function wrapAround(string) {
    var shouldbewrapped = /([a-zA-Z_\(\)])+/g;
    var wrapped = string.replace(shouldbewrapped, function(found) {
      return "[" + found + "]";
    });
    return wrapped;
}

function get_image_dimensions(image) {
    var imgObject =  {
        width: false,
        height: false
    };
    // Create dummy image to get real size
    $("<img>").attr("src", $(image).attr("src")).load(function() {
        imgObject.width = this.width;
        imgObject.height = this.height;
    });

    return imgObject;
}

function getImage(src) {
    var image = new Image();
    //Set the Base64 string return from FileReader as source.
    image.src = src;
    image.onload = function () {
        //Determine the Height and Width.
        var height = this.height;
        var width = this.width;
       
    }
    return image;
}

function getTagCss(tagID) {
    var css = '';
    if ($('#tag_text_' + tagID).length) {
        var size = ($('#tag_text_' + tagID).css('font-size') !== 'undefined')? $('#tag_text_' + tagID).css('font-size') : 'inherit';
        css += 'font-size:' + size + ';';
    }
    
    if ($('#tag_text_' + tagID).length) {
        var fonts = ($('#tag_text_' + tagID).css('font-family') !== 'undefined')? $('#tag_text_' + tagID).css('font-family') : 'inherit';
        css += 'font-family:' + fonts + ';';
    }

    if ($('#tag_text_' + tagID).length) {
        var preselectedColor = (typeof $('#select-color').find('.highlighted') !== 'undefined' && $('#select-color').find('.highlighted'))? $('#select-color').find('.highlighted').closest('.set_colour').attr('data-colorcode') : false;
        //var color = (preselectedColor)? preselectedColor : DEFAULT_CUSTOM_COLOR;
        var color = ($('#tag_text_' + tagID).css('color') !== 'undefined')? $('#tag_text_' + tagID).css('color') : (preselectedColor)? preselectedColor : DEFAULT_CUSTOM_COLOR;
        css += 'color:' + color + ';';
    }


    if ($('#text-alignment').length) {
        var align = $('#text-alignment').find('input[type=radio]:checked').val();
        css += 'text-alignment:' + align + ';';
    }

    if ($('#fonts-style').length) {
        $('#fonts-style').find('input[type=checkbox]:checked').each(function() {
            var type = $(this).val();
            var label = $(this).attr('id');
            $(this).closest('#fonts-style').find('.' + label + ' > span').addClass('selected_tstyle');
            switch ( type ) {
                case 'bold':
                    css += 'font-weight:' + type + ';';
                    break;
                case 'italic':
                    css += 'font-style:' + type + ';';
                    break;
                case 'underline':
                    css += 'text-decoration:' + type + ';';
                    break;
            }
        });
    }
    return css;
}

//alphebets and numbers
function alpha_number(event) {
    if (typeof event == 'undefined') {
        return false;
    }
    var key = event.keyCode;
    return ((key >= 65 && key <= 90)
        || key == 8
        || key == 32
        || (key >= 48 && key <= 57)
        || (key >= 96 && key <= 105)
        || key == 222);
}

// alphabets only
function alphabets_only(event) {
    if (typeof event == 'undefined') {
        return false;
    }
    var key = event.keyCode;
    return ((key >= 65 && key <= 90) || key == 8 || key == 32 || key == 222);
}

function processAjax(requestData) {
    $.ajax(requestData);
}

// code for touch devices
function touchHandler(event) {
    var touches = event.changedTouches,
        first = touches[0],
        type = "";
        switch(event.type) {
            case "touchstart": type = "mousedown"; break;
            case "touchmove":  type="mousemove"; break;        
            case "touchend":   type="mouseup"; break;
            default: return;
        }
 
    var simulatedEvent = document.createEvent("MouseEvent");
    simulatedEvent.initMouseEvent(type, true, true, window, 1, 
                              first.screenX, first.screenY, 
                              first.clientX, first.clientY, false, 
                              false, false, false, 0/*left*/, null);
    first.target.dispatchEvent(simulatedEvent);
    //event.preventDefault();
}
 
function initTouch() {
    document.addEventListener("touchstart", touchHandler, true);
    document.addEventListener("touchmove", touchHandler, true);
    document.addEventListener("touchend", touchHandler, true);
    document.addEventListener("touchcancel", touchHandler, true);    
}

//For Save template - employee
function cpdSaveTemplate(id, el) {
    var requestData = {};
    requestData.action = 'getSaveTemplate';
    requestData.id_design = parseInt(id);
    var _cpd_loader = $('#cpd_save_template_loader');
    _cpd_loader.show();
    $(el).hide();
    var width = $('#cpd_design_preview_' + id).width();
    var height = $('#cpd_design_preview_' + id).height();
    width = parseInt(width);
    height = parseInt(height);
    var img = $('img#cpd_layer_image_'+id);
    var _img_src = img.attr('src');
    var _img_obj = getImage(_img_src);
    if (height <= 0) {
        height = _img_obj.height;
    }
    if (width <= 0) {
        width = _img_obj.width;
    }
    var scaleBy = 2;
    var options = {
        useCORS: true,
        allowTaint: true,
        logging: false,
        scale: scaleBy,
        width: width,
        height: height
    };

    $('html, body').animate({
        scrollTop: $('#custom-design-center-column').offset().top
    }, 100);
    html2canvas($('#design-view-' + id).get(0), options).then(function(canvas) {
        var source = canvas.toDataURL( 'image/png', 1 );
        requestData.img = source;
        var ajaxData = {
            url: design_handler,
            data: requestData,
            dataType: 'json',
            type: 'post',
            success: function(response) {
                setTimeout( function() {
                    _cpd_loader.hide();
                    $('#design-view-' + id).html('<p style="color:green;font-size:16px;padding:10px;">The Template is saved.</p>');
                }, 3000);
                var request = {};
                request.action = 'getSaveTemplateElements';
                var _id_template = parseInt(response.id);
                $('#cpd_layers_section_content input.tag_price').each(function(e) {
                    var _id_tag = parseInt($(this).attr('data-id-tag'));
                    var cpd_element_obj = $('#cpd_tag_'+_id_tag);
                    var _data_type = cpd_element_obj.attr('data-type');
                    var _data_style = cpd_element_obj.attr('style');
                    if (_data_type === "image") {
                        var _data_value = cpd_element_obj.find('img').attr('src');
                        var _data_child_style = cpd_element_obj.find('.tag_image').attr('style');
                    }
                    else {
                        var _data_value = cpd_element_obj.text();
                        var _data_child_style = cpd_element_obj.find('.tag_text').attr('style');
                    }
                    request.id_template = _id_template;
                    request.id_tag = _id_tag;
                    request.type = _data_type;
                    request.style = _data_style;
                    request.value = _data_value;
                    request.child_style = _data_child_style;
                    
                    var _ajaxData = {
                        url: design_handler,
                        data: request,
                        dataType: 'json',
                        type: 'post',
                        success: function(response) {
                            console.log(response);
                        },
                        error: function(xhr, status, error) {
                            console.log(error);
                        }
                    }
                    processAjax(_ajaxData);
                });
                console.log(response);
            },
            error: function(xhr, status, error) {
                console.log(error);
            }
        }
        processAjax(ajaxData);
    });
}

function cpdGetDesignElements(idt, idd, el) {
    var source = $(el).find('img').attr('src');
    image = $( '<img />', {
            src: source,
            class: 'imgm img-thumbnail'
        }).attr( 'rel', 'preview' );
            $.confirm({
                title: reset_label_and_deploy_template,
                content: image,
                animation: 'scale',
                animationClose: 'top',
                escapeKey: true,
                backgroundDismiss: true,
                boxWidth: '40%',
                useBootstrap: false,
                buttons: {
                    confirm: {
                        text: _deploy_label,
                        btnClass: 'btn-red',
                        action: function () { cpdDeployDesign(idt,idd) }
                    },
                    cancel: {
                        text: cancel_label,
                        btnClass: 'btn-blue',
                        cancel: function () {
                            // on cancel do nothing
                        }
                    },
                },
            });
}
function cpdDeployDesign(idt,idd) {
    var requestData = {};
    $('#design_preview .working_loader').show();
    requestData.action = 'getTemplateToDeploy';
    requestData.id_template = parseInt(idt);
    requestData.id_design = parseInt(idd);
    requestData.cpd_product = parseInt($('#cpd_id_product').val());
    var ajaxData = {
            url: design_handler,
            data: requestData,
            dataType: 'json',
            type: 'get',
            success: function(response) {
                $('#customization-wrapper').html(response.html);
                $('#customization-wrapper #personalization').show();
                $('ul#cpd_layers_section_sortable'+idd).show();
                if ($(".cpd_layers_section_sortable").length) {
                    $(".cpd_layers_section_sortable").sortable({
                        opacity: 0.6,
                        cursor: "move",
                        update: function() { cpdSortZindex();}
                    });
                }
                if ($("#materialDropdown").length) {
                    $('#materialDropdown').ddslick({
                        width: 200,
                        data: ['price'],
                        imagePosition: "left",
                        selectText: printmaterial_label,
                        onSelected: function(data) {
                            if (data) {
                                var __cpd_material_val = data.selectedData.value;
                                $('#cpd_material_active').val(__cpd_material_val);
                                $( '#selected_print_material' ).val( __cpd_material_val );
                                var id = $( '#cpd-parts-bar' ).find( '.selected' ).data( 'id' );
                                $('#cpd_material_' + id).val( data.selectedData.value ).attr( 'data-price', data.selectedData.price );
                                cpd_update_price();
                            }
                        }   
                    });
                }
                setTimeout(function () {cpd_update_price(); initCpdLeftMenu();}, 1000);
            },
            error: function(xhr, status, error) {
                console.log(error);
                $('#design_preview .working_loader').hide();
            }
    }
    processAjax(ajaxData);

}
function cpdAddDynamicLayer(el) {
    var requestData = {};
    var _cpd_active_layers_count = 0;
    var _cpd_tagcount = $('input#cpd_dynamic_layer_count');
    var _cpd_id_design = $('div.design_container:visible').attr('data-id');
    $('#design_preview .working_loader').show();
    requestData.action = 'getTagToDeploy';
    _cpd_request_type = parseInt(el);
    var tag_counter = parseInt(_cpd_tagcount.val());
    if (_cpd_request_type <= 0) {
        requestData.type = "txt";
    }
    else {
        requestData.type = "img";
    }
    requestData.count = parseInt(tag_counter);
    requestData.id_design = parseInt(_cpd_id_design);
    var ajaxData = {
            url: design_handler,
            data: requestData,
            dataType: 'json',
            type: 'get',
            success: function(response) {
                _cpd_active_layers_count = parseInt($('#cpd_layer_top_'+_cpd_id_design+' .tags').length);
                if (_cpd_active_layers_count > 0) {
                    $('#cpd_layer_top_'+_cpd_id_design+' .tags:last').after(response.html_main);
                }
                else {
                    _cpd_workplace_count = parseInt($('#cpd_layer_top_'+_cpd_id_design+' .cpd_workplace_holder_blk').length);
                    if (_cpd_workplace_count > 0) {
                        $('#cpd_layer_top_'+_cpd_id_design+' .cpd_workplace_holder_blk:first').append(response.html_main);
                    }
                    else {
                        $('#cpd_layer_top_'+_cpd_id_design+' img.cpd_layer_image').after(response.html_main);
                    }
                }
                //$('#text-panel-'+_cpd_id_design+' div.design_'+_cpd_id_design+' button#design-list').before(response.html_left);
                $('#cpd_img_txt_blox_content #cpd_layers_section_'+_cpd_id_design).append(response.html_left);
                //cpd_layers_section_sortable
                _cpd_sortable_blk_count = parseInt($('ul#cpd_layers_section_sortable_'+_cpd_id_design).length);
                if (_cpd_sortable_blk_count > 0) {
                    $('ul#cpd_layers_section_sortable_'+_cpd_id_design).append(response.layer);
                }
                else {
                    _cpd_html_sortable_ul = '<ul id="cpd_layers_section_sortable_'+_cpd_id_design+'" class="cpd_layers_section_sortable ui-sortable"></ul>';
                    $('#cpd_layers_section #cpd_layers_section_content').append(_cpd_html_sortable_ul);
                    $('ul#cpd_layers_section_sortable_'+_cpd_id_design).append(response.layer);
                    $(".cpd_layers_section_sortable").sortable({
                        opacity: 0.6,
                        cursor: "move",
                        update: function() { cpdSortZindex();}
                    });
                }
                $('#design_preview .working_loader').hide();
                _cpd_tagcount.val(tag_counter+1);
                //Click on tag to activate
                $('#cpd_design_preview_'+_cpd_id_design+' div#cpd_tag_1000'+response.tag_count).click();
                options = {
                    autoHide: true
                };
                $('#design_preview .layer_tag').resizable(options);
            },
            error: function(xhr, status, error) {
                console.log(error);
                $('#design_preview .working_loader').hide();
            }
    }
    processAjax(ajaxData);
}
//function cpdTackleVisibility(ele) {
//    var _cpd_layer_el = $('#cpd_layers_section_content');
//    if (_cpd_layer_el.is(':visible')) {
//        _cpd_layer_el.slideUp();
//        $(ele).find('span').addClass('upward');
//    }
//    else {
//        _cpd_layer_el.slideDown();
//        $(ele).find('span').removeClass('upward');
//    }
//}
//Delete layer with tag on design also
$(document).on( 'click', '.cpd_layer_del', function() {
    var _cpd_del_element_layer = $(this).parent();
    var _id_tag_to_delete = parseInt(_cpd_del_element_layer.attr('data-id-tag'));
    var _id_design_elem = parseInt(_cpd_del_element_layer.attr('data-id-design'));
    $('#design_preview #design-view-'+_id_design_elem+' div#cpd_tag_'+_id_tag_to_delete+' img.delete_tag').click();
});
//Mouse Out on layer, z-index re-fix
$(document).on('mouseleave', '.layer_tag', function() {
cpdSortZindex();
});
//Do the z-index change
function cpdSortZindex() {
    var _z_index = 33;
    var _cpd_li_element = $('ul.cpd_layers_section_sortable li.cpd_ui_layer');
    _cpd_li_element.each(function() {
        var _cpd_el_id_design = $(this).attr('data-id-design');
        var _cpd_el_id_tag = $(this).attr('data-id-tag');
        $('#design_preview div#cpd_tag_'+_cpd_el_id_tag).css('z-index',_z_index);
        --_z_index;
    });
}

//Close hints block
function closeHintsBlk(__ele) {
    $(__ele).parent().hide();
}

//Quantity Modify
function cpdQtyModify(_qty, _qty_elem) {
    var requestData = {};
    var choice = [];
    var hiddenVars = [];
    var total_selector = $('#total-price');
    var has_attributes = false;
    var id = $( '#cpd-parts-bar' ).find( '.selected' ).data( 'id' );
    var __index = 1;
    var design = $( '#design_preview' ).find( '#design-view-' + id );
    //Make price zero if index is more then 1.
    var _index = parseInt(design.attr('data-index'));
    if (_index > 1) {
        __index = _index;
    }
    if ($('#cpd-variants').length) {
            has_attributes = true;
            var radio_inputs = parseInt($('#cpd-variants input[type=radio]:checked').length);
            if (radio_inputs) {
                radio_inputs = '#cpd-variants input[type=radio]:checked';
            }
            $('#cpd-variants select,' + radio_inputs).each(function() {
                var val = $(this).attr('name') + '=' + parseInt($(this).val());
                choice.push( val );
                hiddenVars.push( wrapAround($(this).attr('name')) + '=' + parseInt($(this).val()) );
            });
            requestData = stringToJSON(choice.join('&'));
        }
    requestData.action = 'getFlatPriceFresh';
    requestData.cpd_product = parseInt($('#cpd_id_product').val());
    requestData.index = parseInt(__index);
    requestData.has_attributes = has_attributes;
    requestData.qty = _qty;
    $(_qty_elem).parent().find('.qty_update').fadeIn();
    //If has Combinations
    
    var ajaxData = {
            url: design_handler,
            data: requestData,
            dataType: 'json',
            type: 'post',
            success: function(response) {
                $(_qty_elem).parent().find('.qty_update').fadeOut('slow');
                total_selector.find('.cproduct_price').text(response.price);
                total_selector.find('.cproduct_price').attr('data-price', response.price);
                console.log(response);
            },
            error: function(xhr, status, error) {
                $(_qty_elem).parent().find('.qty_update').fadeOut('slow');
                $.alert(error_label + ': ' + status + ': ' + error);
            }
        }
        processAjax(ajaxData);
        grandTotal();
}
//CPD Switch sys
function cpdSwitch(executer, _target) {
    if ($('#'+_target).is(':visible')) {
        $('#'+_target).hide();
    }
    else {
        $('#'+_target).show();
    }
}
//Clicked outside---
$(document).mouseup(function (e){
    var __container_variants = $("#cpd_product_opts_btn");
   
    if (!__container_variants.is(e.target) && __container_variants.has(e.target).length === 0){
        $('#cpd-variants').hide();
    }
    
    var __container_material = $("#cpd_product_materials_btn");
   
    if (!__container_material.is(e.target) && __container_material.has(e.target).length === 0){
        $('#material-wrapper').hide();
    }
});

function initCpdLeftMenu() {
    $('#cpd_left_basic_nav ul li a').each(function() {
        var _action_attr = $(this).attr('data-action');
        var _origin_attr = $(this).attr('data-origin');
        var __base_element = $(this);
        if (_action_attr && _action_attr === 'txt') {
            setTimeout( function() {
            __base_element.click();
            $('#cpd_img_txt_blox a.add_layer_txt').show();
            options = {
                autoHide: true
            };
            $('#design_preview .layer_tag').resizable(options);
            }, 2500 );
        }
    });
}

$(document).on( 'click', '#cpd_left_basic_nav ul li a', function() {initFirstLayerOpen($(this));});

function initFirstLayerOpen(_t) {
    var _active_design_id = $('#cpd-editor-container #design_preview .design_container:visible').attr('data-id');
    $('#cpd_toolset').html('');
    $('#cpd-tools-box-container .DesignPanel').hide();
    $('#cpd_left_basic_nav ul li a').removeClass('active');
    $('#cpd-tools-box-container .DesignPanel #cpd_img_txt_blox_content ul li, #cpd_img_txt_blox .cpd_extra_layer_btns a').hide();
    var _action_attr = _t.attr('data-action');
    var _origin_attr = _t.attr('data-origin');
    var _action_attr_type = _t.attr('data-type');
    //console.log('Data Action: '+_action_attr);
    if (_action_attr) {
            _t.addClass('active');
            $('#'+_origin_attr).show();
            if (_action_attr === 'txt') {
                $('#cpd-tools-box-container ul#cpd_layers_section_'+_active_design_id).find('li.cpd_layer_text:first').show("fast", function() {
                    var _layer_params_design_id = $(this).attr('data-id-design');
                    var _layer_params_tag_id = $(this).attr('data-id-tag');
                    $('#cpd_layers_section ul#cpd_layers_section_sortable_'+_layer_params_design_id+' li#cpd_ui_layer_'+_layer_params_tag_id).click();
                    getToolBox('#' + $(this).attr('id'), $(this).attr('data-type'));
                  });
                $('#cpd_img_txt_blox a.add_layer_txt').show();
            }
            else if (_action_attr === 'img') {
                $('#cpd-tools-box-container ul#cpd_layers_section_'+_active_design_id).find('li.cpd_layer_image:first').show("fast", function() {
                    var _layer_params_design_id = $(this).attr('data-id-design');
                    var _layer_params_tag_id = $(this).attr('data-id-tag');
                    $('#cpd_layers_section ul#cpd_layers_section_sortable_'+_layer_params_design_id+' li#cpd_ui_layer_'+_layer_params_tag_id).click();
                    getToolBox('#' + $(this).attr('id'), $(this).attr('data-type'));
                  });
                $('#cpd_img_txt_blox a.add_layer_img').show();
            }
        }
}


function openLayerPanel(_id_tag, _layer_type) {
    var _targeted_layer = $('#cpd-tools-box-container .DesignPanel #cpd_img_txt_blox_content ul li#cpd_layer_'+_id_tag);
    $('#cpd_left_basic_nav ul li a').removeClass('active');
    $('#cpd-tools-box-container .DesignPanel').hide();
    $('#cpd_left_basic_nav ul li a.cpd_origin_'+_layer_type).addClass('active');
    $('#cpd-tools-box-container .DesignPanel #cpd_img_txt_blox_content ul li').hide();
    $('#cpd_img_txt_blox .cpd_extra_layer_btns a').hide();
    _targeted_layer.show().click();
    $('#cpd_img_txt_blox').show();
    if (_layer_type === 'text') {
        $('#cpd_img_txt_blox a.add_layer_txt').show();
    }
    else if (_layer_type === 'image') {
        $('#cpd_img_txt_blox a.add_layer_img').show();
    }
}

function initTagOrigins(_val_, _ele_) {
    var __logos_collection = $('#upload_logo_front div.logo_container');
    $('#cpd_tags_filters a').removeClass('active');
    $(_ele_).addClass('active');
    if (_val_ === 0) {
        __logos_collection.show();
    }
    else {
        __logos_collection.each(function(____e) {
            var __this_data_tags = $(this).attr('data-tags');
            if (__this_data_tags.indexOf(_val_) > -1) {
                $(this).show();
            }
            else {
                $(this).hide();
            }
        });
    }
}
//materials required alert
function material_req() {
    alertActionSelMat(printmaterial_label, cancel_label);
}

//first desing not added yet alert
function basedesign_req(_base_design_title) {
    alertActionBasicDesignSelect(first_design_label_l, first_design_label_r, cancel_label, _base_design_title);
}

//cpd custom alert pop-up action
function alertActionSelMat(title, content) {
    $.confirm({
        animation: 'scale',
        closeAnimation: 'scale',
        icon: 'fa fa-warning',
        theme: 'modern',
        title: error_label,
        content: title,
        useBootstrap: false,
        buttons: {
            cancel: {
                text: content,
                btnClass: 'btn-blue',
                cancel: function () {
                    // on cancel do nothing
                }
            },
        }
    });
}

function alertActionBasicDesignSelect(title_l, title_r, content, __d) {
    $.confirm({
        animation: 'scale',
        closeAnimation: 'scale',
        icon: 'fa fa-warning',
        theme: 'modern',
        title: error_label,
        content: title_l+__d+title_r,
        useBootstrap: false,
        buttons: {
            cancel: {
                text: content,
                btnClass: 'btn-blue',
                cancel: function () {
                    // on cancel do nothing
                }
            },
        }
    });
}