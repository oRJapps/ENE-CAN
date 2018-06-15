<?php
//略称変換
function ryakushou($str){
    switch($str){
        case "SW":
            $str="ストロングウェポン";
            break;
        case "PA":
            $str="プロテクトアーマー";
            break;
        case "EA":
            $str="エクストリームアタック";
            break;
        case "EB":
            $str="エレメンタルブレイク";
            break;
        case "変コア":
            $str="変質したコア";
            break;
        case "PW":
            $str="パワーウェポン";
            break;
        case "CA":
            $str="コートアーマー";
            break;
        case "HA":
            $str="ハイパーアタック";
            break;
        case "IH":
            $str="アイアンハート";
            break;
        case "HS":
            $str="ハイパースキル";
            break;
        case "OS":
            $str="オーバースキル";
            break;
        case "魔石":
            $str="魔法師の石";
            break;
        case "赤土":
            $str="アカド";
            break;
        case "ブニクル":
            $str="ブリニクル";
            break;
        default:
            break;
    }
    return $str;
}

//サーバ
function server($server){
    switch ($server){
        case '全サーバ':
            if(isset($_POST['sear']) && !empty($_SESSION['search'])){
                $server = "SELECT * FROM items WHERE item LIKE (:item) AND buyspot='露店' ORDER BY date DESC";
            }else{
                $server='SELECT * FROM items WHERE buyspot ="露店" ORDER BY date DESC';
            }
            break;
        case 'ローゼンバーグ':
            if(isset($_POST['sear']) && !empty($_SESSION['search'])){
                $server = "SELECT * FROM items WHERE item LIKE (:item) AND (buyspot='露店' AND server='ローゼンバーグ') ORDER BY date DESC";
            }else {
                $server = 'SELECT * FROM items WHERE buyspot ="露店" AND server="ローゼンバーグ" ORDER BY date DESC';
            }
            break;
        case 'エルフィンタ':
            if(isset($_POST['sear']) && !empty($_SESSION['search'])){
                $server = "SELECT * FROM items WHERE item LIKE (:item) AND (buyspot='露店' AND server='エルフィンタ') ORDER BY date DESC";
            }else{
                $server='SELECT * FROM items WHERE buyspot ="露店" AND server="エルフィンタ" ORDER BY date DESC';
            }
            break;
        case 'ミストラル':
            if(isset($_POST['sear'])&& !empty($_SESSION['search'])){
                $server ="SELECT * FROM items WHERE item LIKE (:item) AND (buyspot='露店' AND server='ミストラル') ORDER BY date DESC";
            }else{
                $server='SELECT * FROM items WHERE buyspot ="露店" AND server="ミストラル" ORDER BY date DESC';
            }
            break;
        case 'ゼルナ':
            if(isset($_POST) && !empty($_SESSION['search'])){
                $server = "SELECT * FROM items WHERE item LIKE (:item) AND (buyspot='露店' AND server='ゼルナ') ORDER BY date DESC";
            }else{
                $server='SELECT * FROM items WHERE buyspot ="露店" AND server="ゼルナ" ORDER BY date DESC';
            }
            break;
        case 'モエン':
            if(isset($_POST['sear']) && !empty($_SESSION['search'])){
                $server = "SELECT * FROM items WHERE item LIKE (:item) AND (buyspot='露店' AND server='モエン') ORDER BY date DESC";
            }else{
                $server='SELECT * FROM items WHERE buyspot ="露店" AND server="モエン" ORDER BY date DESC';
            }
            break;
        default:
            $server='SELECT * FROM items WHERE buyspot ="露店" ORDER BY date DESC';
            break;
    }
    return $server;
}

//スマートフォン判定
function ua_smt (){
//ユーザーエージェントを取得
    $ua = $_SERVER['HTTP_USER_AGENT'];
//スマホと判定する文字リスト
    $ua_list = array('iPhone','iPad','iPod','Android');
    foreach ($ua_list as $ua_smt) {
//ユーザーエージェントに文字リストの単語を含む場合はTRUE、それ以外はFALSE
        if (strpos($ua, $ua_smt) !== false) {
            return true;
        }
    } return false;
}

//タブの判定--------------thanks to----------------------------
function getActiveTabName($post) {
    //初期
    if (empty($post['sear']) && empty($post['np-sear'])) {
        return 'stalls';
    }
    //どちらかの検索ボタンが押されたら
    return !empty($post['sear']) ? 'stalls' : 'om';
}