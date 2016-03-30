function EasyPeasyParallax() {
    var	windowWidth = $(window).width();
    if(windowWidth > 980){
        scrollPos = $(this).scrollTop();
        $('.parallax').not('.no-animate').css({
            'background-position' : '50%' + (-scrollPos)+"px"
        });
        var text = $('.text').not('.no-animate');
        text.css({
            'margin-top': (scrollPos/4)+"px",
            'opacity': 1-(scrollPos/100)
        });
        var opacityValue = text.css('opacity');
        if(opacityValue == 0){
            text.hide();
        }else{
            text.show();
        }
        if(navigator.userAgent.match(/(iPhone|iPad|iPod)/i) || $('html').hasClass('touch')) {
            $('.parallax').css({
                'background-attachment' : 'scroll'
            }).addClass('no-animate');
            text.addClass('no-animate');
            $('.logotext').css({
                'position': 'absolute'
            });
            opacityValue = 1;
        }
    }
}

function someResize(){
    EasyPeasyParallax();
    var	windowWidth = $(window).width(),
        maxHeight = $('.near-big').height() - 42;
    if(windowWidth > 768){
        $('.big-preview .thumbnail').css('height', maxHeight);
    }
    else{
        $('.big-preview .thumbnail').css('height', 'auto');
    }

    if($('.colorbox').length){
        $('a.colorbox').colorbox({
            rel:'gal',
            retinaImage: true,
            opacity: 1,
            current: false,
            maxWidth: '95%',
            maxHeight: '95%'
        })
    }
}
$(document).ready(function(){

    someResize();

    $('a[rel="popover"]').popover();
    $('a[rel="tooltip"]').tooltip();
    $('.carousel').carousel();


    $(window).scroll(function() {
        EasyPeasyParallax();
        if ($(this).scrollTop() > 300) {
            $('.goTop-link').fadeIn();
        } else {
            $('.goTop-link').fadeOut();
        }
    });

    $('.goTop').on('click', function(){
        $('body,html').animate({scrollTop: 0}, 1000);
        return false;
    });

    var minFormHeight = $('.form-page .text').outerHeight() + $('navbar').height() + parseInt($('.form-page .text').css('top'), 10) + 20;
    $('.form-page').css('min-height', minFormHeight);

    $('#welcome').on('click', function(){
        $('body,html').animate({scrollTop: $(this).scrollTop()+300}, 1000);
        return false;
    });
});

$(window).resize(function(){
    someResize();
});

$(window).scroll(function() {
    var height_screen = $(window).height();
    var topOfWindow = $(window).scrollTop();

    $('.blockquote').each(function(){
        var imagePos = $(this).offset().top;


        if (imagePos < topOfWindow + height_screen-100) {
            $(this).addClass("slideLeft");
        }
    });
    $('.blockquote-reverse').each(function(){
        var imagePos = $(this).offset().top;

        if (imagePos < topOfWindow + height_screen -100) {
            $(this).addClass("slideRight");
        }
    });
    $('.contact img').each(function(){
        var imagePos = $(this).offset().top;

        if (imagePos < topOfWindow + height_screen -100) {
            $(this).addClass("hatch");

        }
    });
});