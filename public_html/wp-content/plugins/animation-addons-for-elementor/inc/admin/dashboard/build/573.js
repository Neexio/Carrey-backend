"use strict";(globalThis.webpackChunkanimation_addon_for_elementor=globalThis.webpackChunkanimation_addon_for_elementor||[]).push([[573],{522:(e,t,n)=>{n.d(t,{A:()=>o});const o=(0,n(9407).A)("Dot",[["circle",{cx:"12.1",cy:"12.1",r:"1",key:"18d7e5"}]])},854:(e,t,n)=>{n.d(t,{In:()=>Ee,JU:()=>He,LM:()=>Ae,PP:()=>We,UC:()=>Me,VF:()=>Ke,WT:()=>De,YJ:()=>Oe,ZL:()=>Le,bL:()=>Ne,l9:()=>Re,p4:()=>Ve,q7:()=>Be,wn:()=>Fe,wv:()=>Ge});var o=n(1609),r=n(5795),l=n(6165),a=n(9957),i=n(4952),s=n(1071),c=n(2133),d=n(1427),u=n(8666),p=n(1463),f=n(8531),h=n(8723),v=n(4206),m=n(3656),g=n(2579),w=n(3362),x=n(263),y=n(1351),S=n(8200),b=n(5357),C=n(4644),_=n(8241),P=n(4909),j=n(4848),k=[" ","Enter","ArrowUp","ArrowDown"],T=[" ","Enter"],I="Select",[N,R,D]=(0,i.N)(I),[E,L]=(0,c.A)(I,[D,v.Bk]),M=(0,v.Bk)(),[A,O]=E(I),[H,B]=E(I),V=e=>{const{__scopeSelect:t,children:n,open:r,defaultOpen:l,onOpenChange:a,value:i,defaultValue:s,onValueChange:c,dir:u,name:p,autoComplete:f,disabled:m,required:g,form:w}=e,x=M(t),[S,b]=o.useState(null),[C,_]=o.useState(null),[P,k]=o.useState(!1),T=(0,d.jH)(u),[I=!1,R]=(0,y.i)({prop:r,defaultProp:l,onChange:a}),[D,E]=(0,y.i)({prop:i,defaultProp:s,onChange:c}),L=o.useRef(null),O=!S||w||!!S.closest("form"),[B,V]=o.useState(new Set),K=Array.from(B).map((e=>e.props.value)).join(";");return(0,j.jsx)(v.bL,{...x,children:(0,j.jsxs)(A,{required:g,scope:t,trigger:S,onTriggerChange:b,valueNode:C,onValueNodeChange:_,valueNodeHasChildren:P,onValueNodeHasChildrenChange:k,contentId:(0,h.B)(),value:D,onValueChange:E,open:I,onOpenChange:R,dir:T,triggerPointerDownPosRef:L,disabled:m,children:[(0,j.jsx)(N.Provider,{scope:t,children:(0,j.jsx)(H,{scope:e.__scopeSelect,onNativeOptionAdd:o.useCallback((e=>{V((t=>new Set(t).add(e)))}),[]),onNativeOptionRemove:o.useCallback((e=>{V((t=>{const n=new Set(t);return n.delete(e),n}))}),[]),children:n})}),O?(0,j.jsxs)(ke,{"aria-hidden":!0,required:g,tabIndex:-1,name:p,autoComplete:f,value:D,onChange:e=>E(e.target.value),disabled:m,form:w,children:[void 0===D?(0,j.jsx)("option",{value:""}):null,Array.from(B)]},K):null]})})};V.displayName=I;var K="SelectTrigger",W=o.forwardRef(((e,t)=>{const{__scopeSelect:n,disabled:r=!1,...l}=e,i=M(n),c=O(K,n),d=c.disabled||r,u=(0,s.s)(t,c.onTriggerChange),p=R(n),f=o.useRef("touch"),[h,m,w]=Te((e=>{const t=p().filter((e=>!e.disabled)),n=t.find((e=>e.value===c.value)),o=Ie(t,e,n);void 0!==o&&c.onValueChange(o.value)})),x=e=>{d||(c.onOpenChange(!0),w()),e&&(c.triggerPointerDownPosRef.current={x:Math.round(e.pageX),y:Math.round(e.pageY)})};return(0,j.jsx)(v.Mz,{asChild:!0,...i,children:(0,j.jsx)(g.sG.button,{type:"button",role:"combobox","aria-controls":c.contentId,"aria-expanded":c.open,"aria-required":c.required,"aria-autocomplete":"none",dir:c.dir,"data-state":c.open?"open":"closed",disabled:d,"data-disabled":d?"":void 0,"data-placeholder":je(c.value)?"":void 0,...l,ref:u,onClick:(0,a.m)(l.onClick,(e=>{e.currentTarget.focus(),"mouse"!==f.current&&x(e)})),onPointerDown:(0,a.m)(l.onPointerDown,(e=>{f.current=e.pointerType;const t=e.target;t.hasPointerCapture(e.pointerId)&&t.releasePointerCapture(e.pointerId),0===e.button&&!1===e.ctrlKey&&"mouse"===e.pointerType&&(x(e),e.preventDefault())})),onKeyDown:(0,a.m)(l.onKeyDown,(e=>{const t=""!==h.current;e.ctrlKey||e.altKey||e.metaKey||1!==e.key.length||m(e.key),t&&" "===e.key||k.includes(e.key)&&(x(),e.preventDefault())}))})})}));W.displayName=K;var F="SelectValue",G=o.forwardRef(((e,t)=>{const{__scopeSelect:n,className:o,style:r,children:l,placeholder:a="",...i}=e,c=O(F,n),{onValueNodeHasChildrenChange:d}=c,u=void 0!==l,p=(0,s.s)(t,c.onValueNodeChange);return(0,S.N)((()=>{d(u)}),[d,u]),(0,j.jsx)(g.sG.span,{...i,ref:p,style:{pointerEvents:"none"},children:je(c.value)?(0,j.jsx)(j.Fragment,{children:a}):l})}));G.displayName=F;var q=o.forwardRef(((e,t)=>{const{__scopeSelect:n,children:o,...r}=e;return(0,j.jsx)(g.sG.span,{"aria-hidden":!0,...r,ref:t,children:o||"▼"})}));q.displayName="SelectIcon";var U=e=>(0,j.jsx)(m.Z,{asChild:!0,...e});U.displayName="SelectPortal";var z="SelectContent",Z=o.forwardRef(((e,t)=>{const n=O(z,e.__scopeSelect),[l,a]=o.useState();if((0,S.N)((()=>{a(new DocumentFragment)}),[]),!n.open){const t=l;return t?r.createPortal((0,j.jsx)(Y,{scope:e.__scopeSelect,children:(0,j.jsx)(N.Slot,{scope:e.__scopeSelect,children:(0,j.jsx)("div",{children:e.children})})}),t):null}return(0,j.jsx)($,{...e,ref:t})}));Z.displayName=z;var X=10,[Y,J]=E(z),$=o.forwardRef(((e,t)=>{const{__scopeSelect:n,position:r="item-aligned",onCloseAutoFocus:l,onEscapeKeyDown:i,onPointerDownOutside:c,side:d,sideOffset:h,align:v,alignOffset:m,arrowPadding:g,collisionBoundary:x,collisionPadding:y,sticky:S,hideWhenDetached:b,avoidCollisions:C,...k}=e,T=O(z,n),[I,N]=o.useState(null),[D,E]=o.useState(null),L=(0,s.s)(t,(e=>N(e))),[M,A]=o.useState(null),[H,B]=o.useState(null),V=R(n),[K,W]=o.useState(!1),F=o.useRef(!1);o.useEffect((()=>{if(I)return(0,_.Eq)(I)}),[I]),(0,p.Oh)();const G=o.useCallback((e=>{const[t,...n]=V().map((e=>e.ref.current)),[o]=n.slice(-1),r=document.activeElement;for(const n of e){if(n===r)return;if(n?.scrollIntoView({block:"nearest"}),n===t&&D&&(D.scrollTop=0),n===o&&D&&(D.scrollTop=D.scrollHeight),n?.focus(),document.activeElement!==r)return}}),[V,D]),q=o.useCallback((()=>G([M,I])),[G,M,I]);o.useEffect((()=>{K&&q()}),[K,q]);const{onOpenChange:U,triggerPointerDownPosRef:Z}=T;o.useEffect((()=>{if(I){let e={x:0,y:0};const t=t=>{e={x:Math.abs(Math.round(t.pageX)-(Z.current?.x??0)),y:Math.abs(Math.round(t.pageY)-(Z.current?.y??0))}},n=n=>{e.x<=10&&e.y<=10?n.preventDefault():I.contains(n.target)||U(!1),document.removeEventListener("pointermove",t),Z.current=null};return null!==Z.current&&(document.addEventListener("pointermove",t),document.addEventListener("pointerup",n,{capture:!0,once:!0})),()=>{document.removeEventListener("pointermove",t),document.removeEventListener("pointerup",n,{capture:!0})}}}),[I,U,Z]),o.useEffect((()=>{const e=()=>U(!1);return window.addEventListener("blur",e),window.addEventListener("resize",e),()=>{window.removeEventListener("blur",e),window.removeEventListener("resize",e)}}),[U]);const[X,J]=Te((e=>{const t=V().filter((e=>!e.disabled)),n=t.find((e=>e.ref.current===document.activeElement)),o=Ie(t,e,n);o&&setTimeout((()=>o.ref.current.focus()))})),$=o.useCallback(((e,t,n)=>{const o=!F.current&&!n;(void 0!==T.value&&T.value===t||o)&&(A(e),o&&(F.current=!0))}),[T.value]),te=o.useCallback((()=>I?.focus()),[I]),ne=o.useCallback(((e,t,n)=>{const o=!F.current&&!n;(void 0!==T.value&&T.value===t||o)&&B(e)}),[T.value]),oe="popper"===r?ee:Q,re=oe===ee?{side:d,sideOffset:h,align:v,alignOffset:m,arrowPadding:g,collisionBoundary:x,collisionPadding:y,sticky:S,hideWhenDetached:b,avoidCollisions:C}:{};return(0,j.jsx)(Y,{scope:n,content:I,viewport:D,onViewportChange:E,itemRefCallback:$,selectedItem:M,onItemLeave:te,itemTextRefCallback:ne,focusSelectedItem:q,selectedItemText:H,position:r,isPositioned:K,searchRef:X,children:(0,j.jsx)(P.A,{as:w.DX,allowPinchZoom:!0,children:(0,j.jsx)(f.n,{asChild:!0,trapped:T.open,onMountAutoFocus:e=>{e.preventDefault()},onUnmountAutoFocus:(0,a.m)(l,(e=>{T.trigger?.focus({preventScroll:!0}),e.preventDefault()})),children:(0,j.jsx)(u.qW,{asChild:!0,disableOutsidePointerEvents:!0,onEscapeKeyDown:i,onPointerDownOutside:c,onFocusOutside:e=>e.preventDefault(),onDismiss:()=>T.onOpenChange(!1),children:(0,j.jsx)(oe,{role:"listbox",id:T.contentId,"data-state":T.open?"open":"closed",dir:T.dir,onContextMenu:e=>e.preventDefault(),...k,...re,onPlaced:()=>W(!0),ref:L,style:{display:"flex",flexDirection:"column",outline:"none",...k.style},onKeyDown:(0,a.m)(k.onKeyDown,(e=>{const t=e.ctrlKey||e.altKey||e.metaKey;if("Tab"===e.key&&e.preventDefault(),t||1!==e.key.length||J(e.key),["ArrowUp","ArrowDown","Home","End"].includes(e.key)){let t=V().filter((e=>!e.disabled)).map((e=>e.ref.current));if(["ArrowUp","End"].includes(e.key)&&(t=t.slice().reverse()),["ArrowUp","ArrowDown"].includes(e.key)){const n=e.target,o=t.indexOf(n);t=t.slice(o+1)}setTimeout((()=>G(t))),e.preventDefault()}}))})})})})})}));$.displayName="SelectContentImpl";var Q=o.forwardRef(((e,t)=>{const{__scopeSelect:n,onPlaced:r,...a}=e,i=O(z,n),c=J(z,n),[d,u]=o.useState(null),[p,f]=o.useState(null),h=(0,s.s)(t,(e=>f(e))),v=R(n),m=o.useRef(!1),w=o.useRef(!0),{viewport:x,selectedItem:y,selectedItemText:b,focusSelectedItem:C}=c,_=o.useCallback((()=>{if(i.trigger&&i.valueNode&&d&&p&&x&&y&&b){const e=i.trigger.getBoundingClientRect(),t=p.getBoundingClientRect(),n=i.valueNode.getBoundingClientRect(),o=b.getBoundingClientRect();if("rtl"!==i.dir){const r=o.left-t.left,a=n.left-r,i=e.left-a,s=e.width+i,c=Math.max(s,t.width),u=window.innerWidth-X,p=(0,l.q)(a,[X,Math.max(X,u-c)]);d.style.minWidth=s+"px",d.style.left=p+"px"}else{const r=t.right-o.right,a=window.innerWidth-n.right-r,i=window.innerWidth-e.right-a,s=e.width+i,c=Math.max(s,t.width),u=window.innerWidth-X,p=(0,l.q)(a,[X,Math.max(X,u-c)]);d.style.minWidth=s+"px",d.style.right=p+"px"}const a=v(),s=window.innerHeight-2*X,c=x.scrollHeight,u=window.getComputedStyle(p),f=parseInt(u.borderTopWidth,10),h=parseInt(u.paddingTop,10),g=parseInt(u.borderBottomWidth,10),w=f+h+c+parseInt(u.paddingBottom,10)+g,S=Math.min(5*y.offsetHeight,w),C=window.getComputedStyle(x),_=parseInt(C.paddingTop,10),P=parseInt(C.paddingBottom,10),j=e.top+e.height/2-X,k=s-j,T=y.offsetHeight/2,I=f+h+(y.offsetTop+T),N=w-I;if(I<=j){const e=a.length>0&&y===a[a.length-1].ref.current;d.style.bottom="0px";const t=p.clientHeight-x.offsetTop-x.offsetHeight,n=I+Math.max(k,T+(e?P:0)+t+g);d.style.height=n+"px"}else{const e=a.length>0&&y===a[0].ref.current;d.style.top="0px";const t=Math.max(j,f+x.offsetTop+(e?_:0)+T)+N;d.style.height=t+"px",x.scrollTop=I-j+x.offsetTop}d.style.margin=`${X}px 0`,d.style.minHeight=S+"px",d.style.maxHeight=s+"px",r?.(),requestAnimationFrame((()=>m.current=!0))}}),[v,i.trigger,i.valueNode,d,p,x,y,b,i.dir,r]);(0,S.N)((()=>_()),[_]);const[P,k]=o.useState();(0,S.N)((()=>{p&&k(window.getComputedStyle(p).zIndex)}),[p]);const T=o.useCallback((e=>{e&&!0===w.current&&(_(),C?.(),w.current=!1)}),[_,C]);return(0,j.jsx)(te,{scope:n,contentWrapper:d,shouldExpandOnScrollRef:m,onScrollButtonChange:T,children:(0,j.jsx)("div",{ref:u,style:{display:"flex",flexDirection:"column",position:"fixed",zIndex:P},children:(0,j.jsx)(g.sG.div,{...a,ref:h,style:{boxSizing:"border-box",maxHeight:"100%",...a.style}})})})}));Q.displayName="SelectItemAlignedPosition";var ee=o.forwardRef(((e,t)=>{const{__scopeSelect:n,align:o="start",collisionPadding:r=X,...l}=e,a=M(n);return(0,j.jsx)(v.UC,{...a,...l,ref:t,align:o,collisionPadding:r,style:{boxSizing:"border-box",...l.style,"--radix-select-content-transform-origin":"var(--radix-popper-transform-origin)","--radix-select-content-available-width":"var(--radix-popper-available-width)","--radix-select-content-available-height":"var(--radix-popper-available-height)","--radix-select-trigger-width":"var(--radix-popper-anchor-width)","--radix-select-trigger-height":"var(--radix-popper-anchor-height)"}})}));ee.displayName="SelectPopperPosition";var[te,ne]=E(z,{}),oe="SelectViewport",re=o.forwardRef(((e,t)=>{const{__scopeSelect:n,nonce:r,...l}=e,i=J(oe,n),c=ne(oe,n),d=(0,s.s)(t,i.onViewportChange),u=o.useRef(0);return(0,j.jsxs)(j.Fragment,{children:[(0,j.jsx)("style",{dangerouslySetInnerHTML:{__html:"[data-radix-select-viewport]{scrollbar-width:none;-ms-overflow-style:none;-webkit-overflow-scrolling:touch;}[data-radix-select-viewport]::-webkit-scrollbar{display:none}"},nonce:r}),(0,j.jsx)(N.Slot,{scope:n,children:(0,j.jsx)(g.sG.div,{"data-radix-select-viewport":"",role:"presentation",...l,ref:d,style:{position:"relative",flex:1,overflow:"hidden auto",...l.style},onScroll:(0,a.m)(l.onScroll,(e=>{const t=e.currentTarget,{contentWrapper:n,shouldExpandOnScrollRef:o}=c;if(o?.current&&n){const e=Math.abs(u.current-t.scrollTop);if(e>0){const o=window.innerHeight-2*X,r=parseFloat(n.style.minHeight),l=parseFloat(n.style.height),a=Math.max(r,l);if(a<o){const r=a+e,l=Math.min(o,r),i=r-l;n.style.height=l+"px","0px"===n.style.bottom&&(t.scrollTop=i>0?i:0,n.style.justifyContent="flex-end")}}}u.current=t.scrollTop}))})})]})}));re.displayName=oe;var le="SelectGroup",[ae,ie]=E(le),se=o.forwardRef(((e,t)=>{const{__scopeSelect:n,...o}=e,r=(0,h.B)();return(0,j.jsx)(ae,{scope:n,id:r,children:(0,j.jsx)(g.sG.div,{role:"group","aria-labelledby":r,...o,ref:t})})}));se.displayName=le;var ce="SelectLabel",de=o.forwardRef(((e,t)=>{const{__scopeSelect:n,...o}=e,r=ie(ce,n);return(0,j.jsx)(g.sG.div,{id:r.id,...o,ref:t})}));de.displayName=ce;var ue="SelectItem",[pe,fe]=E(ue),he=o.forwardRef(((e,t)=>{const{__scopeSelect:n,value:r,disabled:l=!1,textValue:i,...c}=e,d=O(ue,n),u=J(ue,n),p=d.value===r,[f,v]=o.useState(i??""),[m,w]=o.useState(!1),x=(0,s.s)(t,(e=>u.itemRefCallback?.(e,r,l))),y=(0,h.B)(),S=o.useRef("touch"),b=()=>{l||(d.onValueChange(r),d.onOpenChange(!1))};if(""===r)throw new Error("A <Select.Item /> must have a value prop that is not an empty string. This is because the Select value can be set to an empty string to clear the selection and show the placeholder.");return(0,j.jsx)(pe,{scope:n,value:r,disabled:l,textId:y,isSelected:p,onItemTextChange:o.useCallback((e=>{v((t=>t||(e?.textContent??"").trim()))}),[]),children:(0,j.jsx)(N.ItemSlot,{scope:n,value:r,disabled:l,textValue:f,children:(0,j.jsx)(g.sG.div,{role:"option","aria-labelledby":y,"data-highlighted":m?"":void 0,"aria-selected":p&&m,"data-state":p?"checked":"unchecked","aria-disabled":l||void 0,"data-disabled":l?"":void 0,tabIndex:l?void 0:-1,...c,ref:x,onFocus:(0,a.m)(c.onFocus,(()=>w(!0))),onBlur:(0,a.m)(c.onBlur,(()=>w(!1))),onClick:(0,a.m)(c.onClick,(()=>{"mouse"!==S.current&&b()})),onPointerUp:(0,a.m)(c.onPointerUp,(()=>{"mouse"===S.current&&b()})),onPointerDown:(0,a.m)(c.onPointerDown,(e=>{S.current=e.pointerType})),onPointerMove:(0,a.m)(c.onPointerMove,(e=>{S.current=e.pointerType,l?u.onItemLeave?.():"mouse"===S.current&&e.currentTarget.focus({preventScroll:!0})})),onPointerLeave:(0,a.m)(c.onPointerLeave,(e=>{e.currentTarget===document.activeElement&&u.onItemLeave?.()})),onKeyDown:(0,a.m)(c.onKeyDown,(e=>{""!==u.searchRef?.current&&" "===e.key||(T.includes(e.key)&&b()," "===e.key&&e.preventDefault())}))})})})}));he.displayName=ue;var ve="SelectItemText",me=o.forwardRef(((e,t)=>{const{__scopeSelect:n,className:l,style:a,...i}=e,c=O(ve,n),d=J(ve,n),u=fe(ve,n),p=B(ve,n),[f,h]=o.useState(null),v=(0,s.s)(t,(e=>h(e)),u.onItemTextChange,(e=>d.itemTextRefCallback?.(e,u.value,u.disabled))),m=f?.textContent,w=o.useMemo((()=>(0,j.jsx)("option",{value:u.value,disabled:u.disabled,children:m},u.value)),[u.disabled,u.value,m]),{onNativeOptionAdd:x,onNativeOptionRemove:y}=p;return(0,S.N)((()=>(x(w),()=>y(w))),[x,y,w]),(0,j.jsxs)(j.Fragment,{children:[(0,j.jsx)(g.sG.span,{id:u.textId,...i,ref:v}),u.isSelected&&c.valueNode&&!c.valueNodeHasChildren?r.createPortal(i.children,c.valueNode):null]})}));me.displayName=ve;var ge="SelectItemIndicator",we=o.forwardRef(((e,t)=>{const{__scopeSelect:n,...o}=e;return fe(ge,n).isSelected?(0,j.jsx)(g.sG.span,{"aria-hidden":!0,...o,ref:t}):null}));we.displayName=ge;var xe="SelectScrollUpButton",ye=o.forwardRef(((e,t)=>{const n=J(xe,e.__scopeSelect),r=ne(xe,e.__scopeSelect),[l,a]=o.useState(!1),i=(0,s.s)(t,r.onScrollButtonChange);return(0,S.N)((()=>{if(n.viewport&&n.isPositioned){let e=function(){const e=t.scrollTop>0;a(e)};const t=n.viewport;return e(),t.addEventListener("scroll",e),()=>t.removeEventListener("scroll",e)}}),[n.viewport,n.isPositioned]),l?(0,j.jsx)(Ce,{...e,ref:i,onAutoScroll:()=>{const{viewport:e,selectedItem:t}=n;e&&t&&(e.scrollTop=e.scrollTop-t.offsetHeight)}}):null}));ye.displayName=xe;var Se="SelectScrollDownButton",be=o.forwardRef(((e,t)=>{const n=J(Se,e.__scopeSelect),r=ne(Se,e.__scopeSelect),[l,a]=o.useState(!1),i=(0,s.s)(t,r.onScrollButtonChange);return(0,S.N)((()=>{if(n.viewport&&n.isPositioned){let e=function(){const e=t.scrollHeight-t.clientHeight,n=Math.ceil(t.scrollTop)<e;a(n)};const t=n.viewport;return e(),t.addEventListener("scroll",e),()=>t.removeEventListener("scroll",e)}}),[n.viewport,n.isPositioned]),l?(0,j.jsx)(Ce,{...e,ref:i,onAutoScroll:()=>{const{viewport:e,selectedItem:t}=n;e&&t&&(e.scrollTop=e.scrollTop+t.offsetHeight)}}):null}));be.displayName=Se;var Ce=o.forwardRef(((e,t)=>{const{__scopeSelect:n,onAutoScroll:r,...l}=e,i=J("SelectScrollButton",n),s=o.useRef(null),c=R(n),d=o.useCallback((()=>{null!==s.current&&(window.clearInterval(s.current),s.current=null)}),[]);return o.useEffect((()=>()=>d()),[d]),(0,S.N)((()=>{const e=c().find((e=>e.ref.current===document.activeElement));e?.ref.current?.scrollIntoView({block:"nearest"})}),[c]),(0,j.jsx)(g.sG.div,{"aria-hidden":!0,...l,ref:t,style:{flexShrink:0,...l.style},onPointerDown:(0,a.m)(l.onPointerDown,(()=>{null===s.current&&(s.current=window.setInterval(r,50))})),onPointerMove:(0,a.m)(l.onPointerMove,(()=>{i.onItemLeave?.(),null===s.current&&(s.current=window.setInterval(r,50))})),onPointerLeave:(0,a.m)(l.onPointerLeave,(()=>{d()}))})})),_e=o.forwardRef(((e,t)=>{const{__scopeSelect:n,...o}=e;return(0,j.jsx)(g.sG.div,{"aria-hidden":!0,...o,ref:t})}));_e.displayName="SelectSeparator";var Pe="SelectArrow";function je(e){return""===e||void 0===e}o.forwardRef(((e,t)=>{const{__scopeSelect:n,...o}=e,r=M(n),l=O(Pe,n),a=J(Pe,n);return l.open&&"popper"===a.position?(0,j.jsx)(v.i3,{...r,...o,ref:t}):null})).displayName=Pe;var ke=o.forwardRef(((e,t)=>{const{value:n,...r}=e,l=o.useRef(null),a=(0,s.s)(t,l),i=(0,b.Z)(n);return o.useEffect((()=>{const e=l.current,t=window.HTMLSelectElement.prototype,o=Object.getOwnPropertyDescriptor(t,"value").set;if(i!==n&&o){const t=new Event("change",{bubbles:!0});o.call(e,n),e.dispatchEvent(t)}}),[i,n]),(0,j.jsx)(C.s,{asChild:!0,children:(0,j.jsx)("select",{...r,ref:a,defaultValue:n})})}));function Te(e){const t=(0,x.c)(e),n=o.useRef(""),r=o.useRef(0),l=o.useCallback((e=>{const o=n.current+e;t(o),function e(t){n.current=t,window.clearTimeout(r.current),""!==t&&(r.current=window.setTimeout((()=>e("")),1e3))}(o)}),[t]),a=o.useCallback((()=>{n.current="",window.clearTimeout(r.current)}),[]);return o.useEffect((()=>()=>window.clearTimeout(r.current)),[]),[n,l,a]}function Ie(e,t,n){const o=t.length>1&&Array.from(t).every((e=>e===t[0]))?t[0]:t,r=n?e.indexOf(n):-1;let l=(a=e,i=Math.max(r,0),a.map(((e,t)=>a[(i+t)%a.length])));var a,i;1===o.length&&(l=l.filter((e=>e!==n)));const s=l.find((e=>e.textValue.toLowerCase().startsWith(o.toLowerCase())));return s!==n?s:void 0}ke.displayName="BubbleSelect";var Ne=V,Re=W,De=G,Ee=q,Le=U,Me=Z,Ae=re,Oe=se,He=de,Be=he,Ve=me,Ke=we,We=ye,Fe=be,Ge=_e}}]);