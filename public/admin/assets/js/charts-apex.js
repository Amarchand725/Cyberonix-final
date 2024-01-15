/**
 * Charts Apex
 */

'use strict';

(function() {
    let cardColor, headingColor, labelColor, borderColor, legendColor;

    if (isDarkStyle) {
        cardColor = config.colors_dark.cardColor;
        headingColor = config.colors_dark.headingColor;
        labelColor = config.colors_dark.textMuted;
        legendColor = config.colors_dark.bodyColor;
        borderColor = config.colors_dark.borderColor;
    } else {
        cardColor = config.colors.cardColor;
        headingColor = config.colors.headingColor;
        labelColor = config.colors.textMuted;
        legendColor = config.colors.bodyColor;
        borderColor = config.colors.borderColor;
    }

    // Color constant
    const chartColors = {
        column: {
            series1: '#826af9',
            series2: '#d2b0ff',
            bg: '#f8d3ff'
        },
        donut: {
            series1: '#fee802',
            series2: '#3fd0bd',
            series3: '#826bf8',
            series4: '#2b9bf4',
            series5: '#ea5355',
            series6: '#ff9f43'
        },
        area: {
            series1: '#29dac7',
            series2: '#60f2ca',
            series3: '#a5f8cd',
            series5: '#ea5355',
            series6: '#ff9f43'
        }
    };

    // Donut Chart
    // --------------------------------------------------------------------
    var regular = parseInt($('#teamChart').attr('data-regular'));
    var late_in = parseInt($('#teamChart').attr('data-late-in'));
    var absent = parseInt($('#teamChart').attr('data-absent'));
    var half_day = parseInt($('#teamChart').attr('data-half-day'));

    const teamChartEl = document.querySelector('#teamChart'),
        teamChartConfig = {
            chart: {
                height: 390,
                type: 'donut'
            },

            labels: ['Regular', 'Late-in Or Early Out', 'Absent', 'Half Day'],
            series: [regular, late_in, absent, half_day],
            colors: [
                chartColors.donut.series3,
                chartColors.donut.series6,
                chartColors.donut.series5,
                chartColors.donut.series2
            ],
            stroke: {
                show: false,
                curve: 'straight'
            },
            dataLabels: {
                enabled: true,

                formatter: function(val, opts) {
                    return opts.w.config.series[opts.seriesIndex]
                },
            },
            legend: {
                show: true,
                position: 'bottom',
                markers: { offsetX: -3 },
                itemMargin: {
                    vertical: 3,
                    horizontal: 10
                },
                labels: {
                    colors: legendColor,
                    useSeriesColors: false
                }
            },
            // plotOptions: {
            //     pie: {
            //         donut: {
            //             labels: {
            //                 show: false,
            //                 name: {
            //                     fontSize: '2rem',
            //                     fontFamily: 'Open Sans'
            //                 },
            //                 value: {
            //                     fontSize: '1.2rem',
            //                     color: legendColor,
            //                     fontFamily: 'Open Sans',
            //                     formatter: function(val) {
            //                         return parseInt(val, 10) + '%';
            //                     }
            //                 },
            //                 total: {
            //                     show: true,
            //                     fontSize: '1.5rem',
            //                     color: headingColor,
            //                     label: 'Operational',
            //                     formatter: function(w) {
            //                         return '42%';
            //                     }
            //                 }
            //             }
            //         }
            //     }
            // },
            responsive: [{
                    breakpoint: 992,
                    options: {
                        chart: {
                            height: 380
                        },
                        legend: {
                            position: 'bottom',
                            labels: {
                                colors: legendColor,
                                useSeriesColors: false
                            }
                        }
                    }
                },
                {
                    breakpoint: 576,
                    options: {
                        chart: {
                            height: 320
                        },
                        plotOptions: {
                            pie: {
                                donut: {
                                    labels: {
                                        show: true,
                                        name: {
                                            fontSize: '1.5rem'
                                        },
                                        value: {
                                            fontSize: '1rem'
                                        },
                                        total: {
                                            fontSize: '1.5rem'
                                        }
                                    }
                                }
                            }
                        },
                        legend: {
                            position: 'bottom',
                            labels: {
                                colors: legendColor,
                                useSeriesColors: false
                            }
                        }
                    }
                },
                {
                    breakpoint: 420,
                    options: {
                        chart: {
                            height: 280
                        },
                        legend: {
                            show: false
                        }
                    }
                },
                {
                    breakpoint: 360,
                    options: {
                        chart: {
                            height: 250
                        },
                        legend: {
                            show: false
                        }
                    }
                }
            ]
        };
    if (typeof teamChartEl !== undefined && teamChartEl !== null) {
        const teamChart = new ApexCharts(teamChartEl, teamChartConfig);
        teamChart.render();
    }
})();