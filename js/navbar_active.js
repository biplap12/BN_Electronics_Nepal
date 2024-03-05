
var currentUrl = window.location.href;

var links = document.querySelectorAll('.mt-2 ul li a');

for (var i = 0; i < links.length; i++) {
    if (links[i].href === currentUrl) {
        links[i].parentElement.classList.add('active_nav');
        break;
    }
}
