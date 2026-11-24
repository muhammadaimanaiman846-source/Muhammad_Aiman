<?php require 'config/database.php';$title='Beranda';
$n=$pdo->query("SELECT COUNT(*) FROM users WHERE role='user'")->fetchColumn();
$kg=$pdo->query("SELECT COALESCE(SUM(weight),0) FROM deposits WHERE status='verified'")->fetchColumn();
$t=$pdo->query("SELECT COUNT(*) FROM waste_types WHERE active=1")->fetchColumn();
include 'includes/header.php';?>
<section class="hero text-center p-5 mb-4"><h1 class="display-5 fw-bold">Ubah Sampah Jadi Saldo</h1><p class="lead">EcoBank – bank sampah digital untuk lingkungan yang lebih bersih.</p>
<a href="auth/register.php" class="btn btn-light btn-lg">Mulai Menabung</a></section>
<div class="row g-3 text-center"><?php foreach([['Nasabah',$n,'people'],['Kg Terverifikasi',$kg,'box-seam'],['Jenis Sampah',$t,'recycle']] as $c):?>
<div class="col-md-4"><div class="card eco-card p-4"><i class="bi bi-<?=$c[2]?> fs-2 text-success"></i><div class="fs-2 fw-bold"><?=e($c[1])?></div><?=$c[0]?></div></div><?php endforeach;?></div>
<?php include 'includes/footer.php';
