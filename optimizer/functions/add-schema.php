<?php
function add_schema_to_head() {
?>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Organization",
  "name": "Carrey AI",
  "url": "https://carrey.ai",
  "sameAs": [
    "https://www.linkedin.com/company/carreyai",
    "https://www.instagram.com/carreyai"
  ]
}
</script>
<?php } 
add_action('wp_head', 'add_schema_to_head');