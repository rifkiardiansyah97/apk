<?php
declare(strict_types=1);
$c=require __DIR__.'/config.php'; date_default_timezone_set($c['timezone']);
ini_set('display_errors','0');ini_set('log_errors','1');
$secure=(!empty($_SERVER['HTTPS'])&&$_SERVER['HTTPS']!=='off');
session_name('SERVISPROSESSID');session_set_cookie_params(['lifetime'=>0,'path'=>'/','secure'=>$secure,'httponly'=>true,'samesite'=>'Lax']);session_start();
header('X-Content-Type-Options: nosniff');header('X-Frame-Options: DENY');header('Referrer-Policy: strict-origin-when-cross-origin');header('Permissions-Policy: camera=(), microphone=(), geolocation=()');
header("Content-Security-Policy: default-src 'self'; style-src 'self' 'unsafe-inline'; script-src 'self'; form-action 'self'; frame-ancestors 'none'; base-uri 'self'");
if($secure) header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
try{$pdo=new PDO("mysql:host={$c['db']['host']};port={$c['db']['port']};dbname={$c['db']['name']};charset={$c['db']['charset']}",$c['db']['user'],$c['db']['pass'],[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC,PDO::ATTR_EMULATE_PREPARES=>false]);}catch(Throwable $e){http_response_code(500);exit('Database connection error.');}
function e($v):string{return htmlspecialchars((string)$v,ENT_QUOTES|ENT_SUBSTITUTE,'UTF-8');}
function csrf():string{if(empty($_SESSION['csrf']))$_SESSION['csrf']=bin2hex(random_bytes(32));return $_SESSION['csrf'];}
function check_csrf():void{if(!hash_equals($_SESSION['csrf']??'',$_POST['csrf']??'')){http_response_code(419);exit('Invalid CSRF token.');}}
function user():array{if(empty($_SESSION['user'])){header('Location: login.php');exit;}return $_SESSION['user'];}
function role(array $roles):array{$u=user();if(!in_array($u['role'],$roles,true)){http_response_code(403);exit('Forbidden');}return $u;}
function audit($pdo,$uid,$action,$detail=''):void{$s=$pdo->prepare("INSERT INTO audit_logs(user_id,action,detail,ip_address)VALUES(?,?,?,?)");$s->execute([$uid,$action,substr($detail,0,500),$_SERVER['REMOTE_ADDR']??'']);}
