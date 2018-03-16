<?php
    require('connect.php');

    // データベースの接続
    try {
        $dbh = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASSWORD, $options);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        echo "状態：アイテム登録ができます";

        //テーブル表示用SQL-露店
        
        $sql_roten = 'SELECT * FROM items WHERE buyspot ="露店"';
        $stmt_roten = $dbh -> query("SET NAMES utf8;");
        $stmt_roten = $dbh -> query($sql_roten);

        //テーブル表示用SQL-NP-ALL
        
        $sql_np = 'SELECT * FROM items WHERE buyspot ="TOM"';
        $stmt_np = $dbh -> query($sql_np);

       //共通スキル 
        $sql_sw = 'SELECT * FROM items WHERE item LIKE "%ストロングウェポン%"';
        $stmt_sw = $dbh -> query("SET NAMES utf8;");
        $stmt_sw = $dbh -> query($sql_sw);

        $sql_pa = 'SELECT * FROM items WHERE item LIKE "%プロテクトアーマー%"';
        $stmt_pa = $dbh -> query("SET NAMES utf8;");
        $stmt_pa = $dbh -> query($sql_pa);

        $sql_eb = 'SELECT * FROM items WHERE item LIKE "%エレメンタルブレイク%"';
        $stmt_eb = $dbh -> query("SET NAMES utf8;");
        $stmt_eb = $dbh -> query($sql_eb);

        $sql_ea = 'SELECT * FROM items WHERE item LIKE "%エクストリームアタック%"';
        $stmt_ea = $dbh -> query("SET NAMES utf8;");
        $stmt_ea = $dbh -> query($sql_ea);

        $sql_pw = 'SELECT * FROM items WHERE item LIKE "%パワーウェポン%"';
        $stmt_pw = $dbh -> query("SET NAMES utf8;");
        $stmt_pw = $dbh -> query($sql_pw);

        $sql_ca = 'SELECT * FROM items WHERE item LIKE "%コートアーマー%"';
        $stmt_ca = $dbh -> query("SET NAMES utf8;");
        $stmt_ca = $dbh -> query($sql_ca);

        $sql_ha = 'SELECT * FROM items WHERE item LIKE "%ハイパーアタック%"';
        $stmt_ha = $dbh -> query("SET NAMES utf8;");
        $stmt_ha = $dbh -> query($sql_ha);

        $sql_ih = 'SELECT * FROM items WHERE item LIKE "%アイアンハート%"';
        $stmt_ih = $dbh -> query("SET NAMES utf8;");
        $stmt_ih = $dbh -> query($sql_ih);

        //二次覚醒素材
        $sql_geshu = 'SELECT * FROM items WHERE item ="ゲシュタルトの破片"';
        $stmt_geshu = $dbh -> query("SET NAMES utf8;");
        $stmt_geshu = $dbh -> query($sql_geshu);

        $sql_hen = 'SELECT * FROM items WHERE item ="変質したコア"';
        $stmt_hen = $dbh -> query("SET NAMES utf8;");
        $stmt_hen = $dbh -> query($sql_hen);

        //三次覚醒素材
        $sql_namida = 'SELECT * FROM items WHERE item ="精霊の涙"';
        $stmt_namida = $dbh -> query("SET NAMES utf8;");
        $stmt_namida = $dbh -> query($sql_namida);

        $sql_tamashi = 'SELECT * FROM items WHERE item ="精霊の魂"';
        $stmt_tamashi = $dbh -> query("SET NAMES utf8;");
        $stmt_tamashi = $dbh -> query($sql_tamashi);

        $sql_dango = 'SELECT * FROM items WHERE item ="黄金団子"';
        $stmt_dango = $dbh -> query("SET NAMES utf8;");
        $stmt_dango = $dbh -> query($sql_dango);
        


    } catch (PDOException $e) {
        echo $e->getMessage();
        exit;
    }


?>

<!DOCTYPE html>
<html>
    <head>
        <title>エネルギッシュな缶詰</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    </head>
    <body>
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

        <h1>エネルギッシュな缶詰</h1>
        <p>露店からNPまでいつでも気になるアイテムの相場が即確認できます。</p>
        <p>アイテムがない場合は、<a href="newitem.php">こちら</a>から登録できます。</p>

        <ul class="nav nav-tabs nav-justified" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="seed-tab" data-toggle="tab" href="#seed" role="tab" aria-controls="seed-tab" aria-selected="true">露店（ゲーム内通貨）</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="np-tab" data-toggle="tab" href="#np" role="tab" aria-controls="np-tab" aria-selected="false">OM（NP=ネクソンポイント）</a>
            </li>
        </ul>
        
        <div class="tab-content" id="myTabContent">
            <!--露店テーブル-->
            <div class="tab-pane fade show active" id="seed" role="tabpanel" aria-labelledby="seed-tab">
                <p>ゲーム内通貨SEEDで販売されているアイテムです。</p> 
                <table class="table table-hover">
                                <thead>
                                    <tr>
                                    <th scope="col">id</th>
                                    <th scope="col">サーバ名</th>
                                    <th scope="col">アイテム名</th>
                                    <th scope="col">価格</th>
                                    <th scope="col">日付</th>
                                    </tr>
                                </thead>
                    <tbody>
                        
                        <?php while($result = $stmt_roten ->fetch(PDO::FETCH_ASSOC)){ ?>
                            <tr>
                                <th scope="col"><?php echo $result['id']; ?></th>
                                <td><?php echo $result['server']; ?></td>
                                <td><?php echo $result['item']; ?></td>
                                <td align="justify"><?php 
                                    if($result['buyspot']=="露店"){
                                        echo number_format($result['price'])."seed";
                                    }else{
                                        echo number_format($result['price'])."NP";
                                    }
                                    ?>
                                </td>
                                <td><?php echo $result['date']; ?></td>
                            </tr>
                        <?php } ?>
                        
                    </tbody>
                </table>
                
            
            </div>
             <!--OMテーブル-->
            <div class="tab-pane fade" id="np" role="tabpanel" aria-labelledby="np-tab">
                <p>OMで販売されている商品一覧です。1NP=1円換算になります。</p>
                             <!--OMDBテーブル-->
                             <table class="table table-hover">
                                <thead>
                                    <tr>
                                    <th scope="col">id</th>
                                    <th scope="col">サーバ名</th>
                                    <th scope="col">アイテム名</th>
                                    <th scope="col">価格</th>
                                    <th scope="col">日付</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <?php while($result = $stmt_np ->fetch(PDO::FETCH_ASSOC)){ ?>
                                            <th scope="row"><?php echo $result['id']; ?></th>
                                            <td><?php echo $result['server']; ?></td>
                                            <td><?php echo $result['item']; ?></td>
                                            <td><?php 
                                                    if($result['buyspot']=="露店"){
                                                        echo number_format($result['price'])."seed";
                                                    }else{
                                                        echo number_format($result['price'])."NP";
                                                    }
                                                 ?>
                                            </td>
                                            <td><?php echo $result['date']; ?></td>
                                        <?php } ?>
                                    </tr>
                                </tbody>
                             </table>   
            </div>
        </div>
    </body>
</html>