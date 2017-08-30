/**
 * Created by nguyenthierry on 02/10/2016.
 *
 */
$(document).ready(function() {

    $('#blog_content button').click(function () {
       console.log($('textarea').val());
    });

    // $(".grid_overflow").hover(function () {
    //    // $(this).find("p").addClass('titleSlide');
    //    //  console.log('toto');
    // });

    $(".album").mouseover(function(){
        $(this).children("p").removeClass('titleSlideOut');
        $(this).children("p").removeClass('album_title');
        $(this).children("p").addClass('titleSlideIn');
    });

    $(".album").mouseout(function(){
        $(this).children("p").removeClass('titleSlideIn');
        $(this).children("p").addClass('titleSlideOut ');
        // $(this).children("p").addClass('album_title');
    });

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
        $('.slider').fadeOut();
        $(".slider img:last-child").remove();
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

    /*XXXXXXXXXXXXXXXXXXXXXXXX ---- ajax img profil ---- XXXXXXXXXXXXXXXXXXXXXXXX */
    var url = window.location.pathname;
    var id = url.substring(url.lastIndexOf('/') + 1);
    var src;
    var alb;

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

        if(scrollTop<logoHeight){
            $('.ml').removeClass('fixed');
            $('.menu_xs').removeClass('fixed');
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
    $('.divUp').addClass('slideUp');
    $('.divLeft').addClass('slideLeft');

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

    if(location.hash != ''){
        $('a[href="'+location.hash+'"]').trigger('click').addClass('active');
    }


    $('body').css('visibility','visible');

    $("#blog article").each(function(i) {
        $(this).delay(500 * i).fadeIn(500);
    });



    /*XXXXXXXXXXXXXXXXXXXXXXXX ---- Enable click right ---- XXXXXXXXXXXXXXXXXXXXXXXX */
    $("body").on("contextmenu",function(e){
        return false;
    });

    /*XXXXXXXXXXXXXXXXXXXXXXXX ---- Slider image ---- XXXXXXXXXXXXXXXXXXXXXXXX */
    var pointer;

    $('.gallery .grid-item').click(function(){
        var url = window.location.pathname;
        var id = url.substring(url.lastIndexOf('/') + 1);
        var title=url.split('/');
        pointer = $(this).index();
        console.log('id :',pointer);

        $('.slider').fadeIn().css({
            'display':'flex',
            'align-item':'center'
        });
        $.ajax({
            type:'GET',
            url:'/portfolio/album/'+title[3]+'/'+id+'/slider',
            dataType:'JSON',
            success:function(res){
                console.log(res);
                var img = $('<img>');
                $('.sliderImg').css({
                    "background": "url(/images/album/"+title[3]+"/"+res[pointer]+") no-repeat",
                    "background-size":"contain",
                    "background-position": "center",
                });

                console.log(res[pointer]);

                $('.after').click(function () {
                    pointer++;
                    if (pointer >= res.length) {
                        pointer = res.length-1;
                    }
                    console.log('after ',pointer);
                    $('.sliderImg').css({
                        "background": "url(/images/album/"+title[3]+"/"+res[pointer]+") no-repeat",
                        "background-size":"contain",
                        "background-position": "center"
                    })
                });


                $('.prev').click(function () {
                    pointer--;
                    if (pointer < 0) {
                        pointer = 0;
                    }
                    console.log('after ',pointer);
                    $('.sliderImg').css({
                        "background": "url(/images/album/"+title[3]+"/"+res[pointer]+") no-repeat",
                        "background-size":"contain",
                        "background-position": "center"
                    })
                });
            }
        });
    });



});

