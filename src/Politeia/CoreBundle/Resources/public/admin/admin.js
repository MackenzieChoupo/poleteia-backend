
$(function () {

    tinymce.init({
        selector: "textarea.texte-simple-wysiwyg",
        toolbar: 'fontselect | fontsizeselect | removeformat | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent blockquote',
        menubar: 'edit format tools',
        statusbar: false,
        elementpath: false,
        plugins: [
            "advlist autolink lists link charmap anchor",
            "searchreplace visualblocks code",
            "insertdatetime contextmenu paste"
        ],
        language_url: '/bundles/politeiacore/admin/langs/fr_FR.js',
        fontsize_formats: '8px 9px 10px 11px 12px 13px 14px 15px 16px 17px 18px 19px 20px 22px 24px 26px 28px 30px 32px 34px 36px 38px 40px'
    });

    tinymce.init({
        selector: "textarea.texte-mini-wysiwyg",
        toolbar: 'removeformat | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist blockquote',
        menubar: 'edit tools',
        statusbar: false,
        elementpath: false,
        plugins: [
            "advlist autolink lists link charmap anchor",
            "searchreplace visualblocks code",
            "insertdatetime contextmenu paste"
        ],
        language_url: '/bundles/politeiacore/admin/langs/fr_FR.js',
        fontsize_formats: '8px 9px 10px 11px 12px 13px 14px 15px 16px 17px 18px 19px 20px 22px 24px 26px 28px 30px 32px 34px 36px 38px 40px'
    });
    
    
    if($('.frm-sondage').length > 0) {
        var $divQuestionCible = $('div[id$="_questionCible"]');       
        if($('input[id$="_poseQuestionCible_1"]').is(':checked')) {
            $divQuestionCible.show();
            $('label[for$="_questionCible"]').html($('label[for$="_questionCible"]').html() + ' *');            
            $('input[id$="_questionCible"]').prop('required', true);
        } else {
            $divQuestionCible.hide();
            $('label[for$="_questionCible"]').html($('label[for$="_questionCible"]').html().replace(/\*/, ''));            
            $('input[id$="_questionCible"]').removeAttr('required');
        }
       
        $('input[id$="_poseQuestionCible_1"]').on('ifClicked', function() {
            $divQuestionCible.show();
            $('label[for$="_questionCible"]').html($('label[for$="_questionCible"]').html() + '*');            
            $('input[id$="_questionCible"]').prop('required', true);
        });
        $('input[id$="_poseQuestionCible_0"]').on('ifClicked', function() {            
            $divQuestionCible.hide();
            $('label[for$="_questionCible"]').html($('label[for$="_questionCible"]').html().replace(/\*/, ''));            
            $('input[id$="_questionCible"]').removeAttr('required');
        });
        
       
    }
});


