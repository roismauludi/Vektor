
    /* impressions */
    var total = {
        chart: {
            type: 'area',
            height: 50,
            sparkline: {
                enabled: true
            },
            dropShadow: {
                enabled: true,
                enabledOnSeries: undefined,
                top: 7,
                left: 0,
                blur: 1,
                color: 'var(--primary-color)',
                opacity: 0
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
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.4,
                opacityTo: 0.1,
                stops: [0, 90, 100],
                colorStops: [
                    [
                        {
                            offset: 0,
                            color: "var(--primary01)",
                            opacity: 1
                        },
                        {
                            offset: 75,
                            color: "var(--primary01)",
                            opacity: 1
                        },
                        {
                            offset: 100,
                            color: "var(--primary01)",
                            opacity: 1
                        }
                    ]
                ]
            }
        },
        series: [{
            name: 'Value',
            data: [ 1, 5, 2, 7, 2, 6]
        }],
        yaxis: {
            min: 0,
            show: false
        },
        xaxis: {
            axisBorder: {
                show: false
            },
        },
        yaxis: {
            axisBorder: {
                show: false
            },
        },
        colors: ["var(--primary-color)"],
        tooltip: {
            enabled: false,
        }
    }
    document.getElementById('impressions').innerHTML = '';
    var total = new ApexCharts(document.querySelector("#impressions"), total);
    total.render();
    
    /* impressions */

    
    /* session */
    var total = {
        chart: {
            type: 'area',
            height: 50,
            sparkline: {
                enabled: true
            },
            dropShadow: {
                enabled: true,
                enabledOnSeries: undefined,
                top: 7,
                left: 0,
                blur: 1,
                color: '#04cad0',
                opacity: 0
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
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.4,
                opacityTo: 0.1,
                stops: [0, 90, 100],
                colorStops: [
                    [
                        {
                            offset: 0,
                            color: "rgba(0, 202, 208, 0.1)",
                            opacity: 1
                        },
                        {
                            offset: 75,
                            color: "rgba(0, 202, 208, 0.1)",
                            opacity: 1
                        },
                        {
                            offset: 100,
                            color: "rgba(0, 202, 208, 0.1)",
                            opacity: 1
                        }
                    ]
                ]
            }
        },
        series: [{
            name: 'Value',
            data: [ 1, 5, 2, 7, 2, 6]
        }],
        yaxis: {
            min: 0,
            show: false
        },
        xaxis: {
            axisBorder: {
                show: false
            },
        },
        yaxis: {
            axisBorder: {
                show: false
            },
        },
        colors: ["#04cad0"],
        tooltip: {
            enabled: false,
        }
    }
    document.getElementById('session').innerHTML = '';
    var total = new ApexCharts(document.querySelector("#session"), total);
    total.render();
    /* session */
    
    
    /* pageviews */
    var total = {
        chart: {
            type: 'area',
            height: 50,
            sparkline: {
                enabled: true
            },
            dropShadow: {
                enabled: true,
                enabledOnSeries: undefined,
                top: 7,
                left: 0,
                blur: 1,
                color: '#f43350',
                opacity: 0
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
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.4,
                opacityTo: 0.1,
                stops: [0, 90, 100],
                colorStops: [
                    [
                        {
                            offset: 0,
                            color: "rgba(244, 51, 80, 0.1)",
                            opacity: 1
                        },
                        {
                            offset: 75,
                            color: "rgba(244, 51, 80, 0.1)",
                            opacity: 1
                        },
                        {
                            offset: 100,
                            color: "rgba(244, 51, 80, 0.1)",
                            opacity: 1
                        }
                    ]
                ]
            }
        },
        series: [{
            name: 'Value',
            data: [ 1, 5, 2, 7, 2, 6]
        }],
        yaxis: {
            min: 0,
            show: false
        },
        xaxis: {
            axisBorder: {
                show: false
            },
        },
        yaxis: {
            axisBorder: {
                show: false
            },
        },
        colors: ["#f43350"],
        tooltip: {
            enabled: false,
        }
    }
    document.getElementById('pageviews').innerHTML = '';
    var total = new ApexCharts(document.querySelector("#pageviews"), total);
    total.render();
    /* pageviews */
    
    /* users */
    var total = {
        chart: {
            type: 'area',
            height: 50,
            sparkline: {
                enabled: true
            },
            dropShadow: {
                enabled: true,
                enabledOnSeries: undefined,
                top: 7,
                left: 0,
                blur: 1,
                color: '#f6b731',
                opacity: 0
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
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.4,
                opacityTo: 0.1,
                stops: [0, 90, 100],
                colorStops: [
                    [
                        {
                            offset: 0,
                            color: "rgba(246, 183, 49, 0.1)",
                            opacity: 1
                        },
                        {
                            offset: 75,
                            color: "rgba(246, 183, 49, 0.1)",
                            opacity: 1
                        },
                        {
                            offset: 100,
                            color: "rgba(246, 183, 49, 0.1)",
                            opacity: 1
                        }
                    ]
                ]
            }
        },
        series: [{
            name: 'Value',
            data: [ 1, 5, 2, 7, 2, 6]
        }],
        yaxis: {
            min: 0,
            show: false
        },
        xaxis: {
            axisBorder: {
                show: false
            },
        },
        yaxis: {
            axisBorder: {
                show: false
            },
        },
        colors: ["#f6b731"],
        tooltip: {
            enabled: false,
        }
    }
    document.getElementById('users').innerHTML = '';
    var total = new ApexCharts(document.querySelector("#users"), total);
    total.render();
    /* users */
    

    /* Monthly Profits */
    var options = {
        chart: {
            height: 180,
            type: "radialBar",
        },
        series: [72],
        colors: ["var(--primary09)"],
        plotOptions: {
            radialBar: {
                hollow: {
                    margin: 0,
                    size: "60%",
                    background: "#fff",
                },
                dataLabels: {
                    name: {
                        offsetY: -10,
                        color: "#4b9bfa",
                        fontSize: "16px",
                        show: false,
                    },
                    value: {
                        offsetY: 10,
                        color: "#4b9bfa",
                        fontSize: "22px",
                        show: true,
                    },
                    total: {
                        show: true,
                        label: 'Total',
                    }
                },
            },
        },
        stroke: {
            lineCap: "round",
        },
    };
    var chart1 = new ApexCharts(document.querySelector("#Profits"), options);
    chart1.render();
    /* Monthly Profits */

    /* Monthly Lose */
    var options = {
        chart: {
            height: 180,
            type: "radialBar",
        },
        series: [72],
        colors: ["#04cad0"],
        plotOptions: {
            radialBar: {
                hollow: {
                    margin: 0,
                    size: "60%",
                    background: "#fff",
                },
                dataLabels: {
                    name: {
                        offsetY: -10,
                        color: "#4b9bfa",
                        fontSize: "16px",
                        show: false,
                    },
                    value: {
                        offsetY: 10,
                        color: "#4b9bfa",
                        fontSize: "22px",
                        show: true,
                    },
                    total: {
                        show: true,
                        label: 'Total',
                    }
                },
            },
        },
        stroke: {
            lineCap: "round",
        },
    };
    var chart1 = new ApexCharts(document.querySelector("#lose"), options);
    chart1.render();
    /* Monthly Lose */
    
    // Revenue Report
    var options = {
        series: [{
        name: 'progress',
        type: 'line',
        data: [50, 32, 45, 63, 46, 82, 60, 87, 45, 66, 70, 80],
        }, {
        name: 'active',
        type: 'column',
        data: [45, 60, 55, 45, 50, 60, 80, 73, 50, 40, 100, 74],
        }, {
        name: 'inactive',
        type: 'line',
        data: [34, 42, 97, 56, 61, 75, 50, 94, 56, 78, 90, 53]
        }],
        chart: {
        height: 290,
        fontFamily: 'poppins, sans-serif',
        },
        stroke: {
        curve: 'smooth', 
        dashArray: [0, 0, 0],
        width: [2, 0, 2]
        },
        fill: {
        opacity: [1, 1, 1]
        },
        grid: {
        borderColor: '#f2f6f7',
        },
        colors: [ "rgba(4, 202, 208,0.9)", "#564ec1", "rgba(247, 183, 49, 0.9)"],
        plotOptions: {
        bar: {
            columnWidth: '25%',
        }
        },
        dataLabels: {
        enabled: false,
        },
        legend: {
        show: true,
        position: 'top',
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
    var chart1 = new ApexCharts(document.querySelector("#analytic"), options);
    chart1.render();
    
    function analytic() {
        chart1.updateOptions({
        colors: [ "rgba(247, 183, 49, 0.9)",`rgb(${myVarVal})`,"rgba(4, 202, 208,0.9)"],
        })
    }

  
    // Users by country map
    var markers = [{
        name: 'Russia',
        coords: [61, 105],
        style: {
            fill: '#28d193'
        }
    },
    {
        name: 'Geenland',
        coords: [72, -42],
        style: {
            fill: '#ff8c33'
        }
    },
    {
        name: 'Canada',
        coords: [56, -106],
        style: {
            fill: '#ff534d'
        }
    },
    {
        name: 'Palestine',
        coords: [31.5, 34.8],
        style: {
            fill: '#ffbe14'
        }
    },
    {
        name: 'Brazil',
        coords: [-14.2350, -51.9253],
        style: {
            fill: '#4b9bfa'
        }
    },
    ];
    var map = new jsVectorMap({
        map: 'world_merc',
        selector: '#users-map',
        markersSelectable: true,

        onMarkerSelected(index, isSelected, selectedMarkers) {
            console.log(index, isSelected, selectedMarkers);
        },

        // -------- Labels --------
        labels: {
            markers: {
                render: function (marker) {
                    return marker.name
                },
            },
        },

        // -------- Marker and label style --------
        markers: markers,
        markerStyle: {
            hover: {
                stroke: "#DDD",
                strokeWidth: 3,
                fill: '#FFF'
            },
            selected: {
                fill: '#ff525d'
            }
        },
        markerLabelStyle: {
            initial: {
                fontFamily: 'Poppins',
                fontSize: 13,
                fontWeight: 500,
                fill: '#35373e',
            },
        },
    })
