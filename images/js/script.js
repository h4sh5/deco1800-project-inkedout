function goHome (ev) {

    ev.preventDefault();

    if (confirm("This will reset your progress. Are you sure?")) {
        window.location = "/";
    } else {
        console.log("they changed their mind");
    }

}

function loadListener() {
    console.log(this.responseText);
}

function piecesSubmit(ev) {
    ev.preventDefault();
    // console.log("pieces submit clicked!");
    var elements = document.getElementsByClassName('target');
    var result = "";
    for (i = 0; i < elements.length; i++) {
        element = elements[i];
        if (element.lastChild != null) {
            if (i == element.length - 1) {
                result += element.lastChild.id + "&";
            } else {
                result += element.lastChild.id + "+";
            }
        }
    }
    // console.log("result: ")
    // console.log(result);

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "pieces.php");
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onload = function () {
    // do something to response
        console.log(this.responseText);
        if (this.responseText == "right") {
            alert("Congratulations, you did it!");
            window.location = "/final.php";
        } else {
            alert("Nope. Keep trying!");
        }
    };

    xhr.send("result=" + result);

}

if (document.getElementById("home-button")) {
    document.getElementById("home-button").addEventListener("click", goHome, false);
}

if (document.getElementById("pieces-submit")) {
    document.getElementById("pieces-submit").addEventListener("click", piecesSubmit, false);
}

//get all p elements and add an id to it for drag and drop
// var elements = document.getElementsByClassName('dragdrop');
// console.log(elements);
// for (i = 0; i < elements.length; i++) {
//     element = elements[i];
//     element.id = "dragdrop" + i;
//     console.log(element.id)
// }

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
    var element = document.getElementById(elementId);

    // console.log("source:");
    // console.log(ev.srcElement.parentNode.parentNode.id);
    // console.log("element:");
    // console.log(element.parentNode.parentNode.id);

    

    if (ev.srcElement.parentNode.parentNode.id == "articles-box" && 
        element.parentNode.parentNode.id == "articles-box")  { //avoid self destruction
        return;
    }

    // console.log(ev.srcElement.parentNode);

    if (ev.srcElement.hasChildNodes()) { //avoid overwrite
        return;
    }
    

    while (ev.target.hasChildNodes()) { 
        //swap
        ev.srcElement.appendChild(ev.target.lastChild);
        ev.target.removeChild(ev.target.lastChild);
    }

    //TODO: transfer entire div / toggle background
    ev.target.appendChild(element);    
    // ev.target.innerHTML = document.getElementById(data).innerHTML;
}

