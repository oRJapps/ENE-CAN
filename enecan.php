<?php
session_start();

require('connect.php');

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




    }
} catch (PDOException $e) {
    echo $e->getMessage();
    echo "状況：登録できません。";
    exit;
}
?>
<?php
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
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link type="text/css" rel="stylesheet" href="css/style.css?20180528" />
        <link type="text/css" rel="stylesheet" href="js/PaginateMyTable.css" />
        <link type="text/css" rel="stylesheet" href="css/bootstrap-social.css" />
        <link type="text/css" rel="stylesheet" href="css/table.css?20180528" />


        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">

        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-119882133-1"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());

            gtag('config', 'UA-119882133-1');
        </script>

    </head>

    <body style="padding-top: 30px;">
    <script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js" integrity="sha256-T0Vest3yCU7pafRw9r+settMBX6JkKN06dqBnpQ8d30=" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>
    <script src="js/PaginateMyTable.js"></script>
    <script>
        $(document).ready(function() {
            $(".MyTable").paginate({
               rows:100
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
            <?php if(!empty($_SESSION['screen_name'])): ?>
                <?php var_dump($_COOKIE['twitter']); ?>
                <a href="#" data-toggle="popover" data-placement="bottom" data-toggle="popover" title="メニュー">
                    <img src="<?php echo $_SESSION['profile_image_url_https']; ?>">
                </a>




                <!-- ログアウトクリック時ポップオーバーでメニュー通知 -->
                <script>
                    $(document).ready(function() {
                        $('[data-toggle="popover"]').popover({
                            html: true,
                            content: function() {
                                return $('#popover-content').html();
                            }
                        });
                    });
                </script>
                    <div id="popover-content" style="display: none;">
                        <ul class="list-group">
                            <li class="list-group-item">設定</li>
                            <li class="list-group-item"><a href="logout.php">ログアウト</a></li>
                        </ul>
                    </div>


            <?php else: ?>
                <a class="btn btn-block btn-social btn-twitter" href="login.php" style="width: 30%">
                    <i class="fab fa-twitter-square"></i>
                    Sign in with Twitter
                </a>
            <?php endif; ?>

        </div>

    </nav>
    <div style="padding-top:50px;">
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <?php if($_SESSION['oauth_token']){
                echo "Twitterでログイン中!ログアウトは↑メニュー↑からできるよ";
            }else{
                echo "Twitterでログインしてないよ。拡張機能を利用したいときはログインしてね";
            } ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    </div>
    <div class="container-fluid">


        <div class="jumbotron">
            <h1>エネルギッシュな缶詰</h1>
            <?php echo '<h4><span class="badge badge-pill badge-danger">アイテム登録数</span>' .$all."件</h4>" ?>
            <p>露店からNPまでいつでも気になるアイテムの相場が即確認できます。</p>
            <p>アイテムがない場合は、登録もできます！なるべく登録してくださると助かります。<br>
            <p>露店⇒<a href="newitem_roten.php">こちら</a><br>TOM⇒<a href="newitem_tom.php">こちら</a>からお願いします。</p>
            <a href="https://peing.net/ja/wuskkahbrj">アイテムリクエスト</a>も可能になりました！送ると優先して登録されます。</p>
        </div>

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
                        <input class="col-xs-12 col-md-5 form-control" type="text" name="search">
                        <select id="server" class="form-control col-xs-12 col-md-4" name="sl-server">
                            <option selected>全サーバ</option>
                            <option>ローゼンバーグ</option>
                            <option>エルフィンタ</option>
                            <option>ミストラル</option>
                            <option>ゼルナ</option>
                            <option>モエン</option>
                        </select>
                        <input class="btn btn-primary col-xs-12 col-md-3" id="s" style="width: 100%" type="submit" value ="検索"  name="sear">
                    </div>
                </form>
                <p>ゲーム内通貨SEEDで販売されているアイテムです。<br>
                    検索後全サーバ全件表示をしたい場合は、全サーバを選択しテキストボックスをクリアにしたのち、検索ボタンを押してください。<br>
                </p>

                <table class="table table-hover table-bordered table01 MyTable">
                    <thead>
                    <tr>
                        <th scope="col">サーバ名</th>
                        <th scope="col">アイテム名</th>
                        <th data-breakpoints="xs sm md" scope="col">価格</th>
                        <?php if(!empty($_SESSION['oauth_token'])): ?>
                            <th data-breakpoints="xs sm md" scope="col">販売者名</th>
                        <?php else: ?>
                            <?php
                            strip_tags("<th scope='col'>");
                            strip_tags("</th>");
                            ?>
                        <?php endif; ?>
                        <th data-breakpoints="xs sm md" scope="col">日付</th>
                    </tr>
                    </thead>
                    <tbody>
                    <!-- 検索ボタンを押したときの処理 -->
                    <?php if(isset($_POST['sear'])): ?>
                        <!-- テキストボックスが空白じゃない場合、テキストボックスのキーワードを元にSQL文を実行する -->
                        <?php if(!empty($_SESSION['search'])): ?>

                            <?php while($result = $stmt_search_r ->fetch(PDO::FETCH_ASSOC)):?>
                                <tr data-expanded="false">
                                    <td aria-label="サーバ名">
                                        <?php echo $result['server']; ?>
                                    </td>
                                    <td aria-label="アイテム名">
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
                                    <td aria-label="価格">
                                        <?php echo number_format($result['price'])."seed"; ?>
                                    </td>
                                    <?php if(!empty($_SESSION['oauth_token'])): ?>
                                        <td aria-label="販売者名">
                                            <?php echo $result['buyname']; ?>
                                        </td>
                                    <?php else: ?>
                                        <?php
                                        strip_tags("<td>");
                                        strip_tags("</td>");
                                        ?>
                                    <?php endif; ?>
                                    <td aria-label="日付">
                                        <?php echo $result['date']; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                            <!-- テキストボックスが空欄の場合、販売箇所が露店箇所を全件表示 -->
                        <?php elseif(empty($_SESSION['search'])): ?>
                            <?php while($result = $stmt_roten ->fetch(PDO::FETCH_ASSOC)):?>
                                <tr data-expanded="false">
                                    <td aria-label="サーバ名">
                                        <?php echo $result['server']; ?>
                                    </td>
                                    <td aria-label="アイテム名">
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
                                    <td aria-label="価格">
                                        <?php echo number_format($result['price'])."seed"; ?>
                                    </td>
                                    <?php if(!empty($_SESSION['oauth_token'])): ?>
                                        <td aria-label="販売者名">
                                            <?php echo $result['buyname']; ?>
                                        </td>
                                    <?php else: ?>
                                        <?php
                                        strip_tags("<td>");
                                        strip_tags("</td>");
                                        ?>
                                    <?php endif; ?>
                                    <td aria-label="日付">
                                        <?php echo $result['date']; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    <?php else: ?>
                        <!--ページアクセス時には全件表示を行う-->
                        <?php while($result = $stmt_roten ->fetch(PDO::FETCH_ASSOC)):?>
                            <tr>
                                <td aria-label="サーバ名">
                                    <?php echo $result['server']; ?>
                                </td>
                                <td aria-label="アイテム名">
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
                                <td aria-label="価格">
                                    <?php echo number_format($result['price'])."seed"; ?>
                                </td>
                                <?php if(!empty($_SESSION['oauth_token'])): ?>
                                    <td aria-label="販売者名">
                                        <?php echo $result['buyname']; ?>
                                    </td>
                                <?php else: ?>
                                    <?php
                                    strip_tags("<td>");
                                    strip_tags("</td>");
                                    ?>
                                <?php endif; ?>
                                <td aria-label="日付">
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
                        <input class="col-xs-12 col-md-5 form-control" type="text" name="np-search">
                        <input class="col col-xs-12 col-md-3 btn btn-primary" id="s" type="submit" value="検索" name="np-sear">
                    </div>
                </form>

                <p>Tales Open Marcket(通称OM)で売られているアイテムです。<br>1NP=1円換算です。<br> 検索後全件表示をしたい場合は、テキストボックスをクリアにしたのち、検索ボタンを押してください。
                </p>

                <table class="table table01 table-hover table-bordered MyTable">
                    <thead>
                    <tr>
                        <th scope="col">アイテム名</th>
                        <th data-breakpoints="xs" scope="col">価格</th>
                        <?php if(!empty($_SESSION['oauth_token'])): ?>
                            <th data-breakpoints="xs sm md" scope="col">販売者名</th>
                        <?php else: ?>
                            <?php
                            strip_tags("<th scope='col'>");
                            strip_tags("</th>");
                            ?>
                        <?php endif; ?>
                        <th data-breakpoints="xs sm md" scope="col">日付</th>
                    </tr>
                    </thead>
                    <tbody>
                    <!-- 検索ボタンを押したときの処理 -->
                    <?php if(isset($_POST['np-sear'])): ?>
                        <!-- テキストボックスが空白じゃない場合、テキストボックスのキーワードを元にSQL文を実行する -->
                        <?php if(!empty($_SESSION['np-search'])): ?>

                            <?php while($result = $stmt_search_n ->fetch(PDO::FETCH_ASSOC)):?>
                                <tr>
                                    <td aria-label="アイテム名">
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
                                    <td aria-label="価格">
                                        <?php echo number_format($result['price'])."NP"; ?>
                                    </td>
                                    <?php if(!empty($_SESSION['oauth_token'])): ?>
                                        <td aria-label="販売者名">
                                            <?php echo $result['buyname']; ?>
                                        </td>
                                    <?php else: ?>
                                        <?php
                                        strip_tags("<td>");
                                        strip_tags("</td>");
                                        ?>
                                    <?php endif; ?>
                                    <td aria-label="日付">
                                        <?php echo $result['date']; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                            <!-- テキストボックスが空欄の場合、販売箇所が露店箇所を全件表示 -->
                        <?php elseif(empty($_SESSION['np-search'])): ?>
                            <?php while($result = $stmt_np ->fetch(PDO::FETCH_ASSOC)):?>
                                <tr>
                                    <td aria-label="アイテム名">
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
                                    <td aria-label="価格">
                                        <?php echo number_format($result['price'])."NP"; ?>
                                    </td>
                                    <?php if(!empty($_SESSION['oauth_token'])): ?>
                                        <td aria-label="販売者名">
                                            <?php echo $result['buyname']; ?>
                                        </td>
                                    <?php else: ?>
                                        <?php
                                        strip_tags("<td>");
                                        strip_tags("</td>");
                                        ?>
                                    <?php endif; ?>
                                    <td aria-label="日付">
                                        <?php echo $result['date']; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    <?php else: ?>
                        <!--ページアクセス時には全件表示を行う-->
                        <?php while($result = $stmt_np ->fetch(PDO::FETCH_ASSOC)):?>
                            <tr>
                                <td aria-label="アイテム名">
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
                                <td aria-label="価格">
                                    <?php echo number_format($result['price'])."NP"; ?>
                                </td>
                                <?php if(!empty($_SESSION['oauth_token'])): ?>
                                    <td aria-label="販売者名">
                                        <?php echo $result['buyname']; ?>
                                    </td>
                                <?php else: ?>
                                    <?php
                                    strip_tags("<td>");
                                    strip_tags("</td>");
                                    ?>
                                <?php endif; ?>
                                <td aria-label="日付">
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


    </div>






    <div id="footer">
        <center>copy right (c) 2018 RJ,水上商会 all rights reserved</center>
    </div>

    </body>

    </html>
<?php
$_SESSION['search']="";
$_SESSION['np-search']="";
//session_destroy();
$dbh =null;
?>