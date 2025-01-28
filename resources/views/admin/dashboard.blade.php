@include('admin.layout.menu.tdashboard')
<body>

    <div id="preloader">
		<div class="lds-ripple">
			<div></div>
			<div></div>
		</div>
    </div>
   
    <div id="main-wrapper">
        
        @include('admin.layout.menu.navbar')
        <div class="content-body">
            @yield('content')
			<div class="container-fluid">
				<div class="row">
                    <div class="col">
                        <div class="card">
                            <div class="card-body d-flex px-4 pb-0 justify-content-between">
                                <div>
                                    <h4 class="fs-18 font-w600 mb-4 text-nowrap">Total Members</h4>
                                    <div class="d-flex align-items-center">
                                        <h2 class="fs-32 font-w700 mb-0">{{ $total_member }}</h2>
                                        <span class="d-block ms-4">
                                            <svg width="21" height="11" viewbox="0 0 21 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M1.49217 11C0.590508 11 0.149368 9.9006 0.800944 9.27736L9.80878 0.66117C10.1954 0.29136 10.8046 0.291359 11.1912 0.661169L20.1991 9.27736C20.8506 9.9006 20.4095 11 19.5078 11H1.49217Z" fill="#09BD3C"></path>
                                            </svg>
                                            <small class="d-block fs-16 font-w400 text-success">
                                                {{ $percentage_change >= 0 ? '+' : '' }}{{ $percentage_change }}%
                                            </small>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card">
                            <div class="card-body d-flex px-4  justify-content-between">
                                <div>
                                    <div class="">
                                        <h2 class="fs-32 font-w700">{{ $total_member2 }}</h2>
                                        <span class="fs-18 font-w500 d-block">New Members</span>
                                        <span class="d-block fs-16 font-w400">
                                            <small class="{{ $percentage_change2 >= 0 ? 'text-success' : 'text-danger' }}">
                                                {{ $percentage_change2 >= 0 ? '+' : '' }}{{ $percentage_change2 }}%
                                            </small> than last month
                                        </span>
                                    </div>
                                </div>
                                <div id="NewCustomers1"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card">
                            <div class="card-body d-flex px-4  justify-content-between">
                                <div>
                                    <div class="">
                                        <h2 class="fs-32 font-w700">{{ $total_today3 }}</h2>
                                        <span class="fs-18 font-w500 d-block">Member Today</span>
                                        <span class="d-block fs-16 font-w400">
                                            <small class="{{ $percentage_change_today3 >= 0 ? 'text-success' : 'text-danger' }}">
                                                {{ $percentage_change_today3 >= 0 ? '+' : '' }}{{ $percentage_change_today3 }}%
                                            </small> than yesterday
                                        </span>
                                    </div>
                                </div>
                                <div id="NewCustomers"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header border-0">
                        <div>
                            <h4 class="fs-20 font-w700">Member Statistics</h4>
                        </div>	
                    </div>	
                    <div class="card-body">
                        <div id="emailchart"> </div>
                        <div class="mb-3 mt-4">
                            <h4 class="fs-18 font-w600">Legend</h4>
                        </div>
                        <div>
                            <div class="d-flex align-items-center justify-content-between mb-4">
                                <span class="fs-18 font-w500">	
                                    <svg class="me-3" width="20" height="20" viewbox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <rect width="20" height="20" rx="6" fill="#886CC0"></rect>
                                    </svg>
                                    Active ({{ $percentage_active }}%)
                                </span>
                                <span class="fs-18 font-w600">{{ $total_active }}</span>
                            </div>
                            <div class="d-flex align-items-center justify-content-between  mb-4">
                                <span class="fs-18 font-w500">	
                                    <svg class="me-3" width="20" height="20" viewbox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <rect width="20" height="20" rx="6" fill="#26E023"></rect>
                                    </svg>
                                    Non-Active ({{ $percentage_non_active }}%)
                                </span>
                                <span class="fs-18 font-w600">{{ $total_non_active }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-6 col-lg-12 col-sm-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">Information Registrasi</h4>
                                    </div>
                                    <div class="card-body">
                                        <canvas id="barChart_1"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-12 col-sm-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title">Basic Area Chart</h4>
                                    </div>
                                    <div class="card-body">
                                        <canvas id="areaChart_1"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
	</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        emailchart({{ $percentage_active }}, {{ $percentage_non_active }});
    });

    var emailchart = function(activePercentage, nonActivePercentage) {
        var options = {
            series: [activePercentage, nonActivePercentage],
            labels: ['Active', 'Non-Active'], // Tambahkan label untuk series
            chart: {
                type: 'donut',
                height: 300
            },
            dataLabels: {
                enabled: true, // Aktifkan label dalam chart
                formatter: function(val, opts) {
                    return opts.w.globals.labels[opts.seriesIndex] + ": " + val.toFixed(1) + "%";
                }
            },
            stroke: {
                width: 0,
            },
            colors: ['#886CC0', '#26E023'], // Warna Active dan Non-Active
            legend: {
                position: 'bottom',
                show: true
            },
            responsive: [
                {
                    breakpoint: 1800,
                    options: {
                        chart: {
                            height: 200
                        },
                    }
                }
            ]
        };

        var chart = new ApexCharts(document.querySelector("#emailchart"), options);
        chart.render();
    };
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        barChart1({{ json_encode($monthlyData) }});
    });

    var barChart1 = function(monthlyData) {
        if (jQuery('#barChart_1').length > 0) {
            const barChart_1 = document.getElementById("barChart_1").getContext('2d');

            barChart_1.height = 100;

            new Chart(barChart_1, {
                type: 'bar',
                data: {
                    defaultFontFamily: 'Poppins',
                    labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                    datasets: [
                        {
                            label: "Monthly Data",
                            data: monthlyData,
                            borderColor: 'rgba(136,108,192, 1)',
                            borderWidth: "0",
                            backgroundColor: 'rgba(136,108,192, 1)'
                        }
                    ]
                },
                options: {
                    legend: false,
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }],
                        xAxes: [{
                            barPercentage: 0.5
                        }]
                    }
                }
            });
        }
    }
</script>
@include('admin.layout.footer')