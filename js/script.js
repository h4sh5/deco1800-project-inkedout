function goHome (ev) {

    ev.preventDefault()

    if (confirm("This will reset your progress. Are you sure?")) {
        window.location = "/";
    } else {
        console.log("they changed their mind");
    }

}

document.getElementById("home-button").addEventListener("click", goHome, false);