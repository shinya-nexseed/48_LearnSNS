<?php
session_start();

// フォームからデータを受け取る
echo '<pre>';
var_dump($_POST);
echo '</pre>';

$errors = array();
$name = '';
$email = '';

// データが送信された時に処理
// $_POSTが空じゃない時 = データが送信された時
if (!empty($_POST)) {
    $name = $_POST['input_name'];
    $email = $_POST['input_email'];
    $password = $_POST['input_password'];

    // ユーザー名の空チェック
    if ($name == '') {
        $errors['name'] = 'blank';
    }

    // メールアドレスの空チェック
    if ($email == '') {
        $errors['email'] = 'blank';
    }

    // パスワードの空チェック
    $count = strlen($password);
    if ($password == '') {
        $errors['password'] = 'blank';
    } elseif ($count < 4 || 16 < $count) {
        $errors['password'] = 'length';
    }


    // 画像拡張子チェック
    $file_name = $_FILES['input_img_name']['name'];
    if (!empty($file_name)) {
        // 画像を選択した場合
        $file_type = substr($file_name, -3);
        $file_type = strtolower($file_type);
        if ($file_type != 'jpg' && $file_type != 'png' && $file_type != 'gif') {
            $errors['img_name'] = 'type';
        }
    } else {
        // 画像を選択してなかった場合
        $errors['img_name'] = 'blank';
    }



    // 何か入力に問題があった時 = $errorsの中になんか入ってる時
    // すべての入力項目が正常に入力されていた時 = $errorsの中が空の時
    // empty()に突っ込んでtrueって返す時
    if (empty($errors)) {

        $date = date('YmdHis');
        $file_name = $date . $file_name;

        // move_uploaded_file(画像データ, アップロード先);
        move_uploaded_file($_FILES['input_img_name']['tmp_name'], '../user_profile_img/'.$file_name);

        $_SESSION['48_LearnSNS']['name'] = $name;
        $_SESSION['48_LearnSNS']['email'] = $email;
        $_SESSION['48_LearnSNS']['password'] = $password;
        $_SESSION['48_LearnSNS']['file_name'] = $file_name;

        // check.phpへ遷移する
        header('Location: check.php');
        exit();
    }

}



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
                            value="<?php echo $name; ?>">
                        <?php if(isset($errors['name']) && $errors['name'] == 'blank'): ?>
                          <span class="text-danger">ユーザー名を入力してください</span>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label for="email">メールアドレス</label>
                        <input type="email" name="input_email" class="form-control" id="email" placeholder="example@gmail.com"
                            value="<?php echo $email; ?>">
                        <?php if(isset($errors['email']) && $errors['email'] == 'blank'): ?>
                          <span class="text-danger">メールアドレスを入力してください</span>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label for="password">パスワード</label>
                        <input type="password" name="input_password" class="form-control" id="password" placeholder="4 ~ 16文字のパスワード">
                        <?php if(isset($errors['password']) && $errors['password'] == 'blank'): ?>
                          <span class="text-danger">パスワードを入力してください</span>
                        <?php endif; ?>
                        <?php if(isset($errors['password']) && $errors['password'] == 'length'): ?>
                          <span class="text-danger">パスワードは4 ~ 16文字で入力してください</span>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label for="img_name">プロフィール画像</label>
                        <input type="file" name="input_img_name" id="img_name" accept="image/*">
                        <?php if(isset($errors['img_name']) && $errors['img_name'] == 'blank'): ?>
                          <span class="text-danger">プロフィール画像を選択してください</span>
                        <?php endif; ?>
                        <?php if(isset($errors['img_name']) && $errors['img_name'] == 'type'): ?>
                          <span class="text-danger">プロフィール画像は「jpg」「png」「gif」の画像を選択してください</span>
                        <?php endif; ?>
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