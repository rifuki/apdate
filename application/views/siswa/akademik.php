<?php
// Misal, variabel $active_periode sudah di-set dari controller
$active_periode = isset($active_periode) ? $active_periode : '2023/2024';
?>

<div class="card" style="max-width:600px;margin:auto;">
  <div class="card-header bg-primary text-white">
    Anda berada di semester <?= semesterText($active_periode['semester']).' TA '.$active_periode['tahun_ajaran'] ?>
  </div>
  <div class="card-body">
    <canvas id="myChart" width="400" height="200"></canvas>
  </div>
</div>

<?php
// Cari file Chart.js di folder apdate_v2
$chartjs_path = base_url('assets/chartjs/chart.min.js'); // Ganti sesuai path Chart.js Anda
?>
<script src="<?= $chartjs_path ?>"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  var ctx = document.getElementById('myChart').getContext('2d');
  var myChart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: ['A', 'B', 'C', 'D', 'E'],
      datasets: [{
        label: 'Contoh Data',
        data: [12, 19, 3, 5, 2],
        backgroundColor: 'rgba(54, 162, 235, 0.5)',
        borderColor: 'rgba(54, 162, 235, 1)',
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      scales: {
        y: { beginAtZero: true }
      }
    }
  });
});
</script>