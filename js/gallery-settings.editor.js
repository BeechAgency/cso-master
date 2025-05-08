jQuery(function ($) {
  const GallerySettings = wp.media.view.Settings.Gallery;

  wp.media.view.Settings.Gallery = GallerySettings.extend({
    render: function () {
      GallerySettings.prototype.render.apply(this, arguments);

      // Make sure the gallery props object exists
      let props = this.model.get("gallery");
      if (!props) {
        props = {};
      }

      // Add Gallery Style dropdown
      this.$el.append(
        '<label class="setting">' +
          "<span>Gallery Style</span>" +
          '<select class="gallery-style">' +
          '<option value="inline">Inline</option>' +
          '<option value="expanded">Expanded</option>' +
          "</select>" +
          "</label>"
      );

      // Add Lightbox dropdown
      this.$el.append(
        '<label class="setting">' +
          "<span>Lightbox</span>" +
          '<select class="lightbox-toggle">' +
          '<option value="on">On</option>' +
          '<option value="off">Off</option>' +
          "</select>" +
          "</label>"
      );

      // Set dropdown values from props// Load values from model attributes
      const galleryStyle = this.model.get(
        "galleryStyle",
        this.$el.find(".gallery-style")
      );
      const lightbox = this.model.get(
        "lightbox",
        this.$el.find(".lightbox-toggle")
      );

      this.$el.find('.gallery-style').val(this.model.get('gallerystyle') || 'inline');
      this.$el.find('.lightbox-toggle').val(this.model.get('lightbox') || 'off');

      console.log("Galleria!", props, galleryStyle, lightbox);

      // Update model when changed
      // Save to model
      this.$el.find('.gallery-style').on('change', (e) => {
        this.model.set("gallerystyle", $(e.target).val());

        console.log("Galleria! Change", this.model, props);
      });

      this.$el.find('.lightbox-toggle').on('change', (e) => {
        this.model.set('lightbox', $(e.target).val());

        console.log("Galleria! Change", this.model, props);
      });

       console.log("Galleria!", this.model, props);

      return this;
    },
  });

  // Inject the custom props into the [gallery] shortcode
  const originalGalleryShortcode = wp.media.string.gallery;

  wp.media.string.gallery = function (attachments, props) {
    let shortcode = originalGalleryShortcode(attachments, props);

    console.log("Gallery Shortcode", shortcode, props);

    let extra = "";
    if (props.gallerystyle) {
      extra += ' gallerystyle="' + props.gallerystyle + '"';
    }
    if (props.lightbox) {
      extra += ' lightbox="' + props.lightbox + '"';
    }

    return shortcode.replace(/\]$/, extra + "]");
  };
});
