<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Tingkat Kelas</h3>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-4 mb-4">
            <div class="card shadow h-100 border-0">
              <div class="card-body d-flex flex-column justify-content-between">
                <div class="align-items-center mb-3" style="height:10rem;">
                  <div class="bg-primary text-white d-flex align-items-center justify-content-center mr-3" style="width:100%;height:100%;font-size:1.5rem;border-radius:5%">
                    <i class="fa fa-book" style="font-size:80px;"></i>
                  </div>
                </div>
                <div>
                  <div class="font-weight-bold" style="font-size:1.1rem;">Pantau Kelas 7</div>
                </div>
                <hr style="border: 1px solid black; width:100%">
                <button class="btn btn-block btn-outline-primary btnMulaiPembelajaran mt-auto" data-tingkatkelas="7">
                  Lihat List Kelas
                </button>
              </div>
            </div>
          </div>
          <div class="col-md-4 mb-4">
            <div class="card shadow h-100 border-0">
              <div class="card-body d-flex flex-column justify-content-between">
                <div class="align-items-center mb-3" style="height:10rem;">
                  <div class="bg-success text-white d-flex align-items-center justify-content-center mr-3" style="width:100%;height:100%;font-size:1.5rem;border-radius:5%">
                    <i class="fa fa-book" style="font-size:80px;"></i>
                  </div>
                </div>
                <div>
                  <div class="font-weight-bold" style="font-size:1.1rem;">Pantau Kelas 8</div>
                </div>
                <hr style="border: 1px solid black; width:100%">
                <button class="btn btn-block btn-outline-success btnMulaiPembelajaran mt-auto" data-tingkatkelas="8">
                  Lihat List Kelas
                </button>
              </div>
            </div>
          </div>
          <div class="col-md-4 mb-4">
            <div class="card shadow h-100 border-0">
              <div class="card-body d-flex flex-column justify-content-between">
                <div class="align-items-center mb-3" style="height:10rem;">
                  <div class="bg-danger text-white d-flex align-items-center justify-content-center mr-3" style="width:100%;height:100%;font-size:1.5rem;border-radius:5%">
                    <i class="fa fa-book" style="font-size:80px;"></i>
                  </div>
                </div>
                <div>
                  <div class="font-weight-bold" style="font-size:1.1rem;">Pantau Kelas 9</div>
                </div>
                <hr style="border: 1px solid black; width:100%">
                <button class="btn btn-block btn-outline-danger btnMulaiPembelajaran mt-auto" data-tingkatkelas="9">
                  Lihat List Kelas
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.btnMulaiPembelajaran').forEach(function(btn) {
      btn.addEventListener('click', function(e) {
          var id = this.getAttribute('data-tingkatkelas');
          window.location.href = '<?= base_url('dashboard/lms/kelas/') ?>' + id;
      });
    });
  });
</script>