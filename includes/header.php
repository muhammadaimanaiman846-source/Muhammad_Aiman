<?php $u=$_SESSION['u']??null; $f=flash(); ?>
<!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title><?=e($title??'EcoBank')?> - EcoBank</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link href="<?=BASE?>/assets/css/style.css" rel="stylesheet"></head><body>
<nav class="navbar navbar-expand-lg navbar-dark eco-nav"><div class="container"><a class="navbar-brand fw-bold" href="<?=BASE?>/"><i class="bi bi-recycle"></i> EcoBank</a>
<button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#n"><span class="navbar-toggler-icon"></span></button>
<div class="collapse navbar-collapse" id="n"><ul class="navbar-nav ms-auto align-items-lg-center">
<?php
if(!$u)$l=['/'=>'Beranda','/waste-prices.php'=>'Harga Sampah','/auth/login.php'=>'Masuk','/auth/register.php'=>'Daftar'];
elseif($u['role']=='admin')$l=['/admin/dashboard.php'=>'Dashboard','/admin/waste-types.php'=>'Jenis Sampah','/admin/deposits.php'=>'Setoran','/admin/withdrawals.php'=>'Penarikan'];
elseif($u['role']=='officer')$l=['/officer/dashboard.php'=>'Verifikasi Setoran'];
else $l=['/user/dashboard.php'=>'Dashboard','/user/deposits.php'=>'Setoran','/user/wallet.php'=>'Dompet'];
foreach($l as $h=>$t)echo '<li class="nav-item"><a class="nav-link" href="'.BASE.$h.'">'.$t.'</a></li>';
if($u)echo '<li class="nav-item"><a class="btn btn-sm btn-light ms-lg-2" href="'.BASE.'/auth/logout.php">Keluar ('.e($u['name']).')</a></li>';
?></ul></div></div></nav><main class="container py-4">
<?php if($f):?><div class="alert alert-<?=e($f[1])?> alert-dismissible"><?=e($f[0])?><button class="btn-close" data-bs-dismiss="alert"></button></div><?php endif;?>
