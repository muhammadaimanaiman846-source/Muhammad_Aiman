<?php require 'config/database.php';
$demo=[['Admin EcoBank','admin@ecobank.test','admin'],['Petugas EcoBank','petugas@ecobank.test','officer'],['Warga Demo','warga@ecobank.test','user']];
foreach($demo as $d){$c=$pdo->prepare('SELECT id FROM users WHERE email=?');$c->execute([$d[1]]);
 if(!$c->fetch())$pdo->prepare('INSERT INTO users(name,email,password,role) VALUES(?,?,?,?)')->execute([$d[0],$d[1],password_hash('Password123',PASSWORD_DEFAULT),$d[2]]);}
echo 'Akun demo dibuat. Hapus file install.php setelah selesai. <a href="auth/login.php">Masuk</a>';
