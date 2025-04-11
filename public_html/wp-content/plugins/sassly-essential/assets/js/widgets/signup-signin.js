/* global WCF_ADDONS_JS */
(function ($) {
      /**
       * @param $scope The Widget wrapper element as a jQuery element
       * @param $ The jQuery alias
       */
      
      const SasslyUserRegister = function ( $scope , $ ) {
            const $form_data   = $scope.find('.sassly--signupform--wrapper');
            const $msg_labels   = $scope.find('.sassly-msg-labels');
            const enable_redirect = $form_data.attr('data-redirectenable');
            const _redirect = $form_data.attr('data-redirect');
            var valid_wjson = false;
            var mjson = null;
            if(JSON.parse($msg_labels.text())){
               mjson = JSON.parse($msg_labels.text());             
               if(mjson){
                  valid_wjson = true;
               }
            }            
            
            console.log(mjson);
            console.log(valid_wjson);
        
            const $msg_wrapper = $scope.find('.sassly--userjxform-msg');          
            const validateEmail = (email) => {
                  return String(email)
                    .toLowerCase()
                    .match(
                      /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|.(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
                    );
                };            
         
            document.querySelector('.sassly--signupform--wrapper').addEventListener('submit', (e) => {
                  e.preventDefault()
                  const data = Object.fromEntries(new FormData(e.target).entries());                  
                  // Check valid data
                  var error_data = '';
                  var isFormValid = true;
                  if($form_data[0].querySelectorAll('.single-input input')){                                           
                              $form_data[0].querySelectorAll('.single-input input').forEach(element => {  
                              if(!element.parentElement.classList.contains('valid')){
                                    isFormValid = false;                                    
                                    if(mjson && valid_wjson){ 
                                          error_data = `<h2 class="sassly-error">${mjson.required_fields_wlabel}</h2>`;
                                    }else{
                                          error_data = '<h2 class="sassly-error">Fill up all required fields</h2>';
                                    }                                    
                                    $msg_wrapper.html(error_data);                                   
                              }                                                  
                        });   
                  }
                  
                  if(!isFormValid){
                        $msg_wrapper.html(error_data);
                        return;
                  }
                  
                  $msg_wrapper.html('');                  
                  // Registration start
                  let ajx = {
                      action: 'sassly_user_register_form_submit',
                      nonce: sassly_user_register.security,   // pass the nonce here                    
                  };
                  let mergeddata = {...data, ...ajx};
              
                  $.ajax( {
                        url: sassly_user_register.ajax_url,
                        type: 'post',
                        data: mergeddata,
                        success( response ) {                           
                            if ( response.success) {                              
                              if(response.data.cls === 'valid'){ 
                                    if(mjson && valid_wjson){                                            
                                          $msg_wrapper.html(mjson.user_registed_success_wlabel);   
                                    }else{
                                          $msg_wrapper.html(response.data.msg);   
                                    }
                                    
                                    if(enable_redirect == 'yes' && _redirect !=''){
                                          window.location.href = _redirect     
                                    }
                              }
                              if(response.data.cls === 'error'){
                                    $msg_wrapper.html(response.data.msg);    
                              }
                            }                           
                        },
                    } );
                  
            });
            
            // Check username available
            
            $form_data.on('keyup', '.sassly-uform-username', function(e){
                  let username = $( this ).val();   
                  let element = $( this );   
                  if( username && username.length > 4 ){
                        $( this ).next('span').text('');
                        $.ajax( {
                              url: sassly_user_register.ajax_url,
                              type: 'post',
                              data: {
                                  action: 'sassly_user_register_username_validation',
                                  nonce: sassly_user_register.security,   // pass the nonce here
                                  username: username,
                              },
                              success( response ) {
                                  if ( response.success) {
                                    //element.next('span').html(response.data.msg);   
                                    if(response.data.cls == 'valid'){
                                          element.parent().addClass('valid').removeClass('error');  
                                    }else{
                                          element.parent().addClass('error').removeClass('valid');  
                                    }
                                  }
                                  setTimeout(function(){
                                    element.next('span').html('');
                                 },2000);
                              },
                          } );
                  }else{
                        if(mjson && valid_wjson){ 
                              $( this ).next('span').text(mjson.username_length_wlabel); 
                        }else{
                              $( this ).next('span').text('Username Must be greater than 4');
                        }  
                        
                  }
            });
            
            
            $form_data.on('keyup', '.sassly-uform-fullname', function(e){  
                  if( $(this).val().length > 2 ){
                        $(this).parent().addClass('valid').removeClass('error');  
                  }else{
                        $(this).parent().addClass('error').removeClass('valid');   
                  } 
            }); 
            
            $form_data.on('keyup', '.sassly-uform-email', function(e){   
                  let email = $(this).val();  
                  let element = $(this);  
                  if($(this).val().length > 5 && validateEmail($(this).val())){
                        $(this).parent().addClass('valid').removeClass('error');  
                  }else{
                        $(this).parent().addClass('error').removeClass('valid');   
                  }                   
                  if( email && email.length > 4 ){
                        $( this ).next('span').text('');
                        $.ajax( {
                              url: sassly_user_register.ajax_url,
                              type: 'post',
                              data: {
                                  action: 'sassly_user_register_email_validation',
                                  nonce: sassly_user_register.security,   // pass the nonce here
                                  email: email,
                              },
                              success( response ) {
                                  if ( response.success) {
                                    //element.next('span').html(response.data.msg);   
                                    if(response.data.cls == 'valid'){
                                          element.parent().addClass('valid').removeClass('error');  
                                    }else{
                                          element.parent().addClass('error').removeClass('valid');       
                                          if(mjson && valid_wjson){
                                                $msg_wrapper.html(`<h2 class="sassly-error">${mjson.email_already_exist_wlabel}</h2>`);
                                          }else{
                                          $msg_wrapper.html('<h2 class="sassly-error">Email Already Exist</h2>'); 
                                          }
                                    }
                                  }
                                  setTimeout(function(){
                                    element.next('span').html('');
                                    $msg_wrapper.html('');
                                 },5000);
                              },
                          } );
                  }else{
                        if(mjson && valid_wjson){
                              $( this ).next('span').text(mjson.username_length_wlabel);
                        }else{
                              $( this ).next('span').text('Username Must be greater than 4');
                        }                        
                        
                  }
            });
            
            $form_data.on('keyup', '.sassly-uform-password', function(e){            
                  $('.sassly-uform-cpassword').val('');
                  if( $(this).val().length > 5 ){
                        $(this).parent().addClass('valid').removeClass('error');  
                  }else{
                        $(this).parent().addClass('error').removeClass('valid');   
                  } 
            });
            
            $form_data.on('keyup', '.sassly-uform-cpassword', function(e){
                 if($(this).val() === $('.sassly-uform-password').val()){
                    $(this).parent().addClass('valid').removeClass('error');
                    $('.sassly-uform-password').parent().addClass('valid').removeClass('error');
                 }else{
                    $(this).parent().addClass('error').removeClass('valid');
                 } 
            });
            
            $form_data.on('input', 'input', function(e){
                  e.preventDefault();    
                 $msg_wrapper.html('');
            });
               
         setTimeout(function(){
            $msg_wrapper.html('');
         },9000);
      };
      
      const SasslyUserLogin = function ( $scope , $ ) {
          
      };
  
      // Make sure you run this code under Elementor.
      $(window).on('elementor/frontend/init', function () {
          elementorFrontend.hooks.addAction('frontend/element_ready/sassly--user-registration.default', SasslyUserRegister);
          elementorFrontend.hooks.addAction('frontend/element_ready/sassly--user-login.default', SasslyUserLogin);
      });
  })(jQuery);
  