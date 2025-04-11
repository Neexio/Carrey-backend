

(function (elementor, $, window) {
    "use strict"; 
   
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

    async function wcf_template_ready_doAjax(args) {
        let result = false;
        try {
            result = await $.ajax({
                _nonce: fe_templates_lib.ajax_nonce,
                url: wp.ajax.settings.url,
                type: 'get',
                data: args
            });

        } catch (error) {
            $('.wcf-templates-list-renderer').html(error.responseText);
            return error;
        }
        return result;
    }

    var Wcf_Template_Library = {
        init: function (settings) {
            Wcf_Template_Library.config = {
                product_name  : 'WCF',
                templateLibBtn: null,
                primary_cat   : [
					{ 'name':'block','title' : 'Blocks' },
					{ 'name':'page','title' : 'Page' },
					{ 'name':'header','title' : 'Header' },
					{ 'name':'footer','title' : 'Footer' },				
				],
                modal                : null,
                theme                : null,
                container_position   : null,
                theme_types          : fe_templates_lib.theme_types || [],
                single_template      : '',
                loop_wrapper         : null,
                theme_filter_id      : null,
                current_template_type: 'block',
                current_template     : null,
                current_page         : 0,
                paginate_templates   : {'sections': 20, 'pages': 4, 'header': 3},   // perpage
                filter_sections      : [],
                sections             : [],
                headers              : [],
                footers              : [],
                pages                : []
            };
            // Allow overriding the default config
            $.extend(Wcf_Template_Library.config, settings);
            elementor.on('document:loaded', function () {
                Wcf_Template_Library.setup_template_window();
                Wcf_Template_Library.setup_initial_templates();
                Wcf_Template_Library.observeChooseAction();
               
            });
          
            $(document).on('click', '.er-template-go-details', Wcf_Template_Library.go_details);
            $(document).on('click', '.wcf--back--to-templates-lib', Wcf_Template_Library.back_to_template_library);
            jQuery(document).on('click', '#wcf-ready-template-close-icon', Wcf_Template_Library.close_main_modal);
            // paginate
            jQuery(document).on('click', '.wcf-templates-pagination .prev', Wcf_Template_Library.prev_page_template);
            jQuery(document).on('click', '.wcf-templates-pagination .next', Wcf_Template_Library.next_page_template);

            // filter
            jQuery(document).on('click', '.wcf-info-ready--tpl-tag-filter > div', Wcf_Template_Library.filter_templates);
            jQuery(document).on('change', '.wcf-templates-category', Wcf_Template_Library.filter_block_category);
            jQuery(document).on('change', '.wcf-templates-themecategory', Wcf_Template_Library.filter_theme_category);
            jQuery(document).on('input', '.wcf-ready--tpl-search input', Wcf_Template_Library.user_input_filter);
            jQuery(document).on('click', '.wcf-ready--tpl-search span', Wcf_Template_Library.user_input_filter);
           
            $(document).on('click', '.er-template-lib-modal-header .er-template-import, .action-wrapper .er-template-import', Wcf_Template_Library.template_import)
        },
        
        observeChooseAction(){
          
            const editorContainer = elementor.$previewContents.find('.elementor-section-wrap')[0];
            
            function handleNewChooseActionElements() {                
               
                let allinline = elementor.$previewContents.find('.elementor-section-wrap .elementor-add-section-inline');
                
                allinline.each(function(item){
                    let thisnew = $(this).find('.elementor-add-template-button');
                    if( thisnew.parent().find('.wcf-ready-add-template-button').length > 0 ){
                        return;
                    }else{
                        let newEle = $(`<div class="elementor-add-section-area-button wcf-ready-add-template-button" ><img src="${fe_templates_lib.logoUrl}" /></div>`);
                        $(newEle).on('click', 'img', function(){                             
                            Wcf_Template_Library.openM();
                            let activeEle = $(this).parents('.elementor-add-section-inline').addClass('active');
                            Wcf_Template_Library.container_position = $(this).parents('.elementor-section-wrap').find('.elementor-add-section-inline.active').index();
                            activeEle.removeClass('active');
                        });
                        thisnew.after(newEle);
                    }
                    
                });   
               
            }
            
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.addedNodes.length) {
                        handleNewChooseActionElements();
                    }
                });
            });
            
            if (editorContainer) {
              
               observer.observe(editorContainer, {
                   childList: true,
                   subtree: true
               });
            }  
           
        },

        setup_template_window: function () {
    
            var $previewContents                         = elementor.$previewContents;
                Wcf_Template_Library.config.loop_wrapper = $('#wcf-addon-template-grid-wrapper');
            var $templateLibBtnStyle                     = $('<style />'),
                btnStyle                                 = '';
            btnStyle += '.elementor-add-section-area-button.wcf-info-ready-add-template-button { }';
            btnStyle += '.elementor-add-section-area-button.wcf-info-ready-add-template-button img {  }';

            $templateLibBtnStyle.html(btnStyle);
            Wcf_Template_Library.config.modal = $('#wcf-ready-template-lib');
            $previewContents.find('head').append($templateLibBtnStyle);

            var $templateLibBtn = $('<div />');
            Wcf_Template_Library.config.templateLibBtn = $templateLibBtn;

            $templateLibBtn.addClass('elementor-add-section-area-button wcf-info-ready-add-template-button');
            $templateLibBtn.attr('title', 'Add WCF Template');
            $templateLibBtn.html('<img src="' + fe_templates_lib.logoUrl + '" />');
            $templateLibBtn.insertAfter($previewContents.find('.elementor-add-section-area-button.elementor-add-template-button'));
            Wcf_Template_Library.config.templateLibBtn.on('click', function (e) {   
                
                Wcf_Template_Library.openM();
            });   
        },
        
        openM: function(){
            Wcf_Template_Library.config.modal.slideToggle();
            Wcf_Template_Library.back_to_template_library(); 
        },

        back_to_template_library: function () {
            $('.er-template-lib-modal-header').html(Wcf_Template_Library.template_library_header());
            $('.wcf-template-category-section.er-filter-wrapper').html(Wcf_Template_Library.template_filter_html());
            Wcf_Template_Library.mesonary_build();
        },
        close_main_modal: function (e) {
            Wcf_Template_Library.config.modal.slideToggle();
        },
        template_import: function () {

            $(".wcf-templates-list-renderer").addClass('importing').focus();
            $(this).html('importing').focus();
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

            wcf_template_ready_doAjax({
                action: 'eready_get_library_data_single',
                tpl_id: $(this).data('template_id'),
                editor_post_id: ElementorConfig.initial_document.id,
                sync: true
            }).then((response) => {
                console.log(response);
                $(".wcf-templates-list-renderer").removeClass('importing');

                try {
                    // Successs                       
                    let $opts = {
                        model: '',
                        container: elementor.getPreviewContainer()                       
                    };
                    
                    if(Wcf_Template_Library.container_position !=null){
                        $opts.options = {'at': Wcf_Template_Library.container_position}
                    }
                   
                    response.data.cus.content.forEach(element => {                       
                        var newWidget = {};
                        newWidget.elType = element.elType;
                        newWidget.settings = element.settings;
                        newWidget.elements = element.elements;
                        $opts.model = newWidget;
                        $e.run("document/elements/create", $opts);
                    });         
                    Wcf_Template_Library.container_position = null;
                    Wcf_Template_Library.config.modal.slideToggle();
                    elementor.notifications.showToast({
                        message: elementor.translate('Content Pasted! ')
                    });
                    $('.body-import-active-overlay').remove();
                } catch (err) { 
                    console.log(err);
                    $('.wcf-templates-list-renderer').html('Demo Import fail');
                }
            });
        },
        setup_initial_templates: function (type) {
            let pages_cats = new Set();
            if (fe_templates_lib._templates.length) {
                $.each(fe_templates_lib._templates, function (i, item) {
                    if (item.type === 'block') {
                        Wcf_Template_Library.config.sections.push(item);
                    }
                    if (item.type === 'page') {
                        Wcf_Template_Library.config.pages.push(item);
                        const s_num = item.subtype.split(",");
                        s_num.forEach((sca) => {
                            pages_cats.add(parseInt(sca));
                        });
                      
                    }
                    if (item.type === 'header') {
                        Wcf_Template_Library.config.headers.push(item);                      
                    }
                    if (item.type === 'footer') {
                        Wcf_Template_Library.config.footers.push(item);
                    }
                    //
                });
            }
           
            Wcf_Template_Library.config.pages_cats = pages_cats;

        },

        get_initial_element: function (type) {
        
            if (type == 'page') {
                return Wcf_Template_Library.config.pages;
            }

            if (type == 'header') {
                return Wcf_Template_Library.config.headers;
            }

            if (type == 'footer') {
                return Wcf_Template_Library.config.footers;
            }

            return Wcf_Template_Library.config.sections;
        },
        
        is_single_template_insert_button: function (single) {

            let return_data = ``;
            if (single.isPro) {
                return_data = ``;
            } else {
                return_data = `
				<button class="er-template-import wcf--general-tpls-button" href="${single.url}" data-pro="${single.isPro}" data-template_id="${single.template_id}" data-title="${single.title}">
						Insert
					</button>				
				`;
            }
            return return_data;
        },

        setup_loop_template: function (single) {
            return `			
			<div class="img-wrapper">
				<img data-src="${single.thumbnail}" src="${single.thumbnail}"/>
			</div>
			<div class="action-wrapper">				
					${Wcf_Template_Library.is_single_template_pro(single)}
					${Wcf_Template_Library.is_single_template_insert_button(single)}								
			</div>
			<h3 class="wcf-info-ready-tpl-title">
				<b>${single.title}</b>
				<span class="publicationdate" hidden>${single.date}</span>
			</h3>				
			`;
        },

        go_details: function (e) {

            let label = $(this).attr('data-pro') == undefined || $(this).attr('data-pro') == '' ? `<button class="er-template-import wcf--general-tpls-button" data-template_id="${$(this).attr('data-template_id')}">Insert</button>` : `<a class="wcf--template-pro wcf--general-tpls-button">Go Pro</a>`;
            Wcf_Template_Library.config.current_template = parseInt($(this).attr('data-template_id')) || null;
            let details_header = `
		    <div class="wcfready-header-left">
		        <button class="wcf--back--to-templates-lib wcf--general-tpls-button">Back to Library</button>
		    </div>
		    <div class="eready-header-right">
		        ${label}
		    </div>
		  `;

            var result = fe_templates_lib._templates.find(obj => {
                return obj.template_id === Wcf_Template_Library.config.current_template
            });

            $('.er-template-lib-modal-header').html(details_header);
            $('.wcf-template-category-section.er-filter-wrapper').html('');
            $('.wcf-templates-list-renderer').html(`<iframe src="${result.url || '#'}" title="${result.title}"></iframe>`);
        },

        next_page_template: function () {
            Wcf_Template_Library.config.current_page = Wcf_Template_Library.config.current_page + 1;
            Wcf_Template_Library.mesonary_build();
        },

        prev_page_template: function () {
            Wcf_Template_Library.config.current_page = Wcf_Template_Library.config.current_page - 1;
            Wcf_Template_Library.mesonary_build();
        },
        user_input_filter: function (e) {
        
            let user_input = '';
            let filter_blocks = [];
            if ($(this).hasClass('eicon-search')) {
                user_input = $(this).parents('.wcf-ready--tpl-search').find('input').val();
            } else {
                user_input = $(this).val();
            }

            Wcf_Template_Library.config.current_template_type = 'block';
            if (user_input.length > 2) {
                if (fe_templates_lib._templates.length) {
                    $.each(fe_templates_lib._templates, function (i, item) {
                        if (item.title.toUpperCase().indexOf(user_input.toUpperCase()) > 1) {
                            filter_blocks.push(item);
                        }
                    });
                }
                Wcf_Template_Library.config.filter_sections = filter_blocks;
            } else {
                Wcf_Template_Library.config.filter_sections = [];
            }
            if (user_input.length) {
                Wcf_Template_Library.mesonary_build('user_input');
            } else {
                Wcf_Template_Library.mesonary_build();
            }

        },
        filter_templates: function () {
            Wcf_Template_Library.config.theme_filter_id = null;
            Wcf_Template_Library.config.current_page = 0;  
            let current = $(this).attr('data-title');
            $(this).siblings().removeClass('active');
            jQuery('.wcf-templates-themecategory option:selected').prop('selected', false);
            jQuery('.wcf-templates-category option:selected').prop('selected', false);
            if ($(this).hasClass('active')) {
                current = 'block';
                $(this).removeClass('active');
            } else {
                $(this).addClass('active');
            }

            if (current == 'page') {         
                $('.wcf-template-category-section.er-filter-wrapper').html(Wcf_Template_Library.template_pagecat_filter_html());
            } else {
                $('.wcf-template-category-section.er-filter-wrapper').html(Wcf_Template_Library.template_filter_html());
            }
            Wcf_Template_Library.config.filter_sections = [];
            Wcf_Template_Library.config.current_template_type = current;
            Wcf_Template_Library.mesonary_build(current);
         
        },
        
        filter_theme_category: function(e){  
            let filter_primary                              = [];
            let primary_ps_cat = jQuery('.wcf-templates-category option:selected').val();
         
            let cat                                         = '';
                Wcf_Template_Library.config.theme_filter_id = this.value;
                Wcf_Template_Library.config.current_page = 0;    
            Wcf_Template_Library.config.filter_sections = Wcf_Template_Library.get_initial_element(Wcf_Template_Library.config.current_template_type); 
            
            if(Wcf_Template_Library.config.current_template_type == 'header' || Wcf_Template_Library.config.current_template_type == 'footer'){
                filter_primary = Wcf_Template_Library.config.filter_sections;              
            }else if(primary_ps_cat !== 'all'){
                filter_primary = [];                
                for (let j = 0; j < Wcf_Template_Library.config.filter_sections.length; j++) {
                    cat = Wcf_Template_Library.config.filter_sections[j].subtype.split(',');
                    if (cat.includes(primary_ps_cat)) {                       
                        filter_primary.push(Wcf_Template_Library.config.filter_sections[j]);
                    }
                }                
            }else if(primary_ps_cat == 'all'){
                filter_primary = [];                
                for (let j = 0; j < Wcf_Template_Library.config.filter_sections.length; j++) {                                         
                    filter_primary.push(Wcf_Template_Library.config.filter_sections[j]);                   
                } 
            }
            
            if(Wcf_Template_Library.config.theme_filter_id !== 'all'){
                let theme_items = [];              
                for (let j = 0; j < filter_primary.length; j++) {
                    cat = filter_primary[j].subtype.split(',');
                    if (cat.includes(Wcf_Template_Library.config.theme_filter_id)) {                 
                        theme_items.push(filter_primary[j]);
                    }
                } 
                Wcf_Template_Library.config.filter_sections = theme_items;
               
            }else if(Wcf_Template_Library.config.theme_filter_id == 'all'){
                Wcf_Template_Library.config.filter_sections = filter_primary;
            }
      
            Wcf_Template_Library.mesonary_build();         
        },

        filter_block_category: function () {
        
            let search_keys   = this.value;
            let cat           = '';
            let filter_blocks = [];
            
            jQuery('.wcf-templates-themecategory option:selected').prop('selected', false);
            Wcf_Template_Library.config.theme_filter_id = null;
            Wcf_Template_Library.config.current_page    = 0;          
              
            if (search_keys != 'all') {
                // Blocks
                if (Wcf_Template_Library.config.current_template_type == 'block') {
                    for (let i = 0; i < Wcf_Template_Library.config.sections.length; i++) {
                        cat = Wcf_Template_Library.config.sections[i].subtype.split(',');
                        if (cat.includes(search_keys)) {
                            filter_blocks.push(Wcf_Template_Library.config.sections[i]);
                        }
                    }

                }
              
                if (Wcf_Template_Library.config.current_template_type == 'page') {
                    // Page
                  
                    for (let j = 0; j < Wcf_Template_Library.config.pages.length; j++) {
                        cat = Wcf_Template_Library.config.pages[j].subtype.split(',');
                        if (cat.includes(search_keys)) {                        
                    
                            filter_blocks.push(Wcf_Template_Library.config.pages[j]);
                        }
                    }
                }

                Wcf_Template_Library.config.filter_sections = filter_blocks;                
            } else {
                Wcf_Template_Library.config.filter_sections = Wcf_Template_Library.config.sections;
            }
            
            Wcf_Template_Library.mesonary_build();
        },
        
        mesonary_build: function (tpl_type = null) {

            var eleemnt_type              = null;
            let row                       = document.querySelector('.wcf-templates-list-renderer');
            let element_wrapper           = document.createElement('div');                           // is a node
                element_wrapper.className = "wcf-row";
                row.innerHTML             = '';
            let data_status_found         = '';
                                
             
            if (tpl_type == 'block') {
                eleemnt_type = Wcf_Template_Library.get_initial_element(Wcf_Template_Library.config.current_template_type);
            } else {
              
                if ((Wcf_Template_Library.config.current_template_type == 'block' || Wcf_Template_Library.config.current_template_type == 'page' ) && Wcf_Template_Library.config.filter_sections.length) {
                    eleemnt_type = Wcf_Template_Library.config.filter_sections;                   
                }else if(Wcf_Template_Library.config.theme_filter_id !==null){
                
                 eleemnt_type = Wcf_Template_Library.config.filter_sections;       
                } else {
                    if (tpl_type === 'user_input') {
                        data_status_found = 'Nothing Found';
                    }                    
                    eleemnt_type = Wcf_Template_Library.get_initial_element(Wcf_Template_Library.config.current_template_type);
                }
            }
            
            if(Wcf_Template_Library.config.theme_filter_id){
                
            }
          
            var templatesCopy = [...eleemnt_type];
           
            var total_page = 1;
            templatesCopy = templatesCopy.chunk_inefficient(Wcf_Template_Library.config.paginate_templates.sections);
            total_page = templatesCopy.length - 1;
            if (total_page >= Wcf_Template_Library.config.current_page) {
                templatesCopy = templatesCopy[Wcf_Template_Library.config.current_page];
            } else {
                templatesCopy = templatesCopy[0];
            }

            if (Wcf_Template_Library.config.current_template_type == 'block' || Wcf_Template_Library.config.current_template_type == 'page') {
                $('.wcf-ready-tpl-sort-filter-wrapper').css("display", "block");
            } else {
                $('.wcf-ready-tpl-sort-filter-wrapper').css("display", "none");
            }

            var pagination = document.createElement('div'); // is a node
            pagination.className = "wcf-templates-pagination";
            if (Wcf_Template_Library.config.current_page == 0) {
                pagination.innerHTML = '<ul><li class="wcf--general-tpls-button next"> Next </li> </ul>';
            } else if (Wcf_Template_Library.config.current_page === total_page) {
                pagination.innerHTML = '<ul><li class="wcf--general-tpls-button prev"> Prev </li> </ul>';
            } else {
                pagination.innerHTML = '<ul><li class="wcf--general-tpls-button prev"> Prev </li> <li class="wcf--general-tpls-button next"> Next </li> </ul>';
            }
            // Number of columns
            
            let cols = 4;
            var lg_tablet    = window.matchMedia("(max-width: 1300px)");
            var tablet       = window.matchMedia("(max-width: 1023px)");
            var mobile       = window.matchMedia("(max-width: 767px)");

            if ( lg_tablet.matches ) {
                cols = 3;
                if ( Wcf_Template_Library.config.current_template_type == 'header' ) {
                    cols = 2;
                }
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
                    const item = document.createElement('div');
                    item.classList.add('wcf-template-type');
                    item.classList.add(templatesCopy[i].type);
                    item.dataset.category = templatesCopy[i].subtype;
                    item.innerHTML = Wcf_Template_Library.setup_loop_template(templatesCopy[i]);
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

        is_single_template_pro: function (item) {

            let return_data = ``;
            if (item.isPro) {
                return_data = `<a class="er-template-pro" target="_blank" href="https://themeforest.net/user/crowdyflow" data-pro="${item.isPro}" data-title="${item.title}">
				Pro  
			</a>`;
            } else {
                return_data = `
				<a class="er-template-go-details" href="javascript:void(0);" data-pro="${item.isPro}" data-template_id="${item.template_id}" data-title="${item.title}">
					View
				</a>				
				`;
            }
            return return_data;
        },

        template_library_header: function () {
            let html_cat = '';
            Wcf_Template_Library.config.primary_cat.forEach(function(item) {            
                let counter = 0;                
                if('page' == item.name){
                    counter = Wcf_Template_Library.config.pages.length;
                }else if('block' == item.name){
                    counter = Wcf_Template_Library.config.sections.length;
                }else if('header' == item.name){
                    counter = Wcf_Template_Library.config.headers.length;
                }else if('footer' == item.name){
                    counter = Wcf_Template_Library.config.footers.length;
                }
                html_cat += `<div class="${item.name}-filter" data-title="${item.name}">${item.title}</div>`
            });
            return `<div class="wcfready-header-left">
			<img src="${fe_templates_lib.logoUrl}" />
			<h2>${Wcf_Template_Library.config.product_name}</h2>	
		</div>
		<div class="wcfready-header-center"> 
			<div class="wcf-info-ready--tpl-tag-filter">
				${html_cat}
			</div>
		</div>
		<div class="eready-header-right">
			<i id="wcf-ready-template-close-icon" class="eicon-close" aria-hidden="true" title="Close"></i>
		</div>`;
        },

        template_filter_html: function () {
           
            let generate_category = `<option value="all">All Category</option>`;
            $.each(fe_templates_lib.categories, function (i, item) {
                generate_category += `<option value="${item.id}" class="er-templates-cat-option">${item.title}</option>`;
            });
            // Theme Cateory
            // let generate_themecategory = '<option class="theme-block" value="all">All Theme</option>';
            // $.each(Wcf_Template_Library.config.theme_types, function (i, item) {                
            //     generate_themecategory += `<option value="${item.id}" class="er-templates-themecat-option">${item.title}</option>`;               
            // });
            
            let return_html = `<div class="wcf--left-filter-wrapper"> <div class="wcf-ready-tpl-sort-filter-wrapper">
    				<div class="wcf-category-wrapper">				
    					<select class="wcf-templates-category">
    						${generate_category}				
    					</select>
    				</div>				
        		</div>
			   
			</div>
			<div class="wcf-ready-template-search">
				<div class="wcf-ready--tpl-search">
					<span class="eicon-search"></span>
					<input placeholder="Search term" />
				</div>
			</div>`;
            return return_html;
        },

        template_pagecat_filter_html: function () {
            let generate_category = `<option class="page" value="all">All Category</option>`;
            $.each(fe_templates_lib.categories, function (i, item) {
                if (Wcf_Template_Library.config.pages_cats.has(item.id)) {
                    generate_category += `<option value="${item.id}" class="er-templates-cat-option">${item.title}</option>`;
                }
            });
            
            // let generate_themecategory = `<option class="theme-page" value="all">All Theme</option>`;
            // $.each(Wcf_Template_Library.config.theme_types, function (i, item) {                
            //     generate_themecategory += `<option value="${item.id}" class="er-templates-themecat-option">${item.title}</option>`;               
            // });
            
            let return_html = `<div class="wcf--left-filter-wrapper"> <div class="wcf-ready-tpl-sort-filter-wrapper">
    					<div class="wcf-category-wrapper">				
    						<select class="wcf-templates-category">
    							${generate_category}				
    						</select>
    					</div>	    					
    				</div>	    				
				</div>				
				<div class="wcf-ready-template-search">
					<div class="wcf-ready--tpl-search">
						<span class="eicon-search"></span>
						<input placeholder="Search term" />
					</div>
				</div>`;
            return return_html;
        },


    };
    // Run App module
    Wcf_Template_Library.init();
})(elementor, jQuery, window);


