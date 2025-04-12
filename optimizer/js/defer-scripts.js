document.addEventListener("DOMContentLoaded", () => {
  const scripts = document.querySelectorAll("script[src]");
  scripts.forEach(script => {
    const src = script.getAttribute("src");
    if (!src.includes("jquery") && !src.includes("elementor") && !script.defer && !script.async) {
      script.setAttribute("defer", true);
    }
  });
});