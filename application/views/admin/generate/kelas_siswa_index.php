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
        <form action="<?= $own_link ?>" method="post">
          <div class="form-group row">
            <label for="periode_data" class="col-lg-2 col-sm-12 col-form-label">Periode</label>
            <div class="col-lg-6 col-sm-12">
              <select class="form-control select2" style="width: 100%;" name="periode_id">
                <option value="<?= $periode['id'] ?>"><?= $periode['tahun_ajaran'] ?></option>
              </select>
            </div>
          </div>
          <div class="form-group row">
            <label for="periode_data" class="col-lg-2 col-sm-12 col-form-label">Tingkat Kelas</label>
            <div class="col-lg-6 col-sm-12">
              <select class="form-control select2" style="width: 100%;" name="tingkat_kelas_id" required>
                <option value="">- Pilih Tingkat Kelas -</option>
                <?php foreach ($tingkat_kelas as $field): ?>
                  <option <?= $filter['tingkat_kelas_id'] == $field['id'] ? 'selected' : '' ?> value="<?= $field['id'] ?>"><?= $field['code'].' - '.$field['name'] ?></option>
                <?php endforeach ?>
              </select>
            </div>
          </div>
          <?php if (!empty($list_kelas)): ?>
            <div class="form-group row">
              <label for="list_kelas" class="col-lg-2 col-sm-12 col-form-label">Kelas</label>
              <div class="col-lg-6 col-sm-12">
                <select class="form-control select2" style="width: 100%;" name="kelas_id" required>
                  <option value="">- Pilih Kelas -</option>
                  <?php foreach ($list_kelas as $field): ?>
                    <option <?= $filter['kelas_id'] == $field['id'] ? 'selected' : '' ?> value="<?= $field['id'] ?>"><?= $field['kelas'] ?></option>
                  <?php endforeach ?>
                </select>
              </div>
            </div>
          <?php endif ?>
          <div class="mb-2">
            <button type="submit" class="btn btn-warning btn-flat">Filter</button>
          </div>
        </form>
        <br>
        
        <div class="mt-2">
          <div class="row">
            <div class="col-lg-6 col-sm-12">
              <div class="column">
                <h3>Siswa Baru</h3>
                <ul id="siswa-baru" class="list-siswa" data-id-kelas="0">
                    <?php foreach ($list_siswa['new'] as $siswa): ?>
                        <li data-id-siswa="<?= $siswa['id']; ?>"><?= $siswa['nisn'].' | '.$siswa['nama']; ?></li>
                    <?php endforeach; ?>
                </ul>
              </div>
            </div>
            <div class="col-lg-6 col-sm-12">
              <div class="column">
                  <h3>Siswa Saat Ini</h3>
                  <ul id="siswa-current" class="list-siswa" data-id-kelas="<?= $filter['kelas_id'] ?>">
                      <?php foreach ($list_siswa['current'] as $siswa): ?>
                          <li data-id-siswa="<?= $siswa['id']; ?>"><?= $siswa['nisn'].' | '.$siswa['nama']; ?></li>
                      <?php endforeach; ?>
                  </ul>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
  $(document).ready(function() {
      // Ambil semua elemen list siswa
      const listSiswaElements = document.querySelectorAll('.list-siswa');

      // Inisialisasi SortableJS untuk setiap list
      listSiswaElements.forEach(list => {
          new Sortable(list, {
              group: 'shared', // Nama grup yang sama agar bisa saling pindah
              animation: 150,
              ghostClass: 'placeholder', // Class untuk item bayangan saat di-drag

              // Event yang dijalankan setelah drop selesai
              onEnd: function (evt) {
                  const itemEl = evt.item;  // Element <li> yang dipindahkan
                  const toList = evt.to;    // List <ul> tujuan

                  const idSiswa = itemEl.dataset.idSiswa;
                  const idKelasBaru = toList.dataset.idKelas;

                  console.log(`Siswa ID: ${idSiswa} dipindahkan ke Kelas ID: ${idKelasBaru}`);

                  // Panggil fungsi untuk mengirim data via AJAX
                  updateKelasSiswa(idSiswa, idKelasBaru);
              }
          });
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

  function updateKelasSiswa(idSiswa, idKelasBaru) {
    var url = "<?php echo $own_link ?>/update";
    $.ajax({
        url : url,
        type : "POST",
        data: {
          "siswa_id": idSiswa,
          "kelas_id": idKelasBaru
        },
        dataType : "json",
        success:function(response)
        {
            if (response.status) {
              return toastSuccess(response.message);
            }
            return toastError(response.message);
        }
    });
  }
</script>