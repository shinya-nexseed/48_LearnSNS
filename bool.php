<?php
    // 論理演算子について
    // || とか && のこと
    // || or のこと。AもしくはB
    // && and のこと。A且つB

    // true : 1
    // false : 0

    // 優先順位
    // 基本的には左から右へ
    // &&と||が一緒の式に含まれてた場合は&&から読まれる


    if (!1 == 1 && false || 1 || 0 && 10 != 0) {
        echo 'キャリー！';
    }
?>