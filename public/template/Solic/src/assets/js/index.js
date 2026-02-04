
  
  /* total-purchase */
var options = {
    series: [75, 35],
    chart: {
      height: 100,
      width:100,
      type: "pie",
    },
    colors: ["#564ec1", "#f0f3f7"],
    labels: ["", ""],
    legend: {
        show:false,
      position: "bottom",
    },
    dataLabels: {
      dropShadow: {
        enabled: false,
      },
    },
    tooltip:{
        show:false
    }
  };
  var chart2 = new ApexCharts(document.querySelector("#total-purchase"), options);
  chart2.render();
  function totalpurchase() {
    chart2.updateOptions({
        colors: ["rgb(" + myVarVal + ")", "#f0f3f7",],
    })
  };
  /* total-purchase */
  
  /* net-revenue */
var options = {
    series: [75, 35],
    chart: {
      height: 100,
      width:100,
      type: "pie",
    },
    colors: ["#04cad0", "#f0f3f7"],
    labels: ["", ""],
    legend: {
        show:false,
      position: "bottom",
    },
    dataLabels: {
      dropShadow: {
        enabled: false,
      },
    },
    tooltip:{
        show:false
    }
  };
  var chart = new ApexCharts(document.querySelector("#net-revenue"), options);
  chart.render();
  /* net-revenue */

// Statistics Chart//
    function balance() {
        var options = {
            series: [ {
                name: 'Expances',
                data: [100, 108, 128, 140, 110, 125, 105, 118, 120, 145, 105, 125,100, 128, 128, 140, 120, 140, 128, 128]
            }],
            chart: {
                type: 'area',
                height: 330
            },
            grid: {
                borderColor: 'rgba(167, 180, 201 ,0.2)',
            },
            colors: [ "rgba(" + myVarVal + ", 0.95)",],
            markers: {
                size: [0,0],
                strokeColors: '#fff',
                strokeWidth: [0, 0],
                strokeOpacity: 0,
            },
            stroke: {
                curve: 'smooth',
                width: 1,
                dashArray: [0, 4]
            },
            // fill: {
            // 	type: ['gradient','gradient'],
            // 	gradient: {
            // 		shade: 'light',
            // 		type: "vertical",
            // 		opacityFrom: [0.6, 0.5],
            // 		opacityTo: [0.2, 0.1],
            // 		stops: [0, 100]
            // 	}
            // },
            dataLabels: {
                enabled: false,
            },
            legend: {
                show: false,
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
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'sep', 'oct', 'nov', 'dec','Jan', 'Feb', 'Mar', 'Apr', 'May','Jun', 'Jul', 'Aug'],
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
            }
        };
        document.getElementById('balance').innerHTML = '';
        var chart1 = new ApexCharts(document.querySelector("#balance"), options);
        chart1.render();
    }
   //Statistics Chart//

   // widgetChart1//
        var options = {
            series: [{
                name: 'Cancel',
                data: [3, 8, 5, 7, 3, 7, 5, 8]  
            }, {
                name: 'Expansion',
                data: [5, 3, 9, 6, 4, 6, 7, 3]
            }, {
                name: 'Contraction',
                data: [2, 7, 3, 9, 4, 5, 2, 8],
            }],
            chart: {
                type: 'line',
                height: 200
            },
            // grid: {
            //     borderColor: 'rgba(167, 180, 201 ,0.2)',
            // },
            colors: [ "rgba(86, 78, 193,0.9)",  "rgba(4, 202, 208,0.9)","rgba(247, 183, 49, 0.9)"],
            markers: {
                size: [2,2,2],
                strokeColors: [ "rgba(86, 78, 193,0.9)",  "rgba(4, 202, 208,0.9)", "rgba(247, 183, 49, 0.9)"],
                strokeWidth: [2, 2, 2],
                strokeOpacity: 0,
            },
            stroke: {
                curve: 'smooth',
                width: 2,
                dashArray: [0, 0,0]
            },
            // fill: {
            // 	type: ['gradient','gradient','gradient'],
            // 	gradient: {
            // 		shade: 'light',
            // 		type: "vertical",
            // 		opacityFrom: [0.01, 0.01, 0.01],
            // 		opacityTo: [0.05,0.05,0.05 ],
            // 		stops: [0,100]
            // 	}
            // },
            dataLabels: {
                enabled: false,
            },
            legend: {
                show: false,
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
                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'sep', 'oct', 'nov', 'dec','Jan', 'Feb', 'Mar', 'Apr', 'May','Jun', 'Jul', 'Aug', 'sep','oct', 'nov', 'dec'],
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
            }
        };
        document.getElementById('widgetChart1').innerHTML = '';
        var chart12 = new ApexCharts(document.querySelector("#widgetChart1"), options);
        chart12.render();
      function widgetChart1() {
        chart12.updateOptions({
          colors: [ "rgba(247, 183, 49, 0.9)",`rgb(${myVarVal})`,"rgba(4, 202, 208,0.9)"],
        })
    }
   //widgetChart1//
// Revenue Report
var options = {
    series: [{
      name: 'progress',
      type: 'line',
      data: [-15, 32, -11, 63, 16, 82, 292, 107, -18, 56, 200, 80],
    }, {
      name: 'active',
      type: 'column',
      data: [104, 102, 117, 146, 118, 115, 220, 103, 83, 114, 265, 174],
    }, {
      name: 'inactive',
      type: 'column',
      data: [-34, -42, -97, -56, -71, -175, -60, -34, -56, -78, -119, -53]
    }],
    chart: {
      height: 305,
      fontFamily: 'poppins, sans-serif',
    },
    stroke: {
      curve: 'smooth', 
      dashArray: [0, 0, 0],
      width: [2, 0, 0]
    },
    fill: {
      opacity: [1, 1, 1]
    },
    grid: {
      borderColor: '#f2f6f7',
    },
    colors: ["#ffda82", "rgba(4, 202, 208,0.9)", "rgba(247, 183, 49, 0.9)"],
    plotOptions: {
      bar: {
        columnWidth: '30%',
      }
    },
    dataLabels: {
      enabled: false,
    },
    legend: {
      show: true,
      position: 'top',
    },
    toolbar:{
      show: false,
    },
    yaxis: {
      title: {
        style: {
          color: '	#adb5be',
          fontSize: '14px',
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
      type: 'month',
      categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'sep', 'oct', 'nov', 'dec'],
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
  var chart1 = new ApexCharts(document.querySelector("#revenueReport"), options);
  chart1.render();
  
  function revenueReport() {
    chart1.updateOptions({
      colors: [ "rgba(247, 183, 49, 0.9)",`rgb(${myVarVal})`,"rgba(4, 202, 208,0.9)"],
    })
  }
  
    /* Top Categories */
    var options7 = {
        series: [{
          name: 'MRR Revenue',
          data: [80, 50, 30, 40, 100, 20, 40],
        }, {
          name: 'MRR Revenue',
          data: [20, 30, 40, 90, 20, 90, 35],
        }, {
          name: 'Non-Recurring Revenue',
          data: [40, 76, 78, 13, 43, 10, 80],
        }],
        chart: {
          height: 400,
          type: 'radar',
          toolbar: {
            show: false,
          }
        },
        title: {
          align: 'left',
          style: {
            fontSize: '13px',
            fontWeight: 'bold',
            color: '#8c9097'
          },
        },
        colors: ["var(--primary08)","rgba(247, 183, 49, 0.9)","rgba(4, 202, 208,0.9)"],
        stroke: {
          width: 2
        },
        fill: {
          opacity: 0.2
        },
        markers: {
          size: 0
        },
        legend: {
          show: true,
          fontSize: "12px",
          position: 'bottom',
          horizontalAlign: 'center',
          fontFamily: "Montserrat",
          fontWeight: 500,
          offsetX: 0,
          offsetY: -8,
          height: 50,
          labels: {
            colors: '#9ba5b7',
          },
          markers: {
            width: 7,
            height: 7,
            strokeWidth: 0,
            strokeColor: '#fff',
            fillColors: undefined,
            radius: 7,
            offsetX: 0,
            offsetY: 0
          },
        },
        fill: {
        	type: ['gradient','gradient','gradient'],
        	gradient: {
        		shade: 'light',
        		type: "vertical",
        		opacityFrom: [0.4, 0.4, 0.4],
        		opacityTo: [0.3,0.3,0.3 ],
        		stops: [1,1,1]
        	}
        },
        xaxis: {
          categories: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat']
        },
        yaxis: {
          tickAmount: 7,
          labels: {
            formatter: function (val, i) {
              if (i % 5 === 0) {
                return val
              }
            }
          }
        }
      };
      var chart7 = new ApexCharts(document.querySelector("#top-categories"), options7);
      chart7.render();
      /* Top Categories */