<html>
  <head>
    <meta charset="utf-8" />
  </head>
<body>
  <?php 
//名前、コメントの定義
	$edit_name="お名前";
	$edit_comment="コメント";
	$edit_number="";
//テキスト定義
//	$filename="mission_5-1.txt";
//データベース作成
	$dsn = 'mysql:dbname=データベース名;host=localhost';
	$user = 'ユーザー名';
	$password = 'パスワード';
	$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
//テーブル作成
	$sql = "CREATE TABLE IF NOT EXISTS mission5"
	." ("
	. "id INT AUTO_INCREMENT PRIMARY KEY,"
	. "name char(32),"
	. "comment TEXT,"
	. "date DATETIME,"
	. "pass char(12)"
	.");";
	$stmt = $pdo->query($sql);

/*			$sql = 'drop table mission5;';
			$stmt = $pdo->prepare($sql);
			$stmt->execute();*/
//■■■「条件分岐:編集フォーム」■■■
	if(!empty($_POST["edi"])){	
//編集番号・パスワードが入力されているかを確認
		if(!empty($_POST["edit"])&&!empty($_POST["pass_edit"])){
			$id = $_POST["edit"];
			$pass = $_POST["pass_edit"];
			$sql = 'select * from mission5 where id=:id and pass=:pass';
			$stmt = $pdo->prepare($sql);
			$stmt->bindParam(':id', $id, PDO::PARAM_INT);
			$stmt->bindParam(':pass', $pass, PDO::PARAM_STR);
			$stmt->execute();
			$results = $stmt->fetchAll();
			foreach($results as $loop){
				$edit_number=$loop['id'];
				$edit_name=$loop['name'];
				$edit_comment=$loop['comment'];
			}
		}else{
			echo "Error:Delete_number or Password is Empty."."<br>";
		}
	}
  ?>
<!フォームからPOST送信＆受け取り/!>
  <form action="mission_5-1.php" method="post">
  	<input type="text" name="name" size="30" value="<?php echo $edit_name;?>" /><br />
  	<input type="text" name="comment" size="30" value= "<?php echo $edit_comment;?>" /><br />
  	<input type="text" name="pass_send" size="30" value= "パスワード" />
  	<input type="hidden" name="editnumber" size="30" value= "<?php echo $edit_number;?>" />
  	<input type="submit" name="send" value="送信" /><br /><br />
	<input type="text" name="delete" size="30" value="削除対象番号" /><br />
  	<input type="text" name="pass_delete" size="30" value= "パスワード" />
  	<input type="submit" name="del"value="削除" /><br /><br />
	<input type="text" name="edit" size="30" value="編集対象番号" /><br />
  	<input type="text" name="pass_edit" size="30" value= "パスワード" />
  	<input type="submit" name="edi"value="編集" />

  </form>    

  <?php
//■■■「条件分岐:送信フォーム」■■■

	if(!empty($_POST["send"])){
//コメント・名前・パスワードが入力されているかを確認
		if(!empty($_POST["comment"])&&!empty($_POST["name"])&&!empty($_POST["pass_send"])){
			$name=$_POST["name"];
			$comment=$_POST["comment"];
			$date=date("Y-m-d H:i:s");
			$pass=$_POST["pass_send"];
	//■追加書き込み■
			if(empty($_POST["editnumber"])){	//編集番号がないことを確認	
				$sql = $pdo -> prepare("INSERT INTO mission5 (name, comment, date, pass) VALUES (:name, :comment, :date, :pass)");
				$sql -> bindParam(':name', $name, PDO::PARAM_STR);
				$sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
				$sql -> bindParam(':date', $date, PDO::PARAM_STR);
				$sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
				$sql -> execute();
			}
	//■編集書き込み■
			if(!empty($_POST["editnumber"])){	//編集番号があることを確認
				$id = $_POST["editnumber"]; //変更する投稿番号
				$sql = 'update mission5 set name=:name,comment=:comment,date=:date,pass=:pass where id=:id and pass=:pass';
				$stmt = $pdo->prepare($sql);
				$stmt->bindParam(':id', $id, PDO::PARAM_INT);
				$stmt->bindParam(':name', $name, PDO::PARAM_STR);
				$stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
				$stmt->bindParam(':date', $date, PDO::PARAM_STR);
				$stmt->bindParam(':pass', $pass, PDO::PARAM_STR);
				$stmt->execute();
			}
		}else{
			echo "Error:Comment or Name or Password is Empty."."<br>";
		}
	}
//■■■「条件分岐:削除フォーム」■■■	
	if(!empty($_POST["del"])){			
//削除番号・パスワードが入力されているかを確認
		if(!empty($_POST["delete"])&&!empty($_POST["pass_delete"])){
			$id = $_POST["delete"];
			$pass = $_POST["pass_delete"];
			$sql = 'delete from mission5 where id=:id and pass=:pass';
			$stmt = $pdo->prepare($sql);
			$stmt->bindParam(':id', $id, PDO::PARAM_INT);
			$stmt->bindParam(':pass', $pass, PDO::PARAM_STR);
			$stmt->execute();
		}else{
			echo "Error:Delete_number or Password is Empty."."<br>";
		}
	}
//ファイル表示
//	if (file_exists($filename)) {
//		$content = file($filename);    //file関数：テキストファイルをphpで使える配列に収納
//		foreach($content as $value){
//			$bunnkatsu = explode("<>", $value);	//分割
//			echo htmlspecialchars($bunnkatsu[0]." ".$bunnkatsu[1]." ".$bunnkatsu[2]." ".$bunnkatsu[3], ENT_QUOTES)."<br>";
//		}
//	}
	$sql = 'SELECT * FROM mission5';
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
		//$rowの中にはテーブルのカラム名が入る
		echo $row['id'].',';
		echo $row['name'].',';
		echo $row['comment'].',';
		echo $row['date'].'<br>';
		echo "<hr>";
	}
  ?>
</body>
</html>	
