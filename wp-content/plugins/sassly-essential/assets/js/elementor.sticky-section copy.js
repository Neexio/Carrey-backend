;(function ($) {

    // sticky
    
    var Wcf_Sticky_Menu = {

        elementorSection: function( $scope ) {
            var $_target   = $scope,
                instance  = null,
                editMode  = Boolean( elementorFrontend.isEditMode() );
                instance = new Wcf_Sticky_Menu_Plugin( $_target );
                // run main functionality                
                instance.init(instance);
              
        },
    };

    Wcf_Sticky_Menu_Plugin = function( $target ) {

        var self         = this,
        sectionId        = $target.data('id'),
        settings         = false,
        editMode         = Boolean( elementorFrontend.isEditMode() ),
        $window          = $( window ),
        $body            = $( 'body' );
       
        /**
         * Init
         */
        self.init = function() {
            
            self.wcf_infos_sticky( sectionId );         
            return false;
        };

        
        self.wcf_infos_sticky = function (sectionId){
          
            var wcf_global_sticky = false;
            var wcf_sticky_offset = 150;
            var wcf_sticky_type   = null;

            wcf_global_sticky = self.getSettings( sectionId, 'wcf_global_sticky' );
            wcf_sticky_type   = self.getSettings( sectionId, 'wcf_sticky_type' );
            wcf_sticky_offset = parseInt(self.getSettings( sectionId, 'wcf_sticky_offset' ));
            
            if(isNaN( wcf_sticky_offset)){
                wcf_sticky_offset = 150;  
            }
            //default offset
            if(wcf_sticky_offset < 5 ){
                 wcf_sticky_offset = 110;  
            }
          
            if(wcf_global_sticky == 'yes'){

                $target.addClass('wcf-sticky-container');

                if(wcf_sticky_type == 'top'){
                    $target.addClass('top');
                    $target.removeClass('bottom');
                }

                if(wcf_sticky_type == 'bottom'){
                    $target.addClass('bottom');
                    $target.removeClass('top');
                }
                if(wcf_sticky_type == ''){
                    $target.removeClass('top');
                    $target.removeClass('bottom');
                }   
                  
                $window.on('scroll', function (event) {                   
                    var scroll = $window.scrollTop();                    
                    if (scroll < wcf_sticky_offset) {
                        $target.removeClass("wcf-is-sticky");
                    } else {
                        $target.addClass("wcf-is-sticky");
                    }
                });
            }else{

                $target.removeClass('wcf-sticky-container');                
            }


        };

        self.getSettings = function(sectionId, key){
            var editorElements      = null,
            sectionData             = {};
             

            if ( ! editMode ) {
                sectionId = 'section' + sectionId;

                if(!window.wcf_section_sticky_data || ! window.wcf_section_sticky_data.hasOwnProperty( sectionId )){
                    return false;
                }

                if(! window.wcf_section_sticky_data[ sectionId ].hasOwnProperty( key )){
                    return false;
                }

                return window.wcf_section_sticky_data[ sectionId ][key];
            }else{
                 
                if ( ! window.elementor.hasOwnProperty( 'elements' ) ) {
                    return false;
                }
                editorElements = window.elementor.elements;
                
                if ( ! editorElements.models ) {
                    return false;
                }
                $.each( editorElements.models, function( index, obj ) {
                    if ( sectionId == obj.id ) {
                        sectionData = obj.attributes.settings.attributes;
                    }
                });

                if ( ! sectionData.hasOwnProperty( key ) ) {
                    return false;
                }
            }

            return sectionData[ key ];
        };
    }
    
    $(window).on('elementor/frontend/init', function () {
      
        elementorFrontend.hooks.addAction( 'frontend/element_ready/section', Wcf_Sticky_Menu.elementorSection );
        elementorFrontend.hooks.addAction( 'frontend/element_ready/container', Wcf_Sticky_Menu.elementorSection );        
      
    });
    
 

})(jQuery);