!function(t){var e={elementorSection:function(t){var e=null;elementorFrontend.isEditMode(),(e=new Wcf_Sticky_Menu_Plugin(t)).init(e)}};Wcf_Sticky_Menu_Plugin=function(e){var n=this,o=e.data("id"),s=Boolean(elementorFrontend.isEditMode()),i=t(window);t("body"),n.init=function(){return n.wcf_infos_sticky(o),!1},n.wcf_infos_sticky=function(t){var o=!1,s=150,c=null;o=n.getSettings(t,"wcf_global_sticky"),c=n.getSettings(t,"wcf_sticky_type"),isNaN(s=parseInt(n.getSettings(t,"wcf_sticky_offset")))&&(s=150),s<5&&(s=110),"yes"==o?(e.addClass("wcf-sticky-container"),"top"==c&&(e.addClass("top"),e.removeClass("bottom")),"bottom"==c&&(e.addClass("bottom"),e.removeClass("top")),""==c&&(e.removeClass("top"),e.removeClass("bottom")),i.on("scroll",function(t){i.scrollTop()<s?e.removeClass("wcf-is-sticky"):e.addClass("wcf-is-sticky")})):e.removeClass("wcf-sticky-container")},n.getSettings=function(e,n){var o=null,i={};return s?!!(window.elementor.hasOwnProperty("elements")&&(o=window.elementor.elements).models&&(t.each(o.models,function(t,n){e==n.id&&(i=n.attributes.settings.attributes)}),i.hasOwnProperty(n)))&&(0,i[n]):(e="section"+e,!!(window.wcf_section_sticky_data&&window.wcf_section_sticky_data.hasOwnProperty(e)&&window.wcf_section_sticky_data[e].hasOwnProperty(n))&&window.wcf_section_sticky_data[e][n])}},t(window).on("elementor/frontend/init",function(){elementorFrontend.hooks.addAction("frontend/element_ready/section",e.elementorSection),elementorFrontend.hooks.addAction("frontend/element_ready/container",e.elementorSection)})}(jQuery);