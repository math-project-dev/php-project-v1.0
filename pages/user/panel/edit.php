﻿<?
	ob_start();
	session_start();
	require_once '../../../config.php';

	if (!isset($_SESSION['user']) ) {
		header("Location: http://174.129.143.211/pages/user/login.php");
		exit;
	}
	
	$res = mysql_query("SELECT * FROM users WHERE userId=" .$_SESSION['user']);
	$userRow = mysql_fetch_array($res);
	
	if ($userRow['statusID'] < 2 ) {
		header("Location: http://174.129.143.211/");
		exit;
	}
	
	if ($_GET['id'] > 0 )
	{
		$id = (int) $_GET['id'];
	}
		else if ($_GET['id'] === 'new' )  
	{
		// код про новое задание
	} 
		else 
	{
		
		header("Location: http://174.129.143.211/pages/user/panel/list.php");
		exit;
	}
	
	$query = mysql_query('SELECT id, type, tasks, answer  FROM answers WHERE tableID = ' . $id); 
	$row = mysql_fetch_assoc($query);
	
?>
	
	<!DOCTYPE html>
	<html style="background: #b9e9e8;" class="app">
		<head>
			<meta charset="utf-8">
			<title>Справочно-обучающее электронное пособие по математике</title>
			<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
			<meta name="theme-color" content="#1e6d74">
			<link rel="stylesheet" href="../../../css/style.css">
		</head>
		<body>
			<header>
					<div class="logo">
						<a href="http://174.129.143.211/pages/user/panel/list.php" alt="Вернуться назад" style="z-index: 1;">
						  <img src="../../../../../img/ege.png" alt="">
						</a>
					</div>
				 <span style="top: 50px; position: absolute; right: 0px; padding-right: 700px; font-size: 2rem;">РЕЖИМ РЕДАКТИРОВАНИЯ
				 <br>ЗАДАНИЙ</span>	
			</header>
				<main style="background: none;">
					<div class="preview">
					<? if ($row['type'] == 1) {
						$type = "БАЗОВЫЙ";
					   } else {
						$type = "ПРОФИЛЬНЫЙ";
					   }?>
					<div class="task-name"><?=$type?> УРОВЕНЬ / ПОЗИЦИЯ: #<?=$row['tasks'] ?><br>ЗАДАНИЕ #<?=$row['id'] ?></div>
					<div class="preview-task">
					   <div>УСЛОВИЕ:</div>
					   <img alt="Задание" src="../../../pages/ege/tasks/type-<?=$row['type'] ?>/0<?=$row['tasks'] ?>/0<?=$row['tasks'] ?>_0<?=$row['id'] ?>.PNG" /><br>
					   <div style="margin-bottom: 20px;">
						  <form enctype="multipart/form-data" method="POST">
							 <input  type="hidden" name="MAX_FILE_SIZE" value="100000" style="display:none;" />
							 <input name="uploadedfile" type="file" />
							 <input class="profile-buttons" type="submit" value="Загрузить" />
						  </form>
						  
						  <?
							 $target_path = "../../../pages/ege/tasks/type-". $row['type'] ."/0". $row['tasks'] ."/";
							 
							 $filename = "0". $row['tasks']. "_0". $row['id'] .".PNG";

							 $target_path = $target_path . basename( $filename ); 
									
							 if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path)) 
							 { ?>
								<span class="upload-text" Файл <?  echo basename( $_FILES['uploadedfile']['name']) ?> был упешно загружен! </span>
						  <? } ?>
						  
					   </div>
					   <div>РЕШЕНИЕ:</div>
					   <img alt="Ответ" src="../../../pages/ege/tasks/type-<?=$row['type'] ?>/answer/0<?=$row['tasks'] ?>/0<?=$row['tasks'] ?>_0<?=$row['id'] ?>.PNG" />
					   <div style="margin-bottom: 20px;">
						  <form enctype="multipart/form-data" method="POST">
							 <input  type="hidden" name="MAX_FILE_SIZE" value="100000" style="display:none;" />
							 <input name="uploadedfile" type="file" />
							 <input class="profile-buttons" type="submit" value="Загрузить" />
						  </form>
						  
						  <?
							 $target_path = "../../../pages/ege/tasks/type-". $row['type'] ."/answer/0". $row['tasks'] ."/";
							 
							 $filename = "0". $row['tasks']. "_0". $row['id'] .".PNG";

							 $target_path = $target_path . basename( $filename ); 
								
							 if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path)) 
							 { ?>
						  <span class="upload-text" Файл <?  echo basename( $_FILES['uploadedfile']['name']) ?> был упешно загружен! </span>
						  <? } ?>
						  
						 <form action="<?=$_SERVER['PHP_SELF']; ?>" method="post">
							<fieldset>
								<div data-role="fieldcontain">
									<label for="status" style="text-size: 1rem">Ответ к заданию:</label>
										<textarea name="status"></textarea>
									</select>
								</div>
								<input type="submit" data-theme="a" name="submit" value="Отправить"></input>
							</fieldset>
						</form>
						
						<?
							if(isset($_POST['submit'])) {
								$query_w = "UPDATE answers SET answer = '". $_POST['status']."' WHERE tableID = ". (int) $_GET['id'] ."";
								mysql_query($query_w);
							}
						?>
						  
					   </div>
					</div>
				</div>
			</main>
		</body>
	</html>
	
<? ob_end_flush(); ?>