document.addEventListener('DOMContentLoaded', function () {
    // Ativar tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Função de busca no FAQ
    const faqSearch = document.getElementById('faq-search');
    const searchButton = document.getElementById('search-button');
    const noResults = document.getElementById('no-results');
    const faqItems = document.querySelectorAll('.accordion-item');
    const faqCategories = document.querySelectorAll('.faq-category');

    function performSearch() {
        const searchTerm = faqSearch.value.toLowerCase().trim();
        let hasResults = false;

        if (searchTerm === '') {
            // Resetar busca
            faqItems.forEach(item => {
                item.style.display = '';
                const accordionBody = item.querySelector('.accordion-body');
                if (accordionBody) {
                    accordionBody.innerHTML = accordionBody.innerHTML.replace(/<span class="search-highlight">|<\/span>/g, '');
                }
            });

            faqCategories.forEach(category => {
                category.style.display = '';
            });

            noResults.style.display = 'none';
            return;
        }

        faqCategories.forEach(category => {
            let categoryHasResults = false;
            const items = category.querySelectorAll('.accordion-item');

            items.forEach(item => {
                const searchData = item.getAttribute('data-search').toLowerCase();
                const question = item.querySelector('.accordion-button').textContent.toLowerCase();
                const answer = item.querySelector('.accordion-body') ? item.querySelector('.accordion-body').textContent.toLowerCase() : '';

                if (searchData.includes(searchTerm) || question.includes(searchTerm) || answer.includes(searchTerm)) {
                    item.style.display = '';
                    hasResults = true;
                    categoryHasResults = true;

                    // Destacar texto encontrado
                    if (item.querySelector('.accordion-body')) {
                        const answerElement = item.querySelector('.accordion-body');
                        const answerText = answerElement.textContent;
                        const highlightedText = answerText.replace(new RegExp(searchTerm, 'gi'),
                            match => `<span class="search-highlight">${match}</span>`);
                        answerElement.innerHTML = highlightedText;

                        // Abrir o accordion se estiver fechado
                        const collapse = new bootstrap.Collapse(item.querySelector('.accordion-collapse'), {
                            toggle: false
                        });
                        collapse.show();
                    }
                } else {
                    item.style.display = 'none';
                }
            });

            // Mostrar/ocultar categoria com base nos resultados
            category.style.display = categoryHasResults ? '' : 'none';
        });

        // Mostrar mensagem se não houver resultados
        noResults.style.display = hasResults ? 'none' : 'block';
    }

    // Event listeners para busca
    faqSearch.addEventListener('input', performSearch);
    searchButton.addEventListener('click', performSearch);
    faqSearch.addEventListener('keypress', function (e) {
        if (e.key === 'Enter') {
            performSearch();
        }
    });
});