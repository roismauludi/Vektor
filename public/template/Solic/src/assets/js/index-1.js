/*  sales overview chart */

var options = {
	series: [{
		name: "Price",
		data: [20, 58, 38, 72, 55, 63, 43, 76, 55, 80, 40, 80]
	}, {
		name: "Volume",
		data: [65, 45, 75, 38, 85, 35, 62, 40, 40, 64, 50, 89]
	}],
	chart: {
		height: 310,
		type: 'line',
		zoom: {
			enabled: false
		},
		dropShadow: {
			enabled: true,
			enabledOnSeries: undefined,
			top: 5,
			left: 0,
			blur: 3,
			color: '#000',
			opacity: 0.1
		},
	},
	dataLabels: {
		enabled: false
	},
	legend: {
		position: "top",
		horizontalAlign: "center",
		offsetX: -15,
		fontWeight: "bold",
	},
	stroke: {
		curve: 'smooth',
		width: '3',
		dashArray: [0, 0],
	},
	grid: {
		borderColor: '#f2f6f7',
	},
	colors: ["rgb(132, 90, 223)", "rgba(4, 202, 208,0.9)"],
	yaxis: {
		title: {
			text: 'Statistics',
			style: {
				color: '#adb5be',
				fontSize: '14px',
				fontFamily: 'poppins, sans-serif',
				fontWeight: 600,
				cssClass: 'apexcharts-yaxis-label',
			},
		},
	},
	xaxis: {
		type: 'month',
		categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
		axisBorder: {
			show: true,
			color: 'rgba(119, 119, 142, 0.05)',
			offsetX: 0,
			offsetY: 0,
		},
		axisTicks: {
			show: true,
			borderType: 'solid',
			color: 'rgba(119, 119, 142, 0.05)',
			width: 6,
			offsetX: 0,
			offsetY: 0
		},
		labels: {
			rotate: -90
		}
	}
};
document.getElementById('retunchart').innerHTML = ''
var chart = new ApexCharts(document.querySelector("#retunchart"), options);
chart.render();
function retunchart() {
	chart.updateOptions({
		colors: ["rgb(" + myVarVal + ")", "rgba(4, 202, 208,0.9)"],
	})
}
  /*  sales overview chart */

// sparkline-1
function sparkline1() {
  var crm1 = {
      chart: {
          type: 'line',
          height: 28,
          width: 100,
          sparkline: {
              enabled: true
          }
      },
      stroke: {
          show: true,
          curve: 'smooth',
          lineCap: 'butt',
          colors: undefined,
          width: 2,
          dashArray: 0,
      },
      series: [{
          name: 'Value',
          data: [20, 14, 15, 10, 23, 20, 22, 9, 12]
      }],
      yaxis: {
          min: 0,
          show: false,
          axisBorder: {
              show: false
          },
      },
      xaxis: {
          show: false,
          axisBorder: {
              show: false
          },
      },
      colors: ["rgb(" + myVarVal + ")"],

  }
  document.getElementById('sparkline-1').innerHTML = '';
  var crm1 = new ApexCharts(document.querySelector("#sparkline-1"), crm1);
  crm1.render();
}

var crm1 = {
  chart: {
      type: 'line',
      height: 28,
      width: 100,
      sparkline: {
          enabled: true
      }
  },
  stroke: {
      show: true,
      curve: 'smooth',
      lineCap: 'butt',
      colors: undefined,
      width: 2,
      dashArray: 0,
  },
  series: [{
      name: 'Value',
      data: [20, 14, 15, 10, 23, 20, 22, 9, 12]
  }],
  yaxis: {
      min: 0,
      show: false,
      axisBorder: {
          show: false
      },
  },
  xaxis: {
      show: false,
      axisBorder: {
          show: false
      },
  },
  colors: ["#e9ac04"],

}
document.getElementById('sparkline-2').innerHTML = '';
var crm1 = new ApexCharts(document.querySelector("#sparkline-2"), crm1);
crm1.render();


var crm1 = {
  chart: {
      type: 'line',
      height: 28,
      width: 100,
      sparkline: {
          enabled: true
      }
  },
  stroke: {
      show: true,
      curve: 'smooth',
      lineCap: 'butt',
      colors: undefined,
      width: 2,
      dashArray: 0,
  },
  series: [{
      name: 'Value',
      data: [20, 14, 15, 10, 23, 20, 22, 9, 12]
  }],
  yaxis: {
      min: 0,
      show: false,
      axisBorder: {
          show: false
      },
  },
  xaxis: {
      show: false,
      axisBorder: {
          show: false
      },
  },
  colors: ["#2dc3fc"],

}
document.getElementById('sparkline-3').innerHTML = '';
var crm1 = new ApexCharts(document.querySelector("#sparkline-3"), crm1);
crm1.render();


var crm1 = {
  chart: {
      type: 'line',
      height: 28,
      width: 100,
      sparkline: {
          enabled: true
      }
  },
  stroke: {
      show: true,
      curve: 'smooth',
      lineCap: 'butt',
      colors: undefined,
      width: 2,
      dashArray: 0,
  },
  series: [{
      name: 'Value',
      data: [20, 14, 15, 10, 23, 20, 22, 9, 12]
  }],
  yaxis: {
      min: 0,
      show: false,
      axisBorder: {
          show: false
      },
  },
  xaxis: {
      show: false,
      axisBorder: {
          show: false
      },
  },
  colors: ["#ff5c77"],

}
document.getElementById('sparkline-4').innerHTML = '';
var crm1 = new ApexCharts(document.querySelector("#sparkline-4"), crm1);
crm1.render();


  /* earnings report */
  var options = {
    series: [{
        name: 'Machines1',
        data: [44, 42, 57, 86, 58, 55, 70],
    }, {
        name: 'Machines2',
        data: [34, 22, 37, 56, 21, 35, 60],
    }, {
        name: 'Machines3',
        data: [28, 26, 33, 45, 17, 33, 40],
      }, {
        name: 'Machines4',
        data: [30, 30, 29, 46, 28, 45, 53],
      }, {
        name: 'Machines5',
        data: [35, 38, 40, 50, 33, 41, 58],
    }],
    chart: {
        type: 'bar',
        height: 285
    },
    // grid: {
    //     borderColor: '#f1f1f1',
    //     // strokeDashArray: 3
    // },
    stroke: {
        width: 1,
    },
    colors: ["var(--primary-color)", "#04cad0","#f5334f", "#f7b731","#21c44c"],
    plotOptions: {
        bar: {
            borderRadius: 3,
            // colors: {
            //     ranges: [{
            //         from: -100,
            //         to: -46,
            //         color: '#ebeff5'
            //     }, {
            //         from: -45,
            //         to: 0,
            //         color: '#ebeff5'
            //     }]
            // },
            columnWidth: '55%',
            dropShadow: {
                enabled: false,
            }
        }
    },
    dataLabels: {
        enabled: false,
    },
    legend: {
        show: true,
        position: 'top',
    },
    tooltip: {
        enabled: true,
        theme: "dark",
    },
    yaxis: {
        title: {
            style: {
                color: '#adb5be',
                fontSize: '14px',
                fontFamily: 'poppins, sans-serif',
                fontWeight: 600,
                cssClass: 'apexcharts-yaxis-label',
            },
        },
        labels: {
            formatter: function (y) {
                return y.toFixed(0) + "";
            }
        }
    },
    xaxis: {
        type: 'day',
        categories: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
        axisBorder: {
            show: true,
            color: 'rgba(119, 119, 142, 0.05)',
            offsetX: 0,
            offsetY: 0,
        },
        axisTicks: {
            show: true,
            borderType: 'solid',
            color: 'rgba(119, 119, 142, 0.05)',
            width: 6,
            offsetX: 0,
            offsetY: 0
        },
        labels: {
            rotate: -90
        }
    }
};
var chart2 = new ApexCharts(document.querySelector("#machines"), options);
chart2.render();
/* earnings report */
