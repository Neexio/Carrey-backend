<style>

    .wcf-single-tpl-wrapper .wcf--blog-builder-list {
        display: flex;
        gap: 80px;
        flex-wrap: wrap;
    }

    .wcf-hover-element {
        visibility: hidden;
        opacity: 0;
        transition: 0.3s;
    }

    .wcf-image-tpl:hover .wcf-hover-element {
        visibility: visible;
        opacity: 1;
    }

    .wcf-image-tpl {
        position: relative;
    }

    .wcf-image-tpl.active {
        border: 3px solid #db4d4d;
    }

    .wcf-image-tpl::after {
        position: absolute;
        width: 100%;
        height: 100%;
        left: 0;
        top: 0;
        background: #663399cf;
        content: "";
        z-index: 0;
        opacity: 0;
        transition: all 0.3s;
    }

    .wcf-image-tpl:hover:after {
        opacity: 1;
    }

    .wcf-image-tpl img {
        height: 100%;
    }

    .wcf-hover-element {
        cursor: pointer;
        visibility: hidden;
        opacity: 0;
        transition: 0.3s;
        position: absolute;
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        top: 0;
        left: 0;
        z-index: 5;
        color: white;
    }

    .wcf-blog-ele-tpl-pagination ul {
        margin: 0;
        gap: 10px;
        display: flex;
        flex-wrap: wrap;
    }

    .wcf-blog-ele-tpl-pagination ul li {
        border: 1px solid #eee;
        border-radius: 5px;
        padding: 10px;
        width: 20px;
        height: 20px;
        display: flex;
        justify-content: center;
        align-items: center;
        cursor: pointer;
        transition: all 0.3s;
        margin: 0;
        font-size: 16px;
    }

    .wcf-blog-ele-tpl-pagination ul li:hover {
        color: #fff;
        background: #40CF79;
    }

    /** Modal Popup */

    /* [Object] Modal
	 * =============================== */
    .wcf-modal {
        opacity: 0;
        visibility: hidden;
        position: fixed;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        background: rgba(0, 0, 0, .9);
        transition: opacity .25s ease;
        text-align: center;
        z-index: 99999;
    }

    .wcf-modal h2 {
        padding-top: 30px;
        padding-bottom: 30px;
        margin: 0;
        font-size: 36px;
        line-height: 1;
    }

    .wcf-modal__bg {
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        cursor: pointer;
    }

    .wcf-modal-state,
    .wcf-modal-state {
        display: none !important;
    }

    .wcf-modal-state:checked + .wcf-modal {
        opacity: 1;
        visibility: visible;
    }

    .wcf-modal-state:checked + .wcf-modal .wcf-modal__inner {
        top: 0;
    }

    .wcf-modal__inner {
        transition: top .25s ease;
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        width: 100%;
        margin: auto;
        overflow: auto;
        background: #fff;
        height: 100%;
    }

    .wcf-modal__close-import {
        width: 1.1em;
        height: 1.1em;
        cursor: pointer;
    }

    .wcf-page-li.active {
        background: #40CF79;
        color: #fff;
    }

    .wcf-modal__close-import:after,
    .wcf-modal__close-import:before {
        content: '';
        position: absolute;
        width: 2px;
        height: 1.5em;
        background: #ccc;
        display: block;
        transform: rotate(45deg);
        margin: -3px 0 0 -1px;
    }

    .wcf-modal__close-import:hover:after,
    .wcf-modal__close-import:hover:before {
        background: #aaa;
    }

    .wcf-modal__close-import:before {
        transform: rotate(-45deg);
    }

    @media screen and (max-width: 768px) {

        .wcf-modal__inner {
            width: 70%;
            height: 70%;
            box-sizing: border-box;
        }
    }

    .wcf-content-install h1 {
        line-height: 1.2;
    }

    .loading .wcf-tpl-loader {
        display: block;
        margin: 0 auto;
    }

    .wcf-tpl-loader svg {
        width: 100%;
    }

    .wcf-tpl-loader {
        display: none;
    }

    .wcf-row {
        display: grid;
        gap: 20px;
        grid-template-columns: repeat(4, 1fr);
        margin: 0 30px;
        padding: 20px;
        background: #F8F8FA;
        border-radius: 8px 8px 0 0;
    }

    .wcf-item {
        margin-bottom: 20px;
    }

    .wcf-row .wcf-item:hover .wcf-thumb-area::after {
        opacity: 1;
        visibility: visible;
    }

    .grid-wrapper .wcf-button-action {
        background: #fff;
        width: 100%;
        padding: 12px 0;
        z-index: 3;
        display: none;
    }

    .grid-wrapper .wcf-button-action span {
        color: #423b3b;
        font-size: 18px;
        margin: 1em 0;
    }

    .wcf-details-tpl,
    .wcf-preview-page {
        background: #fff;
        color: #1C1D20;
        font-size: 15px;
        transition: 0.3s;
        border-radius: 30px;
        padding: 10px 20px;
        border: none;
        font-weight: 400;
        text-decoration: none;
        opacity: 0;
        cursor: pointer;
    }

    .wcf-details-tpl {
        color: #fff;
        background: #40CF79;
    }

    .wcf-details-tpl:hover,
    .wcf-preview-page:hover {
        color: #fff;
        background: #1C1D20;
    }

    .wcf-row .wcf-item:hover .wcf-preview-page,
    .wcf-row .wcf-item:hover .wcf-details-tpl {
        opacity: 1;
    }

    .main-container {
        margin: 0 auto;
        max-width: 1170px;
        padding: 1rem;
    }

    .type-wcf-single-post input[type=checkbox],
    .type-wcf-blog-tpl input[type=checkbox],
    .type-wcf-search-tpl input[type=checkbox],
    .type-wcf-error-tpl input[type=checkbox]
    {
        height: 0;
        width: 0;
        visibility: hidden;
        display: none;
    }

    .wp-list-table .column-active label {
        cursor: pointer;
        text-indent: -9999px;
        width: 87px;
        height: 32px;
        background: grey;
        display: block;
        border-radius: 100px;
        position: relative;
    }

    .wp-list-table .column-active label:after {
        content: '';
        position: absolute;
        top: 5px;
        left: 5px;
        width: 32px;
        height: 22px;
        background: #fff;
        border-radius: 90px;
        transition: 0.3s;
    }

    .wp-list-table .column-active input:checked + label {
        background: #bada55;
    }

    .wp-list-table .column-active input:checked + label:after {
        left: calc(100% - 5px);
        transform: translateX(-100%);
    }

    .wp-list-table .column-active label:active:after {
        width: 100px;
    }

    .wcf-modal-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin: 0 30px;
        border-bottom: 1px solid #eee;
    }

    .wcf-pagi-wrapper {
        gap: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 30px 30px;
        padding-bottom: 50px;
        background: #F8F8FA;
        border-radius: 0 0 8px 8px;
    }

    .wcf-pagi-wrapper h3 {
        margin: 0;
        font-size: 30px;
        line-height: 1;
        margin-top: -7px;
    }

    .wcf-tpl-details-header {
        padding-bottom: 30px;
    }

    .wcf-blog-type-button {
        background: #666;
        color: #fff !important;
        padding: 10px;
        text-align: center;
    }

    .wcf-blog-type-button.active {
        background: #40CF79;
        font-weight: 700;
        font-size: 20px;
    }

    .link--wrapper {
        gap: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 3;
    }

    .wcf--grid-item {
        background: #fff;
        padding: 10px;
    }

    .wcf-thumb-area {
        position: relative;
    }

    .wcf-thumb-area::after {
        position: absolute;
        width: 100%;
        height: 100%;
        content: "";
        background: rgba(22, 33, 50, 0.4);
        left: 0;
        top: 0;
        opacity: 0;
        z-index: 0;
        visibility: hidden;
        transition: all 0.5s;
    }

    .wcf-thumb-area img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .wcf--blog-tpl-install-remote,
    .wcf-return-to-list {
        background: #40CF79;
        color: #fff;
        font-size: 15px;
        transition: 0.3s;
        border-radius: 30px;
        padding: 10px 20px;
        border: none;
        font-weight: 400;
        text-decoration: none;
        cursor: pointer;
    }

    .wcf--blog-tpl-install-remote:hover,
    .wcf-return-to-list:hover {
        background: #1C1D20;
    }

    .wcf--blog-builder-templates {
        padding-top: 10px;
    }


    /*  Large Tablet  */
    @media (max-width: 1365px) {
        .wcf-row {
            gap: 15px;
            grid-template-columns: repeat(3, 1fr);
            margin: 0 20px;
            padding: 15px;
        }

        .wcf--grid-item {
            padding: 5px;
        }

        .wcf-details-tpl, .wcf-preview-page {
            opacity: 1;
        }

        .wcf-thumb-area::after {
            opacity: 1;
            visibility: visible;
        }

        .wcf-modal h2 {
            padding-top: 20px;
            padding-bottom: 20px;
            font-size: 30px;
        }

        .wcf-pagi-wrapper h3 {
            font-size: 24px;
        }

        .wcf-item {
            margin-bottom: 15px;
        }
        .wcf-modal-top {
            margin: 0 20px;
        }

    }

    /* Tablet */
    @media (max-width: 1023px) {
        .wcf-modal__inner {
            width: 100%;
            height: 100%;
        }

        .wcf-row {
            grid-template-columns: repeat(2, 1fr);
        }

        .wcf-modal h2 {
            font-size: 25px;
        }

        .wcf-pagi-wrapper h3 {
            font-size: 24px;
        }

    }

    /* Mobile */
    @media (max-width: 767px) {
        .wcf-row {
            grid-template-columns:1fr;
            margin: 0 15px;
        }

        .wcf-modal h2 {
            font-size: 24px;
        }

        .wcf-pagi-wrapper h3 {
            font-size: 22px;
        }
        .wcf-pagi-wrapper {
             margin: 0 15px;
             padding-bottom: 50px;
         }
        .wcf-modal-top {
            margin: 0 15px;
            flex-direction: column;
        }
        .wcf-modal h2 {
            padding-top: 15px;
            padding-bottom: 0;
        }
        .wcf-modal__close-import {
            margin-top: 20px;
            margin-bottom: 15px;
        }

    }


</style>

<?php
$json_data = sassly_get_config_value_by_name( 'elementor/layouts' );
?>
<script>

    window.wcf_templates_configs = JSON.parse('<?php echo json_encode( $json_data ) ?>');
    window.wcf_popup_current_page = 0;
    document.addEventListener("DOMContentLoaded", function (event) {
        var data = {action: "wcf_post_tpl_remote_import", tpl_id: 0};
        var ajax_path = '<?php echo admin_url( 'admin-ajax.php' ) ?>';
        jQuery(document).on('click', '.wcf--blog-tpl-install-remote', function (e) {

            var _this_ele = jQuery(this);
            data.action = window.wcf_templates_configs['active'].action_fetch;
            data.source = window.wcf_templates_configs['active'].type;

            jQuery('.wcf-single-tpl-wrapper h1').html('Template Importing . Please Wait');
            jQuery('.wcf-image-tpl').removeClass('active');
            if (_this_ele.attr('data-next')) {
                data.action = window.wcf_templates_configs['active'][_this_ele.attr('data-next')];
            }

            data.tpl_id = jQuery(this).attr('data-id');
            if (wcf_templates_configs['active'].content[data.tpl_id]) {
                data.thumbnail = wcf_templates_configs['active'].content[data.tpl_id].thumbnail;
                data.title = wcf_templates_configs['active'].content[data.tpl_id].title;
            }

            jQuery.ajax({
                type: 'post',
                url: ajax_path,
                data: data,
                success: function (response) {
                    jQuery('.wcf-single-tpl-wrapper h1').html(response.message);
                    setTimeout(() => {
                        jQuery('.wcf-single-tpl-wrapper h1').html('');
                    }, 5000);

                    _this_ele.attr('data-next', response.next_step);

                    if (response.next_step === 'action_activate') {
                        _this_ele.html('Activate');
                    }

                    if (response.hasOwnProperty('current_layout_id')) {
                        _this_ele.attr('data-id', response.current_layout_id);
                    }

                    if (response.next_step === 'action_deactivate') {
                        _this_ele.html('Deactivate');
                    }
                }
            });
        });

        jQuery(document).on('click', ".wcf-post-layout-import-modal", function () {
            jQuery('.wcf-pagi-wrapper').hide();
            jQuery('.wcf--blog-builder-list').addClass('loading');
            var leyout_id = jQuery(this).attr('data-id');
            var post_layout = wcf_templates_configs['layouts'].find(function (item) {
                return item.type === leyout_id;
            });

            if (post_layout) {
                window.wcf_templates_configs['active'] = post_layout;
                jQuery('.wcf--remote-layouts').css({opacity: 1, visibility: 'visible'});
            } else {
                window.wcf_templates_configs['active'] = false;
            }

            if (window.wcf_templates_configs['active']) {
                jQuery.getJSON(window.wcf_templates_configs['active'].base_api, function (data) {
                    window.wcf_templates_configs['active'].content = data.library.templates;
                    wcf__template__content__load();
                });
            }

        });
        // details

        jQuery(document).on('click', '.wcf-details-tpl', function () {
            jQuery('.wcf-pagi-wrapper').hide();
            jQuery('.wcf--blog-builder-templates').hide();
            data.action = window.wcf_templates_configs['active'].action_status;
            data.source = window.wcf_templates_configs['active'].type;
            data.tpl_id = jQuery(this).parent().attr('id');

            jQuery.ajax({
                type: 'post',
                url: ajax_path,
                data: data,
                success: function (response) {

                    if (response.hasOwnProperty('current_layout_id')) {
                        jQuery('.wcf--blog-tpl-install-remote').attr('data-id', response.current_layout_id);
                    }

                    if (response.next_step === 'action_activate') {
                        jQuery('.wcf--blog-tpl-install-remote').html('Activate');
                    }

                    if (response.next_step === 'action_deactivate') {
                        jQuery('.wcf--blog-tpl-install-remote').html('Deactivate');
                    }

                    jQuery('.wcf--blog-tpl-install-remote').attr('data-next', response.next_step);
                    jQuery('.wcf-single-tpl-wrapper h1').html('');

                }
            });

            var html_content = `
        <div class="wcf-tpl-details-header">
         <button class="wcf-return-to-list" >Back To List</button>
         <span data-id="${jQuery(this).parent().attr('id')}" class="wcf--blog-tpl-install-remote">${wcf_templates_configs['active'].button_import}</span>
        </div>       
        <img src="${jQuery(this).parent().attr('data-img')}" />`;
            jQuery('.wcf-single-tpl-wrapper .data-details').html(html_content);
        });

        jQuery(document).on('click', '.wcf-return-to-list', function () {
            jQuery(this).parents('.data-details').html('');
            jQuery('.wcf--blog-builder-templates').show();
            jQuery('.wcf-pagi-wrapper').show();
            wcf__template__content__load(window.wcf_popup_current_page);
        });

        jQuery(document).on('click', ".wcf-modal__close-import", function () {
            jQuery('.wcf--remote-layouts').css({opacity: 0, visibility: 'hidden'});
        });

        jQuery(document).on('click', '.wcf--tpl--switcher', function (e) {
            // e.preventDefault();
            var is_active = jQuery(this).is(':checked');
            var data = {};
            data.action = jQuery(this).attr('data-action');
            data.is_active = is_active;
            data.tpl_id = jQuery(this).attr('data-id');

            jQuery.ajax({
                type: 'post',
                url: ajax_path,
                data: data,
                success: function (response) {
                    location.reload();
                }
            });

        });

        function setup_loop_template(val) {
            return `			
            <div class="wcf--grid-item">
                    <div class="wcf-thumb-area">
                        <img src="${val.thumbnail}" />
                        <div class="link--wrapper" id="${val.id}" data-img="${val.thumbnail}">
                            <span class="wcf-details-tpl">Insert</span>
                             <a target="_blank" href="${val.url}" class="wcf-preview-page">Preview</a>
                        </div>
                    </div>
                    <div class="wcf-button-action" hidden>
                      <span>${val.title}</span>
                    </div>
                </div>			
    			`;
        }

        function wcf__template__content__load(page_id = 0) {
            var items = [];
            let row = document.querySelector('.wcf--blog-builder-templates');
            let element_wrapper = document.createElement('div');                           // is a node
            element_wrapper.className = "wcf-row";
            row.innerHTML = '';
            let counter = 0;
            let page_list_html = '';
            let perpage = 12;
            let items_length = Object.keys(window.wcf_templates_configs['active'].content).length;
            let items_objs = Object.values(window.wcf_templates_configs['active'].content);
            window.wcf_popup_current_page = page_id;
            var results = [];
            while (items_objs.length) {
                results.push(items_objs.splice(0, perpage));
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

            // Map to store all the columns
            let colsCollection = {};
            // Create number of columns
            for (let i = 1; i <= cols; i++) {
                colsCollection[`col${i}`] = document.createElement('div');
                colsCollection[`col${i}`].classList.add('wcf-template-list-column');
            }

            jQuery('.wcf--blog-builder-templates').html('');
            let templatesCopy = results[page_id];
        

            if (templatesCopy != undefined) {
                for (var i = 0; i < cols; i++) {
                    if (!templatesCopy[i]) break;
                    const itemContainer = document.createElement('div');
                    itemContainer.classList.add('wcf-item');
                    const item = document.createElement('div');
                    item.classList.add('wcf-template-type');
                    item.classList.add(templatesCopy[i].type);
                    item.dataset.category = templatesCopy[i].subtype + ' ' + templatesCopy[i].title;
                    item.innerHTML = setup_loop_template(templatesCopy[i]);
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
            row.appendChild(element_wrapper);
            jQuery('.wcf-pagi-wrapper').show();         

            jQuery('.wcf--blog-builder-list').removeClass('loading');
            if (results.length > 1) {
                for (let i = 0; i < results.length; i++) {
                    page_list_html += `<li class="wcf-page-li ${page_id == i ? 'active' : ''}" data-page="${i}">${i + 1}</li>`;
                }
                jQuery('.wcf-blog-ele-tpl-pagination').html(`<ul>${page_list_html}</ul>`);
            } else {
                jQuery('.wcf-pagi-wrapper').hide();
            }

        }

        jQuery(document).on('click', '.wcf-page-li', function () {
            let current_page = parseInt(jQuery(this).attr('data-page'));
            wcf__template__content__load(current_page)
        });
    });
</script>

<div class="wcf-modal wcf--remote-layouts">
    <label class="wcf-modal__bg" for="wcf-elementor-popup"></label>
    <div class="wcf-modal__inner">
        <div class="wcf-modal-top">
        <img src="<?php echo SASSLY_ESSENTIAL_ASSETS_URL . 'images/logo-icon.svg'; ?>" />
        <h2 class="wcf-tpl-layout-header"><?php echo esc_html__( 'Template Layout Import', 'sassly-essential' ) ?></h2>
        <label class="wcf-modal__close-import" for="wcf-elementor-popup"></label>
        </div>
        
        <div class="wcf-content-install">
            <div class="wcf-single-tpl-wrapper">
                <h4><?php //echo esc_html__('Elementor Layout','sassly-essential') ?></h4>
                <h1></h1>
                <div class="data-details"></div>
                <div class="wcf--blog-builder-templates"></div>
                <div hidden class="wcf-pagi-wrapper"><h3><?php echo esc_html__( 'Page:', 'sassly-essential' ); ?></h3>
                    <nav class="wcf-blog-ele-tpl-pagination"></nav>
                </div>
                <div class="wcf--blog-builder-list loading main-container">
                    <div class="wcf-tpl-loader">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200">
                            <rect fill="#FF156D" stroke="#FF156D" stroke-width="15" width="30" height="30" x="25"
                                  y="50">
                                <animate attributeName="y" calcMode="spline" dur="2" values="50;120;50;"
                                         keySplines=".5 0 .5 1;.5 0 .5 1" repeatCount="indefinite"
                                         begin="-.4"></animate>
                            </rect>
                            <rect fill="#FF156D" stroke="#FF156D" stroke-width="15" width="30" height="30" x="85"
                                  y="50">
                                <animate attributeName="y" calcMode="spline" dur="2" values="50;120;50;"
                                         keySplines=".5 0 .5 1;.5 0 .5 1" repeatCount="indefinite"
                                         begin="-.2"></animate>
                            </rect>
                            <rect fill="#FF156D" stroke="#FF156D" stroke-width="15" width="30" height="30" x="145"
                                  y="50">
                                <animate attributeName="y" calcMode="spline" dur="2" values="50;120;50;"
                                         keySplines=".5 0 .5 1;.5 0 .5 1" repeatCount="indefinite" begin="0"></animate>
                            </rect>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>