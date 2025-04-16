( function( $ ) {
  /**
   * @param $scope The Widget wrapper element as a jQuery element
   * @param $ The jQuery alias
   */
  var WcfTHeader_Menu = function ($scope, $) {
      var base_url = window.location.origin+window.location.pathname;
      
      var wrapper = $scope.find('.header__inner-2');
      const is_active_url = wrapper.attr('data-urlattr');
      $(document).on('click', '.header__navicon-2' ,function () {          
        $scope.find('.header__area-2').toggleClass('active');   
      }); 
      
      $(document).on('click', '.close', function(e){
        e.stopPropagation();
        $scope.find('.header__area-2').removeClass('active'); 
        $('.wcf-header-pin-js').removeClass('active');
      }); 
      
      if (typeof(gsap) === "object") {
          // Page loaded event
          if(window.location.hash) {
            var hash = window.location.hash.substring(1);
            let _element = document.querySelector('#'+hash),
              linkST = ScrollTrigger.create({
                trigger: _element,
                start: "top top",
              });
              ScrollTrigger.create({
                trigger: _element,
                start: "top center",
                end: "bottom center",
                onToggle: self => self.isActive && setActive(_element)
              });
              
              gsap.to(window, {
                duration: 0.1,
                scrollTo: linkST.start,
                overwrite: "auto"
              });
          } 
          // Page loaded event end
          // Product Designer Sidebar Menu
            let sidebar_links = gsap.utils.toArray($scope.find(".sidebar-menu li a"));    
              sidebar_links.forEach(a => {
              let element = document.querySelector(a.getAttribute("href")),
              linkST = ScrollTrigger.create({
                trigger: element,
                start: "top top",
              });
              
              ScrollTrigger.create({
                trigger: element,
                start: "top center",
                end: "bottom center",
                onToggle: self => self.isActive && setActive(a)
              });
              
              a.addEventListener("click", e => {
                e.preventDefault();
                //base_url
                if(is_active_url == 'yes'){
                  const nextState = { additionalInformation: 'Updated the URL' };
                  window.history.pushState(nextState, null, base_url+a.getAttribute("href"));
                }
                gsap.to(window, {
                  duration: 0.1,
                  scrollTo: linkST.start,
                  overwrite: "auto"
                });
                if($( window ).width() <= 991){
                  $scope.find('.header__area-2').removeClass('active');               
                }
              });
            });
      
        function setActive(link) {
          sidebar_links.forEach(el => el.classList.remove("active"));
          link.classList.add("active");
        }
          /////////////////////////////////////////////////////
        var gsap_container = null;      
        if($( window ).width() >= 991) {
            // Main Header position Sticky JS
            gsap_container = gsap.to(".wcf-header-pin-js", {
              scrollTrigger: {
                trigger: ".body-wrapper",
                start: "top top",
                end: "bottom bottom",
                markers: false,
                pin: ".wcf-header-pin-js",
                pinSpacing: false,                
              },
            });  
        }
      }
      window.addEventListener("resize", function(){
          if($( window ).width() >= 991) {
            // Main Header position Sticky JS
            if (typeof(gsap) === "object") {
              gsap_container = gsap.to(".wcf-header-pin-js", {
                scrollTrigger: {
                  trigger: ".body-wrapper",
                  start: "top top",
                  end: "bottom bottom",
                  markers: false,
                  pin: ".wcf-header-pin-js",
                  pinSpacing: false,                
                },
              });  
          }
            
        }else{  
          $scope.find('.pin-spacer').toggleClass('remove'); 
          $('.wcf-header-pin-js').removeClass('active');
          this.setTimeout(function(){
            $scope.find('.pin-spacer').removeAttr('style'); 
            $scope.find('.pin-spacer .wcf-header-pin-js').removeAttr('style'); 
          },1000);
                           
        }
      });
    
  };

  // Make sure you run this code under Elementor.
  $( window ).on( 'elementor/frontend/init', function() {
      elementorFrontend.hooks.addAction( 'frontend/element_ready/wcf--site-landing-page.default', WcfTHeader_Menu );
  } );
} )( jQuery );