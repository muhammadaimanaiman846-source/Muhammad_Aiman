<?php
define('BASE','/ecobank');
session_set_cookie_params(['httponly'=>true,'samesite'=>'Lax']);
session_start();
try{$pdo=new PDO('mysql:host=localhost;dbname=ecobank_db;charset=utf8mb4','root','',[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC,PDO::ATTR_EMULATE_PREPARES=>false]);}
catch(PDOException $e){die('Koneksi database gagal. Import database/ecobank_db.sql dan cek config/database.php');}
function e($s){return htmlspecialchars((string)$s,ENT_QUOTES,'UTF-8');}
function csrf(){if(empty($_SESSION['t']))$_SESSION['t']=bin2hex(random_bytes(16));return '<input type="hidden" name="t" value="'.$_SESSION['t'].'">';}
function flash($m=null,$ty='success'){if($m){$_SESSION['f']=[$m,$ty];return;}$f=$_SESSION['f']??null;unset($_SESSION['f']);return $f;}
function go($p){header('Location: '.BASE.$p);exit;}
function back(){header('Location: '.$_SERVER['PHP_SELF']);exit;}
function role($r){if(empty($_SESSION['u'])||$_SESSION['u']['role']!==$r)go('/auth/login.php');}
function audit($pdo,$a){$pdo->prepare('INSERT INTO audit_logs(user_id,action) VALUES(?,?)')->execute([$_SESSION['u']['id']??null,$a]);}
function rp($n){return 'Rp '.number_format((float)$n,0,',','.');}
if($_SERVER['REQUEST_METHOD']==='POST'&&!hash_equals($_SESSION['t']??'',$_POST['t']??''))die('CSRF token tidak valid');
