<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Data <?php echo $judul ?></h3>
      </div>
      <div class="card-body">
        <div class="table-responsive mt-5">
          <table id="datatable_serverside" class="table table-bordered table-striped">
            <thead>
              <tr>
                <th width="5%">No</th>
                <th>NISN</th>
                <th>Nama</th>
                <th>Tanggal Lahir</th>
                <th>Periode Bergabung</th>
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

      $("#btnImportData").on('click', function() {
        $('#modalImport').modal('show');
      })
  });
</script>