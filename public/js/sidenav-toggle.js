document.addEventListener("DOMContentLoaded", function() {
    var toggleButton = document.getElementById("iconNavbarSidenav");
    var closeButton = document.getElementById("iconSidenav");
    var body = document.getElementsByTagName("body")[0];
    var sidenav = document.getElementById("sidenav-main");
    var className = "g-sidenav-pinned";

    if (toggleButton) {
        toggleButton.addEventListener("click", function(e) {
            e.preventDefault();
            if (body.classList.contains(className)) {
                body.classList.remove(className);
            } else {
                body.classList.add(className);
            }
        });
    }

    if (closeButton) {
        closeButton.addEventListener("click", function(e) {
            e.preventDefault();
            body.classList.remove(className);
        });
    }
});
