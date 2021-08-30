<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_5-1</title>
</head>
<body>
    <?php
        //データベース接続
        $dsn = 'データベース名';
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
        . "pass char(32)"
        .");";
        $stmt = $pdo->query($sql);
        
        //新規投稿
        if(!empty($_POST["name"]) && !empty($_POST["comment"]) && !empty($_POST["pass"]) && empty($_POST["edinum"])) {
            $name = $_POST["name"];
            $comment = $_POST["comment"];
            $pass = $_POST["pass"];
            $date = date("Y/m/d H:i:s");
            $sql = $pdo -> prepare("INSERT INTO mission5 (name, comment, date, pass) VALUES (:name, :comment, :date, :pass)");
            $sql -> bindParam(':name', $name, PDO::PARAM_STR);
            $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
            $sql -> bindParam(':date', $date, PDO::PARAM_STR);
            $sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
            $sql -> execute();
        }
        
        //削除
        if(!empty($_POST["delete"]) && !empty($_POST["delpass"])) {
            $delete = $_POST["delete"];
            $delpass = $_POST["delpass"];
            $sql = 'SELECT * FROM mission5';
            $stmt = $pdo->query($sql);
            $results = $stmt->fetchAll();
            foreach ($results as $row){
                if($row['id'] == $delete && $row['pass'] == $delpass) {
                    $sql = 'delete from mission5 where id=:id';
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':id', $delete, PDO::PARAM_INT);
                    $stmt->execute();
                }
            }
        }
        
        //編集選択
        if(!empty($_POST["edit"]) && !empty($_POST["edipass"])) {
            $edit = $_POST["edit"];
            $edipass = $_POST["edipass"];
            $sql = 'SELECT * FROM mission5';
            $stmt = $pdo->query($sql);
            $results = $stmt->fetchAll();
            foreach ($results as $row) {
                if($row['id'] == $edit && $row['pass'] == $edipass) {
                    $enum = $row['id'];
                    $ename = $row['name'];
                    $ecomment = $row['comment'];
                    $epass = $row['pass'];
                }
            }
        }
    ?>
    
    <h2>掲示板</h2>
    <form action="" method="post">
        <input type="text" name="name" placeholder="名前" value="<?php if(!empty($_POST["edit"]) && !empty($_POST["edipass"]) && $row['id'] == $edit && $row['pass'] == $edipass){echo $ename;}?>">
        <input type="text" name="comment" placeholder="コメント" value="<?php if(!empty($_POST["edit"]) && !empty($_POST["edipass"])){echo $ecomment;}?>">
        <input type="hidden" name="edinum" value="<?php if(!empty($_POST["edit"]) && !empty($_POST["edipass"])){echo $enum;}?>">
        <input type="text" name="pass" placeholder="パスワード" value="<?php if(!empty($_POST["edit"]) && !empty($_POST["edipass"])){echo $epass;}?>">
        <input type="submit" name="submit" value="送信">
    </form>
    <form action="" method="post">
        <input type="number" name="delete" placeholder="削除対象番号">
        <input type="text" name="delpass" placeholder="パスワード">
        <input type="submit" name="submit" value="削除">
    </form>
    <form action="" method="post">
        <input type="number" name="edit" placeholder="編集対象番号">
        <input type="text" name="edipass" placeholder="パスワード">
        <input type="submit" name="submit" value="編集">
    </form>
    
    <?php
       //表示
        $sql = 'SELECT * FROM mission5';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach ($results as $row){
            echo $row['id'].',';
            echo $row['name'].',';
            echo $row['comment'].',';
            echo $row['date'].'<br>';
            echo "<hr>";
        }
    ?>
</body>
</html>