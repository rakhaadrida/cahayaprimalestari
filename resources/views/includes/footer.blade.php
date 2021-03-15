@php
  $tahun = \Carbon\Carbon::now('+07:00');
@endphp

<!-- Footer -->
<footer class="sticky-footer bg-white">
  <div class="container my-auto">
    <div class="copyright text-center my-auto">
      <span>Copyright &copy; 2020 - {{ $tahun->year }}  | rakhaadrida | Template by SB Admin 2</span>
    </div>
  </div>
</footer>
<!-- End of Footer -->