<?php
if($_SERVER['REQUEST_METHOD']==='POST'){
 $id=(int)$_POST['id'];$act=$_POST['act']??'';
 try{$pdo->beginTransaction();
  $s=$pdo->prepare("SELECT * FROM deposits WHERE id=? AND status='pending' FOR UPDATE");$s->execute([$id]);$d=$s->fetch();
  if($d&&$act==='ok'){
   $pdo->prepare("UPDATE deposits SET status='verified',verified_by=? WHERE id=?")->execute([$_SESSION['u']['id'],$id]);
   $pdo->prepare("UPDATE users SET balance=balance+? WHERE id=?")->execute([$d['amount'],$d['user_id']]);
   $pdo->prepare("INSERT INTO transactions(user_id,type,amount,note) VALUES(?,'deposit',?,?)")->execute([$d['user_id'],$d['amount'],'Setoran #'.$id]);
   audit($pdo,'verify deposit #'.$id);flash('Setoran diverifikasi, saldo bertambah.');
  }elseif($d&&$act==='no'){
   $pdo->prepare("UPDATE deposits SET status='rejected',verified_by=? WHERE id=?")->execute([$_SESSION['u']['id'],$id]);
   audit($pdo,'reject deposit #'.$id);flash('Setoran ditolak.','warning');
  }else flash('Setoran tidak ditemukan / sudah diproses.','danger');
  $pdo->commit();
 }catch(Exception $x){$pdo->rollBack();flash('Terjadi kesalahan.','danger');}
 back();
}
$rows=$pdo->query("SELECT d.*,u.name uname,w.name wname FROM deposits d JOIN users u ON u.id=d.user_id JOIN waste_types w ON w.id=d.waste_type_id ORDER BY d.status='pending' DESC,d.id DESC LIMIT 100")->fetchAll();
include __DIR__.'/header.php';?>
<h3 class="mb-3">Verifikasi Setoran</h3><div class="card eco-card"><div class="table-responsive"><table class="table align-middle mb-0">
<tr><th>#</th><th>Nasabah</th><th>Jenis</th><th>Berat</th><th>Nilai</th><th>Status</th><th></th></tr>
<?php foreach($rows as $r):?><tr><td><?=$r['id']?></td><td><?=e($r['uname'])?></td><td><?=e($r['wname'])?></td><td><?=e($r['weight'])?> kg</td><td><?=rp($r['amount'])?></td>
<td><span class="badge bg-<?=['pending'=>'warning','verified'=>'success','rejected'=>'danger'][$r['status']]?>"><?=e($r['status'])?></span></td>
<td><?php if($r['status']==='pending'):?><form method="post" class="d-flex gap-1" onsubmit="return confirm('Yakin?')"><?=csrf()?><input type="hidden" name="id" value="<?=$r['id']?>">
<button name="act" value="ok" class="btn btn-sm btn-success">Terima</button><button name="act" value="no" class="btn btn-sm btn-outline-danger">Tolak</button></form><?php endif;?></td></tr>
<?php endforeach; if(!$rows)echo '<tr><td colspan="7" class="text-center text-muted py-4">Belum ada setoran.</td></tr>';?></table></div></div>
<?php include __DIR__.'/footer.php';
