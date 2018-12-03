<?php
session_start();
require('../dbconnect.php'); // ①DB接続

if (!isset($_SESSION['48_LearnSNS'])) {
    header('Location: signup.php');
    exit();
}

$name = $_SESSION['48_LearnSNS']['name'];
$email = $_SESSION['48_LearnSNS']['email'];
$password = $_SESSION['48_LearnSNS']['password'];
$file_name = $_SESSION['48_LearnSNS']['file_name'];

// ユーザー登録ボタンを押した時 = $_POSTが空じゃない時
if (!empty($_POST)) {
    // ②処理
    $sql = 'INSERT INTO `users` SET `name`=?, `email`=?, `password`=?, `img_name`=?, `created`=NOW()';
    $data = array($name, $email, password_hash($password, PASSWORD_DEFAULT), $file_name);
    $stmt = $dbh->prepare($sql);
    $stmt->execute($data);
    
    // ③切断
    $dbh = null;

    // thanks.phpに遷移
    header('Location: thanks.php');
    exit();
}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>Learn SNS</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="../assets/font-awesome/css/font-awesome.css">
</head>
<body style="margin-top: 60px">
    <div class="container">
        <div class="row">
            <div class="col-xs-8 col-xs-offset-2 thumbnail">
                <h2 class="text-center content_header">アカウント情報確認</h2>
                <div class="row">
                    <div class="col-xs-4">
                        <img src="../user_profile_img/<?php echo htmlspecialchars($file_name) ?>" class="img-responsive img-thumbnail">
                    </div>
                    <div class="col-xs-8">
                        <div>
                            <span>ユーザー名</span>
                            <p class="lead"><?php echo htmlspecialchars($name); ?></p>
                        </div>
                        <div>
                            <span>メールアドレス</span>
                            <p class="lead"><?php echo htmlspecialchars($email); ?></p>
                        </div>
                        <div>
                            <span>パスワード</span>
                            <p class="lead">●●●●●●●●</p>
                        </div>
                        <form method="POST" action="check.php">
                            <a href="signup.php?action=rewrite" class="btn btn-default">&laquo;&nbsp;戻る</a> | 
                            <input type="hidden" name="action" value="submit">
                            <input type="submit" class="btn btn-primary" value="ユーザー登録">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="../assets/js/jquery-3.1.1.js"></script>
    <script src="../assets/js/jquery-migrate-1.4.1.js"></script>
    <script src="../assets/js/bootstrap.js"></script>
</body>
</html>