
$(function () {
    console.log('coucou');

    $('.filter_container .form-percent').each(function () {
        var parent = $(this).parent('div');
        parent.html('<div class="input-group" style="width: 196px;">');
        parent.find('.input-group').html($(this));
        parent.find('.input-group').append('<span class="input-group-addon"><span class="fa fa-percent"></span></span>');
    });

    $(document).on('click', '.modal .btn-success', function (e) {
//        e.preventDefault();
        $(e.currentTarget).parents('.modal').modal('hide');
        $('#loading').modal({
            backdrop: 'static'
        });
        $('#loading').modal('show');
        $('#loading .loading-content').css({
            'margin-top': ($(window).innerHeight() - 136) / 2
        });
    });

    $('.sonata-ba-filter .box.box-primary').css({
        'max-height': $(window).innerHeight() - $('header').innerHeight() - $('.right-side .content-header').innerHeight() - 20 - 12
    });
    var filtersOpen = true;
    $('.sonata-ba-filter').animate({
        right: -($('.sonata-ba-filter').innerWidth()) - 5
    }, 500, function () {
        filtersOpen = false;
        $('.sonata-ba-filter .sonata-ba-filter-open i').addClass('fa-chevron-up');
        $('.sonata-ba-filter .sonata-ba-filter-open i').removeClass('fa-chevron-down');
    });
    $('.sonata-ba-filter .sonata-ba-filter-open').click(function () {
        if (filtersOpen) {
            $('.sonata-ba-filter').animate({
                right: -($('.sonata-ba-filter').innerWidth()) - 5
            }, 500, function () {
                filtersOpen = false;
                $('.sonata-ba-filter .sonata-ba-filter-open i').addClass('fa-chevron-up');
                $('.sonata-ba-filter .sonata-ba-filter-open i').removeClass('fa-chevron-down');

            });
        } else {
            $('.sonata-ba-filter').animate({
                right: 0
            }, 500, function () {
                filtersOpen = true;
                $('.sonata-ba-filter .sonata-ba-filter-open i').removeClass('fa-chevron-up');
                $('.sonata-ba-filter .sonata-ba-filter-open i').addClass('fa-chevron-down');
            });
        }

    });

    $('body').click(function () {
        if (filtersOpen) {
            $('.sonata-ba-filter').animate({
                right: -($('.sonata-ba-filter').innerWidth()) - 5
            }, 500, function () {
                filtersOpen = false;
                $('.sonata-ba-filter .sonata-ba-filter-open i').addClass('fa-chevron-up');
                $('.sonata-ba-filter .sonata-ba-filter-open i').removeClass('fa-chevron-down');

            });
        }
    });

    $(window).resize(function () {
        $('.sonata-ba-filter .box.box-primary').css({
            'max-height': $(window).innerHeight() - $('header').innerHeight() - $('.right-side .content-header').innerHeight() - 20 - 12
        });
    });
});