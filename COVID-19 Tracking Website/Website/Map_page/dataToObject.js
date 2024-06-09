let data = [];
let dataPulled = false;
window.addEventListener("DOMContentLoaded", () => {
    let inf = document.getElementById("information");
    let info = document.getElementsByClassName("info");
    if(document.getElementById("rej")) {
        data = null;
        localStorage.setItem("data", JSON.stringify(data));
        window.location.href = "../index.php";
    } else {
        data.push({"postcode":info[info.length-2].textContent, "casesTotal":info[info.length-1].textContent});
        for(let i = 2; i < info.length; i++) {
            let obj = {};
            for(let y = 0; y < info[i].children.length; y++) {
                let key = info[i].children[y].className;
                obj[key] = info[i].children[y].textContent;
            }
            data.push(obj);
        }
        // save to localStorage when data is retrived.
        localStorage.setItem("data", JSON.stringify(data));
        dataPulled = true;
    }
})

//loop to check if the data was retrieved already.
let looper = setInterval(() => {
    if(dataPulled == true) {
        clearInterval(looper);
        window.location.href = "../index.php";
    }
}, 100);