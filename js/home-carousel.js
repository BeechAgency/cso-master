( function() { 
    const carousels = document.querySelectorAll('.main-carousel'), 
    args = {
        accessibility: true,
        resize: true,
        wrapAround: true,
        prevNextButtons: false,
        pageDots: false,
        percentPosition: true,
        setGallerySize: true,
    };

    carousels.forEach((carousel) => {
    let requestId;

    if (carousel.childNodes.length > 3) {
        const mainTicker = new Flickity(carousel, args);

        // Set initial position to be 0
        mainTicker.x = 0;

        // Start the marquee animation
        play();

        // Main function that 'plays' the marquee.
        function play() {
            mainTicker.x = mainTicker.x - 0.75;
            mainTicker.settle(mainTicker.x);
            requestId = window.requestAnimationFrame(play);
        }
    }
    });
    //console.log('0.75');

    
}() );
