<?php
session_start();
require('dbconnect.php');
require('signin_user.php');

$errors = [];

// ユーザーが投稿ボタンを押したら発動
if (!empty($_POST)) {

    // バリデーション
    $feed = $_POST['feed']; // 投稿データ

    // 投稿の空チェック
    if ($feed != '') {
        // 投稿処理
        $sql = 'INSERT INTO `feeds` (`feed`, `user_id`, `created`) VALUES (?, ?, NOW())';
        $data = [$feed, $signin_user['id']];
        $stmt = $dbh->prepare($sql);
        $stmt->execute($data);

        header('Location: timeline.php');
        exit();
    } else {
        $errors['feed'] = 'blank';
    }
}

// ページネーション
const CONTENT_PER_PAGE = 8; // 定数

if (isset($_GET['page'])) {
    $page = $_GET['page']; // ページ数の初期化
} else {
    $page = 1;
}

// 1以下の値を入れられた際、1で上書き
$page = max($page, 1);

$sql_count = "SELECT COUNT(*) AS `cnt` FROM `feeds`";
$stmt_count = $dbh->prepare($sql_count);
$stmt_count->execute();
$record_cnt = $stmt_count->fetch(PDO::FETCH_ASSOC);

// 最後のページが何ページになるのか算出  
$last_page = ceil($record_cnt['cnt'] / CONTENT_PER_PAGE);

// 最後のページより大きい値を渡された場合の対策
$page = min($page, $last_page);

$start = ($page - 1) * CONTENT_PER_PAGE;

$sql = 'SELECT f.*, u.name, u.img_name AS profile_img FROM feeds AS f LEFT JOIN users AS u ON f.user_id = u.id ORDER BY f.created DESC LIMIT ' . CONTENT_PER_PAGE . ' OFFSET ' . $start;
$data = [];
$stmt = $dbh->prepare($sql);
$stmt->execute($data);

$feeds = array();

while(true) {
    $feed = $stmt->fetch(PDO::FETCH_ASSOC);

    // fetch → （連想配列にして）取得する
    // 1fetch 1recordまでしか取得できない

    // echo '<pre>';
    // var_dump($feed);
    // echo '</pre>';

    // もし$feedがfalseだったら
    if ($feed == false) {
        break;
    }

    $feeds[] = $feed;
}

// echo '<pre>';
// var_dump($feeds);
// echo '</pre>';


// foreach練習
// foreach ($feeds as $feed) {
//     echo $feed['profile_img'];
//     echo '<br>';
// }

?>
<?php include('layouts/header.php'); ?>
<body style="margin-top: 60px; background: #E4E6EB;">
    <?php include('navbar.php'); ?>
    <div class="container">
        <div class="row">
            <div class="col-xs-3">
                <ul class="nav nav-pills nav-stacked">
                    <li class="active"><a href="timeline.php?feed_select=news">新着順</a></li>
                    <li><a href="timeline.php?feed_select=likes">いいね！済み</a></li>
                </ul>
            </div>
            <div class="col-xs-9">
                <div class="feed_form thumbnail">
                    <form method="POST" action="">
                        <div class="form-group">
                            <textarea name="feed" class="form-control" rows="3" placeholder="Happy Hacking!" style="font-size: 24px;"></textarea><br>
                            <?php if (isset($errors['feed']) && $errors['feed'] == 'blank') { ?>
                              <p class="text-danger">投稿データを入力してください</p>
                            <?php } ?>

                        </div>
                        <input type="submit" value="投稿する" class="btn btn-primary">
                    </form>
                </div>
                <?php foreach($feeds as $feed): ?>
                  <div class="thumbnail">
                      <div class="row">
                          <div class="col-xs-1">
                              <img src="user_profile_img/<?php echo $feed['profile_img']; ?>" width="40px">
                          </div>
                          <div class="col-xs-11">
                              <a href="profile.php" style="color: #7f7f7f;"><?php echo $feed['name']; ?></a>
                              <?php echo $feed['created']; ?>
                          </div>
                      </div>
                      <div class="row feed_content">
                          <div class="col-xs-12">
                              <span style="font-size: 24px;"><?php echo $feed['feed']; ?></span>
                          </div>
                      </div>
                      <div class="row feed_sub">
                          <div class="col-xs-12">
                              <button class="btn btn-default">いいね！</button>
                              いいね数：
                              <span class="like-count">10</span>
                              <a href="#collapseComment" data-toggle="collapse" aria-expanded="false"><span>コメントする</span></a>
                              <span class="comment-count">コメント数：5</span>
                              <?php if($signin_user['id'] == $feed['user_id']): ?>
                                <a href="edit.php?feed_id=<?php echo $feed['id']; ?>" class="btn btn-success btn-xs">編集</a>
                                <a onclick="return confirm('ほんとに消すの？');" href="delete.php?feed_id=<?php echo $feed['id']; ?>" class="btn btn-danger btn-xs">削除</a>
                              <?php endif; ?>
                          </div>
                          <?php include('comment_view.php'); ?>
                      </div>
                  </div>
                <?php endforeach; ?>
                <div aria-label="Page navigation">
                    <ul class="pager">
                        <?php if($page == 1): ?>
                          <li class="previous disabled"><a href=""><span aria-hidden="true">&larr;</span> Newer</a></li>
                        <?php else: ?>
                          <li class="previous"><a href="timeline.php?page=<?php echo $page - 1 ?>"><span aria-hidden="true">&larr;</span> Newer</a></li>
                        <?php endif; ?>


                        <?php if($page == $last_page): ?>
                          <li class="next disabled"><a href="">Older <span aria-hidden="true">&rarr;</span></a></li>
                        <?php else: ?>
                          <li class="next"><a href="timeline.php?page=<?php echo $page +1 ?>">Older <span aria-hidden="true">&rarr;</span></a></li>
                        <?php endif; ?>

                    </ul>
                </div>
            </div>
        </div>
    </div>
</body>
<?php include('layouts/footer.php'); ?>
</html>
