<?php require '../config/database.php';role('admin');$title='Jenis Sampah';
if($_SERVER['REQUEST_METHOD']==='POST'){
 $a=$_POST['act']??'';$p=(float)($_POST['price']??0);
 if($a==='add'&&trim($_POST['name']??'')!==''&&$p>0){$pdo->prepare('INSERT INTO waste_types(name,price_per_kg) VALUES(?,?)')->execute([trim($_POST['name']),$p]);audit($pdo,'add waste type');flash('Jenis sampah ditambahkan.');}
 elseif($a==='price'&&$p>0){$pdo->prepare('UPDATE waste_types SET price_per_kg=? WHERE id=?')->execute([$p,(int)$_POST['id']]);audit($pdo,'update price #'.(int)$_POST['id']);flash('Harga diperbarui.');}
 elseif($a==='toggle'){$pdo->prepare('UPDATE waste_types SET active=1-active WHERE id=?')->execute([(int)$_POST['id']]);flash('Status diubah.');}
 else flash('Input tidak valid.','danger');
 back();
}
$r=$pdo->query('SELECT * FROM waste_types ORDER BY id')->fetchAll();include '../includes/header.php';?>
<h3 class="mb-3">Jenis & Harga Sampah</h3>
<form method="post" class="row g-2 mb-3"><?=csrf()?><input type="hidden" name="act" value="add">
    <div class="col-md-5"><input name="name" class="form-control" placeholder="Nama jenis" required></div>
    <div class="col-md-4"><input name="price" type="number" min="1" step="1" class="form-control"
            placeholder="Harga per kg" required></div>
    <div class="col-md-3"><button class="btn btn-eco w-100">Tambah</button></div>
</form>
<div class="card eco-card">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <tr>
                <th>Jenis</th>
                <th>Harga / kg</th>
                <th>Status</th>
                <th></th>
            </tr>
            <?php foreach($r as $x):?><tr>
                <td><?=e($x['name'])?></td>
                <td>
                    <form method="post" class="d-flex gap-1"><?=csrf()?><input type="hidden" name="act"
                            value="price"><input type="hidden" name="id" value="<?=$x['id']?>"><input name="price"
                            type="number" step="1" min="1" value="<?=(int)$x['price_per_kg']?>"
                            class="form-control form-control-sm" style="width:120px"><button
                            class="btn btn-sm btn-outline-success">Simpan</button></form>
                </td>
                <td><?=$x['active']?'Aktif':'Nonaktif'?></td>
                <td>
                    <form method="post"><?=csrf()?><input type="hidden" name="act" value="toggle"><input type="hidden"
                            name="id" value="<?=$x['id']?>"><button class="btn btn-sm btn-outline-secondary">Ubah
                            status</button></form>
                </td>
            </tr><?php endforeach;?>
        </table>
    </div>
</div>
<?php include '../includes/footer.php';