

(function ($) {
    'use strict';
   
    Object.defineProperty(Array.prototype, 'chunk_inefficient', {
        value: function (chunkSize) {
            var array = this;
            return [].concat.apply([],
                array.map(function (elem, i) {
                    return i % chunkSize ? [] : [array.slice(i, i + chunkSize)];
                })
            );
        }
    });
    
    var $wcf_page_import = {
    
        init: function( settings ) {
            $wcf_page_import.config = {
                items    : $( "#myFeature li" ),
                container: $( "<div class='container'></div>" ),
                pages    : [],
                current_page: 0,
                per_page: 20,
                search_content: [],
                filter_active: 0,
               
            };     
            // Allow overriding the default config
            $.extend( $wcf_page_import.config, settings );     
            $wcf_page_import.setup();            
        },
     
        setup: function() {  
            $wcf_page_import.setup_initial_templates();
            jQuery("#wcf-page-importeri").on("wcf:pagemodel:open", function(event) {
               $wcf_page_import.mesonary_build();
            });
            jQuery(document).on('click', '.wcf-return-to-libary' ,function(){
              jQuery('.wcf-templates-list-renderer').show();
              jQuery('.wcf-templates-list-renderer').siblings().hide();
              $wcf_page_import.mesonary_build();
            });
            jQuery(document).on('click', '.wcf--general-tpls-button',$wcf_page_import.import_page);
            jQuery(document).on('keyup', '.wcf-page-search-js', function(){
                jQuery('.wcf-templates-list-renderer .body-import-active-overlay').remove();
                jQuery('.wcf-templates-list-renderer').show();
                jQuery('.wcf-templates-list-renderer').siblings().hide();
                $wcf_page_import.config.current_page = 0;
                let search_key = $(this).val();                
                if( search_key.length > 2 ){
                    $wcf_page_import.config.filter_active = 1;
                    $wcf_page_import.filter_content(search_key);
                    $wcf_page_import.mesonary_build();
                }else{
                    $wcf_page_import.config.filter_active = 0;
                    $wcf_page_import.mesonary_build();
                }
               
                jQuery('.wcf-page-select-type option[value="0"]').attr("selected",true);
            });
            
            jQuery(document).on('change' , '.wcf-page-select-type',$wcf_page_import.filter_category);            
            jQuery(document).on('click' , '.wcf-templates-pagination .prev', $wcf_page_import.prev_page_template);
            jQuery(document).on('click' , '.wcf-templates-pagination .next', $wcf_page_import.next_page_template);
        },
        
        import_page: function(e){
            e.preventDefault();
            let id         = jQuery(this).attr('data-id');
            let page_title = jQuery(this).text();
            var data = {
                action    : 'wcf_page_xml_file_import',
                nonce     : wcf_import_obj.ajax_nonce,
                id        : id,
                page_title: page_title,
                step      : 'download'
            };
            
            $wcf_page_import.run_importer_process(data);          
            jQuery('.wcf-dpage-xml-import-container > .wcf-msg').html('');
            jQuery('.wcf-templates-list-renderer').siblings().show();
            $('.wcf-templates-list-renderer').html(`<div class='body-import-active-overlay'><svg width="200px" height="200px"  xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid" style="background: none;">
			<circle cx="75" cy="50" fill="#363a3c" r="6.39718">
				<animate attributeName="r" values="4.8;4.8;8;4.8;4.8" times="0;0.1;0.2;0.3;1" dur="1s" repeatCount="indefinite" begin="-0.875s"></animate>
			</circle>
			<circle cx="67.678" cy="67.678" fill="#363a3c" r="4.8">
				<animate attributeName="r" values="4.8;4.8;8;4.8;4.8" times="0;0.1;0.2;0.3;1" dur="1s" repeatCount="indefinite" begin="-0.75s"></animate>
			</circle>
			<circle cx="50" cy="75" fill="#363a3c" r="4.8">
				<animate attributeName="r" values="4.8;4.8;8;4.8;4.8" times="0;0.1;0.2;0.3;1" dur="1s" repeatCount="indefinite" begin="-0.625s"></animate>
			</circle>
			<circle cx="32.322" cy="67.678" fill="#363a3c" r="4.8">
				<animate attributeName="r" values="4.8;4.8;8;4.8;4.8" times="0;0.1;0.2;0.3;1" dur="1s" repeatCount="indefinite" begin="-0.5s"></animate>
			</circle>
			<circle cx="25" cy="50" fill="#363a3c" r="4.8">
				<animate attributeName="r" values="4.8;4.8;8;4.8;4.8" times="0;0.1;0.2;0.3;1" dur="1s" repeatCount="indefinite" begin="-0.375s"></animate>
			</circle>
			<circle cx="32.322" cy="32.322" fill="#363a3c" r="4.80282">
				<animate attributeName="r" values="4.8;4.8;8;4.8;4.8" times="0;0.1;0.2;0.3;1" dur="1s" repeatCount="indefinite" begin="-0.25s"></animate>
			</circle>
			<circle cx="50" cy="25" fill="#363a3c" r="6.40282">
				<animate attributeName="r" values="4.8;4.8;8;4.8;4.8" times="0;0.1;0.2;0.3;1" dur="1s" repeatCount="indefinite" begin="-0.125s"></animate>
			</circle>
			<circle cx="67.678" cy="32.322" fill="#363a3c" r="7.99718">
				<animate attributeName="r" values="4.8;4.8;8;4.8;4.8" times="0;0.1;0.2;0.3;1" dur="1s" repeatCount="indefinite" begin="0s"></animate>
			</circle>
		</svg></div>`);
        },
        setup_loop_template: function (single) {
            return `			
			<div class="wc-pageimg-wrapper">
				<img data-src="${single.thumbnail}" src="${single.thumbnail}"/>
			</div>
			<div class="action-wrapper">				
				<button class="er-template-import wcf--general-tpls-button" data-id="${single.id}" data-title="${single.title}">
					Import
				</button>	
				<a target="_blank" class="demo-url" href="${single.url}" data-title="${single.title}">
					Preview
				</a>	
			</div>
			<h3 class="wcf-page-ready-tpl-title">
				<b>${single.title}</b>			
			</h3>				
			`;
        },
        
        setup_initial_templates: function (type) {   
           
            let pags = JSON.parse( wcf_import_obj.pages );
            $wcf_page_import.config.categories = pags.categories;          
            for (const [key, value] of Object.entries(pags.templates)) {
                $wcf_page_import.config.pages.push(value);  //
            }
            var output = [];
            output.push('<select class="wcf-page-select-type">');
            output.push('<option value="0">All</option>');
            jQuery.each($wcf_page_import.config.categories, function(i,item){
                if(item.id != 1){
                    output.push('<option value="'+ item.id +'">'+ item.title +'</option>');
                }
                
            });
            output.push('</select>');
            jQuery('.wcf-page-modal-content .wcf-page-subtype').html(output.join(''));
            $wcf_page_import.config.current_page = 0;
        },
        
        filter_category: function(){
            let id = parseInt($('.wcf-page-select-type :selected').val());                
         
            $wcf_page_import.config.filter_active = id === 0 ? 0 : 1;
            $wcf_page_import.config.current_page  = 0;
            $wcf_page_import.filter_cat_content(id);
            $wcf_page_import.mesonary_build();           
            jQuery('.wcf-templates-list-renderer .body-import-active-overlay').remove();
            jQuery('.wcf-templates-list-renderer').show();
            jQuery('.wcf-templates-list-renderer').siblings().hide();
            jQuery('.wcf-page-search-js').val('')
        },
        
        next_page_template: function () {
            
            $wcf_page_import.config.current_page = $wcf_page_import.config.current_page + 1;
            $wcf_page_import.mesonary_build();
        },

        prev_page_template: function () {           
           
            $wcf_page_import.config.current_page = $wcf_page_import.config.current_page - 1;
            $wcf_page_import.mesonary_build();
        },
        
        filter_content: function( user_input ){
           var obj = [...$wcf_page_import.config.pages];
           const pluck = (arr, key) => arr.map(i => i[key]);
           let filter_blocks = [];              
            if(Number.isInteger(user_input)){
                jQuery.each(obj, function (i, item) { 
                    var $ids = pluck(item.types, 'id'); // [8, 36, 34, 10]
                    if(jQuery.inArray(user_input, $ids) != -1) {
                        filter_blocks.push(item);          
                    }
                });
            }else{
                jQuery.each(obj, function (i, item) {  
                    var search_arr = item.title.toLowerCase().split(' ');           
                    if(jQuery.inArray(user_input.toLowerCase(), search_arr) != -1) {
                        filter_blocks.push(item);                
                    }   
                });
            }
          $wcf_page_import.config.search_content = filter_blocks;   
          return filter_blocks;
        },
        
        filter_cat_content: function( user_input ){
            var obj = [...$wcf_page_import.config.pages];
            const pluck = (arr, key) => arr.map(i => i[key]);
            let filter_blocks = [];             
          
             jQuery.each(obj, function (i, item) { 
                 var $ids = pluck(item.types, 'id'); // [8, 36, 34, 10]
                 if(jQuery.inArray(user_input, $ids) != -1) {
                     filter_blocks.push(item);          
                 }
             });
   
           $wcf_page_import.config.search_content = filter_blocks;   
           return filter_blocks;
        },
         
        findWord: function(word, str) {
            return RegExp('\\b'+ word +'\\b').test(str)
        },
        mesonary_build: function () {

            var eleemnt_type              = null;
            let row                       = document.querySelector('.wcf-templates-list-renderer');
            let element_wrapper           = document.createElement('div');                           // is a node
                element_wrapper.className = "wcf-row";
                row.innerHTML             = '';
            let data_status_found         = '';
            var templatesCopy = [];
            if($wcf_page_import.config.filter_active){
                templatesCopy = $wcf_page_import.config.search_content; 
            }else{
                templatesCopy = [...$wcf_page_import.config.pages];
            }
           
            var total_page    = 1;
                templatesCopy = templatesCopy.chunk_inefficient($wcf_page_import.config.per_page);  // page count
                total_page    = templatesCopy.length - 1;
            if (total_page >= $wcf_page_import.config.current_page) {
                templatesCopy = templatesCopy[$wcf_page_import.config.current_page];
            } else {
                templatesCopy = templatesCopy[0];
            }
          
            var pagination = document.createElement('div'); // is a node
            pagination.className = "wcf-templates-pagination";
            if ($wcf_page_import.config.current_page == 0) {
                pagination.innerHTML = '<ul><li class="wcf--page-tpls-button next"> Next </li> </ul>';
            } else if ($wcf_page_import.config.current_page === total_page) {
                pagination.innerHTML = '<ul><li class="wcf--page-tpls-button prev"> Prev </li> </ul>';
            } else {
                pagination.innerHTML = '<ul><li class="wcf--page-tpls-button prev"> Prev </li> <li class="wcf--page-tpls-button next"> Next </li> </ul>';
            }

            // Number of columns
            let cols = 4;

            var lg_tablet = window.matchMedia("(max-width: 1300px)");
            var tablet = window.matchMedia("(max-width: 1023px)");
            var mobile = window.matchMedia("(max-width: 767px)");

            if (lg_tablet.matches) {
                cols = 3;
            }

			if (tablet.matches) {
				cols = 2;
			}

            if (mobile.matches) {
                cols = 1;
            }

            let colsCollection = {};
            // Create number of columns
            for (let i = 1; i <= cols; i++) {
                colsCollection[`col${i}`] = document.createElement('div');
                colsCollection[`col${i}`].classList.add('wcf-template-list-column');
            }

            if (templatesCopy != undefined) {
                for (var i = 0; i < cols; i++) {
                    if (!templatesCopy[i]) break;
                    const itemContainer = document.createElement('div');
                    itemContainer.classList.add('wcf-item');
                    const item                  = document.createElement('div');
                          item.dataset.category = templatesCopy[i].title;
                          item.innerHTML        = $wcf_page_import.setup_loop_template(templatesCopy[i]);
                    itemContainer.appendChild(item);
                    colsCollection[`col${i + 1}`].appendChild(itemContainer);
                    if (i === cols - 1) {
                        templatesCopy.splice(0, cols);
                          // reset i
                        i = -1;
                    }
                }

                Object.values(colsCollection).forEach(column => {
                    element_wrapper.appendChild(column);
                });
            }
            if (data_status_found.length) {
                row.innerHTML = `<div class="wcf-tpl-not-found"> ${data_status_found} </div>`;
            } else {
                row.appendChild(element_wrapper);
            }

            if (total_page >= 1) {
                row.appendChild(pagination);
            }

        },
        run_importer_process: function(obj){
        
            jQuery.ajax({
                type    : "post",
                dataType: "json",
                url     : wcf_import_obj.ajax_url,
                timeout : 10000,
                data    : obj,
                success: function(response) {     
                   jQuery('.wcf-templates-list-renderer').hide();
                   if(response.success && 'step' in response.data && response.data.step === 'import'){
                        jQuery('.wcf-dpage-xml-import-container > .wcf-msg').html(response.data.html);
                        delete response.data.html;                        
                        $wcf_page_import.run_importer_process(response.data);
                   }                   
                   if(response.success && 'step' in response.data && response.data.step === 'done'){
                        jQuery('.wcf-dpage-xml-import-container > .wcf-msg').html(response.data.html);                      
                   }
                   
                   if(!response.success){
                    jQuery('.wcf-dpage-xml-import-container > .wcf-msg').html(response.data.html);          
                   }
                         
                }
            });
        }
     
    };
    
    $( document ).ready( $wcf_page_import.init );       
       
    
})(jQuery);