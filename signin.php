<?php
require('dbconnect.php');
session_start();

echo '<pre>';
var_dump($_POST);
echo '</pre>';

$errors = [];

// ユーザーがデータを送信したとき = $_POSTが空じゃなかったとき
// $_POST が empty() ! とき

if (!empty($_POST)) {
    $email = $_POST['input_email'];
    $password = $_POST['input_password'];

    if ($email != '' && $password != '') {
        $sql = 'SELECT * FROM `users` WHERE `email` = ?';
        $data = [$email];
        $stmt = $dbh->prepare($sql);
        $stmt->execute($data);
        $record = $stmt->fetch(PDO::FETCH_ASSOC);

        echo '<pre>';
        var_dump($record);
        echo '</pre>';

        if ($record == false) {
            $errors['signin'] = 'failed';
        } else {
            if (password_verify($password, $record['password'])) {
                // サインインの処理
                $_SESSION['48_LearnSNS']['id'] = $record['id'];
                header('Location: timeline.php');
                exit();
            } else {
                $errors['signin'] = 'failed';
            }
        }

    } else {
        $errors['signin'] = 'blank';
    }
}

?>
<?php include('layouts/header.php'); ?>
<body style="margin-top: 60px">
    <div class="container">
        <div class="row">
            <div class="col-xs-8 col-xs-offset-2 thumbnail">
                <h2 class="text-center content_header">サインイン</h2>
                <form method="POST" action="signin.php" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="email">メールアドレス</label>
                        <input type="email" name="input_email" class="form-control" id="email" placeholder="example@gmail.com">
                        <?php if(isset($errors['signin']) && $errors['signin'] == 'blank'): ?>
                          <p class="text-danger">メールアドレスとパスワードを正しく入力してください</p>
                        <?php endif; ?>
                        <?php if(isset($errors['signin']) && $errors['signin'] == 'failed'): ?>
                          <p class="text-danger">サインインに失敗しました</p>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label for="password">パスワード</label>
                        <input type="password" name="input_password" class="form-control" id="password" placeholder="4 ~ 16文字のパスワード">
                    </div>
                    <input type="submit" class="btn btn-info" value="サインイン">
                    <span style="float: right; padding-top: 6px;">
                        <a href="index.php">戻る</a>
                    </span>
                </form>
            </div>
        </div>
    </div>
</body>
<?php include('layouts/header.php'); ?>
</html>
