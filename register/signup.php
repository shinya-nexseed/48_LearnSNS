<?php
// フォームからデータを受け取る
echo '<pre>';
var_dump($_POST);
echo '</pre>';

$errors = array();

// データが送信された時に処理
// $_POSTが空じゃない時 = データが送信された時
if (!empty($_POST)) {
    $name = $_POST['input_name'];

    // ユーザー名の空チェック
    if ($name == '') {
        echo 'ユーザー名を入力してください';
        $errors['name'] = '空';
    }

}

$name = '';
$email = 'hoge';
isset($name); // true
isset($hogehoge); // false
empty($name); // true
empty($fuga); // Undefined variable
empty($email); // false 


?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>Learn SNS</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="../assets/font-awesome/css/font-awesome.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/style.css">
</head>
<body style="margin-top: 60px">
    <div class="container">
        <div class="row">
            <div class="col-xs-8 col-xs-offset-2 thumbnail">
                <h2 class="text-center content_header">アカウント作成</h2>
                <form method="POST" action="signup.php" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="name">ユーザー名</label>
                        <input type="text" name="input_name" class="form-control" id="name" placeholder="山田 太郎"
                            value="">
                        <?php if(isset($errors['name']) && $errors['name'] == '空'): ?>
                          ユーザー名を入力してください
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label for="email">メールアドレス</label>
                        <input type="email" name="input_email" class="form-control" id="email" placeholder="example@gmail.com"
                            value="">
                    </div>
                    <div class="form-group">
                        <label for="password">パスワード</label>
                        <input type="password" name="input_password" class="form-control" id="password" placeholder="4 ~ 16文字のパスワード">
                    </div>
                    <div class="form-group">
                        <label for="img_name">プロフィール画像</label>
                        <input type="file" name="input_img_name" id="img_name" accept="image/*">
                    </div>
                    <input type="submit" class="btn btn-default" value="確認">
                    <span style="float: right; padding-top: 6px;">ログインは
                        <a href="../signin.php">こちら</a>
                    </span>
                </form>
            </div>
        </div>
    </div>
</body>
<script src="../assets/js/jquery-3.1.1.js"></script>
<script src="../assets/js/jquery-migrate-1.4.1.js"></script>
<script src="../assets/js/bootstrap.js"></script>
</html>