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
                <form action="{{ route('admin.members.index') }}" method="GET" class="d-flex mb-4">
                    <input type="text" name="search" class="form-control me-2" placeholder="Cari nama..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary">Cari</button>
                </form>
                <button 
                    type="button" 
                    class="btn btn-primary" 
                    data-bs-toggle="modal" 
                    data-bs-target="#createMemberModal">
                    Tambah Data
                </button>
                @foreach($active as $v_active)
                <div class="card mb-4" style="margin-top:10px;">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <!-- Barcode and Name -->
                            <div class="col-xl-3 col-lg-6 col-sm-12 mb-3">
                                <div class="media-body">
                                    <span class="text-primary d-block fs-18 font-w500 mb-1">#{{ $v_active->barcode }}</span>
                                    <h3 class="fs-18 text-black font-w600">{{ $v_active->name_member }} <span style="font-size:14px;">( {{ $v_active->time_remaining }} )</span></h3>
                                    <span class="d-block mb-2 fs-16">
                                        <i class="fas fa-calendar me-2"></i>
                                        Tanggal Daftar : {{ \Carbon\Carbon::parse($v_active->start_training)->format('d-m-Y') }}
                                    </span>
                                </div>
                            </div>

                            <!-- Gender Image -->
                            <div class="col-xl-2 col-lg-3 col-sm-4 col-6 mb-3 text-center">
                                @if ($v_active->upload)
                                    @php
                                        $fileExtension = pathinfo($v_active->upload, PATHINFO_EXTENSION);
                                        $isImage = in_array(strtolower($fileExtension), ['jpg', 'jpeg', 'png', 'gif']);
                                    @endphp

                                    @if ($isImage)
                                        <img 
                                            src="{{ asset('storage/' . $v_active->upload) }}" 
                                            alt="Uploaded File" 
                                            class="img-fluid rounded-circle" 
                                            style="width: 100px; height: 100px; object-fit: cover;"
                                        >
                                    @else
                                        <a 
                                            href="{{ asset('storage/' . $v_active->upload) }}" 
                                            target="_blank" 
                                            class="btn btn-primary"
                                        >
                                            Lihat Berkas
                                        </a>
                                    @endif
                                @elseif ($v_active->photo)
                                    <img 
                                        src="{{ asset('storage/' . $v_active->photo) }}" 
                                        alt="User Photo" 
                                        class="img-fluid rounded-circle" 
                                        style="width: 100px; height: 100px; object-fit: cover;"
                                    >
                                @else
                                    <img 
                                        src="{{ asset('assets/images/default.png') }}" 
                                        alt="Default Photo" 
                                        class="img-fluid rounded-circle" 
                                        style="width: 100px; height: 100px; object-fit: cover;"
                                    >
                                @endif
                            </div>

                            <!-- QR Code -->
                            <div class="col-xl-2 col-lg-3 col-sm-4 col-6 mb-3 text-center">
                                <img src="data:image/png;base64,{{ $v_active->qrcode }}" alt="Barcode for {{ $v_active->barcode }}" class="img-fluid" style="border: 2px solid #000; padding: 10px; background-color: #fff; width: 50%;">
                                <br><br>
                                <a href="{{ route('admin.members.cetakBarcode', $v_active->id) }}" target="_blank">
                                    <button class="btn btn-primary">Cetak Barcode</button>
                                </a>
                            </div>

                            <!-- End Date -->
                            <div class="col-xl-3 col-lg-6 col-sm-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <svg class="me-3" width="55" height="55" viewbox="0 0 55 55" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <circle cx="27.5" cy="27.5" r="27.5" fill="#886CC0"></circle>
                                        <path d="M37.2961 23.6858C37.1797 23.4406 36.9325 23.2843 36.661 23.2843H29.6088L33.8773 16.0608C34.0057 15.8435 34.0077 15.5738 33.8826 15.3546C33.7574 15.1354 33.5244 14.9999 33.2719 15L27.2468 15.0007C26.9968 15.0008 26.7656 15.1335 26.6396 15.3495L18.7318 28.905C18.6049 29.1224 18.604 29.3911 18.7294 29.6094C18.8548 29.8277 19.0873 29.9624 19.3391 29.9624H26.3464L24.3054 38.1263C24.2255 38.4457 24.3781 38.7779 24.6725 38.9255C24.7729 38.9757 24.8806 39 24.9872 39C25.1933 39 25.3952 38.9094 25.5324 38.7413L37.2058 24.4319C37.3774 24.2215 37.4126 23.931 37.2961 23.6858Z" fill="white"></path>
                                    </svg>
                                    <div>
                                        <small class="d-block fs-16 font-w400">End Date</small>
                                        <span class="fs-18 font-w500">{{ \Carbon\Carbon::parse($v_active->end_training)->format('d-m-Y') }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Status -->
                            <div class="col-xl-2 col-lg-6 col-sm-4 mb-3 text-end">
                                <div class="d-flex justify-content-end">
                                <span class="btn {{ $v_active->status_class }} fs-18 font-w600">{{ $v_active->status_label }}</span>
                                    <div class="dropdown ms-4">
                                        <div class="btn-link" data-bs-toggle="dropdown">
                                            <svg width="24" height="24" viewbox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M11 12C11 12.5523 11.4477 13 12 13C12.5523 13 13 12.5523 13 12C13 11.4477 12.5523 11 12 11C11.4477 11 11 11.4477 11 12Z" stroke="#737B8B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                                <path d="M18 12C18 12.5523 18.4477 13 19 13C19.5523 13 20 12.5523 20 12C20 11.4477 19.5523 11 19 11C18.4477 11 18 11.4477 18 12Z" stroke="#737B8B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                                <path d="M4 12C4 12.5523 4.44772 13 5 13C5.55228 13 6 12.5523 6 12C6 11.4477 5.55228 11 5 11C4.44772 11 4 11.4477 4 12Z" stroke="#737B8B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                            </svg>
                                        </div>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <a 
                                                class="dropdown-item" 
                                                href="javascript:void(0);" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editMemberModal" 
                                                data-id="{{ $v_active->id }}">
                                                Ubah Data
                                            </a>
                                            @if($v_active->show_button)
                                                <a class="dropdown-item" href="{{ $v_active->wa_link }}" target="_blank">
                                                    Hubungi Sekarang
                                                </a>
                                            @endif
                                            <a class="dropdown-item" href="javascript:void(0);" onclick="event.preventDefault(); document.getElementById('delete-form-{{ $v_active->id }}').submit();">Hapus</a>
                                            <form id="delete-form-{{ $v_active->id }}" action="{{ route('admin.members.destroy', $v_active->id) }}" method="POST" style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
                @endforeach
                <div class="progect-pagination d-flex justify-content-between align-items-center flex-wrap">
                    <h4 class="mb-3">
                        Showing {{ $active->firstItem() }} to {{ $active->lastItem() }} of {{ $active->total() }} data
                    </h4>
                    <ul class="pagination mb-3">
                        @if ($active->onFirstPage())
                            <li class="page-item page-indicator disabled">
                                <a class="page-link" href="javascript:void(0)">
                                    <i class="fas fa-angle-double-left me-2"></i>Previous
                                </a>
                            </li>
                        @else
                            <li class="page-item page-indicator">
                                <a class="page-link" href="{{ $active->previousPageUrl() }}">
                                    <i class="fas fa-angle-double-left me-2"></i>Previous
                                </a>
                            </li>
                        @endif

                        @foreach ($active->getUrlRange(1, $active->lastPage()) as $page => $url)
                            <li class="page-item {{ $page == $active->currentPage() ? 'active' : '' }}">
                                <a class="page-link {{ $page == $active->currentPage() ? 'active' : '' }}" href="{{ $url }}">{{ $page }}</a>
                            </li>
                        @endforeach

                        @if ($active->hasMorePages())
                            <li class="page-item page-indicator">
                                <a class="page-link" href="{{ $active->nextPageUrl() }}">
                                    Next<i class="fas fa-angle-double-right ms-2"></i>
                                </a>
                            </li>
                        @else
                            <li class="page-item page-indicator disabled">
                                <a class="page-link" href="javascript:void(0)">
                                    Next<i class="fas fa-angle-double-right ms-2"></i>
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>

<!-- Modal -->
<div class="modal fade" id="createMemberModal" tabindex="-1" aria-labelledby="createMemberModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createMemberModalLabel">Tambah Data Member</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="createMemberForm" action="{{ route('admin.members.add_smember') }}" method="POST">
                @csrf
                <input type="hidden" name="total_price" id="input_total_price">
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col">
                            <label class="form-label">Member</label>
                            <select class="form-control" name="idmember" id="idmember" required>
                                <option value="">-- Pilih Member --</option>
                                @foreach ($member as $m)
                                    <option value="{{ $m->id }}">{{ $m->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col">
                            <label class="form-label">Paket</label>
                            <select class="form-control" name="idpaket" id="idpaket" required onchange="calculateEndTraining()">
                                <option value="">-- Pilih Paket --</option>
                                @foreach ($paket as $p)
                                    <option value="{{ $p->id }}" data-days="{{ $p->days }}">{{ $p->packet_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="useTrainer" name="useTrainer">
                                <label class="form-check-label" for="useTrainer">Centang Disini Jika Gunakan Trainer</label>
                            </div>
                        </div>
                    </div>
                    <div id="trainerSection" style="display: none;">
                        <div class="row mb-3">
                            <div class="col">
                                <label class="form-label">Paket Trainer</label>
                                <select class="form-control" name="idpacket_trainer" id="idpacket_trainer">
                                    <option value="">-- Pilih Paket Trainer --</option>
                                    @foreach ($paket_trainer as $pt)
                                        <option value="{{ $pt->id }}" 
                                                data-price="{{ $pt->price }}" 
                                                data-point="{{ $pt->poin }}">
                                            {{ $pt->pertemuan }} Pertemuan
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col">
                                <label class="form-label">Biaya Trainer</label>
                                <input type="number" class="form-control" name="biaya" id="biaya" readonly>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label class="form-label">Poin</label>
                                <input type="number" class="form-control" name="point" id="point" readonly>
                            </div>
                            <div class="col">
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <label class="form-label">Mulai Latihan</label>
                            <input type="date" class="form-control" name="start_training" id="start_training" required onchange="calculateEndTraining()">
                        </div>
                        <div class="col">
                            <label class="form-label">Akhir Latihan</label>
                            <input type="date" class="form-control" name="end_training" id="end_training" required readonly>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="me-auto">
                        <label for="total_price" class="form-label fw-bold">Total Price: </label>
                        <span id="total_price" class="fw-bold">0</span>
                    </div>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editMemberModal" tabindex="-1" aria-labelledby="editMemberModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editMemberModalLabel">Edit Data Member</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editMemberForm" action="" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" id="edit_member_id">
                <input type="hidden" name="total_price" id="edit_input_total_price">
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col">
                            <label class="form-label">Member</label>
                            <select class="form-control" name="idmember" id="edit_idmember" required>
                                <option value="">-- Pilih Member --</option>
                                @foreach ($member as $m)
                                    <option value="{{ $m->id }}">{{ $m->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col">
                            <label class="form-label">Paket</label>
                            <select class="form-control" name="idpaket" id="edit_idpaket" required onchange="calculateEditEndTraining()">
                                <option value="">-- Pilih Paket --</option>
                                @foreach ($paket as $p)
                                    <option value="{{ $p->id }}" data-days="{{ $p->days }}">{{ $p->packet_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="edit_useTrainer" name="useTrainer">
                                <label class="form-check-label" for="edit_useTrainer">Centang Disini Jika Gunakan Trainer</label>
                            </div>
                        </div>
                    </div>
                    <div id="edit_trainerSection" style="display: none;">
                        <div class="row mb-3">
                            <div class="col">
                                <label class="form-label">Paket Trainer</label>
                                <select class="form-control" name="idpacket_trainer" id="edit_idpacket_trainer">
                                    <option value="">-- Pilih Paket Trainer --</option>
                                    @foreach ($paket_trainer as $pt)
                                        <option value="{{ $pt->id }}" 
                                                data-price="{{ $pt->price }}" 
                                                data-point="{{ $pt->poin }}">
                                            {{ $pt->pertemuan }} Pertemuan
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col">
                                <label class="form-label">Biaya Trainer</label>
                                <input type="number" class="form-control" name="biaya" id="edit_biaya" readonly>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label class="form-label">Poin</label>
                                <input type="number" class="form-control" name="point" id="edit_point" readonly>
                            </div>
                            <div class="col"></div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <label class="form-label">Mulai Latihan</label>
                            <input type="date" class="form-control" name="start_training" id="edit_start_training" required onchange="calculateEditEndTraining()">
                        </div>
                        <div class="col">
                            <label class="form-label">Akhir Latihan</label>
                            <input type="date" class="form-control" name="end_training" id="edit_end_training" required readonly>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="me-auto">
                        <label for="edit_total_price" class="form-label fw-bold">Total Price: </label>
                        <span id="edit_total_price" class="fw-bold">0</span>
                    </div>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const idPaketElement = document.getElementById('idpaket');
        const biayaElement = document.getElementById('biaya');
        const totalPriceElement = document.getElementById('total_price');
        const totalPriceInput = document.getElementById('input_total_price');
        const useTrainerCheckbox = document.getElementById('useTrainer');
        const trainerSection = document.getElementById('trainerSection');
        const packetTrainerSelect = document.getElementById('idpacket_trainer');
        const pointInput = document.getElementById('point');
        const startTrainingInput = document.getElementById('start_training');
        const endTrainingInput = document.getElementById('end_training');

        // Harga paket gym dari backend
        const paketPrices = @json($paket->pluck('price', 'id'));

        // Format nilai ke mata uang Rupiah
        function formatToRupiah(value) {
            return value.toLocaleString('id-ID', {
                style: 'currency',
                currency: 'IDR',
            });
        }

        // Hitung total harga (paket + biaya trainer)
        function calculateTotalPrice() {
            const paketPrice = parseFloat(paketPrices[idPaketElement.value] || 0);
            const trainerCost = parseFloat(biayaElement.value || 0);
            const totalPrice = paketPrice + trainerCost;

            // Update tampilan dan input tersembunyi
            totalPriceElement.textContent = formatToRupiah(totalPrice);
            totalPriceInput.value = totalPrice.toFixed(2); // Simpan dalam format angka
        }

        // Event: Ketika paket gym dipilih
        idPaketElement.addEventListener('change', calculateTotalPrice);

        // Event: Ketika biaya trainer diubah
        biayaElement.addEventListener('input', calculateTotalPrice);

        // Event: Ketika checkbox "Gunakan Trainer" berubah
        useTrainerCheckbox.addEventListener('change', function () {
            trainerSection.style.display = this.checked ? 'block' : 'none';

            if (!this.checked) {
                // Reset nilai jika tidak menggunakan trainer
                biayaElement.value = '';
                pointInput.value = '';
                packetTrainerSelect.value = '';
                calculateTotalPrice();
            }
        });

        // Event: Ketika paket trainer dipilih
        packetTrainerSelect.addEventListener('change', function () {
            const selectedOption = this.options[this.selectedIndex];
            const trainerPrice = parseFloat(selectedOption.getAttribute('data-price') || 0);
            const trainerPoint = selectedOption.getAttribute('data-point') || '';

            // Update input biaya dan poin
            biayaElement.value = trainerPrice;
            pointInput.value = trainerPoint;

            // Hitung ulang total harga
            calculateTotalPrice();
        });

        function calculateEndTraining() {
            const idPaketElement = document.getElementById('idpaket');
            const startTrainingInput = document.getElementById('start_training');
            const endTrainingInput = document.getElementById('end_training');
            
            // Ambil data durasi dari paket yang dipilih
            const paket = idPaketElement.options[idPaketElement.selectedIndex];
            const days = parseInt(paket.getAttribute('data-days')) || 0;
            const startDateValue = startTrainingInput.value;

            if (startDateValue) {
                const startDate = new Date(startDateValue);

                if (days > 0) {
                    // Jika ada durasi, tambahkan durasi ke tanggal mulai
                    startDate.setDate(startDate.getDate() + days);
                }

                // Set tanggal akhir yang sudah dihitung
                endTrainingInput.value = startDate.toISOString().split('T')[0]; // Format YYYY-MM-DD
            } else {
                // Reset tanggal akhir jika tanggal mulai tidak valid
                endTrainingInput.value = '';
            }
        }

        // Event listener untuk input tanggal mulai latihan
        startTrainingInput.addEventListener('change', calculateEndTraining);

        // Event listener untuk pilihan paket
        idPaketElement.addEventListener('change', calculateEndTraining);
    });
</script>
<script>
    document.querySelectorAll('.dropdown-item').forEach(item => {
        item.addEventListener('click', function () {
            const memberId = this.getAttribute('data-id');

            // Ambil URL dari route Laravel
            const fetchUrl = `{{ route('admin.members.editmember', ':id') }}`.replace(':id', memberId);

            // Fetch data dari server
            fetch(fetchUrl)
                .then(response => response.json())
                .then(data => {
                    // Isi form modal dengan data
                    document.getElementById('edit_member_id').value = data.id;
                    document.getElementById('edit_idmember').value = data.idmember;
                    document.getElementById('edit_idpaket').value = data.idpaket;
                    document.getElementById('edit_start_training').value = data.start_training;
                    document.getElementById('edit_end_training').value = data.end_training;

                    // Centang trainer jika perlu
                    const useTrainer = document.getElementById('edit_useTrainer');
                    useTrainer.checked = data.use_trainer;
                    document.getElementById('edit_trainerSection').style.display = data.use_trainer ? 'block' : 'none';

                    // Isi data trainer jika ada
                    document.getElementById('edit_idpacket_trainer').value = data.idpacket_trainer;
                    document.getElementById('edit_biaya').value = data.biaya || 0;
                    document.getElementById('edit_point').value = data.point || 0;
                })
                .catch(error => console.error('Error:', error));
        });
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
    const editIdPaketElement = document.getElementById('edit_idpaket');
    const editBiayaElement = document.getElementById('edit_biaya');
    const editTotalPriceElement = document.getElementById('edit_total_price');
    const editTotalPriceInput = document.getElementById('edit_input_total_price');
    const editUseTrainerCheckbox = document.getElementById('edit_useTrainer');
    const editTrainerSection = document.getElementById('edit_trainerSection');
    const editPacketTrainerSelect = document.getElementById('edit_idpacket_trainer');
    const editPointInput = document.getElementById('edit_point');
    const editStartTrainingInput = document.getElementById('edit_start_training');
    const editEndTrainingInput = document.getElementById('edit_end_training');

    // Harga paket gym dari backend
    const paketPrices = @json($paket->pluck('price', 'id'));
    console.log('data harga paket:', paketPrices);

    // Format nilai ke mata uang Rupiah
    function formatToRupiah(value) {
        return value.toLocaleString('id-ID', {
            style: 'currency',
            currency: 'IDR',
        });
    }

    // Hitung total harga (paket + biaya trainer)
    function calculateEditTotalPrice() {
        const paketPrice = parseFloat(paketPrices[editIdPaketElement.value] || 0);
        const trainerCost = parseFloat(editBiayaElement.value || 0);
        const totalPrice = paketPrice + trainerCost;

        // Update tampilan dan input tersembunyi
        editTotalPriceElement.textContent = formatToRupiah(totalPrice);
        editTotalPriceInput.value = totalPrice.toFixed(2); // Simpan dalam format angka
    }

    // Event: Ketika paket gym dipilih
    editIdPaketElement.addEventListener('change', calculateEditTotalPrice);

    // Event: Ketika biaya trainer diubah
    editBiayaElement.addEventListener('input', calculateEditTotalPrice);

    // Event: Ketika checkbox "Gunakan Trainer" berubah
    editUseTrainerCheckbox.addEventListener('change', function () {
        editTrainerSection.style.display = this.checked ? 'block' : 'none';

        if (!this.checked) {
            // Reset nilai jika tidak menggunakan trainer
            editBiayaElement.value = '';
            editPointInput.value = '';
            editPacketTrainerSelect.value = '';
            calculateEditTotalPrice();
        }
    });

    // Event: Ketika paket trainer dipilih
    editPacketTrainerSelect.addEventListener('change', function () {
        const selectedOption = this.options[this.selectedIndex];
        const trainerPrice = parseFloat(selectedOption.getAttribute('data-price') || 0);
        const trainerPoint = selectedOption.getAttribute('data-point') || '';

        // Update input biaya dan poin
        editBiayaElement.value = trainerPrice;
        editPointInput.value = trainerPoint;

        // Hitung ulang total harga
        calculateEditTotalPrice();
    });

    // Hitung tanggal akhir latihan berdasarkan paket yang dipilih dan tanggal mulai
    function calculateEditEndTraining() {
        const paket = editIdPaketElement.options[editIdPaketElement.selectedIndex];
        const days = parseInt(paket.getAttribute('data-days')) || 0;
        const startDateValue = editStartTrainingInput.value;

        if (startDateValue) {
            const startDate = new Date(startDateValue);

            if (days > 0) {
                // Jika ada durasi, tambahkan durasi ke tanggal mulai
                startDate.setDate(startDate.getDate() + days);
            }

            // Set tanggal akhir yang sudah dihitung
            editEndTrainingInput.value = startDate.toISOString().split('T')[0]; // Format YYYY-MM-DD
        } else {
            // Reset tanggal akhir jika tanggal mulai tidak valid
            editEndTrainingInput.value = '';
        }
    }

    // Event listener untuk input tanggal mulai latihan
    editStartTrainingInput.addEventListener('change', calculateEditEndTraining);

    // Event listener untuk pilihan paket
    editIdPaketElement.addEventListener('change', calculateEditEndTraining);

    // Ambil data member ketika modal dibuka
    const editMemberModal = document.getElementById('editMemberModal');
    editMemberModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget; // Tombol yang memicu modal
        const memberId = button.getAttribute('data-id'); // Ambil ID member dari tombol

        const fetchUrl = `{{ route('admin.members.editmember', ':id') }}`.replace(':id', memberId);

        fetch(fetchUrl)
            .then(response => response.json())
            .then(data => {
                console.log('Data Member:', data);

                // Isi data ke dalam form modal
                editIdPaketElement.value = data.idpaket;
                editBiayaElement.value = data.biaya_trainer || '';
                editUseTrainerCheckbox.checked = data.idpacket_trainer;

                // Tampilkan/matikan seksi trainer berdasarkan data
                editTrainerSection.style.display = data.idpacket_trainer ? 'block' : 'none';

                if (data.idpacket_trainer) {
                    editUseTrainerCheckbox.checked = true;
                    editTrainerSection.style.display = 'block';
                } else {
                    editUseTrainerCheckbox.checked = false;
                    editTrainerSection.style.display = 'none';
                }

                if (data.start_training) {
                    const startDate = new Date(data.start_training); // Format: YYYY-MM-DD HH:MM:SS
                    const formattedStartDate = startDate.toISOString().slice(0, 19).replace('T', ' ');
                    editStartTrainingInput.value = formattedStartDate.split(' ')[0]; // Ambil YYYY-MM-DD
                }

                if (data.end_training) {
                    const endDate = new Date(data.end_training); // Format: YYYY-MM-DD HH:MM:SS
                    const formattedEndDate = endDate.toISOString().slice(0, 19).replace('T', ' ');
                    editEndTrainingInput.value = formattedEndDate.split(' ')[0]; // Ambil YYYY-MM-DD
                }

                if (data.idpacket_trainer) {
                    editPacketTrainerSelect.value = data.idpacket_trainer;
                    const selectedOption = editPacketTrainerSelect.options[editPacketTrainerSelect.selectedIndex];
                    editBiayaElement.value = selectedOption.getAttribute('data-price') || '';
                    editPointInput.value = selectedOption.getAttribute('data-point') || '';
                }

                // Hitung ulang total harga
                calculateEditTotalPrice();
                calculateEditEndTraining();
            })
            .catch(error => {
                console.error('Error fetching member data:', error);
            });
    });
});
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const editMemberModal = document.getElementById('editMemberModal');
        const editMemberForm = document.getElementById('editMemberForm');

        // Event: Ketika modal dibuka
        editMemberModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget; // Tombol yang memicu modal
            const memberId = button.getAttribute('data-id'); // Ambil ID member dari tombol

            // Update action pada form
            const formActionUrl = `{{ route('admin.members.update', ':id') }}`.replace(':id', memberId);
            editMemberForm.action = formActionUrl;

            // Ambil data member seperti sebelumnya
            const fetchUrl = `{{ route('admin.members.editmember', ':id') }}`.replace(':id', memberId);
            fetch(fetchUrl)
                .then(response => response.json())
                .then(data => {
                    console.log('Data Member:', data);

                    // Isi input form dengan data member
                    document.getElementById('edit_idpaket').value = data.idpaket;
                    document.getElementById('edit_start_training').value = data.start_training;
                    document.getElementById('edit_end_training').value = data.end_training;
                    document.getElementById('edit_biaya').value = data.biaya_trainer || '';
                    document.getElementById('edit_useTrainer').checked = data.use_trainer;

                    // Tampilkan atau sembunyikan bagian trainer
                    const trainerSection = document.getElementById('edit_trainerSection');
                    trainerSection.style.display = data.use_trainer ? 'block' : 'none';
                })
                .catch(error => {
                    console.error('Error fetching member data:', error);
                });
        });
    });
</script>
@include('admin.layout.footer')