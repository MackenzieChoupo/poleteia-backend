$(function () {
//    $('body').on('click', '.list__icon', function () {
//        alert(context);
//    });

    $('body').on('click', '.page-enabled', function () {
        var btn = $(this);
        var objectId = $(this).data('id');
        var routeName = $(this).data('route');
        $.ajax({
            url: Routing.generate(routeName, {_locale: locale, id: objectId}),
            success: function (data) {
                var enabled = data.enabled;
                if (enabled) {
                    btn.addClass('enabled');
                    btn.find('.fa-times').removeClass('fa-times').addClass('fa-check');
                    btn.parent('.page-tree__item').find('.btn-group.btn-publish').remove();
                } else {
                    btn.removeClass('enabled');
                    btn.find('.fa-check').removeClass('fa-check').addClass('fa-times');
                }
            }
        });
    });

    $("#block-master").sortable({
        handle: ".compose-box-block-header",
        placeholder: "compose-box-block-highlight",
        items: "li.block-sortable",
        forcePlaceholderSize: true,
        update: function (event, ui) {
            var siblingId;
            var pos;
            if (ui.item.prev().attr('id')) {
                siblingId = ui.item.prev().attr('id');
                pos = 'next';
            } else {
                siblingId = ui.item.next().attr('id');
                pos = 'prev';
            }

            $.ajax({
                url: Routing.generate('kreatys_cms_blocks_reorder', {_locale: locale, id: ui.item.attr('id'), siblingId: siblingId, pos: pos})
            });
        }
    });

    $('#compose_add_block').on('click', function () {
        blockCreate($(this).data('page-id'));
    });

    if ($('div.compose-box-content').length > 0) {
        initWysiwygEditors('glob');
    }

    var icon;
    $('#modalChoiceIcon').on('shown.bs.modal', function (e) {
        icon = $(e.relatedTarget);
    });

    $('#modalChoiceIcon .block-icon').click(function () {
        if (context === 'composer') {
            blockUpdate(icon.data('id'), 'icon', $(this).data('icon'));
        } else {
            iconUpdate(icon.data('id'), $(this).data('icon'), icon.data('route'));
        }
        icon.removeClass('fa-' + icon.data('icon')).addClass('fa-' + $(this).data('icon'));
        icon.data('icon', $(this).data('icon'));
        $('#modalChoiceIcon').modal('hide');
    });

    $('body').on('click', '.compose-block-enabled', function () {
        var btn = $(this);
        var blockId = $(this).data('id');
        $.ajax({
            url: Routing.generate('kreatys_cms_blocks_enabled', {_locale: locale, id: blockId}),
            success: function (data) {
                var enabled = data.enabled;
                if (enabled) {
                    btn.addClass('enabled');
                    btn.find('.fa-times').removeClass('fa-times').addClass('fa-check');
                } else {
                    btn.removeClass('enabled');
                    btn.find('.fa-check').removeClass('fa-check').addClass('fa-times');
                }
            }
        });
    });

    $('body').on('click', '.compose-block-settings', function () {
        blockUpdateSettings($(this).data('id'));
    });

    $('.modal').on('show.bs.modal', function () {
        sonataCollectionInitEvent();
        initInput();
    });
//    $('#modalBlockCreate').on('show.bs.modal', function () {
//        console.log('je passe');
//        $('.modal-body .box .box-body').bind('DOMNodeModified', function (event) {
//            console.log(event.target);
//            console.log('-----------------------------');
//        });
//    });


    $("#menuTreeList").sortable({
        handle: ".page-tree__item",
        placeholder: "menu-tree-highlight",
        items: "li.page-tree-li",
        forcePlaceholderSize: true,
        update: function (event, ui) {
            var siblingId;
            var pos;
            if (ui.item.prev().attr('id')) {
                siblingId = ui.item.prev().attr('id');
                pos = 'next';
            } else {
                siblingId = ui.item.next().attr('id');
                pos = 'prev';
            }

            $.ajax({
                url: Routing.generate('kreatys_cms_menu_reorder', {_locale: locale, id: ui.item.attr('id'), siblingId: siblingId, pos: pos})
            });
        }
    });

    $('.compose-box-block-header').dblclick(function () {
        if ($(this).parent('li.compose-box-block-content').length > 0) {
            blockCloseOpen($(this).parent('li.compose-box-block-content').attr('id'), $(this).siblings('.compose-block-btn').find('.open-close-btn'));
        }
    });

    openCurrentBlock();
});

var prevOpenBlock = 0;

function initWysiwygEditors(mode) {
    var inline, title_selector, text_selector;
    if(mode === 'glob') {
        inline = true;
        title_selector = ".title-editable";
        text_selector = ".text-editable";
    } else {
        inline = false;
        title_selector = ".bloc-title-editable";
        text_selector = ".bloc-text-editable";
    }
    
    
    tinymce.init({
        selector: title_selector,
        language: 'fr_FR',
        inline: inline,
        toolbar: "undo redo",
        menubar: false,
        forced_root_block: false,
        force_br_newlines: true,
        force_p_newlines: false,
        setup: function (editor) {
            if(mode === 'glob') {
                editor.on('change', function (e) {
                    editor.setContent(strip_tags(editor.getContent(), 'br'));
                    var $elt = $(editor.getElement());
                    blockUpdate($elt.data('id'), $elt.data('field'), editor.getContent());
                });
            }
//                editor.on('paste', function (e) {  
//                    console.log(editor.getContent());
//                 
//                });
        }
    });

    tinymce.init({
        selector: text_selector,
        language: 'fr_FR',
        inline: inline,
        plugins: [
            "advlist autolink lists link charmap anchor",
            "searchreplace visualblocks code",
            "insertdatetime contextmenu"
        ],
        toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link",
        setup: function (editor) {
            if(mode === 'glob') {
                editor.on('change', function (e) {
                    var $elt = $(editor.getElement());
                    blockUpdate($elt.data('id'), $elt.data('field'), editor.getContent());
                });
            }
            
        },
        link_list: function(result) {
            $.getJSON(Routing.generate('kreatys_cms_ajax_page_list', {_locale: locale}), function(data) {
                result(data.list);
            });
        },
        convert_urls: false,            
    });
}

function iconUpdate(id, icon, route) {
    $.ajax({
        url: Routing.generate(route, {_locale: locale, id: id, icon: icon}),
        type: 'GET',
        dataType: 'json',
        success: function (data) {
            if (data.result === 'ok') {

            } else {
                alert('Une erreur est survenue lors de la modification de l\'icon');
            }
        }
    });
}

function blockCreate(page_id) {
    var block_id = (blockCreate.arguments.length === 2) ? blockCreate.arguments[1] : 0;
    $.get(Routing.generate('kreatys_cms_ajax_block_create', {_locale: locale, page_id: page_id, block_id: block_id}), function (data) {
        $('#modalBlockCreate div.modal-body').html(data);
        $('#modalBlockCreate').modal('show');
        initFormBlockCreate(page_id, block_id);
    });
}

function initFormBlockCreate(page_id, block_id) {
    $('#modalBlockCreate div.box-header:first, #modalBlockCreate div.well, #modalBlockCreate div.locale_switcher').remove();
    $('#modalBlockCreate form').validate({
        errorClass: "error",
        errorPlacement: function (error, element) {
            error.appendTo(element.parents("div.form-group"));
        },
        submitHandler: function (form) {
            $(form).ajaxSubmit({
                success: function (data) {
                    if (data.result === 'ok') {
                        location.hash = '#anchor-' + data.objectId;
                        location.reload();
                    } else {
                        $('#modalBlockCreate div.modal-body').html(data);
                    }
                },
                error: function (jqXHR, textStatus) {
                    alert(textStatus);
                }
            });
        }
    });

    $("#modalBlockCreate input[type='checkbox']:not('label.btn>input'), #modalBlockCreate input[type='radio']:not('label.btn>input')").iCheck({
        checkboxClass: 'icheckbox_square-blue',
        radioClass: 'iradio_square-blue'
    });

    $('#modalBlockCreate form input.type').on('ifClicked', function () {
        $.get(Routing.generate('kreatys_cms_ajax_block_create', {_locale: locale, page_id: page_id, block_id: block_id}), {type: $(this).val(), name: $('#modalBlockCreate form input.name').val()}, function (data) {
            $('#modalBlockCreate div.modal-body').html(data);
            initFormBlockCreate(page_id, block_id);
            sonataCollectionInitEvent();
            initInput();
        });
    });
}

function blockEditContents(id) {
    var tl = findGetParameter('tl');
    var tlParam = (tl !== null) ? '?tl=' + tl : '';
    $.get(Routing.generate('kreatys_cms_ajax_block_edit_contents', {id: id, '_locale': 'fr'}) + tlParam, function (data) {
        $('#modalEditContents div.modal-body').html(data);
        $('#modalEditContents').modal('show');
        $('#modalEditContents div.box-header, #modalEditContents div.well, #modalEditContents div.locale_switcher').remove();
        $('#modalEditContents button[name="btn_update"]').on('click', function () {
            $('#modalEditContents form').submit();
        });
        initWysiwygEditors('edit');

        var m;
        var params;
        m = $('#modalEditContents form').attr('action').match(/uniqid=([^&]+)/);
        params = 'uniqid=' + m[1];
        m = $('#modalEditContents form').attr('action').match(/tl=([^&]+)/);
        if (m) {
            params = params + '&tl=' + m[1];
        }
        var action = Routing.generate('kreatys_cms_ajax_block_edit_contents', {_locale: locale, id: id});
        if (/\?.+=.+/.test(action)) {
            action = action + "&" + params;
        } else {
            action = action + "?" + params;
        }
        $('#modalEditContents form').attr('action', action + params);

        $('#modalEditContents form').validate({
            errorClass: "error help-block",
            errorPlacement: function (error, element) {
                error.appendTo(element.parents("div.form-group:first"));
            },
            submitHandler: function (form) {
                tinyMCE.triggerSave();
                $(form).ajaxSubmit({
                    success: function (data) {
                        if (data.result === 'ok') {
                            location.hash = '#anchor-' + id;
                            location.reload();
                        } else {
                            $('#modalEditContents div.modal-body').html(data);

                            $('#modalEditContents div.box-header, #modalEditContents div.well, #modalEditContents div.locale_switcher').remove();                            
                            
                            initWysiwygEditors('edit');

                            var m;
                            var params;
                            m = $('#modalEditContents form').attr('action').match(/uniqid=([^&]+)/);
                            params = 'uniqid=' + m[1];
                            m = $('#modalEditContents form').attr('action').match(/tl=([^&]+)/);
                            if (m) {
                                params = params + '&tl=' + m[1];
                            }
                            var action = Routing.generate('kreatys_cms_ajax_block_edit_contents', {_locale: locale, id: id});
                            if (/\?.+=.+/.test(action)) {
                                action = action + "&" + params;
                            } else {
                                action = action + "?" + params;
                            }
                            $('#modalEditContents form').attr('action', action + params);
                            
                             initInput();
                        }
                    },
                    error: function (jqXHR, textStatus) {
                        alert(textStatus);
                    }
                });
            }
        });
    });
}

function blockUpdateSettings(id) {
    $.get(Routing.generate('kreatys_cms_ajax_block_edit', {_locale: locale, id: id}), function (data) {
        $('#modalBlockEdit div.modal-body').html(data);
        $('#modalBlockEdit div.box-header, #modalBlockEdit div.well, #modalBlockEdit div.locale_switcher').remove();
        $('#modalBlockEdit div.modal-body form').prepend($('#modalBlockEdit div.modal-body form div.sonata-ba-collapsed-fields:last').html());
        $('#modalBlockEdit div.modal-body form > div.row').remove();
        $('#modalBlockEdit button[name="btn_update"]').on('click', function () {
            $('#modalBlockEdit form').submit();
        });
        $('#modalBlockEdit').modal('show');
        $('#modalBlockEdit').on('hidden.bs.modal', function (e) {
//            location.reload();
        });
        $('#modalBlockEdit form').validate({
            errorClass: "error help-block",
            errorPlacement: function (error, element) {
                error.appendTo(element.parents("div.form-group"));
            },
            submitHandler: function (form) {
                $(form).ajaxSubmit({
                    success: function (data) {
                        if (data.result === 'ok') {
                            location.reload();
                        } else {
                            $('#modalBlockEdit div.box-header, #modalBlockEdit div.well, #modalBlockEdit div.locale_switcher').remove();
                            $('#modalBlockEdit div.modal-body form').prepend($('#modalBlockEdit div.modal-body form div.sonata-ba-collapsed-fields:last').html());
                            $('#modalBlockEdit div.modal-body form > div.row').remove();
                            $('#modalBlockEdit button[name="btn_update"]').on('click', function () {
                                $('#modalBlockEdit form').submit();
                            });
                        }
                    },
                    error: function (jqXHR, textStatus) {
                        alert(textStatus);
                    }
                });
            }
        });
    });
}

function blockUpdate(id, field, value) {
    $.ajax({
        url: Routing.generate('kreatys_cms_ajax_block_update', {_locale: locale, tl: locale, id: id}),
        type: 'POST',
        data: {'field': field, 'value': value},
        dataType: 'json',
        success: function (data) {
            if (data.result === 'ok') {

            } else {
                alert('Une erreur est survenue lors de la modification du bloc');
            }
        }
    });
}

function blockDelete(id) {
    if (confirm('Etes vous sûr de vouloir supprimer ce bloc ?')) {
        $.ajax({
            url: Routing.generate('kreatys_cms_ajax_block_delete', {_locale: locale, id: id}),
            type: 'DELETE',
            dataType: 'json',
            success: function (data) {
                if (data.result === 'ok') {
                    if(prevOpenBlock) {
                        location.hash = prevOpenBlock;
                    }
                    location.reload();
                } else {
                    alert('Une erreur est survenue lors de la suppression du bloc');
                }
            }
        });
    }
}

function initInput() {
    $('.modal').find('select').each(function () {
        if ($(this).hasClass('select-fa-icon')) {
            $(this).select2({
                formatResult: formatFaIcons,
                formatSelection: formatFaIcons,
                escapeMarkup: function (m) {
                    return m;
                }
            });
        } else {
            $(this).select2();
        }
    });
}

function formatFaIcons(state) {
    if (!state.id)
        return state.text;
    return '<i class="fa fa-' + state.id.toLowerCase() + '"></i> ' + state.text;
}

function sonataCollectionInitEvent() {
    $('.modal-body .box .sonata-ba-field-standard-natural').bind('DOMNodeInserted', function (event) {
        if ($(event.target).hasClass('sonata-collection-row')) {
            setTimeout(function () {
                initInput();
            }, 100);
        }
    });
}

function blockCloseOpen(id, button) {
    if ($('#' + id).hasClass('close-box')) {
        $('#' + id).removeClass('close-box');
        $('#' + id).addClass('open-box');
        $(button).find('i.fa-chevron-down').removeClass('fa-chevron-down').addClass('fa-chevron-up');
        addLocationHash(id);
    } else {
        $('#' + id).addClass('close-box');
        $('#' + id).removeClass('open-box');
        $(button).find('i.fa-chevron-up').removeClass('fa-chevron-up').addClass('fa-chevron-down');
        if(location.hash == '#anchor-' + id) {
            location.hash = '';
        }
    }
}

function addLocationHash(id) {
    var hash = window.location.hash;
    if(hash) {
        prevOpenBlock = hash;
    }
    
    location.hash = '#anchor-' + id;
}

function openCurrentBlock() {
    var hash = window.location.hash;
    if(hash) {
        var id = hash.substring(1).replace("anchor-", '');
        openBlock(id);
    }
}
function openBlock(id) {
    $('#' + id).removeClass('close-box');
    $('#' + id).addClass('open-box');
    $('#' + id).find('.compose-block-btn').find('.open-close-btn').find('i.fa-chevron-down').removeClass('fa-chevron-down').addClass('fa-chevron-up');
    
    if($('#' + id).parents('.compose-box-block:first').attr('id')) {
        openBlock($('#' + id).parents('.compose-box-block:first').attr('id'));
    }
}

function strip_tags(str, allowed_tags)
{
    var key = '', allowed = false;
    var matches = [];
    var allowed_array = [];
    var allowed_tag = '';
    var i = 0;
    var k = '';
    var html = '';
    var replacer = function (search, replace, str) {
        return str.split(search).join(replace);
    };
    // Build allowes tags associative array
    if (allowed_tags) {
        allowed_array = allowed_tags.match(/([a-zA-Z0-9]+)/gi);
    }
    str += '';

    // Match tags
    matches = str.match(/(<\/?[\S][^>]*>)/gi);
    // Go through all HTML tags
    for (key in matches) {
        if (isNaN(key)) {
            // IE7 Hack
            continue;
        }

        // Save HTML tag
        html = matches[key].toString();
        // Is tag not in allowed list? Remove from str!
        allowed = false;

        // Go through all allowed tags
        for (k in allowed_array) {            // Init
            allowed_tag = allowed_array[k];
            i = -1;

            if (i != 0) {
                i = html.toLowerCase().indexOf('<' + allowed_tag + '>');
            }
            if (i != 0) {
                i = html.toLowerCase().indexOf('<' + allowed_tag + ' ');
            }
            if (i != 0) {
                i = html.toLowerCase().indexOf('</' + allowed_tag);
            }

            // Determine
            if (i == 0) {
                allowed = true;
                break;
            }
        }
        if (!allowed) {
            str = replacer(html, "", str); // Custom replace. No regexing
        }
    }
    return str;
}

function findGetParameter(parameterName) {
    var result = null,
        tmp = [];
    location.search
    .substr(1)
        .split("&")
        .forEach(function (item) {
        tmp = item.split("=");
        if (tmp[0] === parameterName) result = decodeURIComponent(tmp[1]);
    });
    return result;
}