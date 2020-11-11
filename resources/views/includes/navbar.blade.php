<!-- Topbar -->
<nav class="navbar navbar-expand navbar-light bg-white topbar mb-3 static-top shadow">

  <!-- Sidebar Toggle (Topbar) -->
  <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
    <i class="fa fa-bars"></i>
  </button>

  <!-- Topbar Search -->
  {{-- <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
    <div class="input-group">
      <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
      <div class="input-group-append">
        <button class="btn btn-primary" type="button">
          <i class="fas fa-search fa-sm"></i>
        </button>
      </div>
    </div>
  </form> --}}

  <!-- Topbar Navbar -->
  <ul class="navbar-nav ml-auto">

    <!-- Nav Item - Search Dropdown (Visible Only XS) -->
    <li class="nav-item dropdown no-arrow d-sm-none">
      <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-search fa-fw"></i>
      </a>
      <!-- Dropdown - Messages -->
      <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in" aria-labelledby="searchDropdown">
        <form class="form-inline mr-auto w-100 navbar-search">
          <div class="input-group">
            <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
            <div class="input-group-append">
              <button class="btn btn-primary" type="button">
                <i class="fas fa-search fa-sm"></i>
              </button>
            </div>
          </div>
        </form>
      </div>
    </li>

    <!-- Nav Item - Alerts -->
    <li class="nav-item dropdown no-arrow mx-1">
      <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-bell fa-fw"></i>
        <!-- Counter - Alerts -->
        @if(Auth::user()->roles == 'SUPER')
          @php 
            $items = \App\Models\NeedApproval::All();
          @endphp
          <span class="badge badge-danger badge-counter">{{ $items->count() }}</span>
        @elseif((Auth::user()->roles == 'ADMIN') || (Auth::user()->roles == 'FINANCE'))
          @php 
            $items = \App\Models\SalesOrder::has('approval')->where('status', 'UPDATE')
                        ->get();
          @endphp 
          <span class="badge badge-danger badge-counter">{{ $items->count() }}</span>
        @endif
      </a>
      <!-- Dropdown - Alerts -->
      <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="alertsDropdown">
        <h6 class="dropdown-header">
          Notifikasi
        </h6>
        @if($items->count() != 0)
          @foreach($items as $item)
            <a class="dropdown-item d-flex align-items-center" 
            @if(Auth::user()->roles == 'SUPER') 
              href="{{ route('app-show', $item->id_dokumen) }}"
            @elseif((Auth::user()->roles == 'ADMIN') || (Auth::user()->roles == 'FINANCE'))
              href="{{ route('notif-show', $item->id) }}"
            @endif
            >
              <div class="mr-3">
                <div class="icon-circle bg-primary" style="margin-left: -10px">
                  <i class="fas fa-file-alt text-white"></i>
                </div>
              </div>
              <div>
                @if(Auth::user()->roles == 'SUPER')
                  <div class="small text-dark-500 text-bold">
                    {{ \Carbon\Carbon::parse($item->tgl_so)->format('d-M-y') }}
                  </div>
                  @if($item->status != "PENDING_LIMIT")
                    <span class="font-weight-bold">
                      Perubahan @if($item->status == "PENDING_UPDATE") isi detail @elseif($item->status == "PENDING_BATAL") status @endif pada {{ $item->tipe }} {{ $item->id_dokumen }}
                    </span>
                  @else
                    <span class="font-weight-bold">
                      Customer {{ $item->so->customer->nama }} melebihi limit pada faktur {{ $item->id_dokumen }}
                    </span>
                  @endif
                @elseif((Auth::user()->roles == 'ADMIN') || (Auth::user()->roles == 'FINANCE'))
                  <div class="small text-gray-500">
                    {{ \Carbon\Carbon::parse($item->approval[0]->tanggal)->format('d-m-Y') }}
                  </div>
                  <span class="font-weight-bold">
                    Perubahan @if($item->status == "UPDATE") detail @elseif($item->status == "BATAL") status @endif faktur {{ $item->id_dokumen }} telah di approve. Silahkan cetak faktur.
                  </span>
                @endif
              </div>
            </a>
          @endforeach
          <a class="dropdown-item text-center small text-dark-500" href="#">Show All Alerts</a>
        @else
          <a class="dropdown-item text-center small text-dark-500" href="#">Tidak Ada Notifikasi</a>
        @endif
        {{-- <a class="dropdown-item d-flex align-items-center" href="#">
          <div class="mr-3">
            <div class="icon-circle bg-primary" style="margin-left: -10px">
              <i class="fas fa-file-alt text-white"></i>
            </div>
          </div>
          <div>
            <div class="small text-gray-500">December 12, 2019</div>
            <span class="font-weight-bold">A new monthly report is ready to download!</span>
          </div>
        </a>
        <a class="dropdown-item d-flex align-items-center" href="#">
          <div class="mr-3">
            <div class="icon-circle bg-success">
              <i class="fas fa-donate text-white"></i>
            </div>
          </div>
          <div>
            <div class="small text-gray-500">December 7, 2019</div>
            $290.29 has been deposited into your account!
          </div>
        </a>
        <a class="dropdown-item d-flex align-items-center" href="#">
          <div class="mr-3">
            <div class="icon-circle bg-warning">
              <i class="fas fa-exclamation-triangle text-white"></i>
            </div>
          </div>
          <div>
            <div class="small text-gray-500">December 2, 2019</div>
            Spending Alert: We've noticed unusually high spending for your account.
          </div>
        </a> --}}
      </div>
    </li>

    <div class="topbar-divider d-none d-sm-block"></div>

    <!-- Nav Item - User Information -->
    <li class="nav-item dropdown no-arrow">
      <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ Auth::user()->name }}</span>
      </a>
      <!-- Dropdown - User Information -->
      <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
        <a class="dropdown-item" href="{{ route('user-change') }}">
          <i class="fas fa-lock fa-sm fa-fw mr-2 text-gray-400"></i>
          Ganti Password
        </a>
        <a class="dropdown-item" href="#">
          <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
          Activity Log
        </a>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
          <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
          Logout
        </a>
      </div>
    </li>

  </ul>

</nav>
<!-- End of Topbar -->