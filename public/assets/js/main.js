(function() {
    const defaultImage = "/assets/images/no-image.png";

    function fixImage(img) {
        if (img.dataset.fixed) return;
        
        img.src = defaultImage;
        img.srcset = "";
        img.style.setProperty('width', '100%', 'important');
        img.style.setProperty('height', '100%', 'important');
        img.style.setProperty('object-fit', 'cover', 'important');
        img.classList.add("image-fallback");
        img.dataset.fixed = "true";
    }

    document.addEventListener("error", function (e) {
        if (e.target.tagName === "IMG") {
            fixImage(e.target);
        }
    }, true);

    window.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('img').forEach(img => {
            if (img.complete && img.naturalWidth === 0) {
                fixImage(img);
            }
        });
    });
})();