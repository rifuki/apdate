<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Data <?php echo $judul ?></h3>
      </div>
      <div class="card-body">
        <form action="<?= $own_link ?>" method="post">
          <div class="form-group row">
            <label for="periode_data" class="col-lg-2 col-sm-12 col-form-label">Periode</label>
            <div class="col-lg-10 col-sm-12">
              <select class="form-control select2" style="width: 100%;" name="periode_id">
                <?php foreach($periode_list as $periode): ?>
                  <option value="<?= $periode['id'] ?>" <?= $filter['periode_id'] == $periode['id'] ? 'selected' : '' ?>><?= $periode['tahun_ajaran'] ?></option>
                <?php endforeach ?> 
              </select>
            </div>
          </div>
          
            <div class="float-right mb-5">
              <button type="submit" class="btn btn-warning btn-flat">Filter</button>
              <?php if ($is_create == 1): ?>
                <a href="<?php echo $own_link ?>/create" class="btn btn-primary btn-flat">Tambah</a>
              <?php endif ?>
            </div>
        </form>
        <div class="table-responsive mt-5">
          <table id="datatable_serverside" class="table table-bordered table-striped">
            <thead>
              <tr>
                <th width="5%">No</th>
                <th>Nama Kelas</th>
                <th>Wali Kelas</th>
                <th>Mata Pelajaran</th>
                <th>Siswa</th>
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

<!-- Modal for List Siswa -->
<div class="modal fade" id="modalListSiswa" tabindex="-1" role="dialog" aria-labelledby="modalListSiswaLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalListSiswaLabel">Daftar Siswa</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <table class="table table-bordered" id="tableListSiswa">
          <thead>
            <tr>
              <th>NISN</th>
              <th>Nama</th>
              <th>Tanggal Lahir</th>
            </tr>
          </thead>
          <tbody>
            <!-- Data siswa akan diisi via JS -->
          </tbody>
        </table>
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
              "url":  "<?= $own_link.'/datatables' ?>",
              "type": "POST",
              "data": {
                filter: <?= json_encode($filter) ?>
              }
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

  function listSiswa(id) {
    var url = "<?php echo $own_link ?>/list-siswa/" + id;
    $.ajax({
        url : url,
        type : "GET",
        data: {},
        dataType : "json",
        success:function(response)
        {
            let data = response.data;
            var tbody = '';
            if (Array.isArray(data) && data.length > 0) {
              data.forEach(function(siswa) {
                tbody += '<tr>' +
                  '<td>' + siswa.nisn + '</td>' +
                  '<td>' + siswa.nama + '</td>' +
                  '<td>' + siswa.tanggal_lahir + '</td>' +
                  '</tr>';
              });
            } else {
              tbody = '<tr><td colspan="3" class="text-center">Tidak ada data siswa</td></tr>';
            }
            $('#tableListSiswa tbody').html(tbody);
            $('#modalListSiswa').modal('show');
        }
    });
  }
</script>