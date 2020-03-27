</div>
<!-- End of Main Content -->

<!-- Footer -->
<footer class="sticky-footer bg-white">
  <div class="container my-auto">
    <div class="copyright text-center my-auto">
      <span>Copyright &copy; Your Website 2019</span>
    </div>
  </div>
</footer>
<!-- End of Footer -->

</div>
<!-- End of Content Wrapper -->

</div>
<!-- End of Page Wrapper -->

<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
  <i class="fas fa-angle-up"></i>
</a>

<!-- Logout Modal-->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
      <div class="modal-footer">
        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
        <a class="btn btn-primary" href="login.html">Logout</a>
      </div>
    </div>
  </div>
</div>

<?php
$libraries   = activate_page_js("{$this->uri->segment(1)}/{$this->uri->segment(2)}");
$all_scripts = array_diff(scandir(FCPATH . 'assets/js'), array('.', '..'));
$page_script = "{$this->uri->segment(1)}-{$this->uri->segment(2)}.js";
?>

<!-- JS Libraries -->
<script src="<?= base_url('vendors/') ?>jquery/jquery.min.js"></script>
<script src="<?= base_url('vendors/') ?>bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="<?= base_url('vendors/') ?>jquery-easing/jquery.easing.min.js"></script>
<script src="<?= base_url('vendors/') ?>sb-admin-2/js/sb-admin-2.min.js"></script>
<?php foreach ($libraries as $library) : ?>
  <script src="<?= base_url($library['src']) ?>"></script>
<?php endforeach; ?>
<script src="<?= base_url('vendors/datatables/dataTables.buttons.min.js') ?>"></script>
<script src="<?= base_url('vendors/datatables/buttons.bootstrap4.min.js') ?>"></script>
<script src="<?= base_url('vendors/datatables/buttons.flash.min.js') ?>"></script>
<script src="<?= base_url('vendors/datatables/jszip.min.js') ?>"></script>
<script src="<?= base_url('vendors/datatables/pdfmake.min.js') ?>"></script>
<script src="<?= base_url('vendors/datatables/vfs_fonts.js') ?>"></script>
<script src="<?= base_url('vendors/datatables/buttons.html5.min.js') ?>"></script>
<script src="<?= base_url('vendors/datatables/buttons.print.min.js') ?>"></script>
<script src="<?= base_url('assets/js/tangan-kanan.js') ?>"></script>

<!-- Template JS -->
<?php if (isset($view_script)) : ?>
  <script src="<?= base_url("assets/js/") . $view_script; ?>"></script>
<?php endif; ?>

<!-- Custom JS -->
<?php if (in_array($page_script, $all_scripts)) : ?>
  <script src="<?= base_url("assets/js/{$this->uri->segment(1)}-{$this->uri->segment(2)}.js") ?>"></script>
<?php endif; ?>

</body>

</html>