<?php


// サインアップされたとき
if (isset($_POST["signUp"])) {
    
    // 受け取り
    $loginId = $_POST["loginId"];
    $password = $_POST["password"];
    $password = password_hash($password, PASSWORD_DEFAULT);
    $name = $_POST["name"];

    try {
        
        $DBhost = "localhost";
        $DBname = "test";
        $DBuser = "root";
        $DBpass = "";

        $pdo = new PDO("mysql:host={$DBhost};dbname={$DBname}", $DBuser, $DBpass);


        $sql = "INSERT INTO user (loginId, password, name) ";
        $sql .= "VALUES ('{$loginId}', '{$password}', '{$name}')";
        $pdo->exec($sql);
        echo $sql;

    } catch(PDOException $e) {
        echo "エラー:" . $e->getMessage();
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="./signUp.php" method="post">
        name:<input type="text" name="name">
        loginIdID:<input type="text" name="loginId">
        password:<input type="text" name="password">
        <button name="signUp">signUp</button>
    </form>
</body>
</html>