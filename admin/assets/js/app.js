(function () {
    const activeItem = document.querySelector(".dropdown-item.active");
    if (activeItem) {
        activeItem.closest(".nav-item.dropdown")?.classList.add("active");
    }
})();
