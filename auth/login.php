<?php require '../config/database.php';
if($_SERVER['REQUEST_METHOD']==='POST'){
 $s=$pdo->prepare('SELECT * FROM users WHERE email=?');$s->execute([trim($_POST['email']??'')]);$u=$s->fetch();
 if($u&&password_verify($_POST['password']??'',$u['password'])){
  session_regenerate_id(true);$_SESSION['u']=['id'=>$u['id'],'name'=>$u['name'],'role'=>$u['role']];
  audit($pdo,'login');go('/'.$u['role'].'/dashboard.php');
 }flash('Email atau password salah.','danger');back();
}
$title='Masuk';include '../includes/header.php';?>
<div class="row justify-content-center"><div class="col-md-5"><div class="card eco-card p-4"><h4>Masuk</h4>
<form method="post"><?=csrf()?><label class="form-label mt-2">Email</label><input type="email" name="email" class="form-control" required>
<label class="form-label mt-2">Password</label><input type="password" name="password" class="form-control" required>
<button class="btn btn-eco w-100 mt-3">Masuk</button></form><p class="mt-3 mb-0 small">Belum punya akun? <a href="register.php">Daftar</a></p></div></div></div>
<?php include '../includes/footer.php';
