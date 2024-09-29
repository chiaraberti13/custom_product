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

var cpd_group = {

    id_product:0,

    order: null,

    orderArray: null,

    counter: $('.cpd_layers').length,

    designPanel: '#design-panel',

    dragable_wrapper: '#cpd_layer_top_',

    resizable_wrapper: '#cpd_layer_top_',

    init: function() {
        if (typeof id_product === 'undefined' || id_product == 0) {
            id_product = $('#cpd_from_wrapper').find('input[name=id_product]').val();
        }
        if (typeof counter === 'undefined' || counter == '') {
            counter = 0;
        }
        $( '#design-panel' ).find('.cpd_layers > h3').hover(function() {
            $(this).css("cursor","move");
            },
            function() {
            $(this).css("cursor","auto");
        });

        this.actionCollapsible();
        this.initDragabbles();
        this.initTooltip();
        this.initCoverDrag();
        this.initTagDrag();
        this.initWindowDragging();
        this.initTouch();
        $('a.cpd_workplace_locking_ico').click();
    },

    initTooltip: function() {
        $('a.design_title, a.tag_price').qtip({
            style: {
                classes: 'qtip-blue qtip-shadow'
            }
        });
    },

    initDragabbles: function() {
        $('.cpd_select_image').each(function() {
            $(this).trigger('click');
        });
    },

    initTagDrag: function() {
        $('.tags').each(function(e) {
            var id_tag = $(this).data('id-tag');
            cpd_group.setTagDragging(id_tag);
        });
    },

    initWindowDragging: function() {
        $('.cpd_workplace_area').each(function(e) {
            var _id_window = $(this).attr('data-id-window');
            cpd_group.setWindowDragging(_id_window);
        });
    },
    
    initCoverDrag: function() {
        $('.cpd_layer_image').each(function(e) {
            var id_design = $(this).data('id');
            //cpd_group.actionDraggable(cpd_group.dragable_wrapper + id_design, '#cpd_design_preview_' + id_design);
            //cpd_group.actionResizable(cpd_group.resizable_wrapper + id_design, '#cpd_design_preview_' + id_design + ', #cpd_layer_image_' + id_design);
        });
    },

    getDesignPanel: function() {
        var id = 'cpd_from_wrapper_' + Date.now();
        var data = {
            action: 'getGroupForm',
            id_product: id_product
        };

        var ajaxData = {
            url: cpd_ajax_group,
            data: data,
            dataType: 'json',
            type: 'get',
            success: function(data) {
                if (data) {
                    if (data.hasError) {
                        showErrorMessage(data.msg);
                    } else {
                        showSuccessMessage(data.msg);
                        $(cpd_group.designPanel).append(data.html).show();
                        cpd_group.actionCollapsible();
                    }
                }
            },
            error: function(xhr, status, error) {
                showErrorMessage('Error: ' + status + ': ' + error);
            }
        };
        cpd_group.processAjax(ajaxData);
    },

    openPremades: function() {
        $('#premade_designs_product').slideDown();
        $('html, body').animate({
            scrollTop: $('#premade_designs_product').offset().top
        }, 100);
        },
        
    closePremades: function() {
        $('#premade_designs_product').hide();
        },
    
    showDesignPanel: function() {
        var panel = $('#cpd_from_wrapper');
        var id = 'cpd_from_wrapper_' + Date.now();

        var data = {
            action: 'getGroupForm',
            id_product: id_product
        };
        $('#cpd_add').qtip({
            id: id,
            content: {
                text: function(event, api) {
                    var ajaxData = {
                        url: cpd_ajax_group,
                        data: data,
                        type: 'get',
                        success: function(html) {
                            api.set('content.text', html);
                        },
                        error: function(xhr, status, error) {
                            api.set('content.text', status + ': ' + error);
                        }
                    }
                    cpd_group.processAjax(ajaxData);
                    return cpd_group.showLoader();
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
                target: $('#cpd_from_wrapper'),
                container: $('#content'),
                adjust: {
                    mouse: true,
                    scroll: true
                }
            },
            style: { classes: 'qtip-bootstrap qtip_group_form'},
            events: {
                show: function(event, api) {
                    $('#cpd_loader').addClass('cpd_fade');
                },
                hide: function(event, api) {
                    $('#cpd_loader').removeClass('cpd_fade');
                }
            }
        }).qtip('show');
        return false;
    },

    updateDesignTitle: function() {
        var id = 'design_title_' + Date.now();
        var id_design = $(this).data('id');
        var data = {
            action: 'setDesignTitle',
            id_design: id_design
        };
        $(this).qtip({
            id: id,
            content: {
                text: function(event, api) {
                    var ajaxData = {
                        url: cpd_ajax_group,
                        data: data,
                        dataType: 'json',
                        type: 'get',
                        success: function(response) {
                            api.set('content.text', response.html);
                            $('#' + id_design + '_product_customized_form').find('#fieldset_0').addClass('clearfix');
                            $('#' + id_design + '_product_customized_form').find('.panel-footer').addClass('col-lg-12');
                        },
                        error: function(xhr, status, error) {
                            api.set('content.text', status + ': ' + error);
                        }
                    }
                    cpd_group.processAjax(ajaxData);
                    return cpd_group.showLoader();
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
                my: 'center center',
                target: $('#cpd_groups_wrapper_' + id_design),
                container: $('#form_content'),
                adjust: {
                    mouse: true,
                    scroll: true
                }
            },
            style: { classes: 'qtip-bootstrap qtip_group_form'},
            events: {
                show: function(event, api) {
                    $('#module_form').find('#fieldset_0').addClass('clearfix');
                },
                hide: function(event, api) {
                    //$('#cpd_loader').removeClass('cpd_fade');
                }
            }
        }).qtip('show');
    },

    updateTag: function(event) {
        event.preventDefault();
        var id = 'tag__' + Date.now();
        var id_tag = $(this).data('id');
        var id_design = $(this).data('id-design');
        var data = {
            action: 'updateTag',
            id_tag: id_tag
        };
        $(this).qtip({
            id: id,
            content: {
                text: function(event, api) {
                    var ajaxData = {
                        url: cpd_ajax_group,
                        data: data,
                        dataType: 'json',
                        type: 'get',
                        success: function(response) {
                            api.set('content.text', response.html);
                            $('#' + id_tag + '_product_customized_tags_form').find('#fieldset_0').addClass('clearfix');
                            $('#' + id_tag + '_product_customized_tags_form').find('.panel-footer').addClass('col-lg-12');
                        },
                        error: function(xhr, status, error) {
                            api.set('content.text', status + ': ' + error);
                        }
                    }
                    cpd_group.processAjax(ajaxData);
                    return cpd_group.showLoader();
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
                at: 'bottom center',
                my: 'bottom center',
                target: $('#cpd_tag_' + id_tag),
                container: $('#cpd_groups_wrapper_' + id_design),
                adjust: {
                    mouse: true,
                    scroll: true
                }
            },
            style: { classes: 'qtip-bootstrap qtip_group_form'},
            events: {
                show: function(event, api) {
                    $('#module_form').find('#fieldset_0').addClass('clearfix');
                },
                hide: function(event, api) {
                    //$('#cpd_loader').removeClass('cpd_fade');
                }
            }
        }).qtip('show');
    },

    ajaxSubmitName: function(event) {
        event.preventDefault();
        var selector  = $(this);
        var formData = selector.closest('form').serialize();
        var data = cpd_group.stringToJSON(formData);
        data.action = 'updateDesignTitle';
        var jsonData = {
            url: cpd_ajax_group,
            data: data,
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response) {
                    if (response.hasError) {
                        showErrorMessage(response.msg);
                    } else {
                        showSuccessMessage(response.msg);
                        $('#design-title-' + response.id_design).qtip('toggle', false);
                        $('#design-title-' + response.id_design).text(response.title);
                    }
                    cpd_group.initTooltip();
                }
            },
            error: function(xhr, status, error) {
                showErrorMessage('Error: ' + status + ': ' + error);
            }
        }
        cpd_group.processAjax(jsonData);
    },

    ajaxUpdateTag: function(event) {
        event.preventDefault();
        var selector  = $(this);
        var formData = selector.closest('form').serialize();
        var data = cpd_group.stringToJSON(formData);
        data.action = 'ajaxUpdateTag';
        var jsonData = {
            url: cpd_ajax_group,
            data: data,
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response) {
                    if (response.hasError) {
                        showErrorMessage(response.msg);
                    } else {
                        showSuccessMessage(response.msg);
                        $('#design-tag-' + response.id_tag).qtip('toggle', false);
                        $('#design-tag-' + response.id_tag).text(response.price);
                    }
                    cpd_group.initTooltip();
                }
            },
            error: function(xhr, status, error) {
                showErrorMessage('Error: ' + status + ': ' + error);
            }
        }
        cpd_group.processAjax(jsonData);
    },

    addTag: function() {
        var data = {};
        data.cloneTag = $(this).data('tag');
        data.id_design = $(this).data('id');
        data.tag_type = $(this).data('type');
        data.action = 'addTag';
        cpd_group.createTag(data);
        return false;
    },
    
    addWindowLayer: function() {
        var data = {};
        data.cloneTag = $(this).data('tag');
        data.id_design = $(this).data('id');
        data.tag_type = $(this).data('type');
        data.action = 'addWindowLayer';
        cpd_group.createWorkplaceArea(data);
        return false;
    },

    removeTag: function() {
        var target = $(this);
        var id_tag = $(this).parent().parent().data('id-tag');
        var data = {
            action: 'removeTag',
            id_tag: id_tag
        };
        var jsonData = {
            url: cpd_ajax_group,
            data: data,
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response) {
                    if (response.hasError) {
                        showErrorMessage(response.msg);
                    } else {
                        showSuccessMessage(response.msg);
                        $(target).parent().parent().remove();
                    }
                }
            },
            error: function(xhr, status, error) {
                showErrorMessage('Error: ' + status + ': ' + error);
            }
        }
        cpd_group.processAjax(jsonData);
    },

    removeWorkplace: function() {
        var target = $(this);
        var id_win = $(this).parent().attr('data-id-window');
        //console.log('Del '+id_win);
        var data = {
            action: 'removeWindowLayer',
            id_window: id_win
        };
        var jsonData = {
            url: cpd_ajax_group,
            data: data,
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response) {
                    if (response.hasError) {
                        showErrorMessage(response.msg);
                    } else {
                        showSuccessMessage(response.msg);
                        $(target).parent().remove();
                    }
                }
            },
            error: function(xhr, status, error) {
                showErrorMessage('Error: ' + status + ': ' + error);
            }
        }
        cpd_group.processAjax(jsonData);
    },
    
    removeDesignLayer: function() {
        var id_design = parseInt($(this).data('id'));
        var data = {
            action: 'removeDesign',
            id_design: id_design
        };

        var agree = confirm(labels.conf_text);
        if (!agree) {
            return false;
        }

        var jsonData = {
            url: cpd_ajax_group,
            data: data,
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response) {
                    if (response.hasError) {
                        showErrorMessage(response.msg);
                    } else {
                        showSuccessMessage(response.msg);
                        $('#cpd_groups_wrapper_' + id_design).remove();
                        if ($(cpd_group.designPanel).children().length <= 1) {
                           $(cpd_group.designPanel).hide();
                        }
                    }
                }
            },
            error: function(xhr, status, error) {
                showErrorMessage('Error: ' + status + ': ' + error);
            }
        }
        cpd_group.processAjax(jsonData);
    },

    removePremadeTemplate: function() {
        var id_temp = parseInt($(this).data('id'));
        var data = {
            action: 'removePremade',
            id_temp: id_temp
        };

        var jsonData = {
            url: cpd_ajax_group,
            data: data,
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response) {
                    if (response.hasError) {
                        showErrorMessage(response.msg);
                    } else {
                        showSuccessMessage(response.msg);
                        $('#premade_designs_product tr#premade_' + id_temp).remove();
                    }
                }
            },
            error: function(xhr, status, error) {
                showErrorMessage('Error: ' + status + ': ' + error);
            }
        }
        cpd_group.processAjax(jsonData);
    },
    
    changeDesignStatus: function() {
        var selector = $(this);
        var __parent_accordion = selector.parent().parent().parent();
        var id_design = parseInt(selector.data('id'));
        var data = {
            action: 'changeDesignStatus',
            id_design: id_design
        };

        var jsonData = {
            url: cpd_ajax_group,
            data: data,
            type: 'post',
            dataType: 'json',
            success: function(response) {
                if (response) {
                    if (response.hasError) {
                        showErrorMessage(response.msg);
                    } else {
                        showSuccessMessage(response.msg);
                        if (response.status == true) {
                           selector.removeClass('btn-warning').addClass('btn-success');
                           __parent_accordion.find('h3.layer_panel').removeClass('ui_layer_nonactive').addClass('ui_layer_active');
                           __parent_accordion.find('div.cpd_layer').removeClass('ui_layer_nonactive').addClass('ui_layer_active');
                        } else {
                            selector.removeClass('btn-success').addClass('btn-warning');
                            __parent_accordion.find('h3.layer_panel').removeClass('ui_layer_active').addClass('ui_layer_nonactive');
                            __parent_accordion.find('div.cpd_layer').removeClass('ui_layer_active').addClass('ui_layer_nonactive');
                        }
                    }
                }
            },
            error: function(xhr, status, error) {
                showErrorMessage('Error: ' + status + ': ' + error);
            }
        }
        cpd_group.processAjax(jsonData);
    },

    cloneElement: function(group) {
        return $(group).clone().removeAttr( 'id' );
    },

    actionDraggable: function(selector, parent) {
        var target = selector;
        $(target).draggable({
            cursor: 'move',
            containment: parent,
            stop: cpd_group.dragStop,
        });
    },

    actionResizable: function(selector, alsoResize) {
        var target = selector;
        var options = {
            grid: true,
            create: cpd_group.setContainerResizer,
            resize: cpd_group.startResize,
            stop: cpd_group.resizeStop
        };

        if (typeof alsoResize !== 'undefined' && alsoResize != '') {
            options.alsoResize = alsoResize
        }
        $(target).resizable(options);
    },

    resizeStop: function(event, ui) {
        cpd_group.convert_to_percentage($(this));
    },

    startResize: function(event, ui) {
        $(this).css('position', 'relative');
    },

    dragStop: function(event, ui) {
        cpd_group.convert_to_percentage($(this));
    },

    setContainerResizer: function(event, ui) {
        //$($(this)[0]).children( '.ui-resizable-handle' ).mouseover(cpd_group.setContainerSize);
    },

    convert_to_percentage: function(el) {
        var parent = el.parent();
        var width = el.width() / parent.width()*100;
        var height = el.height() / parent.height()*100;
        var left = parseFloat(el.css('left'))/parent.width()*100;
        var top = parseFloat(el.css('top'))/parent.height()*100;
        
        var data = {
            width : width,
            height : height,
            left : left,
            top : top,
            action: 'setDesignAttributes',
            id_design: el.data('id'),
        }

        el.css({
            left : left + "%",
            top : top + "%",
            width:  width + "%",
            height:  height + "%",
            position: 'relative'
        });
        cpd_group.setAttributes(data);
    },

    setContainerSize: function(el) {
        var parent = $(el.target).parent().parent();
        parent.css('height', parent.height() + "px");
    },

    actionCollapsible: function() {
        $( '.cpd_layers' ).accordion({
            active: false,
            header: '.layer_panel',
            heightStyle: 'content',
            collapsible: true,
            icons: true,
        });
        var $myPanel = $( '#design-panel' );

        $myPanel.sortable({
            axis: 'y',
            handle: '.layer_panel',
            opacity: 0.6,
            cursor: "move",
            update: function() {
                var order = $(this).sortable("serialize") + "&action=updatePosition";
                var jsonData = {
                    url: cpd_ajax_group,
                    data: order,
                    type: 'post',
                    dataType: 'json',
                    success: function(jsonData, textStatus, jqXHR) {
                        if (jsonData !== 'undefined' && jsonData.hasError === false) {
                            showSuccessMessage(jsonData.msg);
                        }
                    }
                }
                cpd_group.processAjax(jsonData);
            },
            stop: function( event, ui ) {
                ui.item.children( '.layer_panel' ).triggerHandler( 'focusout' );
                // Refresh accordion to handle new order
                $( '.cpd_layers' ).accordion( 'refresh' );
            }
        });
    },

    stringToJSON: function(string) {
        var pairs = string.split('&');
        var result = {};
        pairs.forEach(function(pair) {
            pair = pair.split('=');
            result[pair[0]] = decodeURIComponent(pair[1] || '').replace(/\+/g,' ');
        });
        return JSON.parse(JSON.stringify(result));
    },

    showLoader: function() {
        return image = $('<img />', {
            class: 'loader_image' ,
            src: mini_loader
        });
    },

    createTag: function(data) {
        $.post(cpd_ajax_group, data, function(response) {
            if (response) {
                if (response.hasError == false) {
                    showSuccessMessage(response.msg);
                    var id_tag = response.id_tag;
                    var cloneElem = cpd_group.cloneElement('#' + data.cloneTag);
                    cloneElem.attr('data-id-tag', id_tag).prop('id', 'cpd_tag_' + id_tag).addClass('cpd_image_pos');
                    cloneElem.find('.tag_price')
                    .attr('id', 'design-tag-' + id_tag)
                    .attr('data-id', id_tag)
                    .attr('data-id-design', data.id_design)
                    .attr('href', cpd_ajax_group + '&updateTag&id_tag=' + id_tag);
                    cloneElem.appendTo('#cpd_layer_top_' + data.id_design).show();
                    cpd_group.setTagDragging(id_tag);
                    cpd_group.initTooltip();
                } else {
                    showErrorMessage(response.msg);
                }
            }
        }, 'json')
        .fail(function(xhr, status, error) {
            showErrorMessage('Error: ' + status + ': ' + error);
        });
    },

    createWorkplaceArea: function(data) {
        $.post(cpd_ajax_group, data, function(response) {
            if (response) {
                console.log(response);
                if (response.hasError == false) {
                    showSuccessMessage(response.msg);
                    var id_window = response.id_window;
                    var cpd_target_layer = $('#cpd_layer_top_'+data.id_design);
                    cpd_target_layer.append('<div id="cpd_window_'+id_window+'" data-id-window="'+id_window+'" class="cpd_workplace_area ui-resizable ui-draggable ui-draggable-handle"><a class="cpd_workplace_locking_ico" onclick="lockThisWindow('+id_window+', this);"><i class="material-icons">lock_open</i></a><a class="cpd_remove_workplace pull-right" title="Delete"><i class="process-icon-delete"></i><i class="material-icons">delete</i></a></div>');
                    //var cloneElem = cpd_group.cloneElement('#' + data.cloneTag);
                    //cloneElem.attr('data-id-tag', id_tag).prop('id', 'cpd_tag_' + id_tag).addClass('cpd_image_pos');
                    //cloneElem.find('.tag_price')
                    //.attr('id', 'design-tag-' + id_tag)
                    //.attr('data-id', id_tag)
                    //.attr('data-id-design', data.id_design)
                    //.attr('href', cpd_ajax_group + '&updateTag&id_tag=' + id_tag);
                    //cloneElem.appendTo('#cpd_layer_top_' + data.id_design).show();
                    cpd_group.setWindowDragging(id_window);
                    //cpd_group.initTooltip();
                } else {
                    showErrorMessage(response.msg);
                }
            }
        }, 'json')
        .fail(function(xhr, status, error) {
            showErrorMessage('Error: ' + status + ': ' + error);
        });
    },
    
    setTagDragging: function(id_tag) {
        var data = {};
        data.action = 'setTagAttributes';
        data.id_tag = id_tag;

        $('#cpd_tag_' + id_tag).resizable({
            containment: 'parent',
            alsoResize: '#cpd_tag_' + id_tag + ' img',
            stop: function(e, ui) {
                var parent  = ui.element.parent();
                var width   = ui.size.width/parent.width()*100;
                var height  = ui.size.height/parent.height()*100;
                var left    = parseFloat(ui.element.css('left'))/parent.width()*100;
                var top     = parseFloat(ui.element.css('top'))/parent.height()*100;

                data.width = width;
                data.height = height;
                data.pos_top = top;
                data.pos_left = left;
                cpd_group.setAttributes(data);

                ui.element.css({
                    left: left + "%",
                    top: top + "%",
                    width: width + "%",
                    height: height + "%",
                    position: 'absolute'
               });
            }
        })
        .draggable({
            containment: 'parent',
            scroll: false,
            cursor: 'move',
            stop: function (event, ui) {
                var parent  = $(this).parent();
                var left    = parseFloat($(this).css('left'))/parent.width()*100;
                var top     = parseFloat($(this).css('top'))/parent.height()*100;

                data.pos_top = top;
                data.pos_left = left;
                cpd_group.setAttributes(data);

                $(this).css({
                    left: left + "%",
                    top: top + "%",
                    position: 'absolute'
                });
            }
        });
    },

    setWindowDragging: function(id_window) {
        var data = {};
        data.action = 'setWindowAttributes';
        data.id_window = id_window;

        $('#cpd_window_' + id_window).resizable({
            containment: 'parent',
            //alsoResize: '#cpd_tag_' + id_tag + ' img',
            stop: function(e, ui) {
                var parent  = ui.element.parent();
                var width   = ui.size.width/parent.width()*100;
                var height  = ui.size.height/parent.height()*100;
                var left    = parseFloat(ui.element.css('left'))/parent.width()*100;
                var top     = parseFloat(ui.element.css('top'))/parent.height()*100;

                data.width = width;
                data.height = height;
                data.pos_top = top;
                data.pos_left = left;
                cpd_group.setAttributes(data);

                ui.element.css({
                    left: left + "%",
                    top: top + "%",
                    width: width + "%",
                    height: height + "%",
                    position: 'absolute'
               });
            }
        })
        .draggable({
            containment: 'parent',
            scroll: false,
            cursor: 'move',
            stop: function (event, ui) {
                var parent  = $(this).parent();
                var left    = parseFloat($(this).css('left'))/parent.width()*100;
                var top     = parseFloat($(this).css('top'))/parent.height()*100;

                data.pos_top = top;
                data.pos_left = left;
                cpd_group.setAttributes(data);

                $(this).css({
                    left: left + "%",
                    top: top + "%",
                    position: 'absolute'
                });
            }
        });
    },
    
    setAttributes: function(data) {
        $.post(cpd_ajax_group, data, function(response) {
            if (response) {
                if (response.hasError == false) {
                    showSuccessMessage(response.msg);
                } else {
                    showErrorMessage(response.msg);
                }
            }
        }, 'json')
        .fail(function(xhr, status, error) {
            showErrorMessage('Error: ' + status + ': ' + error);
        });
    },

    saveDesignCover: function(data) {
        $.post(cpd_ajax_group, data, function(response) {
            if (response) {
                if (response.hasError == false) {
                    showSuccessMessage(response.msg);
                } else {
                    showErrorMessage(response.msg);
                }
            }
        }, 'json')
        .fail(function(xhr, status, error) {
            showErrorMessage('Error: ' + status + ': ' + error);
        });
    },

    processAjax: function(requestData) {
        $.ajax(requestData);
    },

    touchHandler: function(event) {
        var touches = event.changedTouches,
            first = touches[0],
            type = "";
             switch(event.type)
        {
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
    },
     
    initTouch: function() {
        document.addEventListener("touchstart", cpd_group.touchHandler, true);
        document.addEventListener("touchmove", cpd_group.touchHandler, true);
        document.addEventListener("touchend", cpd_group.touchHandler, true);
        document.addEventListener("touchcancel", cpd_group.touchHandler, true);    
    }
};
function lockThisWindow(id, el) {
    //console.log('Window '+id);
    var __window_tar = $('#cpd_window_'+id);
    if ($(el).hasClass('locked')) {
        __window_tar.draggable({ disabled: false });
        __window_tar.css('z-index','1');
        $('.inner_layer').css('z-index','0');
        $(el).find('i').text('lock');
        $(el).removeClass('locked');
    }
    else {
        __window_tar.draggable({ disabled: true });
        __window_tar.css('z-index','0');
        $('.inner_layer').css('z-index','1');
        $(el).find('i').text('lock_open');
        $(el).addClass('locked');
    }
}

function cpdTriggerClickEdit(ele) {
    $(ele).parent().find('.tag_price').click();
}