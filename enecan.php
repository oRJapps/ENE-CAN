<?php

require('test/connecttest.php');
session_start();
// データベースの接続
try {
    $dbh = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8', DB_USER, DB_PASSWORD);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //全件
    $all = $dbh->prepare("SELECT COUNT(*) FROM items");
    $all -> execute();
    $all = $all->fetchColumn();
    // 露店全件数取得
    $roten_all = $dbh->prepare("SELECT COUNT(*) FROM items WHERE buyspot='露店'");
    $roten_all -> execute();
    $roten_all = $roten_all->fetchColumn();
    //TOM全件数取得
    $tom_all = $dbh->prepare("SELECT COUNT(*) FROM items WHERE buyspot='TOM'");
    $tom_all -> execute();
    $tom_all = $tom_all->fetchColumn();
    //テーブル表示用SQL-露店
    $sql_roten = server(htmlspecialchars($_POST['sl-server']));
    $stmt_roten = $dbh -> query($sql_roten);
    //テーブル表示用SQL-NP-ALL

    $sql_np = 'SELECT * FROM items WHERE buyspot ="TOM" ORDER BY date DESC';
    $stmt_np = $dbh -> query($sql_np);

    //search
    if(!empty($_POST['sear'])){

        $_SESSION['search'] = htmlspecialchars($_POST['search'], ENT_QUOTES, "UTF-8");
        //略称変換
        $_SESSION['search'] =ryakushou($_SESSION['search']);


        if(isset($_POST['sear'])){
            $sql_search_r = server(htmlspecialchars($_POST['sl-server']));
            $stmt_search_r=$dbh->prepare($sql_search_r);

            if($stmt_search_r){
                $item = $_SESSION['search'];
                $like_search = "%".$item."%";

                $stmt_search_r->bindValue(':item', $like_search, PDO::PARAM_STR);
                $stmt_search_r->execute();

                //登録
                if(!empty($item)){
                    $keyword_sql = "INSERT INTO keywords (keyword) VALUES (?)";
                    $stmt_keyword = $dbh -> query("SET NAMES utf8;");
                    $stmt_keyword = $dbh ->prepare($keyword_sql);
                    $stmt_keyword->bindValue(1, $item, PDO::PARAM_STR);
                    $stmt_keyword->execute();
                    $item ="";
                }

            }
        }



    }elseif(!empty($_POST['np-sear'])){
        $_SESSION['np-search'] = htmlspecialchars($_POST['np-search'], ENT_QUOTES, "UTF-8");

        //変換
        $_SESSION['np-search']=ryakushou($_SESSION['np-search']);

        $sql_search_n  = "SELECT * FROM items WHERE item LIKE (:item) AND buyspot='TOM' ORDER BY date DESC";
        $stmt_search_n=$dbh -> prepare($sql_search_n);
        $item_n = $_SESSION['np-search'];
        $like_search_n = "%".$item_n."%";
        $stmt_search_n->bindValue(':item', $like_search_n, PDO::PARAM_STR);
        $stmt_search_n->execute();

        if(!empty($item_n)){
            $keyword_sql_n = "INSERT INTO keywords (keyword) VALUES (?)";
            $stmt_keyword_n = $dbh -> query("SET NAMES utf8;");
            $stmt_keyword_n = $dbh ->prepare($keyword_sql_n);
            $stmt_keyword_n->bindValue(1, $item_n, PDO::PARAM_STR);
            $stmt_keyword_n->execute();
            $item_n ="";
        }


    }
} catch (PDOException $e) {
    echo $e->getMessage();
    echo "状況：登録できません。";
    exit;
}
?>

<?php
//関数
//アイテム省略⇒正式名称置き換え
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
//タブの判定--------------thanks to----------------------------
function getActiveTabName($post) {
    //初期
    if (empty($post['sear']) && empty($post['np-sear'])) {
        return 'stalls';
    }
    //どちらかの検索ボタンが押されたら
    return !empty($post['sear']) ? 'stalls' : 'om';
}
?>


    <!DOCTYPE html>
    <html>

    <head>
        <title>エネルギッシュな缶詰</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <meta charset="utf-8">
        <link rel="stylesheet" href="./css/style.css">
        <link type="text/css" rel="stylesheet" href="js/PaginateMyTable.css" />

    </head>

    <body style="padding-top: 30px;">
    <script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js" integrity="sha256-T0Vest3yCU7pafRw9r+settMBX6JkKN06dqBnpQ8d30=" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="js/PaginateMyTable.js"></script>
    <script>
        $(document).ready(function() {
            $(".MyTable").paginate({
               rows:50
            });
        });

    </script>



    <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
        <a class="navbar-brand" href="index.html">ENE*CAN</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria- controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="ene-can.html">Enemy Killer Count</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="enecan.php">エネルギッシュな缶詰</a>
                </li>

            </ul>

        </div>
    </nav>

    <div class="clear">
        <h1>エネルギッシュな缶詰</h1>
        <?php echo '<h4><span class="badge badge-pill badge-danger">アイテム登録数</span>' .$all."件</h4>" ?>
        <p>露店からNPまでいつでも気になるアイテムの相場が即確認できます。</p>
        <p>アイテムがない場合は、登録もできます！なるべく登録してくださると助かります。<br>
        <p>露店⇒<a href="newitem_roten.php">こちら</a><br>TOM⇒<a href="newitem_tom.php">こちら</a>からお願いします。</p>
        <a href="https://peing.net/ja/wuskkahbrj">アイテムリクエスト</a>も可能になりました！送ると優先して登録されます。</p>
        <p style="color:red;">4月17日から5月17日まで、通知機能実装の検索ワードをデータベースに登録致します。<br>
        登録されたデータベースは、通知アイテムの設定に使われます。<br>ご協力よろしくおねがいしますm(_ _)m<br></p>

        <ul class="nav nav-tabs nav-justified" id="myTab" role="tablist">
            <!-- 露店タブ -->
            <li class="nav-item">
                <a class="nav-link <?php echo getActiveTabName($_POST) === 'stalls' ? 'active' : ''; ?>" id="seed-tab" data-toggle="tab" href="#seed" role="tab" aria-controls="seed-tab">露店（ゲーム内通貨）</a>
            </li>
            <!--OMタブ -->
            <li class="nav-item">
                <a class="nav-link <?php echo getActiveTabName($_POST) === 'om' ? 'active' : ''; ?>" id="np-tab" data-toggle="tab" href="#np" role="tab" aria-controls="np-tab">OM（NP=ネクソンポイント）</a>
            </li>
        </ul>




        <!-- 露店タブの内容 -->
        <div class="tab-content">

            <div class="tab-pane fade <?php echo getActiveTabName($_POST) === 'stalls' ? 'active show' : ''; ?>" id="seed" role="tabpanel" aria-labelledby="seed-tab">


                <?php echo '<h5><span class="badge badge-pill badge-info">露店アイテム登録数</span>' .$roten_all."件</h5>" ?>

                <form method="post" action="enecan.php">
                    <div class="form-group form-inline">
                        <input class="col-5 form-control" type="text" name="search">
                        <select id="server" class="form-control col-2" name="sl-server">
                            <option selected>全サーバ</option>
                            <option>ローゼンバーグ</option>
                            <option>エルフィンタ</option>
                            <option>ミストラル</option>
                            <option>ゼルナ</option>
                            <option>モエン</option>
                        </select>
                        <input class="btn btn-primary col-1" id="s" type="submit" value="検索" name="sear">
                    </div>
                </form>
                <p>ゲーム内通貨SEEDで販売されているアイテムです。<br>
                    検索後全サーバ全件表示をしたい場合は、全サーバを選択しテキストボックスをクリアにしたのち、検索ボタンを押してください。<br>
                </p>

                <table class="table table-hover table--hen MyTable">
                    <thead>
                    <tr>
                        <th scope="col">サーバ名</th>
                        <th scope="col">アイテム名</th>
                        <th scope="col">価格</th>
                        <th scope="col">日付</th>
                    </tr>
                    </thead>
                    <tbody>
                    <!-- 検索ボタンを押したときの処理 -->
                    <?php if(isset($_POST['sear'])): ?>
                        <!-- テキストボックスが空白じゃない場合、テキストボックスのキーワードを元にSQL文を実行する -->
                        <?php if(!empty($_SESSION['search'])): ?>

                            <?php while($result = $stmt_search_r ->fetch(PDO::FETCH_ASSOC)):?>
                                <tr>
                                    <td>
                                        <?php echo $result['server']; ?>
                                    </td>
                                    <td>
                                        <?php //NEWバッジ追加判定
                                        $today = date("Y-m-d",strtotime("-1 day"));
                                        $currenDay =date("Y-m-d",strtotime($result['date']));
                                        if($currenDay >= $today) {
                                            echo '<span class="badge badge-danger">' . 'NEW' . '</span> '.$result['item'];
                                        }else{
                                            echo $result['item'];
                                        }
                                        ?>

                                    </td>
                                    <td>
                                        <?php echo number_format($result['price'])."seed"; ?>
                                    </td>
                                    <td>
                                        <?php echo $result['date']; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                            <!-- テキストボックスが空欄の場合、販売箇所が露店箇所を全件表示 -->
                        <?php elseif(empty($_SESSION['search'])): ?>
                            <?php while($result = $stmt_roten ->fetch(PDO::FETCH_ASSOC)):?>
                                <tr>
                                    <td>
                                        <?php echo $result['server']; ?>
                                    </td>
                                    <td>
                                        <?php //NEWバッジ追加判定
                                        $today = date("Y-m-d",strtotime("-1 day"));
                                        $currenDay =date("Y-m-d",strtotime($result['date']));
                                        if($currenDay >= $today) {
                                            echo '<span class="badge badge-danger">' . 'NEW' . '</span> '.$result['item'];
                                        }else{
                                            echo $result['item'];
                                        }
                                        ?>

                                    </td>
                                    <td>
                                        <?php echo number_format($result['price'])."seed"; ?>
                                    </td>
                                    <td>
                                        <?php echo $result['date']; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    <?php else: ?>
                        <!--ページアクセス時には全件表示を行う-->
                        <?php while($result = $stmt_roten ->fetch(PDO::FETCH_ASSOC)):?>
                            <tr>
                                <td>
                                    <?php echo $result['server']; ?>
                                </td>
                                <td>
                                    <?php //NEWバッジ追加判定
                                    $today = date("Y-m-d",strtotime("-1 day"));
                                    $currenDay =date("Y-m-d",strtotime($result['date']));
                                    if($currenDay >= $today) {
                                        echo '<span class="badge badge-danger">' . 'NEW' . '</span> '.$result['item'];
                                    }else{
                                        echo $result['item'];
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php echo number_format($result['price'])."seed"; ?>
                                </td>
                                <td>
                                    <?php echo $result['date']; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>

                    <?php endif; ?>
                    </tbody>
                </table>

            </div>

            <!-- OMタブの内容 -->

            <div class="tab-pane fade <?php echo getActiveTabName($_POST) === 'om' ? 'active show' : ''; ?>" id="np" role="tabpanel" aria-labelledby="np-tab">
                <?php echo '<h5><span class="badge badge-pill badge-info">TOMアイテム登録数</span>' .$tom_all."件</h5>" ?>


                <form method="post" action="enecan.php">
                    <div class="form-group form-inline">
                        <input class="col col-5 form-control" type="text" name="np-search">
                        <input class="col col-1 btn btn-primary" id="s" type="submit" value="検索" name="np-sear">
                    </div>
                </form>

                <p>Tales Open Marcket(通称OM)で売られているアイテムです。<br>1NP=1円換算です。<br> 検索後全件表示をしたい場合は、テキストボックスをクリアにしたのち、検索ボタンを押してください。
                </p>

                <table class="table table-hover table--hen MyTable">
                    <thead>
                    <tr>
                        <th scope="col">アイテム名</th>
                        <th scope="col">価格</th>
                        <th scope="col">日付</th>
                    </tr>
                    </thead>
                    <tbody>
                    <!-- 検索ボタンを押したときの処理 -->
                    <?php if(isset($_POST['np-sear'])): ?>
                        <!-- テキストボックスが空白じゃない場合、テキストボックスのキーワードを元にSQL文を実行する -->
                        <?php if(!empty($_SESSION['np-search'])): ?>

                            <?php while($result = $stmt_search_n ->fetch(PDO::FETCH_ASSOC)):?>
                                <tr>
                                    <td>
                                        <?php //NEWバッジ追加判定
                                        $today = date("Y-m-d",strtotime("-1 day"));
                                        $currenDay =date("Y-m-d",strtotime($result['date']));
                                        if($currenDay >= $today) {
                                            echo '<span class="badge badge-danger">' . 'NEW' . '</span> '.$result['item'];
                                        }else{
                                            echo $result['item'];
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php echo number_format($result['price'])."NP"; ?>
                                    </td>
                                    <td>
                                        <?php echo $result['date']; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                            <!-- テキストボックスが空欄の場合、販売箇所が露店箇所を全件表示 -->
                        <?php elseif(empty($_SESSION['np-search'])): ?>
                            <?php while($result = $stmt_np ->fetch(PDO::FETCH_ASSOC)):?>
                                <tr>
                                    <td>
                                        <?php //NEWバッジ追加判定
                                        $today = date("Y-m-d",strtotime("-1 day"));
                                        $currenDay =date("Y-m-d",strtotime($result['date']));
                                        if($currenDay >= $today) {
                                            echo '<span class="badge badge-danger">' . 'NEW' . '</span> '.$result['item'];
                                        }else{
                                            echo $result['item'];
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <?php echo number_format($result['price'])."NP"; ?>
                                    </td>
                                    <td>
                                        <?php echo $result['date']; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    <?php else: ?>
                        <!--ページアクセス時には全件表示を行う-->
                        <?php while($result = $stmt_np ->fetch(PDO::FETCH_ASSOC)):?>
                            <tr>
                                <td>
                                    <?php //NEWバッジ追加判定
                                    $today = date("Y-m-d",strtotime("-1 day"));
                                    $currenDay =date("Y-m-d",strtotime($result['date']));
                                    if($currenDay >= $today) {
                                        echo '<span class="badge badge-danger">' . 'NEW' . '</span> '.$result['item'];
                                    }else{
                                        echo $result['item'];
                                    }
                                    ?>

                                </td>
                                <td>
                                    <?php echo number_format($result['price'])."NP"; ?>
                                </td>
                                <td>
                                    <?php echo $result['date']; ?>

                                </td>
                            </tr>
                        <?php endwhile; ?>

                    <?php endif; ?>
                    </tbody>
                </table>

            </div>
        </div>
    </div>



    <div id="footer">
        <center>copy right (c) 2018 RJ,水上商会 all rights reserved</center>
    </div>

    </body>

    </html>
<?php
$dbh =null;
session_destroy();
?>