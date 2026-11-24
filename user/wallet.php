<?php require '../config/database.php';role('user');$title='Dompet';$id=$_SESSION['u']['id'];
$s=$pdo->prepare('SELECT balance FROM users WHERE id=?');$s->execute([$id]);$bal=$s->fetchColumn();
if($_SERVER['REQUEST_METHOD']==='POST'){
 $a=(float)($_POST['amount']??0);
 if($a<10000||$a>$bal)flash('Minimal Rp 10.000 dan tidak melebihi saldo.','danger');
 else{$pdo->prepare('INSERT INTO withdrawals(user_id,amount) VALUES(?,?)')->execute([$id,$a]);audit($pdo,'request withdrawal');flash('Permintaan penarikan dikirim.');}
 back();
}
$s=$pdo->prepare('SELECT * FROM transactions WHERE user_id=? ORDER BY id DESC LIMIT 50');$s->execute([$id]);$r=$s->fetchAll();
include '../includes/header.php';?>
<h3>Dompet</h3><div class="fs-2 fw-bold text-success mb-3"><?=rp($bal)?></div>
<form method="post" class="row g-2 mb-4"><?=csrf()?><div class="col-md-8"><input name="amount" type="number" min="10000" step="1000" class="form-control" placeholder="Jumlah penarikan (min. 10.000)" required></div><div class="col-md-4"><button class="btn btn-eco w-100">Ajukan Penarikan</button></div></form>
<div class="card eco-card"><table class="table mb-0"><tr><th>Tipe</th><th>Jumlah</th><th>Catatan</th><th>Tanggal</th></tr><?php foreach($r as $x):?><tr><td><?=e($x['type'])?></td><td><?=rp($x['amount'])?></td><td><?=e($x['note'])?></td><td><?=e($x['created_at'])?></td></tr><?php endforeach; if(!$r)echo '<tr><td colspan="4" class="text-center text-muted py-4">Belum ada transaksi.</td></tr>';?></table></div>
<?php include '../includes/footer.php';
