document.addEventListener(
    "error",
    function (e) {
        if (e.target.tagName.toLowerCase() === "img") {
            const defaultImage = "/assets/img/no-image.png";

            if (e.target.src !== window.location.origin + defaultImage) {
                e.target.src = defaultImage;
                e.target.classList.add("image-fallback");
            }
        }
    },
    true,
);