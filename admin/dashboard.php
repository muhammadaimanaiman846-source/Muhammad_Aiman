<?php require '../config/database.php';role('admin');$title='Dashboard Admin';
$q=fn($s)=>$pdo->query($s)->fetchColumn();
$st=[['Nasabah',$q("SELECT COUNT(*) FROM users WHERE role='user'")],['Setoran Pending',$q("SELECT COUNT(*) FROM deposits WHERE status='pending'")],['Penarikan Pending',$q("SELECT COUNT(*) FROM withdrawals WHERE status='pending'")],['Kg Terverifikasi',$q("SELECT COALESCE(SUM(weight),0) FROM deposits WHERE status='verified'")]];
$ch=$pdo->query("SELECT w.name,SUM(d.weight) kg FROM deposits d JOIN waste_types w ON w.id=d.waste_type_id WHERE d.status='verified' GROUP BY w.id")->fetchAll();
include '../includes/header.php';?>
<h3 class="mb-3">Dashboard Admin</h3>
<div class="row g-3 mb-4"><?php foreach($st as $s):?><div class="col-6 col-md-3">
        <div class="card eco-card p-3">
            <div class="text-muted small"><?=$s[0]?></div>
            <div class="fs-3 fw-bold"><?=e($s[1])?></div>
        </div>
    </div><?php endforeach;?></div>
<div class="card eco-card p-3">
    <h6>Berat Sampah Terverifikasi per Jenis (kg)</h6><?php if($ch):?><canvas id="c" height="90"></canvas><?php else:?>
    <p class="text-muted text-center my-4">Belum ada data.</p><?php endif;?>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script>
const d = <?=json_encode($ch)?>;
if (d.length) new Chart(document.getElementById('c'), {
    type: 'bar',
    data: {
        labels: d.map(x => x.name),
        datasets: [{
            data: d.map(x => +x.kg),
            backgroundColor: '#2d6a4f'
        }]
    },
    options: {
        plugins: {
            legend: {
                display: false
            }
        }
    }
});
</script>
<?php include '../includes/footer.php';