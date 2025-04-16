(function ($) {
    'use strict';
    var admin_url   = sassly_admin_obj.admin_ajax;
    var cpt_options = {
      action: 'wcf_admin_get_cache_cpt',
      nonce : sassly_admin_obj.ajax_nonce
    }
    var _custom_post_object_data = null;  
    jQuery.ajax({
        type: "GET",
        url: admin_url,
        data: cpt_options,
        success: function(response){
            _custom_post_object_data = response;        
        }
    });
    
    $(document).on('click', '#wcf--theme-update', function(e){
        e.preventDefault();
        $(this).html('Updating...');
        
        jQuery.ajax({
          type    : "post",
          dataType: "json",
          url     : admin_url,
          data    : {
              action: "wcf_update_theme",
              slug  : 'sassly',
          },
          success: function(response) {                     
              $('#wcf---theme-update-respone').html(response.data.debug.join("</p><p>"));
              $('#wcf--theme-update').text('Updated');
          }
       });
    });
    // Notice Dismiss Action
    $(document).on('click', '#wcf-remote-theme-update .notice-dismiss', function(){
        jQuery.ajax({
          type    : "post",
          dataType: "json",
          url     : admin_url,
          data    : {
              action: "wcf_theme_notice_dismiss",
              nonce: sassly_admin_obj.ajax_nonce,
          },
          success: function(response) {                    
              
          }
       });
    });    
    
    $(document).on('click', '#wcf--check-theme-update-status', function(e){
      e.preventDefault();
  
      let $current_element = $(this);
      $current_element.html('Checking theme update');
      jQuery.ajax({
        type : "post",
        dataType : "json",
        url : admin_url,
        data : {
            action: "wcf_update_theme_status", 
            slug: 'sassly',
        },
        success: function(response) {
          $current_element.html('Update Check');
          if(response.success){           
            $('#wcf--theme-update-container').html(
            `
            <a class="button" id="wcf--theme-update">Update Theme Latest Version</a>
            <div style="margin-top:20px;" id="wcf---theme-update-respone">
              <h3>Description</h3>
              ${response.data.sections.description}
              <h3>Changelog</h3>
              ${response.data.sections.changelog}
             
            </div>
            `         
            );
          }else{
            $current_element.parent().find('p').html(response.data.msg);
          }                   
        }
     });
  });
   
})(jQuery);

