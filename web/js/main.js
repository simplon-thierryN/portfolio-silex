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

    $('.delAlbum').click(function () {
        $('.confirmation').fadeIn();
    });

    $('.no').click(function () {
        $('.confirmation').fadeOut();
    });



    /*XXXXXXXXXXXXXXXXXXXXXXXX ---- nav active ---- XXXXXXXXXXXXXXXXXXXXXXXX */

    var loc =$(location).attr('href');
    var link = loc.split("/");
    console.log('link =>',link);
    console.log('link3 =>',link[3].replace('#',''));
    var toto = link[3].replace('#','/').split('');
    console.log('toto =>',toto[0]);

    $('header a[href="/'+link[3]+'"]').addClass('active');
    $('header a[href="'+toto[0]+'"]').addClass('active');


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
    var logo = $('.logo');
    var opacStart = 40;
    var opacUntil = 220;



    //fixed nav bar scrollTop & opacity logo
    $(window).scroll(function () {
        var scrollTop = $(window).scrollTop();
        var calc = 1-scrollTop/opacUntil;

        if(scrollTop<180){
            $('.category').removeClass('fixed');
            $('.container').css('border-top','1px solid');
        }
        else{
            $('.category').addClass('fixed');
            $('.fixed').css({
                'top':headerHeight
            });

            $('.container').css('border-top','hidden');
        }

        if( calc<=0 ){
            logo.css('opacity',0);
        }else if( calc>= 1){
            logo.css('opacity',1);
        }
        logo.css('opacity', calc);
    });

    // add margin top logo
    logo.css({
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


    $grid.imagesLoaded().progress( function() {
        $grid.masonry('reloadItems');
        $grid.masonry('layout');
    });


    $('.index').click(function () {
        grid.find('.hidden').fadeIn();
        grid.find('.grid-item').removeClass('hidden');
        $grid.masonry('reloadItems');
        $grid.masonry('layout');
    });


// add class active on link categgory & fadeIn/fadeOut grid-item
    $('.category a').click(function (e) {
        var cls = $(this).attr('href').replace('#','');
        $('.category a').removeClass('active');
        $(this).addClass('active');
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

    $grid.on( 'click', '.grid-album', function() {
        // change size of item via class
        $( this ).toggleClass('grid-item--gigante');
        // trigger layout
        $grid.masonry();
    });

    //
    // $('.grid-item').mouseover(function(){
    //     var grid_height = $('.grid-item img').height();
    //     $('.grid-item img').css('height',grid_height);
    //     console.log('height =>', grid_height);
    // });

});


