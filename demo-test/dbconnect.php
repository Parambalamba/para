<?php
	$host = 'localhost';
	$db = 'freetest-bd';
	$user = 'parambala';
	$pass = 'lbkzhf23121982';

if (isset($_POST['rolename'])) {
	$rolename = $_POST['rolename'];
	$mysqli = new mysqli($host, $user, $pass, $db);

	if ($mysqli->connect_error) {
		die('Ошибка : (' . $mysqli->connect_error . ')');
	}

	$result = $mysqli->query("Insert into user_role (rolename) values ('$rolename')");

	if ($result == true){
		echo "Информация занесена в базу данных";
	}else{
		echo "Ошибка: " . $mysqli->error;
	}
	$mysqli->close();
}
if ((isset($_POST['roleid'])) && (isset($_POST['username']))) {
	$roleid = $_POST['roleid'];
	$username = $_POST['username'];
	$mysqli = new mysqli($host, $user, $pass, $db);

	if ($mysqli->connect_error) {
		die('Ошибка : (' . $mysqli->connect_error . ')');
	}

	$result = $mysqli->query("Insert into user (username, role_id) values ('$username', '$roleid')");

	if ($result == true){
		echo "Информация занесена в базу данных";
	}else{
		echo "Ошибка: " . $mysqli->error;
	}
	$mysqli->close();

}
 ?>