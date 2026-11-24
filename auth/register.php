<?php require '../config/database.php';
if($_SERVER['REQUEST_METHOD']==='POST'){
 $n=trim($_POST['name']??'');$m=trim($_POST['email']??'');$p=$_POST['password']??'';
 if($n===''||!filter_var($m,FILTER_VALIDATE_EMAIL)||strlen($p)<8){flash('Data tidak valid (password min. 8 karakter).','danger');back();}
 $c=$pdo->prepare('SELECT id FROM users WHERE email=?');$c->execute([$m]);
 if($c->fetch()){flash('Email sudah terdaftar.','danger');back();}
 $pdo->prepare("INSERT INTO users(name,email,password,role) VALUES(?,?,?,'user')")->execute([$n,$m,password_hash($p,PASSWORD_DEFAULT)]);
 flash('Registrasi berhasil, silakan masuk.');go('/auth/login.php');
}
$title='Daftar';include '../includes/header.php';?>
<div class="row justify-content-center"><div class="col-md-5"><div class="card eco-card p-4"><h4>Daftar Nasabah</h4>
<form method="post"><?=csrf()?><label class="form-label mt-2">Nama</label><input name="name" class="form-control" required>
<label class="form-label mt-2">Email</label><input type="email" name="email" class="form-control" required>
<label class="form-label mt-2">Password</label><input type="password" name="password" minlength="8" class="form-control" required>
<button class="btn btn-eco w-100 mt-3">Daftar</button></form></div></div></div>
<?php include '../includes/footer.php';
