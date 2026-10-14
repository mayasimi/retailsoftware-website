(() => {
    "use strict";

    const header = document.querySelector(".site-header");
    if (!header) {
        return;
    }

    const updateStickyHeader = () => {
        header.classList.toggle("is-sticky", window.scrollY > 45);
    };

    window.addEventListener("scroll", updateStickyHeader, { passive: true });
    updateStickyHeader();
})();
