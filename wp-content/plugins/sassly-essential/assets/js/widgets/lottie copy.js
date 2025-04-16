(function ($) {
    /**
     * @param $scope The Widget wrapper element as a jQuery element
     * @param $ The jQuery alias
     */

    var Wcf_Lottie = function ($scope, $) {
        let $container = $scope.find('.elementor-widget-container');
        let settings = JSON.parse( $scope.find('.wcf-lottie-wrp').attr('data-settings') ); 
        let s_id = '#wcf-lottie-player-'+$scope.attr('data-id');        
        const player_element = document.querySelector(s_id);      
        let observer = new IntersectionObserver(function (entries, observer) {
          for (let entry of entries) {         
        
            if( entry.isIntersecting && ('viewport' == settings.event || settings.play == 'inview')){
               player_element.play(); 
            }                   
           
          }
        });
     
        player_element.addEventListener("click", () => {   
        
          if(settings.event === 'click'){
            player_element.play();      
          }  
          
          if(settings.pause === 'onclick'){
            player_element.pause();      
          }
          
        });
        
        player_element.addEventListener("mouseout", () => {    
          if(settings.pause === 'onmouseleave'){
            player_element.pause();      
          }             
        });
        
        player_element.addEventListener("mouseover", () => {    
          if(settings.play === 'onhover'){
            player_element.play();      
          }             
        });
         
        observer.observe(player_element);
        let $start_point =  settings.start_point == '' ? 0 : parseInt(settings.start_point);
        let $end_point =  settings.end_point == '' ? 280 : parseInt(settings.end_point);       
        if("cursor_move" == settings.event){
       
          let $cursor_move = {
            position: { x: [0, 1], y: [0, 1] },
            type: "seek",
            frames: [$start_point, $end_point]
          }
        
          LottieInteractivity.create({
            player: s_id,
            mode:"cursor",
            actions: [
              $cursor_move
            ]
          });
        }
             
        if(settings.event === 'scroll'){
            let $scroll_move = {
              visibility: [0,1],
              type: "seek",
              frames: [$start_point, $end_point]
            }
            LottieInteractivity.create({
              player: s_id,
              mode: 'scroll',
              actions: [
                {
                  visibility: [0,1],
                  type: 'seek',
                  frames: $scroll_move
                },
              ]
            });
        }
                 
        if(settings.event === 'hover'){
         
             LottieInteractivity.create({
              player: s_id,
              mode:"cursor",
              actions: [
                {
                  type: "hover",
                  forceFlag: true
                }
              ]
            });
            
        }       
       
     };

    // Make sure you run this code under Elementor.
    $(window).on('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/wcf--lottie-animation.default', Wcf_Lottie);
    });

})(jQuery);