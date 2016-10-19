/**
 * Created by nguyenthierry on 02/10/2016.
 */
$(function(){

    var grid = $('.grid');
    var headerHeight = $("header").height();
    var logoHeight = $('.logo').height();
    var logoWidth = $('.logo').width();

    $('.addArticle').click(function () {
        $('.update_form').fadeIn();
    })

    $('button').click(function () {
        var form = $(this).attr('class');
        $("#"+form+"").fadeIn();
    });

    $('span').click(function () {
        var form = $(this).attr('class');
        $("#"+form+"").fadeIn();
    });

    $('.back').click(function () {
       $('.update_form').fadeOut();
    });

    $('.no').click(function () {
       $('.confirmation').fadeOut();
    });

    // $('nav a').click(function () {
    //     $('nav a ').removeClass('isActive');
    //     $(this).addClass('isActive');
    // });

    /*XXXXXXXXXXXXXXXXXXXXXXXX ---- Height auto textarea ---- XXXXXXXXXXXXXXXXXXXXXXXX */

    var numberArea = $('textarea').length-1;

    function expandTextarea(id,i) {

        var $element = $('textarea').get(i);

        $element.addEventListener('keyup', function() {
            this.style.overflow = 'hidden';
            this.style.height = 0;
            this.style.height = this.scrollHeight + 'px';
        }, false);

        console.log($element);
    }

    if($("textarea").length>0){
        for(var i =0; i<=numberArea;i++){
            expandTextarea('textarea',i);
        }
    }

    /*XXXXXXXXXXXXXXXXXXXXXXXX ---- SCROLL ---- XXXXXXXXXXXXXXXXXXXXXXXX */

    //fixed nav bar scrollTop
    $(window).scroll(function () {
        var scrollTop = $('body').scrollTop();
        if(scrollTop<180){
            $('.category').removeClass('fixed');
        }
        else{
            $('.category').addClass('fixed');
            $('.fixed').css({
                'top':headerHeight
            })
        }
    });

    // add margin top logo
    $(".logo").css({
        'top':headerHeight * 1.5,
        'margin-left': -logoWidth/2
    });

    // add margin top section
    $('section').css({
        'margin-top': logoHeight + headerHeight * 2
    });

    /*XXXXXXXXXXXXXXXXXXXXXXXX ---- MANSORY ---- XXXXXXXXXXXXXXXXXXXXXXXX */

    var $grid= grid.masonry({
        isAnimated:true,
        animationOptions: {
            duration: 750,
            easing: 'linear',
            queue: false
        },
        itemSelector:'.grid-item:not(.hidden)',
        isFitWidth: true
    });


    $('.index').click(function () {
        grid.find('.hidden').fadeIn();
        grid.find('.grid-item').removeClass('hidden');
        $grid.masonry('reloadItems');
        $grid.masonry('layout');
    });

    $('.masonry a').click(function (e) {
        var cls = $(this).attr('href').replace('#','');
        grid.find('.hidden').fadeIn();
        grid.find('.grid-item').removeClass('hidden');
        grid.find('.grid-item:not(.'+cls+')').addClass('hidden').stop().fadeOut();
        $grid.masonry('reloadItems');
        $grid.masonry('layout');
        location.hash = cls;
        e.preventDefault();
    });

    if(location.hash != ''){
        $('a[href="'+location.hash+'"]').trigger('click');
    }

});


