        </div>
      </section>
    </div>

    <footer class="main-footer">
      <strong>Copyright &copy; <?php echo date("Y"); ?> APDATE.</strong>
      All rights reserved.
      <div class="float-right d-none d-sm-inline-block">
        <b>Version</b> 0.1.0
      </div>
    </footer>

    <aside class="control-sidebar control-sidebar-dark">
    </aside>
    </div>
    <script>
      $(document).ready(function(){
        $("#datatable").DataTable();
      });
      $.widget.bridge('uibutton', $.ui.button)
    </script>
    <script src="<?php echo base_url() ?>/assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo base_url() ?>/assets/plugins/chart.js/Chart.min.js"></script>
    <script src="<?php echo base_url() ?>/assets/plugins/jquery-knob/jquery.knob.min.js"></script>
    <script src="<?php echo base_url() ?>/assets/plugins/moment/moment.min.js"></script>
    <script src="<?php echo base_url() ?>/assets/plugins/daterangepicker/daterangepicker.js"></script>
    <script src="<?php echo base_url() ?>/assets/plugins/toastr/toastr.min.js"></script>
    <script src="<?php echo base_url() ?>/assets/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
    <script src="<?php echo base_url() ?>/assets/plugins/summernote/summernote-bs4.min.js"></script>
    <script src="<?php echo base_url() ?>/assets/plugins/select2/js/select2.full.min.js"></script>
    <script src="<?php echo base_url() ?>/assets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
    <script src="<?php echo base_url() ?>/assets/plugins/datatables/jquery.dataTables.js"></script>
    <script src="<?php echo base_url() ?>/assets/plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>
    <script src="<?php echo base_url() ?>/assets/plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
    <script src="<?php echo base_url() ?>/assets/dist/js/adminlte.js"></script>
    <script src="<?php echo base_url() ?>/assets/plugins/sweetalert2/sweetalert2.min.js"></script>
    <!-- <script src="<?php echo base_url() ?>/assets/dist/js/pages/dashboard.js"></script> -->
    <script src="<?php echo base_url() ?>/assets/dist/js/Sortable.min.js"></script>
    <script type="text/javascript">
      $(document).ready(function(){
        <?php 
          if ($this->session->flashdata("error")) {
        ?>
            toastError('<?php echo $this->session->flashdata("error"); ?>');
        <?php
            unset($_SESSION['error']);
          } elseif ($this->session->flashdata("success")) {
        ?>
            toastSuccess('<?php echo $this->session->flashdata("success"); ?>');
        <?php
            unset($_SESSION['success']);
          }
        ?>
        ?>

        $('.select2').select2({theme: 'bootstrap4'});
        
        $("input[data-bootstrap-switch]").each(function(){
          $(this).bootstrapSwitch('state', $(this).prop('checked'));
        });

      });

      function toastError(message) {
        return toastr.error(message);
      }
      function toastSuccess(message) {
        return toastr.success(message)
      }

      $('.date').daterangepicker({
        locale: {
          format: 'YYYY/MM/DD',
        },
          singleDatePicker: true,
          // showDropdowns: true,
        minYear: 2020,
      });
      $('.textarea').summernote({
        height: 250,
        toolbar: [
          // [groupName, [list of button]]
          ['style', ['bold', 'italic', 'underline', 'clear']],
          ['font', ['strikethrough', 'superscript', 'subscript']],
          ['fontsize', ['fontsize']],
          ['color', ['color']],
          ['para', ['ul', 'ol', 'paragraph']],
          ['height', ['height']]
        ]
      });

      function isNumber(evt) {
          var iKeyCode = (evt.which) ? evt.which : evt.keyCode
          if (iKeyCode != 46 && iKeyCode > 31 && (iKeyCode < 48 || iKeyCode > 57))
              return false;

          return true;
      }
    </script>
  </body>
</html>