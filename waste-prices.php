<?php require 'config/database.php';$title='Harga Sampah';
$r=$pdo->query('SELECT * FROM waste_types WHERE active=1 ORDER BY name')->fetchAll();include 'includes/header.php';?>
<h3>Harga Sampah</h3><div class="card eco-card"><table class="table mb-0"><tr><th>Jenis</th><th>Harga / kg</th></tr>
<?php foreach($r as $x):?><tr><td><?=e($x['name'])?></td><td><?=rp($x['price_per_kg'])?></td></tr><?php endforeach;?></table></div>
<?php include 'includes/footer.php';
