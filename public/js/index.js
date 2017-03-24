$(document).ready(function() {

    
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
