!function(){"use strict";var t,e={860:function(){document.addEventListener("DOMContentLoaded",(()=>{!function(t){const{activating:e,installing:n,done:o,activationUrl:i,ajaxUrl:r,nonce:a,otterStatus:s}=otterAnimationWelcodeNoticeData,c=t(".otter-animation-welcome-notice #otter-animation-install-otter"),l=t(".otter-animation-welcome-notice .notice-dismiss"),u=t(".otter-animation-welcome-notice"),d=c.find(".text"),f=c.find(".dashicons"),v=()=>{u.fadeTo(100,0,(()=>{u.slideUp(100,(()=>{u.remove(),window.location.reload()}))}))},m=async()=>{var t;d.text(e),await(t=i,new Promise((e=>{jQuery.get(t).done((()=>{e({success:!0})})).fail((()=>{e({success:!1})}))}))),f.removeClass("dashicons-update"),f.addClass("dashicons-yes"),d.text(o),setTimeout(v,1500)};t(c).on("click",(async()=>{f.removeClass("hidden"),c.attr("disabled",!0),"installed"!==s?(d.text(n),await new Promise((t=>{wp.updates.ajax("install-plugin",{slug:"otter-blocks",success:()=>{t({success:!0})},error:e=>{t({success:!1,code:e.errorCode})}})})),await m()):await m()})),t(l).on("click",(()=>{t.post(r,{nonce:a,action:"otter_animation_dismiss_welcome_notice",success:v})}))}(jQuery)}))}},n={};function o(t){var i=n[t];if(void 0!==i)return i.exports;var r=n[t]={exports:{}};return e[t](r,r.exports,o),r.exports}o.m=e,t=[],o.O=function(e,n,i,r){if(!n){var a=1/0;for(u=0;u<t.length;u++){n=t[u][0],i=t[u][1],r=t[u][2];for(var s=!0,c=0;c<n.length;c++)(!1&r||a>=r)&&Object.keys(o.O).every((function(t){return o.O[t](n[c])}))?n.splice(c--,1):(s=!1,r<a&&(a=r));if(s){t.splice(u--,1);var l=i();void 0!==l&&(e=l)}}return e}r=r||0;for(var u=t.length;u>0&&t[u-1][2]>r;u--)t[u]=t[u-1];t[u]=[n,i,r]},o.o=function(t,e){return Object.prototype.hasOwnProperty.call(t,e)},function(){var t={834:0,820:0};o.O.j=function(e){return 0===t[e]};var e=function(e,n){var i,r,a=n[0],s=n[1],c=n[2],l=0;if(a.some((function(e){return 0!==t[e]}))){for(i in s)o.o(s,i)&&(o.m[i]=s[i]);if(c)var u=c(o)}for(e&&e(n);l<a.length;l++)r=a[l],o.o(t,r)&&t[r]&&t[r][0](),t[r]=0;return o.O(u)},n=self.webpackChunkotter_blocks=self.webpackChunkotter_blocks||[];n.forEach(e.bind(null,0)),n.push=e.bind(null,n.push.bind(n))}();var i=o.O(void 0,[820],(function(){return o(860)}));i=o.O(i)}();