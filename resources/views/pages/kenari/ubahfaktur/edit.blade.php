@extends('layouts.admin')

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

  <!-- Page Heading -->
  <div class="d-sm-flex align-items-center justify-content-between mb-0">
      <h1 class="h3 mb-0 text-gray-800 menu-title">Ubah Faktur</h1>
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
              <!-- Inputan Data Id, Tanggal, Supplier PO -->
              <div class="container so-container">
                <div class="row">
                  <div class="col-12">
                    <div class="form-group row">
                      <label for="kode" class="col-2 col-form-label text-bold text-dark">Nomor SO</label>
                      <span class="col-form-label text-bold">:</span>
                      <div class="col-2 mt-1">
                        <input type="text" readonly class="form-control-plaintext form-control-sm text-bold text-dark" name="kode" value="{{ $items[0]->id }}">
                      </div>
                    </div>  
                  </div>
                  <div class="col" style="margin-left: -380px">
                    <div class="form-group row sj-first-line">
                      <label for="tglSO" class="col-5 col-form-label text-bold text-right text-dark">Tanggal SO</label>
                      <span class="col-form-label text-bold">:</span>
                      <div class="col-4">
                        <input type="text" readonly class="form-control-plaintext col-form-label-sm text-bold text-dark" name="tglSO" value="{{ \Carbon\Carbon::parse($items[0]->tgl_so)->format('d-M-y') }}">
                      </div>
                    </div>
                    <div class="form-group row sj-after-first">
                      <label for="namaCust" class="col-5 col-form-label text-bold text-right text-dark">Nama Customer</label>
                      <span class="col-form-label text-bold">:</span>
                      <div class="col-6">
                        <input type="text" readonly class="form-control-plaintext col-form-label-sm text-bold text-dark" name="namaCust" value="{{ $items[0]->customer->nama }}">
                        <input type="hidden" name="kodeCust" value="{{ $items[0]->id_customer }}">
                      </div>
                    </div>
                    <div class="form-group row sj-after-first">
                      <label for="namaSales" class="col-5 col-form-label text-bold text-right text-dark">Nama Sales</label>
                      <span class="col-form-label text-bold">:</span>
                      <div class="col-5">
                        <input type="text" readonly class="form-control-plaintext col-form-label-sm text-bold text-dark" name="namaSales" value="{{ $items[0]->customer->sales->nama }}">
                      </div>
                    </div>
                  </div>
                </div>
                <div class="form-group row so-update-left">
                  <label for="nama" class="col-2 col-form-label text-bold text-dark">Tanggal Update</label>
                  <span class="col-form-label text-bold">:</span>
                  <div class="col-2 mt-1">
                    <input type="text" readonly class="form-control-plaintext form-control-sm text-bold text-dark" name="tanggal" value="{{ $tanggal }}">
                  </div>
                </div>
                <div class="form-group row so-update-input">
                  <label for="alamat" class="col-2 col-form-label text-bold text-dark">Keterangan</label>
                  <span class="col-form-label text-bold">:</span>
                  <div class="col-5">
                    <input type="text" tabindex="1" name="keterangan" id="keterangan" class="form-control form-control-sm mt-1 text-dark" required autofocus>
                    @php
                      if(($items[0]->need_approval->count() != 0) && ($items[0]->need_approval->last()->status == 'PENDING_UPDATE')) {
                        $itemsApp = \App\Models\NeedApproval::where('id_dokumen', $items[0]->id)
                                    ->latest()->get();
                        $itemsRow = $itemsApp[0]->need_appdetil->count();
                      }
                    @endphp
                    <input type="hidden" name="jumBaris" id="jumBaris" value="{{ $itemsRow }}">
                    <input type="hidden" name="id" value="{{ $id }}">
                    <input type="hidden" name="nama" value="{{ $nama }}">
                    <input type="hidden" name="tglAwal" value="{{ $tglAwal }}">
                    <input type="hidden" name="tglAkhir" value="{{ $tglAkhir }}">
                  </div>
                </div>
              </div>
              <hr>
              <!-- End Inputan Data Id, Tanggal, Supplier PO -->
              
              <!-- Tabel Data Detil PO -->
              <table class="table table-sm table-bordered table-striped table-responsive-sm table-hover" >
                <thead class="text-center text-bold text-dark">
                  <tr>
                    <td class="align-middle" style="width: 30px">No</td>
                    <td class="align-middle" style="width: 90px">Kode</td>
                    <td class="align-middle">Nama Barang</td>
                    <td class="align-middle" style="width: 55px">Qty</td>
                    <td class="align-middle" style="width: 50px">Tipe Harga</td>
                    <td class="align-middle" style="width: 80px">Harga</td>
                    <td class="align-middle" style="width: 100px">Jumlah</td>
                    <td class="align-middle" style="width: 100px">Diskon(%)</td>
                    <td class="align-middle" style="width: 80px">Diskon(Rp)</td>
                    <td class="align-middle" style="width: 100px">Netto (Rp)</td>
                    <td class="align-middle">Hapus</td>
                  </tr>
                </thead>
                <tbody id="tablePO">
                  @php 
                    $i = 1; $tab = 1;
                    if(($items[0]->need_approval->count() != 0) && ($items[0]->need_approval->last()->status == 'PENDING_UPDATE')) {
                      $itemsDetail = \App\Models\NeedAppDetil::with(['barang'])
                                  ->select('id_barang', 'id_gudang', 'diskon')
                                  ->selectRaw('avg(harga) as harga, sum(qty) as qty, sum(diskonRp) as diskonRp')
                                  ->where('id_app', $items[0]->need_approval->last()->id)
                                  ->groupBy('id_barang', 'diskon')
                                  ->get();
                    } else {
                      $itemsDetail = \App\Models\DetilSO::with(['barang'])
                                  ->select('id_barang', 'id_gudang', 'diskon')
                                  ->selectRaw('avg(harga) as harga, sum(qty) as qty, sum(diskonRp) as diskonRp')
                                  ->where('id_so', $items[0]->id)
                                  ->groupBy('id_barang', 'diskon')
                                  ->get();
                    }
                  @endphp
                  @foreach($itemsDetail as $item)
                    <tr class="text-dark" id="{{ $i }}">
                      <td align="center" class="align-middle">{{ $i }}</td>
                      <td>
                        <input type="text" tabindex="{{ $tab++ }}" name="kodeBarang[]" class="form-control form-control-sm text-bold text-dark kodeBarang" value="{{ $item->id_barang }}" required>
                        <span class="kodeAwal" hidden>{{ $item->id_barang }}</span>
                      </td>
                      <td>
                        <input type="text" tabindex="{{ $tab += 2 }}" name="namaBarang[]" class="form-control form-control-sm text-bold text-dark namaBarang" value="{{ $item->barang->nama }}" required>
                        {{-- <input type="text" name="qtyAwal" class="text-bold text-dark qtyAwal" value="{{ $item->qty }}"> --}}
                        <span class="qtyAwal" hidden>{{ $item->qty }}</span>
                      </td>
                      <td> 
                        <input type="text" tabindex="{{ $tab += 3 }}" name="qty[]" class="form-control form-control-sm text-bold text-dark text-right qty" value="{{ $item->qty }}" onkeypress="return angkaSaja(event, {{$i}})" data-toogle="tooltip" data-placement="bottom" title="Hanya input angka 0-9" autocomplete="off" required>
                        <input type="hidden" name="teksSat[]" class="teksSat" value="{{ substr($item->barang->satuan, 0, 3) }}">
                        <input type="hidden" name="kodeGudang[]" class="kodeGudang" value="{{ $item->id_gudang }}">
                      </td>
                      @php
                        foreach($harga as $hb) {
                          if($hb->id_barang == $item->id_barang) {
                            $tipe = $hb->hargaBarang->tipe;
                            break;
                          }
                          else
                            $tipe = '';
                        }
                      @endphp
                      <td>
                        <input type="text" tabindex="{{ $tab += 4 }}" name="tipe[]" id="tipe" class="form-control form-control-sm text-bold text-dark text-center tipe" 
                        value="{{ $tipe }}">
                      </td>
                      <td align="right">
                        <input type="text" name="harga[]" readonly class="form-control-plaintext form-control-sm text-bold text-dark text-right harga" value="{{ number_format($item->harga, 0, "", ".") }}" readonly>
                      </td>
                      <td align="right">
                        <input type="text" name="jumlah[]" id="jumlah" readonly class="form-control-plaintext form-control-sm text-bold text-dark text-right jumlah" value="{{ number_format($item->qty * $item->harga, 0, "", ".") }}" >
                      </td>
                      <td align="right" style="width: 60px">
                        <input type="text" tabindex="{{ $tab += 5 }}" name="diskon[]" class="form-control form-control-sm text-bold text-right diskon" value="{{ $item->diskon }}" onkeypress="return angkaPlus(event, {{$i}})" data-toogle="tooltip" data-placement="bottom" title="Hanya input angka 0-9 dan tanda +" autocomplete="off" required>
                      </td>
                      @php 
                        $diskon = 100;
                        $arrDiskon = explode("+", $item->diskon);
                        for($j = 0; $j < sizeof($arrDiskon); $j++) {
                          $diskon -= ($arrDiskon[$j] * $diskon) / 100;
                        } 
                        $diskon = number_format((($diskon - 100) * -1), 2, ",", "");
                      @endphp
                      <td align="right" style="width: 120px" >
                        <input type="text" name="diskonRp[]" id="diskonRp" readonly class="form-control-plaintext form-control-sm text-bold text-right text-dark diskonRp" 
                        value="{{ number_format((($item->qty * $item->harga) * str_replace(",", ".", $diskon)) / 100, 0, "", ".") }}" >
                      </td>
                      <td align="right">
                        <input type="text" name="netto[]" id="netto" readonly class="form-control-plaintext form-control-sm text-bold text-dark text-right netto" value="{{ number_format(($item->qty * $item->harga) - 
                        ((($item->qty * $item->harga) * str_replace(",", ".", $diskon)) / 100), 0, "", ".") }}" >
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
                            <h5>Qty input tidak bisa melebihi total stok. Total stok untuk barang <span class="col-form-label text-bold nmbrg"></span> adalah <span class="col-form-label text-bold totalstok"></span> atau <span class="col-form-label text-bold totalsatuan"></span></h5>
                          </div>
                        </div>
                      </div>
                    </div>
                    @php $i++; @endphp
                  @endforeach
                </tbody>
              </table>

              <div class="form-group row justify-content-end subtotal-so so-info-total">
                <label for="totalNotPPN" class="col-3 col-form-label text-bold text-right text-dark">Sub Total</label>
                <span class="col-form-label text-bold">:</span>
                <span class="col-form-label text-bold ml-2">Rp</span>
                <div class="col-2">
                  <input type="text" name="subtotal" id="subtotal" readonly class="form-control-plaintext col-form-label-sm text-bold text-danger text-right" value="{{ number_format($items[0]->total + $items[0]->diskon, 0, "", ".") }}" >
                </div>
              </div>
              <div class="form-group row justify-content-end total-so so-info-total">
                <label for="totalNotPPN" class="col-2 col-form-label text-bold text-right text-dark">Diskon Faktur</label>
                <span class="col-form-label text-bold">:</span>
                <span class="col-form-label text-bold ml-2">Rp</span>
                <div class="col-2">
                  <input type="text" name="diskonFaktur" id="diskonFaktur" readonly class="form-control-plaintext col-form-label-sm text-bold text-danger text-right" value="{{ number_format($items[0]->diskon, 0, "", ".") }}" >
                </div>
              </div>
              <div class="form-group row justify-content-end total-so so-info-total">
                <label for="totalNotPPN" class="col-2 col-form-label text-bold text-right text-dark">Total Sebelum PPN</label>
                <span class="col-form-label text-bold">:</span>
                <span class="col-form-label text-bold ml-2">Rp</span>
                <div class="col-2">
                  <input type="text" name="totalNotPPN" id="totalNotPPN" readonly class="form-control-plaintext col-form-label-sm text-bold text-danger text-right" value="{{ number_format($items[0]->total, 0, "", ".") }}" >
                </div>
              </div>
              <div class="form-group row justify-content-end total-so so-info-total">
                <label for="ppn" class="col-1 col-form-label text-bold text-right text-dark">PPN</label>
                <span class="col-form-label text-bold">:</span>
                <span class="col-form-label text-bold ml-2">Rp</span>
                <div class="col-2">
                  <input type="text" name="ppn" id="ppn" readonly class="form-control-plaintext col-form-label-sm text-bold text-danger text-right" 
                  value="0" />
                </div>
              </div>
              <div class="form-group row justify-content-end total-so so-info-total">
                <label for="grandtotal" class="col-2 col-form-label text-bold text-right text-dark">Total Tagihan</label>
                <span class="col-form-label text-bold">:</span>
                <span class="col-form-label text-bold ml-2">Rp</span>
                <div class="col-2">
                  <input type="text" name="grandtotal" id="grandtotal" readonly class="form-control-plaintext text-bold text-secondary text-right" 
                  value="{{ number_format($items[0]->total, 0, "", ".") }}"
                  />
                </div>
              </div>
              <hr>
              <!-- End Tabel Data Detil PO -->

              <!-- Button Submit dan Reset -->
              <div class="form-row justify-content-center">
                <div class="col-2">
                  <button type="submit" tabindex="{{ $tab++ }}" formaction="{{ route('so-update-kenari') }}" formmethod="POST" class="btn btn-success btn-block text-bold">Submit</>
                </div>
                <div class="col-2">
                  <button type="reset" tabindex="{{ $tab += 2 }}" class="btn btn-outline-danger btn-block text-bold">Reset</button>
                </div>
                <div class="col-2">
                  <a href="{{ url()->previous() }}" tabindex="{{ $tab += 3 }}" class="btn btn-outline-primary btn-block text-bold">Kembali</a>
                </div>
              </div>
              <!-- End Button Submit dan Reset -->

            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('addon-script')
<script type="text/javascript">
const kodeBarang = document.querySelectorAll('.kodeBarang');
const kodeAwal = document.querySelectorAll(".kodeAwal");
const brgNama = document.querySelectorAll(".namaBarang");
const qtyAwal = document.querySelectorAll(".qtyAwal");
const qty = document.querySelectorAll(".qty");
const teksSat = document.querySelectorAll(".teksSat");
const kodeGudang = document.querySelectorAll(".kodeGudang");
const qtyGudang = document.querySelectorAll(".qtyGudang");
const tipe = document.querySelectorAll(".tipe");
const harga = document.querySelectorAll(".harga");
const jumlah = document.querySelectorAll(".jumlah");
const diskon = document.querySelectorAll(".diskon");
const diskonRp = document.querySelectorAll(".diskonRp");
const netto = document.querySelectorAll(".netto");
const hapusBaris = document.querySelectorAll(".icRemove");
const subtotal = document.getElementById('subtotal');
const diskonFaktur = document.getElementById('diskonFaktur');
const totalNotPPN = document.getElementById('totalNotPPN');
const ppn = document.getElementById('ppn');
const grandtotal = document.getElementById('grandtotal');
const jumBaris = document.getElementById('jumBaris');
const totalstok = document.querySelectorAll(".totalstok");
const totalsatuan = document.querySelectorAll(".totalsatuan");
const nmbrg = document.querySelectorAll(".nmbrg");
var ukuran; var satuanUkuran; var pcs;
var netPast; var cek; var stokTambah;
var kodeModal; var arrKodeGud; var arrQtyAwal; var arrQtyGud;
var totTemp; var qtyJohar; var qtyLebih;
var sisa; var stokJohar; var stokLain; var kodeLain; var totStok;

/** Tampil Nama dan Kode Barang Otomatis **/
for(let i = 0; i < brgNama.length; i++) {
  brgNama[i].addEventListener("keyup", displayHarga) ;
  kodeBarang[i].addEventListener("keyup", displayHarga);
  brgNama[i].addEventListener("blur", displayHarga) ;
  kodeBarang[i].addEventListener("blur", displayHarga);

  function displayHarga(e) {
    if(e.target.value == "") {
      subtotal.value = addCommas(+subtotal.value.replace(/\./g, "") - +netto[i].value.replace(/\./g, ""));
      totalNotPPN.value = addCommas(+totalNotPPN.value.replace(/\./g, "") - +netto[i].value.replace(/\./g, ""));
      grandtotal.value = totalNotPPN.value;

      $(this).parents('tr').find('input').val('');
      qty[i].removeAttribute('required');
    }

    @foreach($barang as $br)
      if(('{{ $br->nama }}' == e.target.value) || ('{{ $br->id }}' == e.target.value)) {
        kodeBarang[i].value = '{{ $br->id }}';
        brgNama[i].value = '{{ $br->nama }}';
      }
    @endforeach

    @foreach($harga as $hb)
      if(('{{ $hb->id_barang }}' == kodeBarang[i].value) && ('{{ $hb->id_harga }}' == 'HRG01')) {
        tipe[i].value = '{{ $hb->hargaBarang->tipe }}';
        harga[i].value = addCommas('{{ $hb->harga_ppn }}');
        qty[i].setAttribute('required', true);
      }
    @endforeach

    if(qty[i].value != '') {
      netPast = +netto[i].value.replace(/\./g, "");
      jumlah[i].value = addCommas(harga[i].value.replace(/\./g, "") * qty[i].value);
      netto[i].value = addCommas(jumlah[i].value.replace(/\./g, "") - diskonRp[i].value.replace(/\./g, ""));
      checkSubtotal(netPast, +netto[i].value.replace(/\./g, ""));
      totalNotPPN.value = addCommas(+subtotal.value.replace(/\./g, "") - +diskonFaktur.value.replace(/\./g, ""));
      grandtotal.value = totalNotPPN.value;
    }

    kodeGudang[i].value = '{{ $gudang[0]->id }}';
  }
}

/** Tampil Jumlah Harga Otomatis **/
for(let i = 0; i < qty.length; i++) {
  qty[i].addEventListener("blur", function (e) {
    stokJohar = 0;
    stokLain = []; kodeLain = [];
    totStok = 0;

    @foreach($stok as $s)
      if(('{{ $s->id_barang }}' == kodeBarang[i].value) && ('{{ $s->id_gudang }}' == kodeGudang[i].value))
        totStok = '{{ $s->stok }}';
    @endforeach

    @foreach($barang as $br)
      if('{{ $br->id }}' == kodeBarang[i].value) {
        satuanUkuran = '{{ substr($br->satuan, -3) }}';
        if(satuanUkuran == 'Dus')
          pcs = 'Pcs';
        else
          pcs = 'Rol';
        ukuran = '{{ $br->ukuran }}';
      }
    @endforeach

    if(e.target.value == "") {
      subtotal.value = addCommas(+subtotal.value.replace(/\./g, "") - +netto[i].value.replace(/\./g, ""));
      jumlah[i].value = "";
      netto[i].value = "";
      kodeGudang[i].value = '{{ $gudang[0]->id }}';
      qtyGudang[i].value = "";
      qty[i].value = "";
    }
    else if(((kodeBarang[i].value == kodeAwal[i].textContent) && (+e.target.value - +qtyAwal[i].textContent) > totStok) || ((kodeBarang[i].value != kodeAwal[i].textContent) && (+e.target.value > totStok))) {
      $('#notif'+i).modal("show");
      nmbrg[i].textContent = brgNama[i].value;
      if(kodeBarang[i].value == kodeAwal[i].textContent)
        totStok = +totStok + +qtyAwal[i].textContent;

      totalstok[i].textContent = `${totStok} ${pcs}`;
      totalsatuan[i].textContent = `${totStok / ukuran} ${satuanUkuran}`;

      qty[i].value = "";
      jumlah[i].value = "";
      netto[i].value = "";

      return false;
    }
    else {
      netPast = +netto[i].value.replace(/\./g, "");
      jumlah[i].value = addCommas(e.target.value * harga[i].value.replace(/\./g, ""));
      if(diskon[i].value != "") {
        var angkaDiskon = hitungDiskon(diskon[i].value)
        diskonRp[i].value = addCommas(angkaDiskon * jumlah[i].value.replace(/\./g, "") / 100);
      }

      netto[i].value = addCommas(+jumlah[i].value.replace(/\./g, "") - +diskonRp[i].value.replace(/\./g, ""));
      checkSubtotal(netPast, +netto[i].value.replace(/\./g, ""));
    }
    totalNotPPN.value = addCommas(+subtotal.value.replace(/\./g, "") - +diskonFaktur.value.replace(/\./g, ""));
    grandtotal.value = totalNotPPN.value;
  });
} 

// Pilih Tipe
for(let i = 0; i < tipe.length; i++) {
  tipe[i].addEventListener("keyup", displayTipe);
  tipe[i].addEventListener("blur", displayTipe);

  function displayTipe(e) {
    if(e.target.value == "") {
      subtotal.value = addCommas(+subtotal.value.replace(/\./g, "") - +netto[i].value.replace(/\./g, ""));
      totalNotPPN.value = addCommas(+subtotal.value.replace(/\./g, "") - +diskonFaktur.value.replace(/\./g, ""));
      grandtotal.value = totalNotPPN.value;
      harga[i].value = "";
      jumlah[i].value = "";
      diskonRp[i].value = "";
      netto[i].value = "";
    }

    @foreach($harga as $hb)
      if(('{{ $hb->id_barang }}' == kodeBarang[i].value) && ('{{ $hb->hargaBarang->tipe }}' == e.target.value)) {
        harga[i].value = addCommas('{{ $hb->harga_ppn }}');
        jumlah[i].value = addCommas(+harga[i].value.replace(/\./g, "") * qty[i].value);

        netPast = +netto[i].value.replace(/\./g, "");
        if(diskon[i].value != "") {
          var angkaDiskon = hitungDiskon(diskon[i].value)
          diskonRp[i].value = addCommas(angkaDiskon * jumlah[i].value.replace(/\./g,"") / 100);
        }

        netto[i].value = addCommas(+jumlah[i].value.replace(/\./g, "") - +diskonRp[i].value.replace(/\./g, ""));
        checkSubtotal(netPast, +netto[i].value.replace(/\./g, ""));
        totalNotPPN.value = addCommas(+subtotal.value.replace(/\./g, "") - +diskonFaktur.value.replace(/\./g, ""));
        grandtotal.value = totalNotPPN.value;
      }
    @endforeach
  }
}

/** Tampil Diskon Rupiah Otomatis **/
for(let i = 0; i < diskon.length; i++) {
  diskon[i].addEventListener("keyup", function (e) {
    if(e.target.value == "") {
      netPast = netto[i].value.replace(/\./g, "");
      netto[i].value = addCommas(+netto[i].value.replace(/\./g, "") + +diskonRp[i].value.replace(/\./g, ""))
      checkSubtotal(netPast, netto[i].value.replace(/\./g, ""));
      diskonRp[i].value = "";
    }
    else {
      var angkaDiskon = hitungDiskon(e.target.value);
      netPast = +netto[i].value.replace(/\./g, "")
      diskonRp[i].value = addCommas((angkaDiskon * jumlah[i].value.replace(/\./g, "") / 100).toFixed(0));
      netto[i].value = addCommas(+jumlah[i].value.replace(/\./g, "") - +diskonRp[i].value.replace(/\./g, ""))
      checkSubtotal(netPast, +netto[i].value.replace(/\./g, ""));
    }
    totalNotPPN.value = addCommas(+subtotal.value.replace(/\./g, "") - +diskonFaktur.value.replace(/\./g, ""));
    grandtotal.value = totalNotPPN.value;
  });
}

/** Check Jumlah Netto onChange **/
function checkSubtotal(Past, Now) {
  if(Past > Now) {
    subtotal.value = addCommas(+subtotal.value.replace(/\./g, "") - (+Past - +Now));
  } else {
    subtotal.value = addCommas(+subtotal.value.replace(/\./g, "") + (+Now - +Past));
  }
}

/** Hitung Diskon **/
function hitungDiskon(angka) {
  var totDiskon = 100;
  var arrDiskon = angka.split('+');
  for(let i = 0; i < arrDiskon.length; i++) {
    totDiskon -= (arrDiskon[i] * totDiskon) / 100;
  }
  totDiskon =  Math.floor(((totDiskon - 100) * -1).toFixed(2));
  return totDiskon;
}

/** Hitung PPN Dan Total **/
function total_ppn(sub) {
  ppn.value = addCommas(sub * 10 / 100);
  grandtotal.value = addCommas(+sub + +ppn.value.replace(/\./g, ""));
}

/** Inputan hanya bisa angka **/
function angkaSaja(evt, inputan) {
  evt = (evt) ? evt : window.event;
  var charCode = (evt.which) ? evt.which : evt.keyCode;
  if (charCode > 31 && (charCode < 48 || charCode > 57)) {   
    for(let i = 1; i <= qty.length; i++) {
      if(inputan == i)
        $(qty[inputan-1]).tooltip('show');
    }

    return false;
  }
  return true;
}

/** Inputan hanya bisa angka dan plus **/
function angkaPlus(evt, inputan) {
  evt = (evt) ? evt : window.event;
  var charCode = (evt.which) ? evt.which : evt.keyCode;
  if (charCode > 31 && charCode != 43  && (charCode < 48 || charCode > 57)) {
    for(let i = 1; i <= diskon.length; i++) {
      if(inputan == i)
        $(diskon[inputan-1]).tooltip('show');
    }
    return false;
  }
  return true;
}

/** Add Thousand Separators **/
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

/** Delete Baris Pada Tabel **/
for(let i = 0; i < hapusBaris.length; i++) {
  hapusBaris[i].addEventListener("click", function (e) {
    if(qty[i].value != "") {
      subtotal.value = addCommas(+subtotal.value.replace(/\./g, "") - +netto[i].value.replace(/\./g, ""));
      grandtotal.value = subtotal.value;
    }

    for(let j = i; j < hapusBaris.length; j++) {
      if(j == hapusBaris.length - 1) {
        $(tablePO).find('tr:last-child').remove();  
      }
      else {
        netto[j].value = netto[j+1].value;
        diskonRp[j].value = diskonRp[j+1].value;
        diskon[j].value = diskon[j+1].value;
        jumlah[j].value = jumlah[j+1].value;
        harga[j].value = harga[j+1].value;
        qtyGudang[j].value = qtyGudang[j+1].value;
        kodeGudang[j].value = kodeGudang[j+1].value;
        qtyAwalArr[j].value = qtyAwalArr[j+1].value;
        kodeGudangArr[j].value = kodeGudangArr[j+1].value;
        qty[j].value = qty[j+1].value;
        qtyAwal[j].value = qtyAwal[j+1].value;
        brgNama[j].value = brgNama[j+1].value;
        kodeBarang[j].value = kodeBarang[j+1].value;
        if(kodeBarang[j+1].value == "")
          qty[j].removeAttribute('required');
        else
          qty[j+1].removeAttribute('required');
      }     
    }
    jumBaris.value -= 1; 
  });
}

/** Autocomplete Input Text **/
$(function() {
  var kodeBrg = [];
  var namaBrg = [];
  @foreach($barang as $b)
    kodeBrg.push('{{ $b->id }}');
    namaBrg.push('{{ $b->nama }}');
  @endforeach

  var tipeHarga = [];
  @foreach($hrg as $h)
    tipeHarga.push('{{ $h->tipe }}');
  @endforeach
    
  function split(val) {
    return val.split(/,\s*/);
  }

  function extractLast(term) {
    return split(term).pop();
  }

  /*-- Autocomplete Input Barang --*/
  for(let i = 0; i < brgNama.length; i++) {
    $(brgNama[i]).on("keydown", function(event) {
      if(event.keyCode === $.ui.keyCode.TAB && $(this).autocomplete("instance").menu.active) {
        event.preventDefault();
      }
    })
    .autocomplete({
      minLength: 0,
      source: function(request, response) {
        // delegate back to autocomplete, but extract the last term
        response($.ui.autocomplete.filter(namaBrg, extractLast(request.term)));
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
  }

  for(let i = 0; i < kodeBarang.length; i++) {
    $(kodeBarang[i]).on("keydown", function(event) {
      if(event.keyCode === $.ui.keyCode.TAB && $(this).autocomplete("instance").menu.active) {
        event.preventDefault();
      }
    })
    .autocomplete({
      minLength: 0,
      source: function(request, response) {
        // delegate back to autocomplete, but extract the last term
        response($.ui.autocomplete.filter(kodeBrg, extractLast(request.term)));
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
  }

  /*-- Autocomplete Input Tipe Harga --*/
  for(let i = 0; i < tipe.length; i++) {
    $(tipe[i]).on("keydown", function(event) {
      if(event.keyCode === $.ui.keyCode.TAB && $(this).autocomplete("instance").menu.active) {
        event.preventDefault();
      }
    })
    .autocomplete({
      minLength: 0,
      source: function(request, response) {
        // delegate back to autocomplete, but extract the last term
        response($.ui.autocomplete.filter(tipeHarga, extractLast(request.term)));
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
  }
});

</script>
@endpush