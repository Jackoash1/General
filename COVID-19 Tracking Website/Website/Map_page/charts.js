let showChartBtn = document.getElementsByClassName("navbar_btn")[2];
let state = false;
if(data != null) {
    showChartBtn.addEventListener("click", showCharts);
} else {
    showChartBtn.style.display = "none";
}

function showCharts() {
    let map = document.getElementById("google-map-embed");
    let chartsMenu = document.getElementById("graphsMenu");
    let btn = document.getElementsByClassName("navbar_btn")[2];
    if(!state) {
        map.style.height = "0vh";
        btn.style.backgroundColor = "#fa6e85";
        document.getElementById("searchModal").style.display = "none";
        state = !state;
    } else {
        map.style.height = "100vh";
        btn.removeAttribute("style");
        document.getElementById("searchModal").removeAttribute("style");
        state = !state;
    }
}


//charts
if(data != null) {
    let ctx = document.getElementsByClassName("graphCanvas")[0];
    _postcodes = [];
    _data = [[],[]];
    for(let i = 1; i < data.length; i++) {
        _postcodes.push(data[i].PostCode);
        _data[0].push(Number(data[i].PostCodePopulation));
        _data[1].push((Number(data[i].PostCodeTotalPositive)));
    }
    
    //PostCodePopulation, PostCodeTotalPositive, PostCodeTotalRecovered
    
    const chartData = {
        labels: _postcodes,
        datasets: [
            {
                label: 'Population',
                data: _data[1],
                backgroundColor: randomHexColor(),
            },
            {
                label: 'Cases positive',
                data: _data[0],
                backgroundColor: randomHexColor(),
            }
        ]
    };
    
    const config = {
        type: 'bar',
        data: chartData,
        options: {
            plugins: {
                legend: {
                    display: false,
                },
                zoom: {
                    zoom: {
                        wheel: {
                            enabled: true,
                        },
                        pinch: {
                          enabled: true
                        },
                        mode: 'x',
                    }
                }
            }
        }
    };
    
    const myChart = new Chart(ctx, config)
}