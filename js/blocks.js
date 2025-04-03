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