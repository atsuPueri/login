<?php
$DBhost = "localhost";
$DBname = "test";
$DBuser = "root";
$DBpass = "";



// クッキーがあるとき
if (isset($_COOKIE["token"])) {
    // トークン取得
    $token = $_COOKIE["token"];
    $pdo = new PDO("mysql:host={$DBhost};dbname={$DBname}", $DBuser, $DBpass);

    // SQL作成
    $sql = "SELECT * ";
    $sql .= "FROM loginStatus ";
    $sql .= "WHERE token = '{$token}' ";
    $sql .= "AND '" . time() . "' < dateExpiry"; // 有効時間より前

    $array = $pdo->query($sql)->fetch();

    // 存在したら
    if (count($array) !== 0) {
        echo "画面遷移A";
    }
}

// サインインが押されたとき
if (isset($_POST["signIn"])) {

    $loginId = $_POST["loginId"];
    $password = $_POST["password"];
    try {

        $pdo = new PDO("mysql:host={$DBhost};dbname={$DBname}", $DBuser, $DBpass);

        // SQL作成
        $sql = "SELECT id, password ";
        $sql .= "FROM user ";
        $sql .= "WHERE loginId = '{$loginId}'";

        // 実行
        $array = $pdo->query($sql)->fetch();
        $checkPassword = $array["password"];
        $userId = $array["id"];

        // パスワードが一致したら
        if (password_verify($password, $checkPassword)) {

            // 有効期限
            $time = time() + (60 * 60) * 24;


            do {
                // token生成
                $bytes = random_bytes(256);
                $token = bin2hex($bytes);

                // INSERT句
                $sql = "INSERT INTO loginStatus(token, userId, dateExpiry) ";
                $sql .= "VALUES ('{$token}', '{$userId}', '{$time}')";
            } while (!$pdo->exec($sql));

            // クッキー発行
            setcookie("token", $token, $time);

            echo "画面遷移B";
        }
    } catch (PDOException $e) {
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
    <form action="./signIn.php" method="post">
        loginId:<input type="text" name="loginId">
        password:<input type="text" name="password">
        <button name="signIn">signIn</button>
    </form>
</body>

</html>