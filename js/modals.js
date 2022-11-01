/**
 * File navigation.js.
 *
 * Handles the big slidy boy of the nav. Use inconjunction with some tasty CSS.
 */
 ( function() {
    document.addEventListener('DOMContentLoaded', function () {
        // Modals
        const modals = document.querySelectorAll('.modal-trigger');

        modals.forEach((modalOpen) => {
            console.log(modalOpen);

            modalOpen.addEventListener('click', (e) => {
                e.preventDefault();
                const el = e.currentTarget; 
                const modal = el.nextElementSibling;
                modal.classList.add('open');

                console.log(modal);
            });
        });

        const modalButtons = document.querySelectorAll('.modal-outer button');

        modalButtons.forEach( btn => {
            btn.addEventListener('click', e => {
                e.preventDefault();
                
                const btn = e.currentTarget;

                const outer = btn.parentElement.parentElement;
                
                outer.classList.remove('open');

            })
        })

    });
}() );