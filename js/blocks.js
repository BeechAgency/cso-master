/**
 * File blocks.js.
 *
 * Handles any JS required for the blocky boiz
 */
 ( function() {
    document.addEventListener('DOMContentLoaded', function () {
        /* ==== FAQs ==== */
        const faqBtns = document.querySelectorAll('.faq-grid .faq-item .faq-question, .faq-list .faq-item .faq-question');

        faqBtns.forEach( (btn) => {
            const answer = btn.nextElementSibling;
            answer.style = '--scroll-height: ' + answer.scrollHeight + 'px';

            //console.log(btn.nextElementSibling.scrollHeight);

            btn.addEventListener('click', (event) => {
                const btn = event.currentTarget;

                const faqItem = btn.parentElement;
                const isOpen = faqItem.classList.contains('open');

                //btn.classList.toggle('open');
                faqItem.classList.toggle('open');

                return;
            });
        });
        /* ===== END: FAQs ===== */

        /* ==== TABBED CONTENT ==== */

        const tabItems = document.querySelectorAll('.tab-header__item');
        const tabContents = document.querySelectorAll('.tab-content');

        tabItems.forEach( (tabItem) => { 
            tabItem.addEventListener('click', (event) => {
                event.preventDefault();

                const tabItem = event.currentTarget;
                const tabId = tabItem.dataset.tabId;

                tabItems.forEach( (tabItem) => {
                    tabItem.classList.remove('active');
                });

                tabItem.classList.add('active');

                tabContents.forEach( (tabContent) => {
                    tabContent.classList.remove('active');
                });

                document.querySelector('#' + tabId).classList.add('active');
            }); 
        });

        /* ==== END: TABBED CONTENT ==== */
    });
}() );

//console.log('YAY');