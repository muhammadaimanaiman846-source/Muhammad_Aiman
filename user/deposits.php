<?php require '../config/database.php';role('user');$title='Setoran';$id=$_SESSION['u']['id'];
if($_SERVER['REQUEST_METHOD']==='POST'){
 $w=(float)($_POST['weight']??0);$s=$pdo->prepare('SELECT * FROM waste_types WHERE id=? AND active=1');$s->execute([(int)($_POST['type']??0)]);$t=$s->fetch();
 if(!$t||$w<=0||$w>1000)flash('Input tidak valid.','danger');
 else{$pdo->prepare('INSERT INTO deposits(user_id,waste_type_id,weight,amount) VALUES(?,?,?,?)')->execute([$id,$t['id'],$w,round($w*$t['price_per_kg'])]);audit($pdo,'submit deposit');flash('Setoran diajukan, menunggu verifikasi petugas.');}
 back();
}
$types=$pdo->query('SELECT * FROM waste_types WHERE active=1')->fetchAll();
$s=$pdo->prepare('SELECT d.*,w.name wname FROM deposits d JOIN waste_types w ON w.id=d.waste_type_id WHERE d.user_id=? ORDER BY d.id DESC');$s->execute([$id]);$r=$s->fetchAll();
include '../includes/header.php';?>
<h3 class="mb-3">Ajukan Setoran</h3><form method="post" class="row g-2 mb-4"><?=csrf()?><div class="col-md-5"><select name="type" class="form-select" required><?php foreach($types as $t):?><option value="<?=$t['id']?>"><?=e($t['name'])?> – <?=rp($t['price_per_kg'])?>/kg</option><?php endforeach;?></select></div>
<div class="col-md-4"><input name="weight" type="number" step="0.01" min="0.01" max="1000" class="form-control" placeholder="Berat (kg)" required></div><div class="col-md-3"><button class="btn btn-eco w-100">Ajukan</button></div></form>
<div class="card eco-card"><table class="table mb-0"><tr><th>Jenis</th><th>Berat</th><th>Nilai</th><th>Status</th><th>Tanggal</th></tr><?php foreach($r as $x):?><tr><td><?=e($x['wname'])?></td><td><?=e($x['weight'])?> kg</td><td><?=rp($x['amount'])?></td><td><?=e($x['status'])?></td><td><?=e($x['created_at'])?></td></tr><?php endforeach; if(!$r)echo '<tr><td colspan="5" class="text-center text-muted py-4">Belum ada setoran.</td></tr>';?></table></div>
<?php include '../includes/footer.php';
