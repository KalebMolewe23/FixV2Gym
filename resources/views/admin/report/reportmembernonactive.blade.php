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
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="example" class="display" style="min-width: 845px">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Mulai Latihan</th>
                                        <th>Harga Trainer</th>
                                        <th>Total Harga</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data as $v_data)
                                    <tr>
                                        <td>{{ $v_data->name_member }}</td>
                                        <td>{{ \Carbon\Carbon::parse($v_data->start_training)->format('d-m-Y') }}</td>
                                        <td>{{ 'Rp ' . number_format($v_data->price_member, 0, ',', '.') }}</td>
                                        <td>{{ 'Rp ' . number_format($v_data->total_price, 0, ',', '.') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
	</div>
@include('admin.layout.footer')