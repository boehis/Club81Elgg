"use strict";

$(document).ready(function () {

    var navigation = document.getElementById("navigation");
    var header = document.getElementById("header");
    var fixed = false;

    document.onscroll = function () {
    if (!fixed && navigation.getBoundingClientRect().top <= 0) {
        document.body.classList.add("fixed");
        fixed = true
    } else if (fixed && header.getBoundingClientRect().bottom > 53) {
        document.body.classList.remove("fixed");
        fixed = false
    }
};
});
