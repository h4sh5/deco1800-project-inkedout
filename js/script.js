function goHome (ev) {

    ev.preventDefault()

    if (confirm("This will reset your progress. Are you sure?")) {
        window.location = "/";
    } else {
        console.log("they changed their mind");
    }

}

if (document.getElementById("home-button")) {
    document.getElementById("home-button").addEventListener("click", goHome, false);
}


//get all p elements and add an id to it for drag and drop
var elements = document.getElementsByClassName('dragdrop');
console.log(elements);
for (i = 0; i < elements.length; i++) {
    element = elements[i];
    element.id = "dragdrop" + i;
    console.log(element.id)
}

//drag and srop
function allowDrop(ev) {
    ev.preventDefault();
}

function drag(ev) {
    ev.dataTransfer.setData("item", ev.target.id);
}

function drop(ev) {
    ev.preventDefault();

    var elementId = ev.dataTransfer.getData("item");

    while (ev.target.hasChildNodes()) {
        ev.target.removeChild(ev.target.lastChild);
    }

    //TODO: transfer entire div / toggle background
    ev.target.appendChild(document.getElementById(elementId));    
    // ev.target.innerHTML = document.getElementById(data).innerHTML;
}

