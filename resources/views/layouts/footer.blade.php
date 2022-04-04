<footer class="main-footer">
    <strong>Copyright &copy; 2021 <a href="http://combycut.com/"target="_blank">AURALIO</a>.</strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 1.0
    </div>
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script  src="{{ url('public/plugins/jquery/jquery.min.js')}}"></script>
<!-- jQuery UI 1.11.4 -->
<script  src="{{ url('public/plugins/jquery-ui/jquery-ui.min.js')}}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="{{ url('public/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- ChartJS -->
<script src="{{ url('public/plugins/chart.js/Chart.min.js')}}"></script>
<!-- Sparkline -->
<script src="{{ url('public/plugins/sparklines/sparkline.js')}}"></script>
<!-- JQVMap -->
<script src="{{ url('public/plugins/jqvmap/jquery.vmap.min.js')}}"></script>
<script src="{{ url('public/plugins/jqvmap/maps/jquery.vmap.usa.js')}}"></script>
<!-- jQuery Knob Chart -->
<script src="{{ url('public/plugins/jquery-knob/jquery.knob.min.js')}}"></script>
<!-- daterangepicker -->
<script src="{{ url('public/plugins/moment/moment.min.js')}}"></script>
<script src="{{ url('public/plugins/daterangepicker/daterangepicker.js')}}"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="{{ url('public/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js')}}"></script>
<!-- Summernote -->
<script src="{{ url('public/plugins/summernote/summernote-bs4.min.js')}}"></script>
<!-- overlayScrollbars -->
<script src="{{ url('public/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{ url('public/dist/js/adminlte.js')}}"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<!-- <script src="{{ url('public/dist/js/pages/dashboard.js')}}"></script> -->
<!-- AdminLTE for demo purposes -->
<script src="{{ url('public/dist/js/demo.js')}}"></script>

<!-- additionally added from combycut footer -->
 <!-- plugins:js -->
 <script src="{{ url('vendors\js\vendor.bundle.base.js')}}"></script>
  <script src="{{ url('vendors\js\vendor.bundle.addons.js')}}"></script>
  <!-- endinject -->
  <!-- Plugin js for this page-->
  <!-- End plugin js for this page-->
  <!-- inject:js -->
  <script src="{{ url('js\template.js')}}"></script>

  <!-- endinject -->
  <!-- Custom js for this page-->
  <script src="{{ url('js\dashboard.js')}}"></script>
  <script src="{{ url('js\select2.js')}}"></script>
  <script src="{{ url('js\formpickers.js')}}"></script>
  <script src="{{ url('js\form-repeater.js')}}"></script>
  <script src="{{ url('js\file-upload.js')}}"></script>
  <script src="{{ url('js\data-table.js')}}"></script>
  <!-- End custom js for this page-->
</body>
</html>
