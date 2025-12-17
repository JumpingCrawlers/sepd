<?php
/**
 * @author Alexis Bogado <alexis.bogado@s3w.es>
 */

error_reporting(0);
ini_set('display_errors','Off');

$user = null;
$pass = null;

if (isset($_POST['user']) && isset($_POST['pass'])):
    $user = normalize($_POST['user']);
    $pass = normalize($_POST['pass']);
elseif (isset($_GET['user']) && isset($_GET['pass'])):
    $user = normalize($_GET['user']);
    $pass = normalize($_GET['pass']);
else:
    die('KO');
endif;

require_once __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Http\Kernel')->handle(Illuminate\Http\Request::capture());

if (Illuminate\Support\Facades\Auth::attempt(['email' => $user, 'password' => $pass])): // Check Laravel authentication
	die('OK');
elseif (App\User::checkLoginViejo($user, $pass)): // Check old platform authentication
	die('OK');
else:
	die('KO');
endif;

function normalize($string) {
    $return = addslashes(urldecode(trim($string)));
    return $return;
}
