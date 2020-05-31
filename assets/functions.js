function on() {
    document.getElementById("overlay").style.height = "100%";
}

function off() {
    document.getElementById("overlay").style.height = "0";
}

function XCloseEscapeKey() {
    document.addEventListener("keyup", function(event) {
        if (event.keyCode == 27) {
            setTimeout(function () {
                off()
            }, 0);
        }
    });
}