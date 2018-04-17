STUDIP.Gallery = {
    showGallery: function () {
        jQuery(".lightbox-image").first().click();
    }
};

jQuery(function () {
    if (jQuery("table.documents").length) {
        jQuery("table.documents tbody.files > tr").each(function () {
            if (jQuery(this).find(".document-icon img.icon-shape-file-pic").length > 0) {
                //jQuery(this).find(".document-icon a").attr("data-lightbox", "gallery");
                //console.log(jQuery(this));
                jQuery("<a></a>")
                    .addClass("lightbox-image")
                    .hide()
                    .attr("href", jQuery(this).find(".document-icon a").attr("href"))
                    .attr("data-lightbox", "gallery")
                    .appendTo(jQuery(this).find(".document-icon"));
            }
        });
    }
});