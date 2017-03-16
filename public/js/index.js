$(document).ready(function() {

    var links = document.getElementsByClassName('link');
    for (link of links) {
        link.onclick = switchActive
    }


    function switchActive() {
        for (link of links) {
            if (link == this) {
                link.classList.add("active")
            } else {
                link.classList.remove("active")
            }
        }
    }

})
