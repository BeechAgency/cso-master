/**
 * File blocks.js.
 *
 * Handles any JS required for the blocky boiz
 */

if(typeof AnalyticsHandler !== 'function') {
	function AnalyticsHandler( event_name = 'ga_event', event_params = {}, user_props = {} ) {
		if(typeof window.dataLayer !== 'object' ) return false;

		let event = { 'event' : 'gtm.ga4.event', 'event_name' : event_name, event_params, user_props }

		dataLayer.push(event);

		return true;
	}
}

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

            const toggleVal = isOpen ? 'close_faq' : 'open_faq';

			AnalyticsHandler('content_interaction', { 'faq_toggle' : toggleVal });

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

            const tabContentGroupId = tabId.split('__')[0];

            //console.log(tabContentGroupId);

            const parentTabGroup = document.querySelector(`.tab-wrapper[data-tab-group="${tabContentGroupId}"]`)
            const siblingItems = parentTabGroup.querySelectorAll(`.tab-header__item`);
            const siblingContents = parentTabGroup.querySelectorAll(`.tab-content`);

            siblingItems.forEach((tabItem) => {
              tabItem.classList.remove("active");
            });

            tabItem.classList.add('active');

            siblingContents.forEach((tabContent) => {
              tabContent.classList.remove("active");
            });

            document.querySelector('#' + tabId).classList.add('active');

            AnalyticsHandler('content_interaction', { 'tab_toggle' : 'active '+ tabId });
        }); 
    });

    /* ==== END: TABBED CONTENT ==== */

    /*==== START: FLICKITY SLIDERS ==== */
    const flickities = document.querySelectorAll('[data-flickity-options]');

    flickities.forEach( (flick) => {
        if(typeof Flickity !== 'function') return false;

        const type = flick.classList[0];
        let block_id = '';

        if(type === 'header-slider-wrapper') {
            block_id = 'header';
        } else {
            block_id = flick.parentElement.parentElement.parentElement.id;
        }

        const options = flick.dataset.flickityOptions;
        const optionsJSON = options ? JSON.parse(options) : {};

        optionsJSON.on = {
            dragEnd : function ( event, pointer ) {
                //console.log('Drag End JSON', this.selectedIndex)
                AnalyticsHandler('slide_interaction', {'slide_content_type' : type, 'slide_interaction_type': 'drag', 'slide_to' : flickObj.selectedIndex, 'slide_location' : block_id});
            },
            ready : function () {
                const that = this;

                const flickEl = this.element;

                const btnPrev = flickEl.querySelector('.flickity-button.previous');
                const btnNext = flickEl.querySelector('.flickity-button.next');
                const dots = flickEl.querySelectorAll('.dot');

                if(btnPrev) {
                    btnPrev.addEventListener('click', e => {
                        AnalyticsHandler('slide_interaction', {'slide_content_type' : type, 'slide_interaction_type': 'button_prev', 'slide_to' : this.selectedIndex, 'slide_location' : block_id});
                    })
                }

                if(btnNext) {
                    btnNext.addEventListener('click', e => {
                        AnalyticsHandler('slide_interaction', {'slide_content_type' : type, 'slide_interaction_type': 'button_next', 'slide_to' : this.selectedIndex, 'slide_location' : block_id});
                    })
                }

                if(dots) {
                    let thisSelectedIndex =  this.selectedIndex;

                    dots.forEach( dot => {
                        dot.addEventListener('click', e => {

                            function listener(slide) {
                                if(thisSelectedIndex === slide) return;

                                AnalyticsHandler('slide_interaction', {'slide_content_type' : type, 'slide_interaction_type': 'dot_nav', 'slide_to' : slide, 'slide_location' : block_id});
                            }

                            that.on('change', listener )
                            
                            setTimeout(() => {
                                that.off('change', listener);
                            }, 50)
                        })
                    })
                }
            }
        }

        const flickObj = new Flickity(flick, optionsJSON);
    })

});

    // Lazy load the lozads
    if(typeof window.lozad === 'function') {
        const observer = lozad(); // lazy loads elements with default selector as '.lozad'
        observer.observe();
    }
}() );

//console.log('YAY');

document.addEventListener('DOMContentLoaded', function () {
    console.log('Hover on the events');

    const eventLists = document.querySelectorAll('.text-block__upcoming-events');

    eventLists.forEach((eventList) => {
      const images = eventList.querySelectorAll("img");
      const items = eventList.querySelectorAll(".event-item");

      items.forEach((item) => {
        item.addEventListener("mouseover", (event) => {
          const index = item.dataset.eventIndex;
          const image = eventList.querySelector(
            'img.event-image-with-index[data-event-index="' + index + '"]'
          );

          items.forEach((item) => {
            item.classList.remove("active");
          });

          item.classList.add("active");

          images.forEach((image) => {
            image.classList.remove("active");
          });

          if(image) {
            image.classList.add("active");
          }
        });
      });
    });
});


document.addEventListener("DOMContentLoaded", function () {
  //* GALLERY LIGHTBOX! *//
  // Check if there is a gallery with the 'gallery-lightbox-on' class
  const allLightboxGallery = document.querySelectorAll(
    "div.gallery.gallery-lightbox-on"
  );

  // Store all images in the gallery and create a reference to the current image
  const images = Array.from(
    document.querySelectorAll("div.gallery.gallery-lightbox-on figure img")
  );

  if (allLightboxGallery.length > 0) {
    // Create the lightbox container if it's not already in the document
    if (!document.getElementById("lightbox")) {
      const lightbox = document.createElement("div");
      lightbox.id = "lightbox";
      lightbox.classList.add("lightbox");

      // Add the close button to the lightbox
      const closeButton = document.createElement("span");
      closeButton.classList.add("close-btn");
      closeButton.innerHTML = "&times;";
      lightbox.appendChild(closeButton);

      // Add the lightbox image container
      const lightboxContent = document.createElement("img");
      lightboxContent.classList.add("lightbox-content");
      lightbox.appendChild(lightboxContent);

      const lightboxNav = document.createElement("div");
      lightboxNav.classList.add("lightbox-nav");

      const lightboxNavButtons = document.createElement("div");
      lightboxNavButtons.classList.add("lightbox-buttons");

      console.log(lightboxNav);
      // Add previous and next buttons
      const prevButton = document.createElement("span");
      prevButton.classList.add("lightbox-prev");
      prevButton.innerHTML = "&#10094;";

      const nextButton = document.createElement("span");
      nextButton.classList.add("lightbox-next");
      nextButton.innerHTML = "&#10095;";

      lightbox.appendChild(lightboxNav);

      const dotsContainer = document.createElement("ul");
      dotsContainer.classList.add("lightbox-dots");

      lightboxNav.appendChild(dotsContainer);

      lightboxNavButtons.appendChild(prevButton);
      lightboxNavButtons.appendChild(nextButton);

      lightboxNav.appendChild(lightboxNavButtons);

      images.forEach((image, index) => {
        const dot = document.createElement("li");
        dot.classList.add("dot");
        dot.dataset.index = index;
        dotsContainer.appendChild(dot);
      });

      // Append the lightbox to the body
      document.body.appendChild(lightbox);

      // Close the lightbox when the close button is clicked
      closeButton.addEventListener("click", () => {
        lightbox.style.display = "none";
      });

      // Close the lightbox if the user clicks outside the image
      lightbox.addEventListener("click", (event) => {
        if (event.target === event.currentTarget) {
          lightbox.style.display = "none";
        }
      });
    }

    let currentImageIndex = 0; // Track the current image index

    // Function to open the lightbox with the selected image
    function openLightbox(image) {
      currentImageIndex = images.indexOf(image); // Set the current image index
      const largeImageSrc = image.getAttribute("src");
      const lightbox = document.getElementById("lightbox");
      const lightboxImage = lightbox.querySelector(".lightbox-content");
      lightboxImage.src = largeImageSrc;
      lightbox.style.display = "flex";
    }

    // Add event listeners to gallery images within the lightbox-enabled gallery
    images.forEach((image) => {
      image.addEventListener("click", (event) => {
        // Prevent default action
        event.preventDefault();
        openLightbox(event.target);
      });
    });

    function updateDots() {
      const dots = document.querySelectorAll(".dot");
      dots.forEach((dot, index) => {
        if (index === currentImageIndex) {
          dot.classList.add("active");
        } else {
          dot.classList.remove("active");
        }
      });
    }

    function nextImage() {
      // Loop back to the last image if the current image is the first one
      if (currentImageIndex === 0) {
        currentImageIndex = images.length - 1; // Go to the last image
      } else {
        currentImageIndex--; // Go to the previous image
      }

      openLightbox(images[currentImageIndex]);
      updateDots(); // If you have any dots or other indicators to update
    }

    function prevImage() {
      // Loop back to the first image if the current image is the last one
      if (currentImageIndex === images.length - 1) {
        currentImageIndex = 0; // Go to the first image
      } else {
        currentImageIndex++; // Go to the next image
      }

      openLightbox(images[currentImageIndex]);
      updateDots(); // If you have any dots or other indicators to update
    }

    // Add navigation functionality for next and previous buttons
    document
      .querySelector(".lightbox-prev")
      .addEventListener("click", nextImage);
    document
      .querySelector(".lightbox-next")
      .addEventListener("click", prevImage);

    document.querySelectorAll(".dot").forEach((dot) => {
      dot.addEventListener("click", (event) => {
        const index = parseInt(event.target.dataset.index);
        currentImageIndex = index;
        openLightbox(images[currentImageIndex]);
        updateDots(); // If you have any dots or other indicators to update
      });
    });

    // Event listener for keyboard navigation
    document.addEventListener("keydown", (event) => {
      // Check if the lightbox is visible before handling keyboard navigation
      const lightbox = document.getElementById("lightbox");
      if (lightbox.style.display === "flex") {
        if (event.key === "ArrowLeft") { nextImage(); }
        if (event.key === "ArrowRight") { prevImage(); }
      }
    });
  }
});