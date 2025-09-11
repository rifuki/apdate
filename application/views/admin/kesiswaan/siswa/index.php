<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Data <?php echo $judul ?></h3>
      </div>
      <div class="card-body">
          <div class="float-right">
            <?php if (!empty($active_periode) && $active_periode['status'] == '1'): ?>
              <a href="<?php echo $own_link ?>/create" class="btn btn-primary btn-flat">Tambah</a>
              <button type="button" class="btn btn-success btn-flat" id="btnImportData">Import Data</button>
            <?php endif ?>
            <a href="<?php echo $own_link ?>/export" class="btn btn-danger btn-flat">Export Excel</a>
          </div>
        <div class="table-responsive mt-5">
          <table id="datatable_serverside" class="table table-bordered table-striped">
            <thead>
              <tr>
                <th width="5%">No</th>
                <th>NISN</th>
                <th>Nama</th>
                <th>Tanggal Lahir</th>
                <th>Periode Bergabung</th>
                <th>Kelas Saat Ini</th>
                <th width="15%">Action</th>
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
<!-- Modal Import -->
<div class="modal fade" id="modalImport" tabindex="-1" role="dialog" aria-labelledby="modalImportLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalImportLabel">Import Data Siswa</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="importDataBody">
        <form action="<?php echo $own_link ?>/import" method="post" enctype="multipart/form-data">
          <div class="form-group row">
            <label for="jumlah_pertemuan" class="col-sm-12 col-md-12 col-form-label">Upload File Excel <span class="text-danger">*</span></label>
            <div class="col-sm-12 col-md-12">
              <input type="file" name="file" class="form-control" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" required>
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-12">
              <strong>Belum punya template?</strong> 
              <br>
              <a href="<?php echo $own_link ?>/template" class="btn btn-primary btn-flat">Download Template</a>
            </div>
          </div>
          <div class="form-group mt-5 float-right">
            <button type="button" data-dismiss="modal" class="btn btn-warning mr-2">Batal</a>
            <button type="submit" class="btn btn-success">Simpan</button>
          </div>
        </form>
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

      $("#btnImportData").on('click', function() {
        $('#modalImport').modal('show');
      })
  });

  function deleteConfirm(id) {
    var url = "<?php echo $own_link ?>/delete/" + id;
    var swal = Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to recover this data!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
      if (result.value) {
        window.location.href = url;
      }
    });
  }
</script>