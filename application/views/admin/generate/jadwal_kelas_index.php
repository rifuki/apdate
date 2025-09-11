 <style>
        /* Sederhana styling untuk kejelasan */
        .column { flex: 1; padding: 15px; border: 1px solid #ccc; margin: 5px; background-color: #f9f9f9; }
        .list-siswa { list-style-type: none; padding: 0; min-height: 50px; background-color: #fff; border: 1px dashed #ddd; }
        .list-siswa li { padding: 10px; margin: 5px; background-color: #e9efff; border: 1px solid #b3c7ff; cursor: grab; }
    </style>
<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Data <?php echo $judul ?></h3>
      </div>
      <div class="card-body">
        <form id="frm-filter" action="<?= $own_link ?>" method="post">
          <div class="form-group row">
            <label for="periode_data" class="col-lg-2 col-sm-12 col-form-label">Periode</label>
            <div class="col-lg-6 col-sm-12">
              <input type="text" class="form-control" value="<?= $periode['tahun_ajaran'] ?>" disabled>
            </div>
          </div>
          <div class="form-group row">
            <label for="semester_id" class="col-lg-2 col-sm-12 col-form-label">Semester</label>
            <div class="col-lg-6 col-sm-12">
              <input type="text" class="form-control" value="<?= $periode['semester'] ?>" disabled>
            </div>
          </div>
        </form>
        <br>
        <div class="table-responsive mt-5">
          <table id="datatable" class="table table-bordered table-striped">
            <thead>
              <tr>
                <th width="5%">No</th>
                <th>Nama Kelas</th>
                <th>Wali Kelas</th>
                <th>Jadwal Kelas</th>
                <th width="15%">Action</th>
              </tr>
            </thead>
            <tbody>
              <?php if(!empty($list_kelas)): ?>
                <?php foreach ($list_kelas as $i => $v): ?>
                  <tr>
                    <td><?= $i + 1 ?></td>
                    <td><?= $v['kelas'] ?></td>
                    <td><?= $v['guru_nip'].' - '.$v['guru_nama'] ?></td>
                    <td>
                      <?php if($v['total_mapel'] > 0): ?>
                        <button data-toggle="modal" data-target="#staticBackdrop" type="button" data-kelasid="<?= $v['id'] ?>" class="btn btn-sm btn-success detail-jadwal">Lihat Jadwal</button>
                      <?php else: ?>
                        <button type="button" data-kelasid="<?= $v['id'] ?>" class="btn btn-sm btn-info generate-jadwal">Buat Jadwal Kelas</button>
                      <?php endif ?>
                    </td>
                    <td>
                      <?php if($v['total_mapel'] > 0): ?>
                        <button type="button" data-kelasid="<?= $v['id'] ?>" class="btn btn-sm btn-danger delete-jadwal">Delete Jadwal</button>
                      <?php endif ?>
                    </td>
                  </tr>
                <?php endforeach ?>
              <?php endif ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>


<!-- Modal -->
<div class="modal fade" id="jadwalKelas" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="jadwalKelasLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="jadwalKelasLabel">Jadwal Kelas <span id="jadwaKelas_namakelas"></span></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>Mata Pelajaran</th>
              <th>Guru</th>
              <th>Hari</th>
              <th>Jumlah Pertemuan</th>
            </tr>
          </thead>
          <tbody id="listMapel"></tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
  let periodeID   = `<?= $periode['periode_id'] ?>`;
  let semesterID  = `<?= $periode['id'] ?>`;
  $(document).ready(function() {
      $(".generate-jadwal").on('click', function() {
        let kelasID = $(this).data('kelasid');
        generateJadwalKelas(kelasID);
      });

      $(".detail-jadwal").on('click', function() {
        let kelasID = $(this).data('kelasid');
        detailJadwalKelas(kelasID);
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

  function detailJadwalKelas(kelasID) {
    var url = "<?php echo $own_link ?>/detail";
    $.ajax({
        url : url,
        type : "POST",
        data: {
          "periode_id": periodeID,
          "semester_id": semesterID,
          "kelas_id": kelasID
        },
        dataType : "json",
        complete:function(result)
        {
          let response = result.responseJSON;
            if (response.status) {
              let dataJadwal = response.data.jadwal;

              let html_jadwal = dataJadwal.map(function(item, i) {
                console.log(i, item);
                return `
                  <tr>
                    <td>${item.mapel_code} - ${item.mapel_name}</td>
                    <td>${item.guru_code} - ${item.guru_nama}</td>
                    <td>${item.hari}</td>
                    <td>${item.jumlah_pertemuan}</td>
                  </tr>
                `;
              });
              $("#listMapel").html(html_jadwal);
              $("#jadwalKelas").modal('show');
              return toastSuccess(response.message);
            }
            return toastError(response.message);
        }
    });
  }

  function generateJadwalKelas(kelasID) {
    var url = "<?php echo $own_link ?>/update";
    $.ajax({
        url : url,
        type : "POST",
        data: {
          "periode_id": periodeID,
          "semester_id": semesterID,
          "kelas_id": kelasID
        },
        dataType : "json",
        complete:function(result)
        {
          let response = result.responseJSON;
            if (response.status) {
              setTimeout(() => {
                document.location.reload();
              }, 1000);
              
              return toastSuccess(response.message);
            }
            return toastError(response.message);
        }
    });
  }
</script>