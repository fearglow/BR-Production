jQuery(function ($) {
    var body = $('body');

    if ($('.has-matchHeight', body).length) {
        $('.has-matchHeight', body).matchHeight();
    }

    $('.st-quickview-demo .toolbar-icon').on('click', function(){
        $('.st-quickview-backdrop').addClass('active');
        $('.st-quickview-demo').addClass('active');
    });

    $('.st-quickview-demo .toolbar-content .close').on('click', function(){
        $(this).closest('.st-quickview-demo').removeClass('active');
        $('.st-quickview-backdrop').removeClass('active');
    });

    $('.st-quickview-backdrop').on('click', function(){
        $('.st-quickview-demo').removeClass('active');
        $(this).removeClass('active');
    });

    $('.qv-demo-tab a').on('click', function (e) {
        e.preventDefault();
        $('.qv-demo-tab a').removeClass('active');
        $(this).addClass('active');

        var dataTab = $(this).data('tab');

        $('.qv-demo-tab-wrapper .item-tab').removeClass('active');
        $('.qv-demo-tab-wrapper .item-tab[data-tab="'+ dataTab +'"]').addClass('active');
    });
});
