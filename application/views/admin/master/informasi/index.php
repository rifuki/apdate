<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Data <?php echo $judul ?></h3>
      </div>
      <div class="card-body">
        <?php if ($is_create == 1): ?>
          <div class="float-right">
            <a href="<?php echo $own_link ?>/create" class="btn btn-primary btn-flat">Tambah</a>
          </div>
        <?php endif ?>
        <div class="table-responsive mt-5">
          <table id="datatable_serverside" class="table table-bordered table-striped">
            <thead>
              <tr>
                <th width="5%">No</th>
                <th>Judul</th>
                <th>Tanggal</th>
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
  });

  function deleteConfirm(id) {
    var url = "<?= $own_link ?>/delete/" + id;
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