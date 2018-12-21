<?php
// 配列と組み合わせて使うもの

$members = array('Takeshi', 'Rico', 'Erika', 'Hagy');

// foreach ($配列 as $変数)
foreach ($members as $member) {
    // $member = $members[0];
    // echo $member;
    // echo '<br>';
}


$feed1 = array('id'=>'1', 'feed'=>'Hello', 'name'=>'野原ひろし');
$feed2 = array('id'=>'2', 'feed'=>'ほげぇ', 'name'=>'野原しんのすけ');

$feeds = array($feed1, $feed2);


echo '<pre>';
var_dump($feeds);
echo '</pre>';

// echo $feeds[1]['name'];

foreach ($feeds as $feed) {
    // $feed = $feeds[0];
    echo $feed['feed'];
    echo '<br>';
}

?>














