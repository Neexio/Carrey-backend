( function( $ ) {

  /**
   * @param $scope The Widget wrapper element as a jQuery element
   * @param $ The jQuery alias
   */
  var Wcf_Offcanvas_Menu = function ($scope, $) {         
    var offcanvas_html = $scope.find( '.wcf-element-transfer-to-body' );
    if(offcanvas_html.length){   
        $($scope.find( '.wcf-element-transfer-to-body' ).prop('outerHTML')).appendTo( 'body' );
        offcanvas_html.remove();
    }
    var content_source = $scope.find('.wcf--info-animated-offcanvas').attr( 'data-content_source' );
    var preset         = $scope.find('.wcf--info-animated-offcanvas').attr( 'data-preset' );
    var canvas_gl = null;
    if (typeof(gsap) === "object") {
       canvas_gl = gsap.timeline();     
    }
 
    $(document).on('click', '.wcf--info-animated-offcanvas' ,function(e){        
        e.preventDefault();

        if (typeof(gsap) === "object") {
        if( content_source === 'elementor_shortcode' ) {            
            canvas_gl.to(".wcf-offcanvas-gl-style", {
              top: 0,
              visibility : "visible",
              duration : 0.8,
              opacity : 1,
              rotationX : 0,
              perspective : 0,
            }); 
            
        }else{
          //preset
          if(preset === 'two'){
            showCanvas2();
          }
          
          if(preset === 'three'){
            showCanvas3();
          }
          
          if(preset === 'four'){
            showCanvas4();
          }
          
          if(preset === 'five'){
            showCanvas5();
          }
          
          if(preset === 'six'){
            showCanvas6();
          }
          
        }            
       }else{
        $('.wcf-offcanvas-gl-style').css({
          opacity: 1,
          visibility: "visible",  
          transition: "all 0.5s"
        });
        
        $('.offcanvas__left-2').css({
          opacity: 1,
          top: 0,
          visibility: "visible",  
          transition: "all 0.5s"
        });
        $('.offcanvas__right-2').css({
          opacity: 1,
          bottom: 0,
          visibility: "visible",  
          transition: "all 0.5s"
        });
        $('.offcanvas__menu-2 ul li').css({
          opacity: 1 
   
        });
        
        $('.offcanvas-3__area').css({
          transform: "unset"      
        });
        
        $('.offcanvas-3__menu ul li').css({
          transform: "unset",
          opacity: 1 
        }); 
        
        $('.offcanvas-4__area,.offcanvas-4__menu ul li,.offcanvas-3__meta, .offcanvas-3__social').css({
          transform: "unset",
          opacity: 1 ,
          visibility: "visible",  
          top: 0
        });
        
        $('.offcanvas-4__thumb,.offcanvas-4__meta').css({
         
          opacity: 1 ,
          left:0,
          visibility: "visible",  
        });
        
        $('.offcanvas-5__area,.offcanvas-6__menu ul li').css({           
          opacity: 1 ,
          left:0,
          visibility: "visible",  
        });
        $('.wcf-offcanvas-gl-style').css({
         "z-index": 9999
        });
        $('.offcanvas-6__menu-wrapper,.offcanvas-6__meta-wrapper').css({           
          left: 0,           
          visibility: "visible",
          opacity: "1",        
          transform: "unset"
        });
        
       } // gsap end
    });       
    
    $(document).on('click', '.offcanvas--close--button-js' ,function(){   
      if (typeof(gsap) === "object") {
        if( content_source === 'elementor_shortcode' ) {
        
            canvas_gl.to(".wcf-offcanvas-gl-style", {
              top: "-20%",
              duration: 0.8,
              rotationX: 25,
              perspective: 359,
              opacity: 0,              
            }); 
            
            canvas_gl.to(".wcf-offcanvas-gl-style", {
              visibility: "hidden",
              duration: 0.8,
            });  
            
        }else{
          // preset
          if(preset === 'two'){
            hideCanvas2();
          }
          if(preset === 'three'){
            hideCanvas3();                
          }
          if(preset === 'four'){
            hideCanvas4();                
          }
          if(preset === 'five'){
            hideCanvas5();                 
          }
          if(preset === 'six'){
            hideCanvas6();                
          }
        }
      }else{
          $('.wcf-offcanvas-gl-style').css({
              opacity: 0,
              visibility: "hidden",  
              transition: "all 0.5s"
          });
          
          $('.wcf-offcanvas-gl-style').css({
              "z-index": 0
          });
      }
    });

};

// Make sure you run this code under Elementor.
$( window ).on( 'elementor/frontend/init', function() {
    elementorFrontend.hooks.addAction( 'frontend/element_ready/wcf--offcanvas-menu.default', Wcf_Offcanvas_Menu );
} );


// offcanvas 2 Started  -------------------------------------
function showCanvas2() {
  var canvas2 = gsap.timeline();

  canvas2.to(".wcf-offcanvas-gl-style", {
    duration: 0.5,
    opacity: 1,
  });

  canvas2.to(
    ".offcanvas__left-2",
    {
      duration: 0.8,
      top: 0,
      opacity: 1,
      visibility: "visible",
    },
    "-==.5"
  );

  // Part 2
  canvas2.to(
    ".offcanvas__right-2",
    {
      duration: 0.8,
      bottom: 0,
      opacity: 1,
      visibility: "visible",
    },
    "-=0.8"
  );

  // Offcanvas Menu
  canvas2.to(
    ".offcanvas__menu-2 ul li",
    {
      opacity: 1,
      bottom: 0,
      stagger: 0.08,
    },
    "-=.2"
  );
}

// offcanvas 2 Hide Started  -------------------------------------
function hideCanvas2() {
  var canvas2 = gsap.timeline();
  // Offcanvas Menu

  canvas2.to(".offcanvas__menu-2 ul li", {
    opacity: 0,
    bottom: -60,
    stagger: 0.08,
  });

  // Part 2
  canvas2.to(
    ".offcanvas__right-2",
    {
      duration: 0.6,
      bottom: "-50%",
      opacity: 0,
    },
    "-=.3"
  );

  canvas2.to(
    ".offcanvas__left-2",
    {
      duration: 0.6,
      top: "-50%",
      opacity: 0,
    },
    "-=.6"
  );
  canvas2.to(".wcf-offcanvas-gl-style", {
    duration: 1,
    opacity: 0,
  });

  canvas2.to(
    ".offcanvas__left-2",
    {
      visibility: "hidden",
    },
    "-=.7"
  );
  canvas2.to(
    ".offcanvas__right-2",
    {
      visibility: "hidden",
    },
    "-=0.7"
  );
}

// offcanvas 3 js code -------------------------------------
function showCanvas3() {
  var canvas3 = gsap.timeline();

  canvas3.to(".offcanvas-3__area", {
    left: 0,
    visibility: "visible",
    duration: 0.8,
    opacity: 1,
    rotationY: 0,
    perspective: 0,
  });

  // Menu Item
  canvas3.to(
    ".offcanvas-3__menu ul li",
    {
      opacity: 1,
      top: 0,
      stagger: 0.05,
      duration: 1,
      rotationX: 0,
    },
    "-=0.1"
  );
  // Meta
  canvas3.to(
    ".offcanvas-3__meta",
    {
      top: 0,
      visibility: "visible",
      duration: 0.8,
      opacity: 1,
    },
    "-=0.5"
  );

  // Social
  canvas3.to(
    ".offcanvas-3__social",
    {
      top: 0,
      visibility: "visible",
      duration: 0.8,
      opacity: 1,
    },
    "-=0.5"
  );
}

// offcanvas 3 Hide  -------------------------------------
function hideCanvas3() {
  var canvas3 = gsap.timeline();

  canvas3.to(".offcanvas-3__area", {
    duration: 0.8,
    rotationY: -90,
    opacity: 0,
  });
  canvas3.to(".offcanvas-3__area", {
    visibility: "hidden",
    duration: 0.1,
    rotationY: 50,
    left: 0,
    rotationX: 0,
  });

  // Menu Item
  canvas3.to(".offcanvas-3__menu ul li", {
    opacity: 0,
    top: -100,
    stagger: 0.01,
    duration: 0.1,
    rotationX: 50,
  });

  // Meta
  canvas3.to(
    ".offcanvas-3__meta",
    {
      top: -30,
      visibility: "hidden",
      duration: 0.8,
      opacity: 1,
    },
    "-=0.5"
  );

  // Social
  canvas3.to(
    ".offcanvas-3__social",
    {
      top: -30,
      visibility: "hidden",
      duration: 0.8,
      opacity: 1,
    },
    "-=0.5"
  );
}

// offcanvas 4 js code -------------------------------------
function showCanvas4() {
  var canvas4 = gsap.timeline();

  canvas4.to(".offcanvas-4__area", {
    top: 0,
    visibility: "visible",
    duration: 0.8,
    opacity: 1,
    rotationX: 0,
    perspective: 0,
  });

  canvas4.to(".offcanvas-4__menu ul li", {
    visibility: "visible",
    duration: 0.8,
    opacity: 1,
    rotationX: 0,
    perspective: 0,
    stagger: 0.05,
    top: 0,
  });

  // Can Vas Image
  canvas4.to(
    ".offcanvas-4__thumb",
    {
      left: 0,
      visibility: "visible",
      duration: 0.8,
      opacity: 1,
    },
    "-=0.8"
  );
  // Canvas Meta
  canvas4.to(
    ".offcanvas-4__meta",
    {
      left: 0,
      visibility: "visible",
      duration: 0.8,
      opacity: 1,
    },
    "-=1"
  );
}

// offcanvas hide  4 js code \\\\\\\\\\\\\\ \\\\\\\\\\\\\\\\\\\\\\\
function hideCanvas4() {
  var canvas4 = gsap.timeline();

  canvas4.to(".offcanvas-4__area", {
    top: "-20%",
    duration: 0.8,
    rotationX: 25,
    perspective: 359,
    opacity: 0,
  });

  canvas4.to(".offcanvas-4__area", {
    visibility: "hidden",
    duration: 0.8,
  });

  // Menu Item
  canvas4.to(
    ".offcanvas-4__menu ul li",
    {
      visibility: "visible",
      duration: 0.8,
      opacity: 0,
      rotationX: 90,
      perspective: 350,
      stagger: 0.05,
      top: "-10px",
    },
    "-=.5"
  );

  // Image
  canvas4.to(
    ".offcanvas-4__thumb",
    {
      left: "50px",
      duration: 0.8,
      opacity: 0,
      visibility: "hidden",
    },
    "-=0.5"
  );
  // Meta
  canvas4.to(
    ".offcanvas-4__meta",
    {
      left: "-50px",
      duration: 0.8,
      opacity: 0,
      visibility: "hidden",
    },
    "-=1"
  );
}

// offcanvas 6 js code -------------------------------------
function showCanvas5() {
  var canvas6 = gsap.timeline();
 
  canvas6.to(".wcf-offcanvas-gl-style", {
    left: 0,
    visibility: "visible",
    duration: 0.8,
    opacity: 1,
    rotationY: 0,
    perspective: 0,
  });
  
  gsap.to(".offcanvas-5__content-wrapper", {
    left: 0,
    visibility: "visible",
    duration: 1.5,
    opacity: 1,
    rotationY: 0,
    perspective: 0,
  });
  
  gsap.to(".offcanvas-5__lang", {
    left: 0,
    visibility: "visible",
    duration: 1.6,
    opacity: 1,
    rotationY: 0,
    perspective: 0,
  });
  
  gsap.to(".offcanvas-5__meta-wrapper", {
    bottom: 0,
    visibility: "visible",
    duration: 1.4,
    opacity: 1,
    rotationY: 0,
    perspective: 0,
  });

  // Menu Item      
  
  canvas6.to(
    ".offcanvas-5__menu li",
    {
      opacity: 1,
      bottom: 0,
      stagger: 0.08,
    },
    "-=.2"
  );
  
  // Social Title
  canvas6.to(
    ".offcanvas-5__social-title",
    {
      opacity: 1,
      left: 0,
      duration: 1,
    },
    "=-1"
  );
  // Social Item
  canvas6.to(
    ".offcanvas-5__social-links a",
    {
      opacity: 1,
      scale: 1,
      stagger: 0.1,
      left: 0,
      ease: "bounce",
    },
    "=-0.5"
  );
}

// Menu Hiding
function hideCanvas5() {
  var canvas6 = gsap.timeline();    
  // Menu Item
  canvas6.to(".offcanvas-5__menu li", {
    opacity: 0,
    left: -200,
    stagger: 0.2,
    ease: "linear",
  });

  canvas6.to(".wcf-offcanvas-gl-style", {
    visibility: "hidden",
  });
  
  canvas6.to(
    ".offcanvas-5__social-title",
    {
      opacity: 0,
      left: -50,
      duration: 1,
    },
    "=-1"
  );

  // Social Item
  canvas6.to(
    ".offcanvas-5__social-links a",
    {
      opacity: 0,
      scale: 0.8,
      stagger: 0.1,
    },
    "=-1"
  );
  
  canvas6.to(
    ".offcanvas-5__meta-wrapper",
    {
      bottom: "100%",
      duration: 1,
      opacity: 0,    
      rotationY: -25,
      perspective: 359,
    },
    "=-1"
  );
  
  canvas6.to(
    ".offcanvas-5__lang",
    {
      bottom: "100%",
      duration: 1,
      opacity: 0,    
      rotationY: -25,
      perspective: 359,
    },
    "=-1"
  );
  
  
  
  
}

// offcanvas 6 js code -------------------------------------
function showCanvas6() {
var canvas6 = gsap.timeline();

// canvas6.to(".wcf-offcanvas-gl-style", {
//   left: 0,
//   top: 0,
//   visibility: "visible",
//   duration: 1,
//   opacity: 1,
//   rotationY: 0,
//   perspective: 0,
// });

gsap.to(".offcanvas-6__menu-wrapper", {
left: 0,
visibility: "visible",
duration: 1,
opacity: 1,
rotationY: 0,
perspective: 0,
});
gsap.to(".offcanvas-6__meta-wrapper", {
left: 0,
visibility: "visible",
duration: 1,
opacity: 1,
rotationY: 0,
perspective: 0,
});

// Menu Item
canvas6.to(".offcanvas-6__menu ul li", {
opacity: 1,
left: 0,
stagger: 0.15,
duration: 1.5,
ease: "bounce",
delay: 1,
});
// Second Level
canvas6.to(
".second_level_canvas",
{
  opacity: 1,
  scale: 1,
  duration: 1,
},
"=-1.7"
);
// Social Title
canvas6.to(
".offcanvas-6__social-title",
{
  opacity: 1,
  left: 0,
  duration: 1,
},
"=-1"
);
// Social Item
canvas6.to(
".offcanvas-6__social-links a",
{
  opacity: 1,
  scale: 1,
  stagger: 0.1,
  left: 0,
  ease: "bounce",
},
"=-0.5"
);
}

// Menu Hiding
function hideCanvas6() {
var canvas6 = gsap.timeline();
canvas6.to(".wcf-offcanvas-gl-style", {
visibility: "hidden",    
});
// Menu Item
canvas6.to(".offcanvas-6__menu ul li", {
opacity: 0,
left: -200,
stagger: 0.2,
ease: "linear",
});
//   Second Level
canvas6.to(
".second_level_canvas",
{
  opacity: 0,
  scale: 0.8,
  duration: 1,
},
"=-1"
);

// Social Title
canvas6.to(
".offcanvas-6__social-title",
{
  opacity: 0,
  left: -50,
  duration: 1,
},
"=-1"
);

// Social Item
canvas6.to(
".offcanvas-6__social-links a",
{
  opacity: 0,
  scale: 0.8,
  stagger: 0.1,
},
"=-1"
);

canvas6.to(".offcanvas-6__menu-wrapper", {
left: "-100%",
duration: 1,
opacity: 0,
rotationY: 25,
perspective: 359,
});
canvas6.to(
".offcanvas-6__meta-wrapper",
{
  left: "100%",
  duration: 1,
  opacity: 0,

  rotationY: -25,
  perspective: 359,
},
"=-1"
);

}


} )( jQuery );




