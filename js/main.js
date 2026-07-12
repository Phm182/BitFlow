(function($) {
    "use strict";

    document.addEventListener('DOMContentLoaded', function() {


        // Boton menu 

        document.querySelector('.material-design-hamburger__icon').addEventListener('click',
            function() {
                var child;

                document.body.classList.toggle('background--blur');
                this.parentNode.nextElementSibling.classList.toggle('menu--on');

                child = this.childNodes[1].classList;

                if (child.contains('material-design-hamburger__icon--to-arrow')) {
                    child.remove('material-design-hamburger__icon--to-arrow');
                    child.add('material-design-hamburger__icon--from-arrow');
                    $(".bitflow-hero, .trifusion").removeClass("tit-position");

                } else {
                    child.remove('material-design-hamburger__icon--from-arrow');
                    child.add('material-design-hamburger__icon--to-arrow');
                    $(".bitflow-hero, .trifusion").addClass("tit-position");
                }

            });


    });
})
(jQuery);