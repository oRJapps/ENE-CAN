<?php
    
require('connect.php');

session_start();

    // データベースの接続
    try {
        $dbh = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8', DB_USER, DB_PASSWORD);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

       //テーブル表示用SQL-露店
       switch(htmlspecialchars($_POST['sl-server'])){
        case '全サーバ':
            $sql_roten='SELECT * FROM items WHERE buyspot ="露店" ORDER BY date DESC';
            break;
        case 'ローゼンバーグ':
            $sql_roten='SELECT * FROM items WHERE buyspot ="露店" AND server="ローゼンバーグ" ORDER BY date DESC';
            break;
        case 'エルフィンタ':
            $sql_roten='SELECT * FROM items WHERE buyspot ="露店" AND server="エルフィンタ" ORDER BY date DESC';
            break;
        case 'ミストラル':
            $sql_roten='SELECT * FROM items WHERE buyspot ="露店" AND server="ミストラル" ORDER BY date DESC';
            break;
        case 'ゼルナ':
            $sql_roten='SELECT * FROM items WHERE buyspot ="露店" AND server="ゼルナ" ORDER BY date DESC';
            break;
        case 'モエン':
            $sql_roten='SELECT * FROM items WHERE buyspot ="露店" AND server="モエン" ORDER BY date DESC';
            break;
        default:
            $sql_roten='SELECT * FROM items WHERE buyspot ="露店" ORDER BY date DESC';
            break;
    }
    
    $stmt_roten = $dbh -> query($sql_roten);

        //テーブル表示用SQL-NP-ALL
        
        $sql_np = 'SELECT * FROM items WHERE buyspot ="TOM" ORDER BY date DESC';
        $stmt_np = $dbh -> query($sql_np);
        
        //search
        if(!empty($_POST['sear'])){
            $_SESSION['search'] = htmlspecialchars($_POST['search'], ENT_QUOTES, "UTF-8");

            //変換
            switch($_SESSION['search']){
                case "SW":
                    $_SESSION['search']="ストロングウェポン";
                    break;
                case "PA":
                    $_SESSION['search']="プロテクトアーマー";
                    break;
                case "EA":
                    $_SESSION['search']="エクストリームアタック";
                    break;
                case "EB":
                    $_SESSION['search']="エレメンタルブレイク";
                    break;
                case "変コア":
                    $_SESSION['search']="変質したコア";
                    break;
                case "PW":
                    $_SESSION['search']="パワーウェポン";
                    break;
                case "CA":
                    $_SESSION['search']="コートアーマー";
                    break;
                case "HA":
                    $_SESSION['search']="ハイパーアタック";
                    break;
                case "IH":
                   $_SESSION['search']="アイアンハート";
                    break;
            }
            
            if(isset($_POST['sear'])){
                switch(htmlspecialchars($_POST['sl-server'])){
                    case 'ローゼンバーグ':
                        $sql_search_r ="SELECT * FROM items WHERE item LIKE (:item) AND (buyspot='露店' AND server='ローゼンバーグ') ORDER BY date DESC";
                        break;
                    case 'エルフィンタ':
                        $sql_search_r ="SELECT * FROM items WHERE item LIKE (:item) AND (buyspot='露店' AND server='エルフィンタ') ORDER BY date DESC";
                        break;
                    case 'ゼルナ':
                        $sql_search_r ="SELECT * FROM items WHERE item LIKE (:item) AND (buyspot='露店' AND server='ゼルナ') ORDER BY date DESC";
                        break;
                    case 'ミストラル':
                        $sql_search_r ="SELECT * FROM items WHERE item LIKE (:item) AND (buyspot='露店' AND server='ミストラル') ORDER BY date DESC";
                        break;
                    case 'モエン':
                        $sql_search_r ="SELECT * FROM items WHERE item LIKE (:item) AND (buyspot='露店' AND server='モエン') ORDER BY date DESC";
                        break;
                    default:
                        $sql_search_r ="SELECT * FROM items WHERE item LIKE (:item) AND buyspot='露店' ORDER BY date DESC";
                        break;
                }
                
                
                
                $stmt_search_r=$dbh->prepare($sql_search_r);
                
                if($stmt_search_r){
                    $item = $_SESSION['search'];
                    $like_search = "%".$item."%";
                    //プレースホルダへ実際の値を設定する
                    $stmt_search_r->bindValue(':item', $like_search, PDO::PARAM_STR);
                    $stmt_search_r->execute();
                }
            }
            
            
            
        }elseif(!empty($_POST['np-sear'])){
            $_SESSION['np-search'] = htmlspecialchars($_POST['np-search'], ENT_QUOTES, "UTF-8");
             
            //変換
            switch($_SESSION['np-search']){
                case "SW":
                   $_SESSION['np-search']="ストロングウェポン";
                    break;
                case "PA":
                    $_SESSION['np-search']="プロテクトアーマー";
                    break;
                case "EA":
                    $_SESSION['np-search']="エクストリームアタック";
                    break;
                case "EB":
                    $_SESSION['np-search']="エレメンタルブレイク";
                    break;
                case "変コア":
                    $_SESSION['np-search']="変質したコア";
                    break;
                case "PW":
                    $_SESSION['np-search']="パワーウェポン";
                    break;
                case "CA":
                    $_SESSION['np-search']="コートアーマー";
                    break;
                case "HA":
                    $_SESSION['np-search']="ハイパーアタック";
                    break;
                case "IH":
                   $_SESSION['np-search']="アイアンハート";
                    break;
            }
            
            $sql_search_n  = sprintf("SELECT * FROM items WHERE item LIKE '%%%s%%' AND buyspot='TOM' ORDER BY date DESC",$_SESSION['np-search']);
            //$stmt_search_n = $dbh -> query("SET NAMES utf8;");
            $stmt_search_n=$dbh -> query($sql_search_n);
            
            
        }


    } catch (PDOException $e) {
        echo $e->getMessage();
        echo "状況：登録できません。";
        exit;
    }


?>

<?php
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

    </head>

    <body>
        <script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js" integrity="sha256-T0Vest3yCU7pafRw9r+settMBX6JkKN06dqBnpQ8d30=" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>


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
            <p>露店からNPまでいつでも気になるアイテムの相場が即確認できます。</p>
            <p>アイテムがない場合は、<a href="newitem.php">こちら</a>から登録できます。<br>
            <a href="https://peing.net/ja/wuskkahbrj">アイテムリクエスト</a>も可能になりました！送ると優先して登録されます。</p>

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

    <p>ゲーム内通貨SEEDで販売されているアイテムです。<br> 
    検索後全サーバ全件表示をしたい場合は、全サーバを選択しテキストボックスをクリアにしたのち、検索ボタンを押してください。<br>
    
    </p>
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
            <input  class="btn btn-primary col-1" id="s" type="submit" value="検索" name="sear">
        </div>
    </form>

    
    <table class="table table-hover table--hen">
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

    <p>Tales Open Marcket(通称OM)で売られているアイテムです。<br>1NP=1円換算です。<br> 検索後全件表示をしたい場合は、テキストボックスをクリアにしたのち、検索ボタンを押してください。
    </p>
    <form method="post" action="enecan.php">
        <input class="col col-5" type="text" name="np-search">
        <input class="col col-1" id="s" type="submit" value="検索" name="np-sear">
    </form>
    
    <table class="table table-hover table--hen">
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
