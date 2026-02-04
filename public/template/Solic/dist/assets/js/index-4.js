
  /* totalrevenue */
  var options = {
    chart: {
        height: 140,
        width: 100,
        type: "radialBar",
    },
  
    series: [75],
    colors: ["#554dbf"],
    plotOptions: {
        radialBar: {
            hollow: {
                margin: 0,
                size: "35%",
                background: "#fff"
            },
            dataLabels: {
                name: {
                    offsetY: -10,
                    color: "#4b9bfa",
                    fontSize: "10px",
                    show: false
                },
                value: {
                    offsetY: 5,
                    color: "#4b9bfa",
                    fontSize: "12px",
                    show: true,
                    fontWeight: 800
                }
            }
        }
    },
    stroke: {
        lineCap: "round"
    },
    labels: ["Followers"]
  };
  document.querySelector("#totalrevenue").innerHTML = ""
  var chart = new ApexCharts(document.querySelector("#totalrevenue"), options);
  chart.render();
  function totalrevenue() {
  chart.updateOptions({
      colors: ["rgb(" + myVarVal + ")"],
  })
}
  /* totalrevenue */
  
  /* totalcost */
  var options = {
    chart: {
        height: 140,
        width: 100,
        type: "radialBar",
    },
  
    series: [25],
    colors: ["#04cad0"],
    plotOptions: {
        radialBar: {
            hollow: {
                margin: 0,
                size: "35%",
                background: "#fff"
            },
            dataLabels: {
                name: {
                    offsetY: -10,
                    color: "#4b9bfa",
                    fontSize: "10px",
                    show: false
                },
                value: {
                    offsetY: 5,
                    color: "#4b9bfa",
                    fontSize: "12px",
                    show: true,
                    fontWeight: 800
                }
            }
        }
    },
    stroke: {
        lineCap: "round"
    },
    labels: ["Followers"]
  };
  document.querySelector("#totalcost").innerHTML = ""
  var chart2 = new ApexCharts(document.querySelector("#totalcost"), options);
  chart2.render();
  /* totalcost */

  
  /* totalprofit */
  var options = {
    chart: {
        height: 140,
        width: 100,
        type: "radialBar",
    },
  
    series: [38],
    colors: ["#f5334f"],
    plotOptions: {
        radialBar: {
            hollow: {
                margin: 0,
                size: "35%",
                background: "#fff"
            },
            dataLabels: {
                name: {
                    offsetY: -10,
                    color: "#4b9bfa",
                    fontSize: "10px",
                    show: false
                },
                value: {
                    offsetY: 5,
                    color: "#4b9bfa",
                    fontSize: "12px",
                    show: true,
                    fontWeight: 800
                }
            }
        }
    },
    stroke: {
        lineCap: "round"
    },
    labels: ["Followers"]
  };
  document.querySelector("#totalprofit").innerHTML = ""
  var chart7 = new ApexCharts(document.querySelector("#totalprofit"), options);
  chart7.render();
  /* totalprofit */
  
  /* totalshipment */
  var options = {
    chart: {
        height: 140,
        width: 100,
        type: "radialBar",
    },
  
    series: [48],
    colors: ["#f7b731"],
    plotOptions: {
        radialBar: {
            hollow: {
                margin: 0,
                size: "35%",
                background: "#fff"
            },
            dataLabels: {
                name: {
                    offsetY: -10,
                    color: "#4b9bfa",
                    fontSize: "10px",
                    show: false
                },
                value: {
                    offsetY: 5,
                    color: "#4b9bfa",
                    fontSize: "12px",
                    show: true,
                    fontWeight: 800
                }
            }
        }
    },
    stroke: {
        lineCap: "round"
    },
    labels: ["Followers"]
  };
  document.querySelector("#totalshipment").innerHTML = ""
  var chart6 = new ApexCharts(document.querySelector("#totalshipment"), options);
  chart6.render();
  /* totalshipment */

  
    /* perfectorder */
    var chart3 = {
    series: [{
        name: 'Income',
        data: [130, 90, 108,80, 136, 92, 149, 93, 113, 88],
    }, {
    name: 'Expances',
    data: [100, 85, 128, 100, 80, 125, 105, 118, 90, 145]
    }],
    chart: {
        type: 'area',
        height: 300
    },
    grid: {
        borderColor: 'rgba(167, 180, 201 ,0.2)',
    },
    markers: {
    size: [0,0],
    strokeColors: '#fff',
    strokeWidth: [3, 3],
    strokeOpacity: 0,
    },
    stroke: {
    curve: 'smooth',
    width: 2,
    dashArray: [0, 4]
    },
    fill: {
    type: ['gradient','gradient'],
    gradient: {
    shade: 'light',
    type: "vertical",
    opacityFrom: [0.6, 0.5],
    opacityTo: [0.2, 0.1],
    stops: [0, 100]
    }
    },
    dataLabels: {
        enabled: false,
    },
    legend: {
        show: true,
    position: 'top',
        labels: {
            colors: '#74767c',
        },
    },
    yaxis: {
        labels: {
            formatter: function (y) {
                return y.toFixed(0) + "";
            }
        },
        labels: {
            style: {
                colors: "#8c9097",
                fontSize: '11px',
                fontWeight: 600,
                cssClass: 'apexcharts-xaxis-label',
            },
        }
    },
    xaxis: {
        type: 'month',
        categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'sep', 'oct', 'nov', 'dec'],
        axisBorder: {
            show: true,
            color: 'rgba(167, 180, 201 ,0.2)',
            offsetX: 0,
            offsetY: 0,
        },
        axisTicks: {
            show: true,
            borderType: 'solid',
            color: 'rgba(167, 180, 201 ,0.2)',
            width: 6,
            offsetX: 0,
            offsetY: 0
        },
        labels: {
            rotate: -90,
            style: {
                colors: "#8c9097",
                fontSize: '11px',
                fontWeight: 600,
                cssClass: 'apexcharts-xaxis-label',
            },
        }
    },
    
    colors: [  "#564ec1", "#04cad0"],
        };
        document.getElementById('perfectorder').innerHTML = '';
        var chart3 = new ApexCharts(document.querySelector("#perfectorder"), chart3);
        chart3.render();
    
        function perfectorder() {
        chart3.updateOptions({
        colors: [  "#04cad0", "rgba(" + myVarVal + ", 0.95)"],
        })
    }
  /* perfectorder */

  /* Status Chart */
    var options = {
    series: [{
        name: "Orders",
        data: [30, 25, 36, 30, 45]
    }, {
        name: "Sales",
        data: [33, 21, 32, 37, 23]
    }],
    chart: {
        height: 290,
        type: "bar",
    },
    dataLabels: {
        enabled: false
    },
    stroke: {
        width: [1.1, 1.1],
        show: true,
        curve: ['smooth', 'smooth'],
    },
    grid: {
        borderColor: '#f3f3f3',
        strokeDashArray: 3
    },
    xaxis: {
        axisBorder: {
            color: 'rgba(119, 119, 142, 0.05)',
        },
    },
    legend: {
        show: false
    },
    labels: ["2014", "2015", "2016", "2017", "2018"],
    markers: {
        size: 0
    },
    colors: ["#564ec1", "#2dcad3"],
    plotOptions: {
        bar: {
            columnWidth: "50%",
            borderRadius: 2,
        }
    },
    };
    document.getElementById('Status').innerHTML = ''
    var chart4 = new ApexCharts(document.querySelector("#Status"), options);
    chart4.render();
    function Status() {
    chart4.updateOptions({
        colors: ["rgb(" + myVarVal + ")", "#2dcad3"],
    })
    }
    /* Status Chart */


  /* inventory Chart */
    var options = {
        series: [{
            name: 'Sessions',
            data: [950, 780, 550, 940, 1100,]
        }],
        chart: {
            fontFamily: 'Poppins, Arial, sans-serif',
            toolbar: {
                show: false
            },
            type: 'bar',
            height: 270
        },
        grid: {
            borderColor: '#f2f6f7',
        },
        plotOptions: {
            bar: {
                horizontal: false,
                barHeight: "70%",
                borderRadius: 1,
                columnWidth: "30%",
            }
        },
        colors: ["#564ec1"],
        dataLabels: {
            enabled: false
        },
        xaxis: {
            categories: ['Risk', 'Service', 'Storage', 'Admin', 'Freight'],
        }
    };

    document.getElementById('inventory').innerHTML = ''
    var chart5 = new ApexCharts(document.querySelector("#inventory"), options);
    chart5.render();
    function inventory() {
    chart5.updateOptions({
        colors: ["rgb(" + myVarVal + ")"],
    })
  }
  /* inventory Chart */