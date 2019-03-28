/*******************************
* (C) 2018 grupomarostica.com
* Written by Franco Marostica
*******************************/

var GMWeb = {
    caller: null,
    initialize: function(){
        var $this = this;

        $(window).resize(function(){
            $this.resize();
        });
    },
    resize: function(){
        var headerHeight = $(".gm-header").outerHeight();
        $(".gm-hero").css("padding-top", headerHeight);

        $(".gm-offcanvas-menu").css("top",headerHeight);
    },
    burgerMenu: function() {
        $('body').on('click', '.nav-toggle', function(event){
            var $this = $(this);
            if ( $('body').hasClass('overflow offcanvas') ) {
                $('body').removeClass('overflow offcanvas');
            } else {
                $('body').addClass('overflow offcanvas');
            }
            $this.toggleClass('active');
            event.preventDefault();
        });
    },
    offcanvasMenu: function() {
        $('body').prepend('<div class="gm-offcanvas-menu" />');
        $('body').prepend('<a href="#" class="nav-toggle"><i></i></a>');
        var clone1 = $('.gm-menu').clone();
        $('.gm-offcanvas-menu').append(clone1);

        $('#offcanvas-menu .has-dropdown').addClass('offcanvas-has-dropdown');
        $('#offcanvas-menu')
            .find('li')
            .removeClass('has-dropdown');

        // Hover dropdown menu on mobile
        $('.offcanvas-has-dropdown').mouseenter(function(){
            var $this = $(this);
            $this
                .addClass('active')
                .find('ul')
                .slideDown(500, 'easeOutExpo');				
        }).mouseleave(function(){
            var $this = $(this);
            $this
                .removeClass('active')
                .find('ul')
                .slideUp(500, 'easeOutExpo');				
        });

        $(window).resize(function(){
            if ( $('body').hasClass('offcanvas') ) {
                $('body').removeClass('offcanvas');
                $('.nav-toggle').removeClass('active');
            }
        });
    }
}

GMWeb.initialize();
$(document).ready(function(){
    GMWeb.resize();
    GMWeb.burgerMenu();
    GMWeb.offcanvasMenu();
});