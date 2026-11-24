<?php require '../config/database.php';role('user');$title='Dashboard';$id=$_SESSION['u']['id'];
$s=$pdo->prepare('SELECT balance FROM users WHERE id=?');$s->execute([$id]);$bal=$s->fetchColumn();
$s=$pdo->prepare("SELECT COALESCE(SUM(weight),0) FROM deposits WHERE user_id=? AND status='verified'");$s->execute([$id]);$kg=$s->fetchColumn();
$s=$pdo->prepare('SELECT d.*,w.name wname FROM deposits d JOIN waste_types w ON w.id=d.waste_type_id WHERE d.user_id=? ORDER BY d.id DESC LIMIT 5');$s->execute([$id]);$r=$s->fetchAll();
include '../includes/header.php';?>
<h3 class="mb-3">Halo, <?=e($_SESSION['u']['name'])?></h3><div class="row g-3 mb-4"><div class="col-md-6"><div class="card eco-card p-3"><div class="text-muted">Saldo</div><div class="fs-2 fw-bold text-success"><?=rp($bal)?></div></div></div><div class="col-md-6"><div class="card eco-card p-3"><div class="text-muted">Total Setoran Terverifikasi</div><div class="fs-2 fw-bold"><?=e($kg)?> kg</div></div></div></div>
<div class="card eco-card"><div class="card-header bg-white">Setoran Terakhir</div><table class="table mb-0"><?php foreach($r as $x):?><tr><td><?=e($x['wname'])?></td><td><?=e($x['weight'])?> kg</td><td><?=rp($x['amount'])?></td><td><?=e($x['status'])?></td></tr><?php endforeach; if(!$r)echo '<tr><td class="text-center text-muted py-4">Belum ada setoran. <a href="deposits.php">Ajukan sekarang</a></td></tr>';?></table></div>
<?php include '../includes/footer.php';
