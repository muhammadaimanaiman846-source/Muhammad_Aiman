<?php require '../config/database.php';role('admin');$title='Penarikan';
if($_SERVER['REQUEST_METHOD']==='POST'){
 $id=(int)$_POST['id'];$act=$_POST['act']??'';
 try{$pdo->beginTransaction();
  $s=$pdo->prepare("SELECT * FROM withdrawals WHERE id=? AND status='pending' FOR UPDATE");$s->execute([$id]);$w=$s->fetch();
  if($w&&$act==='ok'){
   $b=$pdo->prepare('SELECT balance FROM users WHERE id=? FOR UPDATE');$b->execute([$w['user_id']]);
   if($b->fetchColumn()<$w['amount']){flash('Saldo nasabah tidak cukup.','danger');}
   else{$pdo->prepare('UPDATE users SET balance=balance-? WHERE id=?')->execute([$w['amount'],$w['user_id']]);
    $pdo->prepare("UPDATE withdrawals SET status='approved' WHERE id=?")->execute([$id]);
    $pdo->prepare("INSERT INTO transactions(user_id,type,amount,note) VALUES(?,'withdrawal',?,?)")->execute([$w['user_id'],$w['amount'],'Penarikan #'.$id]);
    audit($pdo,'approve withdrawal #'.$id);flash('Penarikan disetujui.');}
  }elseif($w&&$act==='no'){$pdo->prepare("UPDATE withdrawals SET status='rejected' WHERE id=?")->execute([$id]);audit($pdo,'reject withdrawal #'.$id);flash('Penarikan ditolak.','warning');}
  $pdo->commit();
 }catch(Exception $x){$pdo->rollBack();flash('Terjadi kesalahan.','danger');}
 back();
}
$r=$pdo->query('SELECT w.*,u.name FROM withdrawals w JOIN users u ON u.id=w.user_id ORDER BY w.status="pending" DESC,w.id DESC LIMIT 100')->fetchAll();include '../includes/header.php';?>
<h3 class="mb-3">Permintaan Penarikan</h3>
<div class="card eco-card">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <tr>
                <th>#</th>
                <th>Nasabah</th>
                <th>Jumlah</th>
                <th>Status</th>
                <th></th>
            </tr>
            <?php foreach($r as $x):?><tr>
                <td><?=$x['id']?></td>
                <td><?=e($x['name'])?></td>
                <td><?=rp($x['amount'])?></td>
                <td><?=e($x['status'])?></td>
                <td><?php if($x['status']==='pending'):?><form method="post" class="d-flex gap-1"
                        onsubmit="return confirm('Yakin?')"><?=csrf()?><input type="hidden" name="id"
                            value="<?=$x['id']?>"><button name="act" value="ok"
                            class="btn btn-sm btn-success">Setujui</button><button name="act" value="no"
                            class="btn btn-sm btn-outline-danger">Tolak</button></form><?php endif;?></td>
            </tr>
            <?php endforeach; if(!$r)echo '<tr><td colspan="5" class="text-center text-muted py-4">Belum ada permintaan.</td></tr>';?>
        </table>
    </div>
</div>
<?php include '../includes/footer.php';