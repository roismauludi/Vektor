/* total-orders Chart */
var options = {
  series: [{
      name: "Orders",
      data: [30, 25, 36, 30, 45, 35, 64, 51, 59, 36, 39, 51]
  }, {
      name: "Sales",
      data: [33, 21, 32, 37, 23, 32, 47, 31, 54, 32, 20, 38]
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
  labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
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
document.getElementById('total-orders').innerHTML = ''
var chart10 = new ApexCharts(document.querySelector("#total-orders"), options);
chart10.render();
function totalorders() {
  chart10.updateOptions({
      colors: ["rgb(" + myVarVal + ")", "#2dcad3"],
  })
}
/* total-orders Chart */

  /* Sales */
var options = {
  chart: {
      height: 110,
      width: 100,
      type: "radialBar",
  },

  series: [48],
  colors: ["#554dbf"],
  plotOptions: {
      radialBar: {
          hollow: {
              margin: 0,
              size: "50%",
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
document.querySelector("#Sales").innerHTML = ""
var chart5 = new ApexCharts(document.querySelector("#Sales"), options);
chart5.render();
function Sales() {
chart5.updateOptions({
    colors: ["rgb(" + myVarVal + ")"],
})
}
/* Sales */

/* Clicks Chart */
var options = {
  chart: {
      height: 110,
      width: 100,
      type: "radialBar",
  },

  series: [65],
  colors: ["#04cad0"],
  plotOptions: {
      radialBar: {
          hollow: {
              margin: 0,
              size: "50%",
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
  labels: ["Views"]
};
document.querySelector("#Toys").innerHTML = ""
var chart6 = new ApexCharts(document.querySelector("#Toys"), options);
chart6.render();
/* Clicks Chart */

/* Clicks Chart */
var options = {
  chart: {
      height: 110,
      width: 100,
      type: "radialBar",
  },

  series: [65],
  colors: ["#f5334f"],
  plotOptions: {
      radialBar: {
          hollow: {
              margin: 0,
              size: "50%",
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
  labels: ["Views"]
};
document.querySelector("#Kids").innerHTML = ""
var chart6 = new ApexCharts(document.querySelector("#Kids"), options);
chart6.render();
/* Clicks Chart */
/* Clicks Chart */
var options = {
  chart: {
      height: 110,
      width: 100,
      type: "radialBar",
  },

  series: [65],
  colors: ["#f7b731"],
  plotOptions: {
      radialBar: {
          hollow: {
              margin: 0,
              size: "50%",
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
  labels: ["Views"]
};
document.querySelector("#Decores").innerHTML = ""
var chart6 = new ApexCharts(document.querySelector("#Decores"), options);
chart6.render();
/* Clicks Chart */


    /* polar area monochrome chart */
    var options = {
      series: [72, 67, 58, 48, 45],
      chart: {
          height: 360,
          type: 'polarArea'
      },
      labels: ['Usa', 'India', 'Russia', 'Canada', 'Germany'],
      fill: {
          opacity: 1
      },
      stroke: {
          width: 1,
          colors: undefined
      },
      yaxis: {
          show: false
      },
      legend: {
          position: 'bottom'
      },
      plotOptions: {
          polarArea: {
              rings: {
                  strokeWidth: 0
              },
              spokes: {
                  strokeWidth: 0
              },
          }
      },
      colors: ["#564ec1", "#04cad0", "#f5334f", "#f7b731", "#26c2f7"],
  };
  var chart11 = new ApexCharts(document.querySelector("#country-sales"), options);
  chart11.render();
  function countrysales() {
    chart11.updateOptions({
        colors: ["rgb(" + myVarVal + ")", "#04cad0", "#f5334f", "#f7b731", "#26c2f7"],
    })
  }




/* totalvisitors */

var chart1 = {
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
dashArray: [0, 0]
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
    document.getElementById('totalvisitors').innerHTML = '';
    var chart1 = new ApexCharts(document.querySelector("#totalvisitors"), chart1);
    chart1.render();

    function totalvisitors() {
    chart1.updateOptions({
    colors: [  "#04cad0", "rgba(" + myVarVal + ", 0.95)"],
    })
}

/* totalvisitors */