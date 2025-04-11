document.addEventListener('DOMContentLoaded', function() {
    const faqItems = document.querySelectorAll('.faq-item');
    
    faqItems.forEach(item => {
        const question = item.querySelector('.faq-question');
        const answer = item.querySelector('.faq-answer');
        
        // Skjul alle svar som standard
        answer.style.display = 'none';
        
        question.addEventListener('click', () => {
            // Toggle svar synlighet
            const isOpen = answer.style.display === 'block';
            
            // Lukk alle andre svar
            document.querySelectorAll('.faq-answer').forEach(a => {
                a.style.display = 'none';
            });
            
            // Toggle gjeldende svar
            answer.style.display = isOpen ? 'none' : 'block';
            
            // Oppdater spørsmålsstil
            question.classList.toggle('active', !isOpen);
        });
    });
}); 