/**
 * Created by nguyenthierry on 02/10/2016.
 *
 */
$(window).on('load', function(){

    var grid = $('.grid');

    var filterBool = false;

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

    $('.filter').click(function () {
        if(filterBool==false){
            $('.xs').fadeIn();
            filterBool = true;
        }
        else {
            $('.xs').fadeOut();
            filterBool = false;
        }
    });

    $(window).resize(function () {
        var logwidth = $('.logo').width();
        if(logwidth != logoWidth){
            location.reload();
        }
    });


    // var tonyHeight = $('.tony').height();
    // console.log('tonyHeight ',tonyHeight);
    // $('.tony').css('height',tonyHeight*1.5);


    /*XXXXXXXXXXXXXXXXXXXXXXXX ---- ajax img profil ---- XXXXXXXXXXXXXXXXXXXXXXXX */
    var url = window.location.pathname;
    var id = url.substring(url.lastIndexOf('/') + 1);
    var src;
    var img;

    $('#article_portrait img').click(function () {
        src = $(this).attr('src');
        img = src.substring(src.lastIndexOf('/')+1);
        console.log('album id => ',id);
        console.log('album img =>',img);

        $.ajax({
            type:'POST',
            url:'/update/img_profil/'+id,
            data:{img:img},
            success:function(res){
                console.log(res);
            }
        });
    });

    $('#blog_portrait img').click(function () {
        src = $(this).attr('src');
        img = src.substring(src.lastIndexOf('/')+1);
        $.ajax({
            type:'POST',
            url:'/update/img_blog/'+id,
            data:{img:img},
            success:function (res) {
                console.log(res);
            }

        });
    });

    /*XXXXXXXXXXXXXXXXXXXXXXXX ---- nav active ---- XXXXXXXXXXXXXXXXXXXXXXXX */

    //add class active nav portfolio
    var loc =$(location).attr('href');
    var link = loc.split("/");

    var href = link[3].replace('#','/').split('');
    if(link[3]=='portfolio'){
        $('header a[href="/"]').addClass('active');
    }
    else{
        $('header a[href="/'+link[3]+'"]').addClass('active');
    }
    // $('header a[href="'+href[0]+'"]').addClass('active');

    console.log('link ',link);
    console.log('link3 ',link[3]);
    console.log('href ',href[0]);
    //add class active nav price
    var urlTarif = link[3].replace('#','/');
    var navTarif = urlTarif.split('/');

    if(link[3]=='tarifs'){
        $('.evenement').addClass('hidden').fadeOut();
        $('.pro').addClass('hidden').fadeOut();
    }

    $('header a[href="/'+navTarif[0]+'"]').addClass('active');
    $('.price a[href="'+navTarif[01]+'"]').addClass('active');

    $('.price a').click(function(){
        var menuActive = $(this).attr('href').replace('#','');
        $('.price a').removeClass('active');
        $(this).addClass('active');
        $('.menu_price').find('.hidden').fadeIn();
        $('.menu_price').find('article').removeClass('hidden');
        $('.menu_price').find('article:not(.'+menuActive+')').addClass('hidden').stop().fadeOut();
        $('.xs').fadeOut();
    });




    /*XXXXXXXXXXXXXXXXXXXXXXXX ---- Height auto textarea ---- XXXXXXXXXXXXXXXXXXXXXXXX */

    var numberArea = $('textarea').length-1;

    function expandTextarea(id,i) {

        var $element = $('textarea').get(i);

        $element.addEventListener('keyup', function() {
            this.style.overflow = 'hidden';
            this.style.height = 0;
            this.style.height = this.scrollHeight + 'px';
        }, false);
        // console.log($element);
    }

    if($("textarea").length>0){
        for(var i =0; i<=numberArea;i++){
            expandTextarea('textarea',i);
        }
    }

    /*XXXXXXXXXXXXXXXXXXXXXXXX ---- SCROLL ---- XXXXXXXXXXXXXXXXXXXXXXXX */
    var logo = $('.logo');
    var headerHeight = $("header").height();
    var logoHeight = $('.logo').height();
    var logoWidth = $('.logo').width();
    var opacUntil = 220;

    // console.log('logoWidth ',logoWidth);
    console.log('section ',$('section').scrollTop());

    //fixed nav bar scrollTop & opacity logo
    $(window).scroll(function () {

        var headerHeightFixed = $("header").height();
        var scrollTop = $(window).scrollTop();
        var calc = 1-((scrollTop/opacUntil)*3);

        // if(scrollTop<headerHeight){
        //     $('header').removeClass('headerFixed');
        // }
        // else if (scrollTop>(headerHeight)){
        //     $('header').addClass('headerFixed');
        // }

        if(scrollTop<logoHeight){
            $('.ml').removeClass('fixed');
            $('.menu_xs').removeClass('fixed');
            // $('.container').css('border-top','1px solid');
        }
        else if(scrollTop>logoHeight){
            // log('add fixed');
            $('.ml').addClass('fixed');
            $('.menu_xs').addClass('fixed');
            $('.fixed').css({
                'top':headerHeight
            });
            $('.container').css('border-top','hidden');
        }

        if(scrollTop<headerHeight){
            $('.ml').removeClass('fixed');
            $('.menu_xs').removeClass('fixed');
            // $('.container').css('border-top','1px solid');
        }
        else if(logo.length<=0 && scrollTop>headerHeight){
            // log('add fixed');
            $('.ml').addClass('fixed');
            $('.menu_xs').addClass('fixed');
            $('.fixed').css({
                'top':headerHeightFixed
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
        'top': headerHeight,
        'margin-left': -logoWidth/2
    });
console.log('logo ',logoHeight);

    console.log('headerHeight ',headerHeight);
    // add margin top section
    if(logo.length<=0){
        $('section').css({
            'margin-top': headerHeight
        });
    }else{
        $('section').css({
            'margin-top': logoHeight + headerHeight
        });
    }

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
    $('.portfolio a').click(function (e) {
        var cls = $(this).attr('href').replace('#','');
        console.log(cls)
        $('.portfolio a').removeClass('active');
        $(this).addClass('active');
        grid.find('.hidden').fadeIn();
        grid.find('.grid-item').removeClass('hidden');
        grid.find('.grid-item:not(.'+cls+')').addClass('hidden').stop().fadeOut();
        $grid.masonry('reloadItems');
        $grid.masonry('layout');
        location.hash = cls;
        $('.xs').fadeOut();
        e.preventDefault();
    });


    $grid.on( 'click', '.grid-album', function() {
        // change size of item via class
        $( this ).toggleClass('grid-item--gigante');
        // trigger layout
        $grid.masonry();
    });

    if(location.hash != ''){
        $('a[href="'+location.hash+'"]').trigger('click').addClass('active');
    }
});

$(document).ready(function() {
    $('body').css('visibility','visible');

    $("#blog article").each(function(i) {
        $(this).delay(500 * i).fadeIn(500);
    });


    $("img").on("contextmenu",function(e){
        return false;
    });

});

