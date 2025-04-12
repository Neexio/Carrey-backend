document.querySelectorAll('a.wcf-nav-item:not([href])').forEach(el => {
  el.setAttribute('href', '#');
  el.textContent = el.textContent || "Navigation Link";
});