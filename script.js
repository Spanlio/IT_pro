let modalBtns = document.querySelectorAll("[data-target]")
let closeModal = document.querySelectorAll(".close-modal")

modalBtns.forEach(function(btn){
    btn.addEventListener('click', function(){
        document.querySelector(btn.dataset.target).classList.add('modal-active')
    })
})

closeModal.forEach(function(btn){
    btn.addEventListener('click', function(){
        document.querySelector(btn.dataset.target).classList.remove('modal-active')
    })
})




async function setLanguage(lang) {
    // Nomainīts uz 'language'
    localStorage.setItem('language', lang); 
    
    try {
        const response = await fetch(`lang/${lang}.json`);
        if (!response.ok) throw new Error('Language file not found');
        
        const translations = await response.json();

        document.querySelectorAll('[data-lang-key]').forEach(el => {
            const key = el.getAttribute('data-lang-key');
            if (translations[key]) {
                el.innerHTML = translations[key];
            }
        });

        document.querySelectorAll('[data-lang-placeholder]').forEach(el => {
            const key = el.getAttribute('data-lang-placeholder');
            if (translations[key]) {
                el.placeholder = translations[key];
            }
        });

        document.documentElement.lang = lang;

    } catch (error) {
        console.error('Translation Error:', error);
    }
}

document.addEventListener('DOMContentLoaded', () => {
    // Šeit arī nomainīts uz 'language'
    const savedLang = localStorage.getItem('language') || 'lv';
    setLanguage(savedLang);
});

