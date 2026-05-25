@extends('layouts.admin')

@push('addon-style')
    <link href="{{ url('backend/vendor/datepicker/css/bootstrap-datepicker3.min.css') }}" rel="stylesheet">
@endpush

@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-0">
            <h1 class="h3 mb-0 text-gray-800 menu-title">Faktur Toko</h1>
        </div>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="row">
            <div class="card-body">
                <div class="table-responsive">
                    <div class="card show">
                        <div class="card-body">
                            <form action="" method="">
                                @csrf
                                <div class="container so-container" style="margin-bottom: -20px">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group row">
                                                <label for="kode" class="col-2 col-form-label text-bold text-right">Nomor SO</label>
                                                <span class="col-form-label text-bold">:</span>
                                                <div class="col-2">
                                                    <input type="text" tabindex="1" class="form-control form-control-sm text-bold mt-1" name="kode" value="{{ $newNumber }}" readonly>
                                                </div>
                                                <label for="tanggal" class="col-2 col-form-label text-bold text-right">Tanggal SO</label>
                                                <span class="col-form-label text-bold">:</span>
                                                <div class="col-2">
                                                    <input type="text" tabindex="2" class="form-control datepicker form-control-sm text-bold mt-1" name="tanggal" value="{{ $tanggal }}" required>
                                                    <input type="hidden" name="jumBaris" id="jumBaris" value="5">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <span class="table-add float-right mb-3 mr-2"><a href="#!" tabindex="-1" class="text-primary text-bold">
                                    Tambah Baris <i class="fas fa-plus fa-lg ml-2" aria-hidden="true"></i></a>
                                </span>
                                <table class="table table-sm table-bordered table-striped table-responsive-sm table-hover" >
                                    <thead class="text-center text-bold text-dark">
                                    <tr>
                                        <td rowspan="2" style="width: 50px" class="align-middle">No</td>
                                        <td rowspan="2" style="width: 130px" class="align-middle">Kode Barang</td>
                                        <td rowspan="2" class="align-middle">Nama Barang</td>
                                        <td colspan="2" style="width: 130px" class="align-middle">Qty</td>
                                        <td rowspan="2" style="width: 120px" class="align-middle">Harga</td>
                                        <td rowspan="2" style="width: 130px" class="align-middle">Jumlah</td>
                                        <td rowspan="2" style="width: 50px" class="align-middle">Hapus</td>
                                    </tr>
                                    <tr>
                                        <td style="width: 70px" id="pcs">Pcs</td>
                                        <td style="width: 60px" id="satuanUkuran">Dus</td>
                                    </tr>
                                    </thead>
                                    <tbody id="tablePO">
                                    @php $tab = 6; @endphp
                                    @for($i = 1; $i <= 5; $i++)
                                        <tr class="text-bold text-dark" id="{{ $i }}">
                                            <td align="center" class="align-middle">{{ $i }}</td>
                                            <td>
                                                <input type="text" tabindex="{{ $tab++ }}" name="kodeBarang[]" id="kodeBarang" class="form-control form-control-sm text-bold text-dark kodeBarang"
                                                       value="{{ old('kodeBarang[]') }}" @if($i == 1) required @endif >
                                            </td>
                                            <td>
                                                <input type="text" tabindex="{{ $tab += 2 }}" name="namaBarang[]" id="namaBarang" class="form-control form-control-sm text-bold text-dark namaBarang"
                                                       value="{{ old('namaBarang[]') }}" @if($i == 1) required @endif>
                                            </td>
                                            <td>
                                                <input type="text" tabindex="{{ $tab += 3 }}" name="qty[]" id="qty" class="form-control form-control-sm text-bold text-dark text-right qty"
                                                       value="{{ old('qty[]') }}" onkeypress="return angkaSaja(event, {{$i}}, 'qty')" data-toogle="tooltip" data-placement="bottom" title="Hanya input angka 0-9" autocomplete="off">
                                                <input type="hidden" name="teksSat[]" class="teksSat">
                                                <input type="hidden" name="teksSatUk[]" class="teksSatUk">
                                                <input type="hidden" name="ukuran[]" class="ukuran">
                                                <input type="hidden" name="kodeGudang[]" class="kodeGudang">
                                                <input type="hidden" name="qtyGudang[]" class="qtyGudang">
                                            </td>
                                            <td>
                                                <input type="text" tabindex="{{ $tab += 4 }}" name="satuan[]" id="satuan" class="form-control form-control-sm text-bold text-dark text-right satuan"
                                                   value="{{ old('satuan[]') }}" onkeypress="return angkaSaja(event, {{$i}}, 'sat')" data-toogle="tooltip" data-placement="bottom" title="Hanya input angka 0-9" autocomplete="off">
                                            </td>
                                            <td>
                                                <input type="text" name="harga[]" id="harga" readonly class="form-control form-control-sm text-bold text-dark text-right harga" value="{{ old('harga[]') }}" onkeypress="return angkaSajaHarga(event, {{$i}})" data-toogle="tooltip" data-placement="bottom" title="Hanya input angka 0-9" autocomplete="off">
                                            </td>
                                            <td>
                                                <input type="text" name="jumlah[]" id="jumlah" readonly class="form-control-plaintext form-control-sm text-bold text-dark text-right jumlah" value="{{ old('jumlah[]') }}" >
                                            </td>
                                            <td align="center" class="align-middle">
                                                <a href="#" class="icRemove">
                                                    <i class="fas fa-fw fa-times fa-lg ic-remove mt-1"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <div class="modal" id="notif{{$i-1}}" tabindex="-1" role="dialog" aria-labelledby="modalKonfirm" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true" class="h2 text-bold">&times;</span>
                                                        </button>
                                                        <h4 class="modal-title text-bold">Notifikasi Stok Barang</h4>
                                                    </div>
                                                    <div class="modal-body text-dark">
                                                        <h5>Qty input tidak bisa melebihi total stok. Total stok <b>Kenari</b> untuk barang <span class="col-form-label text-bold nmbrg"></span> adalah <span class="col-form-label text-bold totalstok"></span><span class="col-form-label text-bold totalsatuan"></span></h5>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endfor
                                    </tbody>
                                </table>
                                <div class="form-group row justify-content-end subtotal-so so-info-total">
                                    <label for="subtotal" class="col-3 col-form-label text-bold text-right text-dark">Grand Total</label>
                                    <span class="col-form-label text-bold">:</span>
                                    <span class="col-form-label text-bold ml-2">Rp</span>
                                    <div class="col-2">
                                        <input type="text" name="subtotal" id="subtotal" readonly class="form-control-plaintext form-control-sm text-bold text-secondary text-right mt-1" />
                                    </div>
                                </div>
                                <hr>
                                <div class="form-row justify-content-center">
                                    <div class="col-2">
                                        <button type="submit" tabindex="{{ $tab++ }}" class="btn btn-success btn-block text-bold" onclick="return checkRequired(event)" id="submitSO" >Submit</button>
                                    </div>
                                    <div class="col-2">
                                        <button type="reset" tabindex="{{ $tab += 2 }}" class="btn btn-outline-secondary btn-block text-bold" id="resetSO">Reset</button>
                                    </div>
                                </div>
                                <div class="modal" id="modalKonfirm" tabindex="-1" role="dialog" aria-labelledby="modalKonfirm" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true" class="h2 text-bold">&times;</span>
                                                </button>
                                                <h4 class="modal-title">Konfirmasi Faktur <b>{{$newNumber}}</b></h4>
                                            </div>
                                            <div class="modal-body">
                                                <p>Faktur <strong>{{$newNumber}}</strong> akan disimpan. Silahkan klik submit atau batal jika ada yg masih ingin diubah.</p>
                                                <hr>
                                                <div class="form-row justify-content-center">
                                                    <div class="col-3">
                                                        <button type="submit" formaction="{{ route('so-process-cianjur', ['id' => $newNumber]) }}" formmethod="POST" class="btn btn-success btn-block text-bold btnCetak">Submit</button>
                                                    </div>
                                                    <div class="col-3">
                                                        <button type="button" data-dismiss="modal" class="btn btn-outline-secondary btn-block text-bold">Batal</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal" id="modalDuplikat" tabindex="-1" role="dialog" aria-labelledby="modalNotif" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true" class="h2 text-bold">&times;</span>
                                                </button>
                                                <h4 class="modal-title text-bold">Notifikasi Barang</h4>
                                            </div>
                                            <div class="modal-body text-dark">
                                                <h5>Terdapat <b>Kode Barang</b> yang sama. Silahkan <b>Jumlahkan Qty pada Kode Barang yang Sama </b>atau ubah kode barang.</h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('addon-script')
    <script src="{{ url('backend/vendor/datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <script type="text/javascript">
        $.fn.datepicker.dates['id'] = {
            days:["Minggu","Senin","Selasa","Rabu","Kamis","Jumat","Sabtu"],
            daysShort:["Mgu","Sen","Sel","Rab","Kam","Jum","Sab"],
            daysMin:["Min","Sen","Sel","Rab","Kam","Jum","Sab"],
            months:["Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"],
            monthsShort:["Jan","Feb","Mar","Apr","Mei","Jun","Jul","Ags","Sep","Okt","Nov","Des"],
            today:"Hari Ini",
            clear:"Kosongkan"
        };

        $('.datepicker').datepicker({
            format: 'dd-mm-yyyy',
            autoclose: true,
            todayHighlight: true,
            language: 'id',
        });

        const pcs = document.getElementById("pcs");
        const satuanUkuran = document.getElementById("satuanUkuran");
        const kodeBarang = document.querySelectorAll('.kodeBarang');
        const brgNama = document.querySelectorAll(".namaBarang");
        const qty = document.querySelectorAll(".qty");
        const teksSat = document.querySelectorAll(".teksSat");
        const teksSatUk = document.querySelectorAll(".teksSatUk");
        const ukuran = document.querySelectorAll(".ukuran");
        const satuan = document.querySelectorAll(".satuan");
        const kodeGudang = document.querySelectorAll(".kodeGudang");
        const qtyGudang = document.querySelectorAll(".qtyGudang");
        const harga = document.querySelectorAll(".harga");
        const jumlah = document.querySelectorAll(".jumlah");
        const hapusBaris = document.querySelectorAll(".icRemove");
        const subtotal = document.getElementById('subtotal');
        const newRow = document.getElementsByClassName('table-add')[0];
        const jumBaris = document.getElementById('jumBaris');
        const totalstok = document.querySelectorAll(".totalstok");
        const totalsatuan = document.querySelectorAll(".totalsatuan");
        const nmbrg = document.querySelectorAll(".nmbrg");
        const totalSO = document.getElementById('totalSO');
        var netPast; var tab = '{{ $tab }}'; var tempTempo = '';
        var kodeModal; var totPast;
        var sisa; var stokJohar; var stokLain; var totStok;

        newRow.addEventListener("click", displayRow);

        function displayRow(e) {
            const lastRow = $(tablePO).find('tr:last').attr("id");
            const lastNo = $(tablePO).find('tr:last td:first-child').text();
            var newNum = +lastRow + 1;
            var newNo = +lastNo + 1;
            
            const newTr = `
                <tr class="text-bold text-dark" id="${newNum}">
                  <td align="center" class="align-middle nomor">${newNo}</td>
                  <td>
                    <input type="text" tabindex="${tab++}" name="kodeBarang[]" id="kdBrgRow${newNum}" class="form-control form-control-sm text-bold text-dark kdBrgRow">
                  </td>
                  <td>
                    <input type="text" tabindex="${tab += 2}" name="namaBarang[]" id="nmBrgRow${newNum}" class="form-control form-control-sm text-bold text-dark nmBrgRow">
                  </td>
                  <td>
                    <input type="text" tabindex="${tab += 3}" name="qty[]" id="qtyRow${newNum}" class="form-control form-control-sm text-bold text-dark text-right qtyRow" value="{{ old('qty[]') }}" data-toogle="tooltip" data-placement="bottom" title="Hanya input angka 0-9" autocomplete="off">
                    <input type="hidden" name="teksSat[]" class="teksSatRow" id="teksSatRow${newNum}">
                    <input type="hidden" name="kodeGudang[]" class="kodeGudangRow" id="kodeGudangRow${newNum}">
                    <input type="hidden" name="qtyGudang[]" class="qtyGudangRow" id="qtyGudangRow${newNum}">
                  <td>
                    <input type="text" tabindex="${tab += 4}" name="satuan[]" id="satuanRow${newNum}" class="form-control form-control-sm text-bold text-dark text-right satuanRow"
                    value="{{ old('satuan[]') }}" data-toogle="tooltip" data-placement="bottom" title="Hanya input angka 0-9"
                    autocomplete="off">
                  </td>
                  <td>
                    <input type="text" name="harga[]" id="hargaRow${newNum}" readonly class="form-control form-control-sm text-bold text-dark text-right hargaRow" onkeypress="return angkaSajaHarga(event, {{$i}})" data-toogle="tooltip" data-placement="bottom" title="Hanya input angka 0-9" autocomplete="off">
                  </td>
                  <td>
                    <input type="text" name="jumlah[]" id="jumlahRow${newNum}" readonly class="form-control-plaintext form-control-sm text-bold text-dark text-right jumlahRow">
                  </td>
                  <td align="center" class="align-middle">
                    <a href="#" class="icRemoveRow" id="icRemoveRow${newNum}">
                      <i class="fas fa-fw fa-times fa-lg ic-remove mt-1"></i>
                    </a>
                  </td>
                </tr>
              `;

            const newModal = `
                <div class="modal" id="notif${newNum}" tabindex="-1" role="dialog" aria-labelledby="modalKonfirm" aria-hidden="true">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true" class="h2 text-bold">&times;</span>
                        </button>
                        <h4 class="modal-title text-bold">Notifikasi Stok Barang</h4>
                      </div>
                      <div class="modal-body text-dark">
                        <h5>Qty input tidak bisa melebihi total stok. Total stok <b>Kenari</b> untuk barang <span class="col-form-label text-bold nmbrgRow" id="nmbrgRow${newNum}"></span> adalah <span class="col-form-label text-bold totalstokRow" id="totalstokRow${newNum}"></span><span class="col-form-label text-bold totalsatuanRow" id="totalsatuanRow${newNum}"></span></h5>
                      </div>
                    </div>
                  </div>
                </div>
              `;

            $(tablePO).append(newTr);
            $(tablePO).append(newModal);
            jumBaris.value = +jumBaris.value + 1;
            const newRow = document.getElementById(newNum);
            const newMod = document.getElementById("gud"+newNum);
            const newModNotif = document.getElementById("notif"+newNum);
            const brgRow = document.getElementById("nmBrgRow"+newNum);
            const kodeRow = document.getElementById("kdBrgRow"+newNum);
            const qtyRow = document.getElementById("qtyRow"+newNum);
            const teksSatRow = document.getElementById("teksSatRow"+newNum);
            const satuanRow = document.getElementById("satuanRow"+newNum);
            const kodeGudangRow = document.getElementById("kodeGudangRow"+newNum);
            const qtyGudangRow = document.getElementById("qtyGudangRow"+newNum);
            const hargaRow = document.getElementById("hargaRow"+newNum);
            const jumlahRow = document.getElementById("jumlahRow"+newNum);
            const hapusRow = document.getElementById("icRemoveRow"+newNum);
            const totalstokRow = document.getElementById("totalstokRow"+newNum);
            const totalsatuanRow = document.getElementById("totalsatuanRow"+newNum);
            const nmbrgRow = document.getElementById("nmbrgRow"+newNum);
            var ukuranRow;
            kodeRow.focus();
            document.getElementById("submitSO").tabIndex = tab++;
            document.getElementById("resetSO").tabIndex = tab++;

            brgRow.addEventListener("keyup", displayHargaRow);
            kodeRow.addEventListener("keyup", displayHargaRow);
            brgRow.addEventListener("blur", displayHargaRow);
            kodeRow.addEventListener("blur", displayHargaRow);

            brgRow.addEventListener("change", resetQtyRow);
            kodeRow.addEventListener("change", resetQtyRow);

            function displayHargaRow(e) {
                if(e.target.value == "") {
                    subtotal.value = addCommas(+subtotal.value.replace(/\./g, "") - +jumlahRow.value.replace(/\./g, ""));

                    $(this).parents('tr').find('input').val('');
                    qtyRow.removeAttribute('required');
                }

                @foreach($barang as $br)
                if(('{{ $br->nama }}' == e.target.value) || ('{{ $br->id }}' == e.target.value)) {
                    kodeRow.value = '{{ $br->id }}';
                    brgRow.value = '{{ $br->nama }}';
                    satuanUkuran.innerHTML = '{{ substr($br->satuan, -3) }}';
                    if(satuanUkuran.innerHTML == 'Dus') {
                        pcs.innerHTML = 'Pcs';
                        teksSatRow.value = 'Pcs';
                        satuanRow.removeAttribute('readonly');
                    }
                    else if(satuanUkuran.innerHTML == 'Rol') {
                        pcs.innerHTML = 'Rol';
                        teksSatRow.value = 'Rol';
                        satuanUkuran.innerHTML = 'Meter';
                        satuanRow.value = '{{ $br->ukuran }}';
                        satuanRow.setAttribute('readonly', 'true');
                    }
                    else if(satuanUkuran.innerHTML == 'Set') {
                        pcs.innerHTML = 'Set';
                        satuanUkuran.innerHTML = 'Dus';
                        teksSatUkRow.value = 'Dus';
                        teksSatRow.value = 'Set';
                    }
                    else {
                        pcs.innerHTML = 'Meter';
                        teksSatRow.value = 'Meter';
                        satuanUkuran.innerHTML = '';
                        satuanRow.value = '{{ $br->ukuran }}';
                        satuanRow.setAttribute('readonly', 'true');
                    }
                    ukuranRow = '{{ $br->ukuran }}';

                    if(('{{ $br->jenis->nama == 'NITTO' }}') || ('{{ $br->jenis->nama == 'BOSS' }}') || ('{{ $br->jenis->nama == 'POWERLINDO' }}')) {
                        hargaRow.removeAttribute('readonly');
                    } else {
                        hargaRow.setAttribute('readonly', 'true');
                    }
                }
                @endforeach

                @foreach($harga as $hb)
                    if(('{{ $hb->id_barang }}' == kodeRow.value) && ('{{ $hb->id_harga }}' == 'HRG01')) {
                        hargaRow.value = addCommas('{{ $hb->harga_ppn }}');
                        qtyRow.setAttribute('required', true);
                    }
                @endforeach
            }

            function resetQtyRow(e) {
                kodeGudangRow.value = '{{ $gudang[0]->id }}';
                qtyGudangRow.value = '';
                qtyRow.value = '';
                satuanRow.value = '';
            }

            hargaRow.addEventListener("keyup", displayHargaCustomRow);
            hargaRow.addEventListener("blur", displayHargaCustomRow);

            function displayHargaCustomRow(e) {
                $(this).val(function(index, value) {
                    return value.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                });

                netPast = +jumlahRow.value.replace(/\./g, "");
                jumlahRow.value = addCommas(hargaRow.value.replace(/\./g, "") * qtyRow.value);

                checkSubtotal(netPast, +jumlahRow.value.replace(/\./g, ""));
            }

            qtyRow.addEventListener("keypress", function (e, evt) {
                evt = (evt) ? evt : window.event;
                var charCodeRow = (evt.which) ? evt.which : evt.keyCode;
                if (charCodeRow > 31 && (charCodeRow < 48 || charCodeRow > 57)) {
                    $(qtyRow).tooltip('show');
                    e.preventDefault();
                }

                return true;
            });

            satuanRow.addEventListener("keypress", function (e, evt) {
                evt = (evt) ? evt : window.event;
                var charCodeRow = (evt.which) ? evt.which : evt.keyCode;
                if (charCodeRow > 31 && (charCodeRow < 48 || charCodeRow > 57)) {
                    $(satuanRow).tooltip('show');
                    e.preventDefault();
                }

                return true;
            });

            qtyRow.addEventListener("blur", displayQtyRow);
            if(teksSatRow.value == 'Pcs')
                satuanRow.addEventListener("blur", displayQtyRow);
            else
                satuanRow.addEventListener("change", displayQtyRow);

            function displayQtyRow(e) {
                stokJohar = 0;
                stokLain = [];
                totStok = 0;
                @foreach($stok as $s)
                if(('{{ $s->id_barang }}' == kodeRow.value) && ('{{ $s->gudang->tipe }}' == 'TOKO'))
                    totStok = '{{ $s->stok }}';

                @endforeach

                hitungQtyRow(e.target.id, e.target.value, teksSatRow.value, ukuranRow);

                if(e.target.value == "") {
                    subtotal.value = addCommas(+subtotal.value.replace(/\./g, "") - +jumlahRow.value.replace(/\./g, ""));
                    jumlahRow.value = "";
                    kodeGudangRow.value = '{{ $gudang[0]->id }}';
                    qtyGudangRow.value = "";
                    qtyRow.value = "";
                    satuanRow.value = "";
                }
                else if(((e.target.id == `qtyRow${newNum}`) && (+e.target.value > totStok)) || ((e.target.id == `satuanRow${newNum}`) && (teksSatRow.value == 'Pcs') && (+e.target.value * +ukuranRow) > totStok)) {
                    $('#notif'+newNum).modal("show");
                    nmbrgRow.textContent = brgRow.value;
                    totalstokRow.textContent = `${totStok} ${pcs.innerHTML}`;
                    if(teksSatRow.value == 'Pcs')
                        totalsatuanRow.textContent = ` atau ${totStok / ukuranRow} ${satuanUkuran.innerHTML}`;
                    else
                        totalsatuanRow.textContent = ``;

                    checkSubtotal(+jumlahRow.value.replace(/\./g, ""), 0);

                    qtyRow.value = "";
                    satuanRow.value = "";
                    jumlahRow.value = "";

                    return false;
                }
                else {
                    kodeGudangRow.value = '{{ $gudang[0]->id }}';
                    qtyGudangRow.value = e.target.value;

                    netPast = +jumlahRow.value.replace(/\./g, "");
                    jumlahRow.value = addCommas(qtyRow.value * hargaRow.value.replace(/\./g, ""));

                    checkSubtotal(netPast, +jumlahRow.value.replace(/\./g, ""));
                }
            }

            function hitungQtyRow(kode, angka, teks, ukuran) {
                if(kode == `qtyRow${newNum}`) {
                    if((teks == 'Pcs') || (teks == 'Set'))
                        satuanRow.value = +angka / +ukuran;
                    else if(teks == 'Rol')
                        satuanRow.value = +angka * +ukuran;
                }
                else if(kode == `satuanRow${newNum}`)
                    qtyRow.value = +angka * +ukuran;
            }

            /** Delete Table Row **/
            hapusRow.addEventListener("click", function (e) {
                if(qtyRow.value != "") {
                    subtotal.value = addCommas(+subtotal.value.replace(/\./g, "") - +jumlahRow.value.replace(/\./g, ""));
                }

                const curNum = $(this).closest('tr').find('td:first-child').text();
                const lastNum = $(tablePO).find('tr:last td:first-child').text();
                var numRow;
                if(+curNum < +lastNum) {
                    $(newRow).remove();
                    $(newMod).remove();
                    $(newModNotif).remove();
                    var j = curNum;
                    var selisih = +lastNum - +curNum;
                    var nomor = document.querySelectorAll('.nomor');
                    for(let i = +curNum; i < +lastNum; i++) {
                        nomor[i-6].innerHTML = i;
                    }
                    numRow = lastNum;
                }
                else if(+curNum == +lastNum) {
                    $(newRow).remove();
                    numRow = +curNum - 1;
                }
                jumBaris.value -= 1;
                var kdBrg = document.querySelectorAll('.kdBrgRow');
                if(jumBaris.value > 5)
                    kdBrg[lastNum-7].focus();
                else
                    kodeBarang[4].focus();
            })

            /** Autocomplete Nama  Barang **/
            $(function() {
                var idBarang = [];
                var nmBarang = [];
                @foreach($barang as $b)
                idBarang.push('{{ $b->id }}');
                nmBarang.push('{{ $b->nama }}');
                @endforeach

                function split(val) {
                    return val.split(/,\s/);
                }

                function extractLast(term) {
                    return split(term).pop();
                }

                $(kodeRow).on("keydown", function(event) {
                    if(event.keyCode === $.ui.keyCode.TAB && $(this).autocomplete("instance").menu.active) {
                        event.preventDefault();
                    }
                })
                    .autocomplete({
                        minLength: 0,
                        source: function(request, response) {
                            response($.ui.autocomplete.filter(idBarang, extractLast(request.term)));
                        },
                        focus: function() {
                            return false;
                        },
                        select: function(event, ui) {
                            var terms = split(this.value);
                            terms.pop();
                            terms.push(ui.item.value);
                            terms.push("");
                            this.value = terms.join("");
                            return false;
                        }
                    });

                $(brgRow).on("keydown", function(event) {
                    if(event.keyCode === $.ui.keyCode.TAB && $(this).autocomplete("instance").menu.active) {
                        event.preventDefault();
                    }
                })
                    .autocomplete({
                        minLength: 0,
                        source: function(request, response) {
                            response($.ui.autocomplete.filter(nmBarang, extractLast(request.term)));
                        },
                        focus: function() {
                            return false;
                        },
                        select: function(event, ui) {
                            var terms = split(this.value);
                            terms.pop();
                            terms.push(ui.item.value);
                            terms.push("");
                            this.value = terms.join("");
                            return false;
                        }
                    });
            });
        }

        for(let i = 0; i < brgNama.length; i++) {
            brgNama[i].addEventListener("keyup", displayHarga) ;
            kodeBarang[i].addEventListener("keyup", displayHarga);
            brgNama[i].addEventListener("blur", displayHarga) ;
            kodeBarang[i].addEventListener("blur", displayHarga);

            brgNama[i].addEventListener("change", resetQty) ;
            kodeBarang[i].addEventListener("change", resetQty);

            function displayHarga(e) {
                satuan[i].removeAttribute('readonly');

                if(e.target.value == "") {
                    subtotal.value = addCommas(+subtotal.value.replace(/\./g, "") - +jumlah[i].value.replace(/\./g, ""));
                    $(this).parents('tr').find('input').val('');
                    qty[i].removeAttribute('required');
                }

                @foreach($barang as $br)
                if(('{{ $br->nama }}' == e.target.value) || ('{{ $br->id }}' == e.target.value)) {
                    kodeBarang[i].value = '{{ $br->id }}';
                    brgNama[i].value = '{{ $br->nama }}';
                    satuanUkuran.innerHTML = '{{ substr($br->satuan, -3) }}';
                    if(satuanUkuran.innerHTML == 'Dus') {
                        pcs.innerHTML = 'Pcs';
                        teksSatUk[i].value = 'Dus';
                        teksSat[i].value = 'Pcs';
                    }
                    else if(satuanUkuran.innerHTML == 'Rol') {
                        pcs.innerHTML = 'Rol';
                        teksSat[i].value = 'Rol';
                        teksSatUk[i].value = 'Meter';
                        satuanUkuran.innerHTML = 'Meter';
                        satuan[i].value = '{{ $br->ukuran }}';
                        satuan[i].setAttribute('readonly', 'true');
                    }
                    else if(satuanUkuran.innerHTML == 'Set') {
                        pcs.innerHTML = 'Set';
                        satuanUkuran.innerHTML = 'Dus';
                        teksSatUk[i].value = 'Dus';
                        teksSat[i].value = 'Set';
                    }
                    else {
                        pcs.innerHTML = 'Meter';
                        teksSat[i].value = 'Meter';
                        teksSatUk[i].value = '';
                        satuanUkuran.innerHTML = '';
                        satuan[i].value = '{{ $br->ukuran }}';
                        satuan[i].setAttribute('readonly', 'true');
                    }
                    ukuran[i].value = '{{ $br->ukuran }}';

                    if(('{{ $br->jenis->nama == 'NITTO' }}') || ('{{ $br->jenis->nama == 'BOSS' }}') || ('{{ $br->jenis->nama == 'POWERLINDO' }}')) {
                        harga[i].removeAttribute('readonly');
                    } else {
                        harga[i].setAttribute('readonly', 'true');
                    }
                }
                @endforeach

                @foreach($harga as $hb)
                    if(('{{ $hb->id_barang }}' == kodeBarang[i].value) && ('{{ $hb->id_harga }}' == 'HRG01')) {
                        harga[i].value = addCommas('{{ $hb->harga_ppn }}');
                        qty[i].setAttribute('required', true);
                    }
                @endforeach
            }

            function resetQty(e) {
                kodeGudang[i].value = '{{ $gudang[0]->id }}';
                qtyGudang[i].value = '';
                qty[i].value = '';
                satuan[i].value = '';
            }
        }

        for(let i = 0; i < harga.length; i++) {
            harga[i].addEventListener("keyup", displayHargaCustom);
            harga[i].addEventListener("blur", displayHargaCustom);

            function displayHargaCustom(e) {
                $(this).val(function(index, value) {
                    return value.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                });

                netPast = +jumlah[i].value.replace(/\./g, "");
                jumlah[i].value = addCommas(harga[i].value.replace(/\./g, "") * qty[i].value);

                checkSubtotal(netPast, +jumlah[i].value.replace(/\./g, ""));
            }
        }

        for(let i = 0; i < qty.length; i++) {
            qty[i].addEventListener("blur", displayQty);

            if(teksSat[i].value == 'Pcs')
                satuan[i].addEventListener("blur", displayQty);
            else
                satuan[i].addEventListener("change", displayQty);

            function displayQty(e) {
                stokJohar = 0;
                stokLain = [];
                totStok = 0;
                @foreach($stok as $s)
                if(('{{ $s->id_barang }}' == kodeBarang[i].value) && ('{{ $s->gudang->tipe }}' == 'TOKO'))
                    totStok = '{{ $s->stok }}';
                @endforeach

                hitungQty(i, e.target.id, e.target.value, teksSat[i].value, ukuran[i].value);

                if(e.target.value == "") {
                    subtotal.value = addCommas(+subtotal.value.replace(/\./g, "") - +jumlah[i].value.replace(/\./g, ""));
                    jumlah[i].value = "";
                    kodeGudang[i].value = '{{ $gudang[0]->id }}';
                    qtyGudang[i].value = "";
                    qty[i].value = "";
                    satuan[i].value = "";
                }
                else if(((e.target.id == 'qty') && (+e.target.value > totStok)) || ((e.target.id == 'satuan') && (teksSat[i].value == 'Pcs') && ((+e.target.value * +ukuran[i].value) > totStok))) {
                    $('#notif'+i).modal("show");
                    nmbrg[i].textContent = brgNama[i].value;
                    totalstok[i].textContent = `${totStok} ${teksSat[i].value}`;

                    if(teksSat[i].value == 'Pcs')
                        totalsatuan[i].textContent = ` atau ${totStok / ukuran[i].value} ${satuanUkuran.innerHTML}`;
                    else
                        totalsatuan[i].textContent = ``;

                    checkSubtotal(+jumlah[i].value.replace(/\./g, ""), 0);

                    qty[i].value = "";
                    satuan[i].value = "";
                    jumlah[i].value = "";

                    return false;
                }
                else {
                    kodeGudang[i].value = '{{ $gudang[0]->id }}';
                    qtyGudang[i].value = qty[i].value;

                    netPast = +jumlah[i].value.replace(/\./g, "");
                    jumlah[i].value = addCommas(qty[i].value * harga[i].value.replace(/\./g, ""));
                    checkSubtotal(netPast, +jumlah[i].value.replace(/\./g, ""));
                }
            }
        }

        function hitungQty(urutan, kode, angka, teks, ukuran) {
            if(kode == 'qty') {
                if((teks == 'Pcs') || (teks == 'Set'))
                    satuan[urutan].value = +angka / +ukuran;
                else if(teks == 'Rol')
                    satuan[urutan].value = +angka * +ukuran;
            }
            else if(kode == 'satuan') {
                qty[urutan].value = +angka * +ukuran;
            }
        }

        function checkSubtotal(Past, Now) {
            if(Past > Now) {
                subtotal.value = addCommas(+subtotal.value.replace(/\./g, "") - (+Past - +Now));
            } else {
                subtotal.value = addCommas(+subtotal.value.replace(/\./g, "") + (+Now - +Past));
            }
        }

        function angkaSaja(evt, inputan, teks) {
            evt = (evt) ? evt : window.event;
            var charCode = (evt.which) ? evt.which : evt.keyCode;
            if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                for(let i = 1; i <= qty.length; i++) {
                    if(inputan == i) {
                        if(teks == 'qty')
                            $(qty[inputan-1]).tooltip('show');
                        else
                            $(satuan[inputan-1]).tooltip('show');
                    }
                }

                return false;
            }
            return true;
        }

        function angkaSajaHarga(evt, inputan) {
            evt = (evt) ? evt : window.event;
            var charCode = (evt.which) ? evt.which : evt.keyCode;
            if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                for(let i = 1; i <= harga.length; i++) {
                    if(inputan == i)
                        $(harga[inputan-1]).tooltip('show');
                }
                return false;
            }
            return true;
        }

        function formatNominal(e){
            $(this).val(function(index, value) {
                return value
                    .replace(/\D/g, "")
                    .replace(/\B(?=(\d{3})+(?!\d))/g, ".")
                    ;
            });
        }

        function addCommas(nStr) {
            nStr += '';
            x = nStr.split(',');
            x1 = x[0];
            x2 = x.length > 1 ? ',' + x[1] : '';
            var rgx = /(\d+)(\d{3})/;
            while (rgx.test(x1)) {
                x1 = x1.replace(rgx, '$1' + '.' + '$2');
            }
            return x1 + x2;
        }

        for(let i = 0; i < hapusBaris.length; i++) {
            hapusBaris[i].addEventListener("click", function (e) {
                if(qty[i].value != "") {
                    subtotal.value = addCommas(+subtotal.value.replace(/\./g, "") - +jumlah[i].value.replace(/\./g, ""));
                }

                for(let j = i; j < hapusBaris.length; j++) {
                    if(j+1 != hapusBaris.length) {
                        jumlah[j].value = jumlah[j+1].value;
                        harga[j].value = harga[j+1].value;
                        satuan[j].value = satuan[j+1].value;
                        teksSat[j].value = teksSat[j+1].value;
                        qtyGudang[j].value = qtyGudang[j+1].value;
                        kodeGudang[j].value = kodeGudang[j+1].value;
                        qty[j].value = qty[j+1].value;
                        brgNama[j].value = brgNama[j+1].value;
                        kodeBarang[j].value = kodeBarang[j+1].value;
                        if(kodeBarang[j+1].value == "")
                            qty[j].removeAttribute('required');
                        else
                            qty[j+1].removeAttribute('required');
                    } else {
                        jumlah[j].value = '';
                        harga[j].value = '';
                        satuan[j].value = '';
                        teksSat[j].value = '';
                        qtyGudang[j].value = '';
                        kodeGudang[j].value = '';
                        qty[j].value = '';
                        brgNama[j].value = '';
                        kodeBarang[j].value = '';
                    }
                }

                for(let j = 0; j < kodeBarang.length; j++) {
                    if(kodeBarang[j].value == '') {
                        kodeBarang[j].focus();
                        break;
                    }
                }
            });
        }

        function checkRequired(e) {
            if((kodeBarang[0].value === "") || (qty[0].value === "")) {
                e.stopPropagation();
            }
            else {
                const kdRow = document.querySelectorAll('.kdBrgRow');
                document.getElementById("submitSO").removeAttribute('data-toggle');
                document.getElementById("submitSO").removeAttribute('data-target');
                cek = 0;
                var kode = [];
                for(let i = 0; i < (jumBaris.value - kdRow.length); i++) {
                    if(kodeBarang[i].value != '') {
                        kode.push(kodeBarang[i].value);
                    }
                }

                for(let i = 0; i < kdRow.length; i++) {
                    if(kdRow[i].value != '') {
                        kode.push(kdRow[i].value);
                    }
                }

                cek = new Set(kode).size !== kode.length;

                if(cek === true) {
                    document.getElementById("submitSO").dataset.toggle = "modal";
                    document.getElementById("submitSO").dataset.target = "#modalDuplikat";
                    return false;
                }
                else {
                    document.getElementById("submitSO").dataset.toggle = "modal";
                    document.getElementById("submitSO").dataset.target = "#modalKonfirm";
                    return false;
                }
            }
        }

        $(function() {
            var barangKode = [];
            var barangNama = [];
            @foreach($barang as $b)
            barangKode.push('{{ $b->id }}');
            barangNama.push('{{ $b->nama }}');
            @endforeach

            function split(val) {
                return val.split(/,\s*/);
            }

            function extractLast(term) {
                return split(term).pop();
            }

            /*-- Autocomplete Input Kode Barang --*/
            $(kodeBarang).on("keydown", function(event) {
                if(event.keyCode === $.ui.keyCode.TAB && $(this).autocomplete("instance").menu.active) {
                    event.preventDefault();
                }
            })
                .autocomplete({
                    minLength: 0,
                    source: function(request, response) {
                        // delegate back to autocomplete, but extract the last term
                        response($.ui.autocomplete.filter(barangKode, extractLast(request.term)));
                    },
                    focus: function() {
                        // prevent value inserted on focus
                        return false;
                    },
                    select: function(event, ui) {
                        var terms = split(this.value);
                        // remove the current input
                        terms.pop();
                        // add the selected item
                        terms.push(ui.item.value);
                        // add placeholder to get the comma-and-space at the end
                        terms.push("");
                        this.value = terms.join("");
                        return false;
                    }
                });

            /*-- Autocomplete Input Nama Barang --*/
            $(brgNama).on("keydown", function(event) {
                if(event.keyCode === $.ui.keyCode.TAB && $(this).autocomplete("instance").menu.active) {
                    event.preventDefault();
                }
            })
                .autocomplete({
                    minLength: 0,
                    source: function(request, response) {
                        // delegate back to autocomplete, but extract the last term
                        response($.ui.autocomplete.filter(barangNama, extractLast(request.term)));
                    },
                    focus: function() {
                        // prevent value inserted on focus
                        return false;
                    },
                    select: function(event, ui) {
                        var terms = split(this.value);
                        // remove the current input
                        terms.pop();
                        // add the selected item
                        terms.push(ui.item.value);
                        // add placeholder to get the comma-and-space at the end
                        terms.push("");
                        this.value = terms.join("");
                        return false;
                    }
                });
        });
    </script>
@endpush
