//loop to check if the data was retrieved already.
let looper = setInterval(() => {
    if(dataPulled == true) {
        clearInterval(looper);
        window.location.href = "../index.php";
    }
}, 100);