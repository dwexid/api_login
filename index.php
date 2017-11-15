<?php

require 'vendor/autoload.php';
require 'libs/NotOrm.php';

$app = new \Slim\App;

function getConnect(){
	require_once 'include/dbHandler.php';
	$db = new dbHandler();
	return $db;
}

$app->get('/', function(){
	echo "hello world !!";
});

$app->post('/login', function($req, $res, $arg) use($app){
	$db = getConnect();
	$user = $req->getParam('username');
	$pass = md5($req->getParam('password'));
	if($result = $db->verifyLogin($user, $pass)){
		echo json_encode($result);
	}else{
		echo json_encode(array(
			'status'	=> false,
			'message'	=> 'Data tidak ditemukan'));
	}
});

$app->get('/users/{auth}', function($req, $res, $arg) use($app){
	$db = getConnect();
	if(isset($arg['auth'])){
		if($db->validate($arg['auth'])){
			$result = $db->getUsers();
			echo json_encode($result);
		}else{
			echo json_encode(array(
				'status'	=> false,
				'message'	=> 'Api key belum terdaftar'));
		}
	}
});

$app->get('/user/{id}/{auth}', function($req, $res, $arg) use($app){
	$db = getConnect();
	if(isset($arg['auth'])){
		if($db->validate($arg['auth'])){
			if($db->getUserById($arg['id'])){
				$result = $db->getUserById($arg['id']);
				echo json_encode($result);
			}
		}else{
			echo json_encode(array(
				'status'	=> false,
				'message'	=> 'Api key belum terdaftar'));
		}
	}
});

//Register user
$app->post('/user', function($req, $res, $arg) use($app){
	$db = getConnect();	
	$data = $req->getParams();
	if($result = $db->createUser($data))
		echo json_encode(array('status'	=> $result));
	else
		echo json_encode(array(
			'status'	=> false,
			'message'	=> 'Gagal menambahkan data'));
});

$app->put('/user/{id}/{auth}', function($req, $res, $arg) use($app){
	$db = getConnect();
	if(isset($arg['auth'])){
		if($db->validate($arg['auth'])){	
			$data = $req->getParams();
			$result = $db->updateUser($arg['id'], $data);
			echo json_encode(array(
				'status'	=> $result));
		}else{
			echo json_encode(array(
				'status'	=> false,
				'message'	=> 'Api key belum terdaftar'));
		}
	}
});

$app->get('/ujian/{auth}', function($req, $res, $arg) use($app){
	$db = getConnect();
	if(isset($arg['auth'])){
		if($db->validate($arg['auth'])){
			$result = $db->getUjian();
			echo json_encode($result);
		}else{
			echo json_encode(array(
				'status'	=> false,
				'message'	=> 'Api key belum terdaftar'));
		}
	}
});

$app->post('/ujian/{auth}', function($req, $res, $arg) use($app){
	$db = getConnect();
	if(isset($arg['auth'])){
		if($db->validate($arg['auth'])){
			$data = $req->getParams();
			$result = $db->createUjian($data);
			echo json_encode(array(
				'status'	=> $result));
		}else{
			echo json_encode(array(
				'status'	=> false,
				'message'	=> 'Api key belum terdaftar'));
		}
	}
});

$app->get('/ujian/{id}/{auth}', function($req, $res, $arg) use($app){
	$db = getConnect();
	if(isset($arg['auth'])){
		if($db->validate($arg['auth'])){
			if($db->getUserById($arg['id'])){
				$result = $db->getUjianById($arg['id']);
				echo json_encode($result);
			}
		}else{
			echo json_encode(array(
				'status'	=> false,
				'message'	=> 'Api key belum terdaftar'));
		}
	}
});

$app->get('/ujian/user/{username}/{auth}', function($req, $res, $arg) use($app){
	$db = getConnect();
	if(isset($arg['auth'])){
		if($db->validate($arg['auth'])){
			if($db->getUjianByUsername($arg['username'])){
				$result = $db->getUjianByUsername($arg['username']);
				echo json_encode($result);
			}
		}else{
			echo json_encode(array(
				'status'	=> false,
				'message'	=> 'Api key belum terdaftar'));
		}
	}
});


$app->delete('/user/{id}/{auth}', function($req, $res, $arg) use($app){
	$db = getConnect();
	if(isset($arg['auth'])){
		if($db->validate($arg['auth'])){
			if(isset($arg['id'])){
				$delete = $db->deleteUser($arg['id']);
				echo json_encode(array(
					'status'	=> $delete));
			}
		}else{
			echo json_encode(array(
				'status'	=> false,
				'message'	=> 'Api key belum terdaftar'));
		}
	}
});

$app->delete('/ujian/{id}/{auth}', function($req, $res, $arg) use($app){
	$db = getConnect();
	if(isset($arg['auth'])){
		if($db->validate($arg['auth'])){
			if(isset($arg['id'])){
				$delete = $db->deleteUjian($arg['id']);
				echo json_encode(array(
					'status'	=> $delete));
			}
		}else{
			echo json_encode(array(
				'status'	=> false,
				'message'	=> 'Api key belum terdaftar'));
		}
	}
});

$app->run();
?>