
"use strict";

$(function(){
    let _url = 'http://127.0.0.1:8000';
    $.ajax({
        url : _url + '/graph',
        method : 'get',
        data : {'_token': $('#csrf-token')[0].content},
        dataType: 'json',
        success: function(data){
            data = data.data;
            if(data.error == true){iziToast.error({ title: 'Error!', message: data.message, position: 'topRight' });}
            if($('#graph').length || $('#grapHome').length){
                var graph = new Chart(document.getElementById('graph'), {
                    type: 'line',
                    data: {
                        labels: [data.day[0], data.day[1], data.day[2], data.day[3], data.day[4], data.day[5], data.day[6]],
                        datasets: [{
                            label: 'Penjualan',
                            backgroundColor: 'rgb(255, 99, 132)',
                            borderColor: 'rgb(255, 99, 132)',
                            data: [
                                (data.sales[0].total == null) ? 0 : data.sales[0].total,
                                (data.sales[1].total == null) ? 0 : data.sales[1].total,
                                (data.sales[2].total == null) ? 0 : data.sales[2].total,
                                (data.sales[3].total == null) ? 0 : data.sales[3].total,
                                (data.sales[4].total == null) ? 0 : data.sales[4].total,
                                (data.sales[5].total == null) ? 0 : data.sales[5].total,
                                (data.sales[6].total == null) ? 0 : data.sales[6].total,
                            ],
                            fill: false,
                        }, {
                            label: 'Pembelian Bahan Mentah',
                            backgroundColor: 'rgb(192, 99, 255)',
                            borderColor: 'rgb(192, 99, 255)',
                            data: [
                                (data.material[0].total == null) ? 0 : data.material[0].total,
                                (data.material[1].total == null) ? 0 : data.material[1].total,
                                (data.material[2].total == null) ? 0 : data.material[2].total,
                                (data.material[3].total == null) ? 0 : data.material[3].total,
                                (data.material[4].total == null) ? 0 : data.material[4].total,
                                (data.material[5].total == null) ? 0 : data.material[5].total,
                                (data.material[6].total == null) ? 0 : data.material[6].total,
                            ],
                            fill: false,
                        }]
                    },
                    options: {
                        responsive: true,
                        title: {
                            display: true,
                            text: 'Penjualan vs Pengeluaran'
                        },
                        tooltips: {
                            mode: 'index',
                            intersect: false,
                        },
                        hover: {
                            mode: 'nearest',
                            intersect: true
                        },
                        scales: {
                            xAxes: [{
                                display: true,
                                scaleLabel: {
                                    display: true,
                                    labelString: '1 Minggu Terakhir'
                                }
                            }],
                            yAxes: [{
                                display: true,
                                scaleLabel: {
                                    display: true,
                                    labelString: 'Penjualan (Dalam Ribu Rupiah)'
                                }
                            }]
                        }
                    }
                });
                //Graph
                var grapHome = new Chart(document.getElementById('grapHome'), {
                    type: 'line',
                    data: {
                        labels: [data.day[0], data.day[1], data.day[2], data.day[3], data.day[4], data.day[5], data.day[6]],
                        datasets: [{
                            label: 'Pendapatan',
                            backgroundColor: 'rgb(255, 99, 132)',
                            borderColor: 'rgb(255, 99, 132)',
                            data: [
                                (data.income[0].total == null) ? 0 : data.income[0].total,
                                (data.income[1].total == null) ? 0 : data.income[1].total,
                                (data.income[2].total == null) ? 0 : data.income[2].total,
                                (data.income[3].total == null) ? 0 : data.income[3].total,
                                (data.income[4].total == null) ? 0 : data.income[4].total,
                                (data.income[5].total == null) ? 0 : data.income[5].total,
                                (data.income[6].total == null) ? 0 : data.income[6].total,
                            ],
                            fill: false,
                        }, {
                            label: 'Pengeluaran',
                            backgroundColor: 'rgb(192, 99, 255)',
                            borderColor: 'rgb(192, 99, 255)',
                            data: [
                                (data.outcome[0].total == null) ? 0 : data.outcome[0].total,
                                (data.outcome[1].total == null) ? 0 : data.outcome[1].total,
                                (data.outcome[2].total == null) ? 0 : data.outcome[2].total,
                                (data.outcome[3].total == null) ? 0 : data.outcome[3].total,
                                (data.outcome[4].total == null) ? 0 : data.outcome[4].total,
                                (data.outcome[5].total == null) ? 0 : data.outcome[5].total,
                                (data.outcome[6].total == null) ? 0 : data.outcome[6].total,
                            ],
                            fill: false,
                        }]
                    },
                    options: {
                        responsive: true,
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'Pendapatan Non-Penjualan vs Pengeluaran'
                        },
                        animation: {
                            animateScale: true,
                            animateRotate: true
                        },
                        scales: {
                            xAxes: [{
                                display: true,
                                scaleLabel: {
                                    display: true,
                                    labelString: '1 Minggu Terakhir'
                                }
                            }],
                            yAxes: [{
                                display: true,
                                scaleLabel: {
                                    display: true,
                                    labelString: 'Pendapatan (Dalam Ribu Rupiah)'
                                }
                            }]
                        }
                    }
                });
                //Graph Tool
                var balance_chart = document.getElementById("balance-chart").getContext('2d');
                var balance_chart_bg_color = balance_chart.createLinearGradient(0, 0, 0, 70);
                balance_chart_bg_color.addColorStop(0, 'rgba(63,82,227,.2)');
                balance_chart_bg_color.addColorStop(1, 'rgba(63,82,227,0)');
                var myChart = new Chart(balance_chart, {
                    type: 'line',
                    data: {
                      labels: [data.day[0], data.day[1], data.day[2], data.day[3], data.day[4], data.day[5], data.day[6]],
                      datasets: [{
                        label: 'Balance',
                        data: [(data.sales[0].total == null) ? 0 : data.sales[0].total,
                        (data.sales[1].total == null) ? 0 : data.sales[1].total,
                        (data.sales[2].total == null) ? 0 : data.sales[2].total,
                        (data.sales[3].total == null) ? 0 : data.sales[3].total,
                        (data.sales[4].total == null) ? 0 : data.sales[4].total,
                        (data.sales[5].total == null) ? 0 : data.sales[5].total,
                        (data.sales[6].total == null) ? 0 : data.sales[6].total,],
                        backgroundColor: balance_chart_bg_color,
                        borderWidth: 3,
                        borderColor: 'rgba(63,82,227,1)',
                        pointBorderWidth: 0,
                        pointBorderColor: 'transparent',
                        pointRadius: 3,
                        pointBackgroundColor: 'transparent',
                        pointHoverBackgroundColor: 'rgba(63,82,227,1)',
                      }]
                    },
                    options: {
                      layout: {
                        padding: {
                          bottom: -1,
                          left: -1
                        }
                      },
                      legend: {
                        display: false
                      },
                      scales: {
                        yAxes: [{
                          gridLines: {
                            display: false,
                            drawBorder: false,
                          },
                          ticks: {
                            beginAtZero: true,
                            display: false
                          }
                        }],
                        xAxes: [{
                          gridLines: {
                            drawBorder: false,
                            display: false,
                          },
                          ticks: {
                            display: false
                          }
                        }]
                      },
                    }
                });

                var sales_chart = document.getElementById("sales-chart").getContext('2d');

                var sales_chart_bg_color = sales_chart.createLinearGradient(0, 0, 0, 80);
                sales_chart_bg_color.addColorStop(0, 'rgba(63,82,227,.2)');
                sales_chart_bg_color.addColorStop(1, 'rgba(63,82,227,0)');

                var myChart = new Chart(sales_chart, {
                    type: 'line',
                    data: {
                        labels: [
                            data.day[0], data.day[1], data.day[2], data.day[3], data.day[4], data.day[5], data.day[6]
                        ],
                        datasets: [{
                        label: 'Sales',
                        data: [
                            (data.sales[0].total == null) ? 0 : data.sales[0].total,
                            (data.sales[1].total == null) ? 0 : data.sales[1].total,
                            (data.sales[2].total == null) ? 0 : data.sales[2].total,
                            (data.sales[3].total == null) ? 0 : data.sales[3].total,
                            (data.sales[4].total == null) ? 0 : data.sales[4].total,
                            (data.sales[5].total == null) ? 0 : data.sales[5].total,
                            (data.sales[6].total == null) ? 0 : data.sales[6].total,
                        ],
                        borderWidth: 2,
                        backgroundColor: sales_chart_bg_color,
                        borderWidth: 3,
                        borderColor: 'rgba(63,82,227,1)',
                        pointBorderWidth: 0,
                        pointBorderColor: 'transparent',
                        pointRadius: 3,
                        pointBackgroundColor: 'transparent',
                        pointHoverBackgroundColor: 'rgba(63,82,227,1)',
                        }]
                    },
                    options: {
                        layout: {
                        padding: {
                            bottom: -1,
                            left: -1
                        }
                        },
                        legend: {
                        display: true
                        },
                        scales: {
                        yAxes: [{
                            gridLines: {
                            display: false,
                            drawBorder: false,
                            },
                            ticks: {
                            beginAtZero: true,
                            display: false
                            }
                        }],
                        xAxes: [{
                            gridLines: {
                            drawBorder: false,
                            display: false,
                            },
                            ticks: {
                            display: false
                            }
                        }]
                        },
                    }
                });
            }
        }, error: function() {
            iziToast.error({
                title: 'Sorry!',
                message: 'System Busy!, Try Again',
                position: 'topRight'
            });
        }
    });
});
