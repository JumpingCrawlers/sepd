
<?php

error_reporting(0);
ini_set('display_errors','Off');
$path_entornos="";
function login_user($user, $pass) {	

        $BD_server  = "localhost";
	$BD_user    = 'prod_sepd';
	$BD_pass    = 'laEo97_2'; 
	$BD_name    = 'produccion_sepd';

//        $BD_user    = 'root';
//	$BD_pass    = ''; 
//	$BD_name    = 'sepd_devadmin';
        

	if (!($link=@mysqli_connect($BD_server, $BD_user, $BD_pass, $BD_name))){
	    return false;
	}

	$sql 		= "
SELECT	u.id_usuario, u.nombre, u.apellidos, u.sexo
FROM 	usuarios AS u, perfiles_usuarios AS p
WHERE 	u.id_usuario = p.id_usuario
 AND	(p.id_perfil = 'SOC' OR p.id_perfil = 'SUP' OR p.id_perfil = 'NSC')
 AND	u.borrado = 0
 AND	u.email = '{$user}'
 AND 	(u.pass = '".md5($pass)."' OR u.pass = '".md5((int)$pass)."')
";
	$result = mysqli_query($link, $sql);
	if(mysqli_num_rows($result) > 0) {
		return true;
	}

	return false;
}

function normalize($string) {
    $return = addslashes(urldecode(trim($string)));
    return $return;
}

$user	= null;
$pass	= null;

if(isset($_POST['user']) && isset($_POST['pass'])) {
    
    $user   = normalize($_POST['user']);
    $pass   = normalize($_POST['pass']);
    
} elseif (isset($_GET['user']) && isset($_GET['pass'])) {
    
    $user   = normalize($_GET['user']);
    $pass   = normalize($_GET['pass']);
    
} else {
    print_r('KO');
    die();
}

if(login_user($user, $pass)) {
	print_r('OK');
} else {
	print_r('KO');
}