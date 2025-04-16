document.addEventListener('DOMContentLoaded', function() {
    // Fjern loading-klasser
    document.documentElement.classList.remove('loading');
    document.body.classList.remove('loading');
    
    // Fjern loading-skjermen
    const loadingScreen = document.querySelector('.loading-screen');
    if (loadingScreen) {
        loadingScreen.remove();
    }
    
    // Vis innholdet umiddelbart
    document.body.style.opacity = '1';
    document.body.style.visibility = 'visible';
    
    // Mobil-spesifikke fikser
    if (window.innerWidth <= 768) {
        // Fiks eventuelle mobil-layout-problemer
        document.body.style.overflowX = 'hidden';
        document.body.style.width = '100%';
        
        // Fiks bilder
        const images = document.querySelectorAll('img');
        images.forEach(img => {
            img.style.maxWidth = '100%';
            img.style.height = 'auto';
        });
    }
}); 