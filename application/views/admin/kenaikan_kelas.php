<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Data <?php echo $judul ?></h3>
      </div>
      <div class="card-body">
        <form id="frm-kenaikan-kelas">
          <div class="form-group row">
            <label for="select2_ajax" class="col-lg-12 col-sm-12 col-form-label">Siswa Tidak Naik Kelas / Tidak Lulus</label>
            <div class="col-lg-12 col-sm-12">
              <select id="select2_ajax" class="form-control" style="width: 100%;" name="list_siswa[]" multiple="multiple">
              </select>
            </div>
          </div>
          <div class="mb-2">
            <button type="submit" class="btn btn-success btn-flat">Proses Kenaikan Kelas</button>
          </div>
        </form>
        <div class="table-responsive mt-5">
          <table id="datatable_serverside" class="table table-bordered table-striped">
            <thead>
              <tr>
                <th width="5%">No</th>
                <th>Kelas</th>
                <th>NISN</th>
                <th>Nama</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
  $(document).ready(function() {
      $('#datatable_serverside').DataTable({ 
          "processing": true, 
          "serverSide": true, 
          "order": [],
          "ajax": {
              "url": "<?= $own_link.'/datatables' ?>",
              "type": "POST"
          },
          "columnDefs": [
            { 
                "targets": [ 0 ], 
                "orderable": false, 
            }
          ],
      });

      $('#frm-kenaikan-kelas').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);
        $.ajax({
          type: "POST",
          url: "<?= $own_link.'/do_update' ?>",
          data: form.serialize(),
          dataType: "json",
          beforeSend: function() {
            form.find('button[type=submit]').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Processing...');
          },
          complete: function(result) {
            let response = result.responseJSON;
            if (response.status) {
              Swal.fire({
                icon: 'success',
                title: 'Success',
                text: response.message,
                timer: 2000,
                showConfirmButton: false
              });
              $('#datatable_serverside').DataTable().ajax.reload();
              form[0].reset();
            } else {
              Swal.fire({
                icon: 'error',
                title: 'Error',
                text: response.message,
              });
            }
            form.find('button[type=submit]').prop('disabled', false).html('Proses Kenaikan Kelas');
          }
        });
      });
  });

</script>