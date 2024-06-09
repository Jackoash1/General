let postCode_input = document.getElementById("searchBar");
let darkener_panel = document.getElementById("darkener");
function searchbarDisplay() {
    if (postCode_input.value.length < 3) {
        // console.log("called")
        postCode_input.style.top = (window.innerHeight - postCode_input.offsetHeight) / 2 + "px";
        postCode_input.style.left = '30%';
        postCode_input.style.width = '40%';
        postCode_input.style.fontSize = '2rem';
        darkener_panel.style.opacity = '0.75';
        darkener_panel.style.visibility = "visible";
    } else {
        postCode_input.style.top = '2rem';
        postCode_input.style.left = '40%';
        postCode_input.style.width = '20%';
        postCode_input.style.fontSize = '1rem';
        darkener_panel.style.opacity = '0';
        darkener_panel.style.visibility = "hidden";
    }
}
//api postcode calls
function mapAPICall(postcode) {
    //make a new api call to display the address
    let map = document.getElementById("google-map-embed");
    map.src = `https://www.google.com/maps/embed/v1/place?key=AIzaSyA-GdBSgDjdpLSvczF4JcBrdD967SA-wEQ&q=${postcode}`;
}

postCode_input.addEventListener("input", () => {
    postCode_input.value = postCode_input.value.toUpperCase();
    searchbarDisplay();
})

postCode_input.addEventListener("keypress", (e) => {
    //check if the postcode is properly written. At least 3 chars
    if(e.key == "Enter" && postCode_input.value.length >= 3) {
        document.getElementById("searchPCForm").submit();
    }
})

window.addEventListener("DOMContentLoaded", () => {
    if(data != null) postCode_input.value = data[0].postcode;
    else if(data == null) postCode_input.placeholder = "We didn't find any data for provided postcode";
    else postCode_input.placeholder = "Enter the postcode";
    searchbarDisplay();
    if(data != null) mapAPICall(data[0].postcode);
})