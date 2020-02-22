<?php
	require_once 'dbconnect.php';
?>
<!DOCTYPE html>
<html>
<head>
	<title>Тест</title>
	<style type="text/css">
		.us_table {
			display: block;
			overflow: hidden;
		}
		.us_table div {
			display: block;
 		   float: left;
 		   width: 100%;
		}
		.us_table p {
			border: 1px solid #000;
			border-collapse: collapse;
			border-collapse: collapse;
			padding: 10px;
			width: 10%;
			float: left;
			display: block;
		}
	</style>
</head>
<body>
	<p>Добавить роль</p>
	<div>
		<form method="POST" action="">
			<input type="text" name="rolename" placeholder="Имя Роли" />
			<input type="submit" name="Отправить" />
		</form>
	</div>
	<p>Добавить пользователя</p>
	<div>
		<form method="POST" action="">
			<input type="text" name="username" placeholder="Имя пользователя">
			<select id="role_id" name="roleid">
				<?php
					$mysqli = new mysqli($host, $user, $pass, $db);
					if ($mysqli->connect_error) {
						die('Ошибка : (' . $mysqli->connect_error . ')');
					}
					$result = $mysqli->query("Select id, rolename from user_role");
					if ($result) {
						$rows = $result->num_rows;
						for ($i=0; $i < $rows; ++$i) {
							$row = $result->fetch_array();
							echo '<option value="' . $row['id'] . '">' . $row['rolename'] . '</option>';
						}
					}
					$mysqli->close();
				 ?>
			</select>
			<input type="submit" name="Сохранить" value="Сохранить">

		</form>
	</div>

	<div>
		<p>Список пользователей</p>
		<div class="us_table">
			<table border="1">
				<tr>
					<td>Имя пользователя</td>
					<td>Роль пользователя</td>
				</tr>
			<?php
				$mysqli = new mysqli($host, $user, $pass, $db);
				if ($mysqli->connect_error) {
					die('Ошибка : (' . $mysqli->connect_error . ')');
				}
				$result = $mysqli->query("Select us.id, us.username, usrole.rolename from user us join user_role usrole on usrole.id = us.role_id");
				if ($result) {
					$rows = $result->num_rows;
					while ($row = $result->fetch_array()) {
						echo '<tr><td>' . $row['username'] . '</td><td>' . $row['rolename'] . '</td></tr>';
					}
				}
				$mysqli->close();
			 ?>
			 </table>
		</div>
	</div>

</body>
</html>