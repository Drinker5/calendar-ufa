jQuery(document).ready(function($) {
 $('.royalSlider').royalSlider({
        arrowsNav: false,
        imageScalePadding: 0,
        controlNavigation: 'bullets',
        loop: true,
        autoPlay: {
                enabled: true,
                pauseOnHover: true,
                delay: 3000,
                stopAtAction: false
            }
      });
});