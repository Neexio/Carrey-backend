document.addEventListener('DOMContentLoaded', () => {
  const runBtn = document.querySelector('#run-analysis');
  const urlInput = document.querySelector('#seo-url');
  const resultBox = document.querySelector('#seo-results');

  if (!runBtn || !urlInput || !resultBox) return;

  runBtn.addEventListener('click', () => {
    const url = urlInput.value.trim();
    if (!url) {
      resultBox.innerHTML = `<div style="color:red;">❌ Please enter a URL</div>`;
      return;
    }

    resultBox.innerHTML = '<div>⏳ Analyzing...</div>';

    fetch('/wp-json/carrey/v1/analyze', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ url })
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        const html = `
          <div class="seo-score">🎯 SEO Score: <strong>${data.score}/100</strong></div>
          <div style="margin-top:10px;"><em>${data.summary}</em></div>
          <ul style="margin-top:15px;">${data.tips.map(t => `<li>✅ ${t}</li>`).join('')}</ul>
        `;
        resultBox.innerHTML = html;
      } else {
        resultBox.innerHTML = `<div style="color:red;">❌ Analysis failed</div>`;
      }
    })
    .catch(err => {
      resultBox.innerHTML = `<div style="color:red;">❌ Error: ${err.message}</div>`;
    });
  });
});
