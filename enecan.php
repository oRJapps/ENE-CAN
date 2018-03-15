<?php
    require('connect.php');

    // データベースの接続
    try {
        $dbh = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASSWORD, $options);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        echo "状態：アイテム登録ができます";

        //テーブル表示用SQL-露店
        $sql_roten = 'SELECT * FROM items WHERE buyspot ="露店"';
        $stmt_roten = $dbh -> query($sql_roten);

        //テーブル表示用SQL-NP
        $sql_np = 'SELECT * FROM items WHERE buyspot ="TOM"';
        $stmt_np = $dbh -> query($sql_np);



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

        <ul class="nav nav-tabs" id="myTab" role="tablist">
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
              露店 
            </div>

             <!--OMテーブル-->
            <div class="tab-pane fade" id="np" role="tabpanel" aria-labelledby="np-tab">
                NP
                <div class="row">
                    <div class="col-3">
                        <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                            <a class="nav-link active" id="v-pills-all-tab" data-toggle="pill" href="#v-pills-all" role="tab" aria-controls="v-pills-all" aria-selected="true">All</a>
                            <共通スキル>
                            <a class="nav-link" id="v-pills-sw-tab" data-toggle="pill" href="#v-pills-sw" role="tab" aria-controls="v-pills-sw" aria-selected="true">共通スキルSW</a>
                            <a class="nav-link" id="v-pills-pa-tab" data-toggle="pill" href="#v-pills-pa" role="tab" aria-controls="v-pills-pa" aria-selected="false">共通スキルPA</a>
                            <a class="nav-link" id="v-pills-eb-tab" data-toggle="pill" href="#v-pills-eb" role="tab" aria-controls="v-pills-eb" aria-selected="false">共通スキルEB</a>
                            <a class="nav-link" id="v-pills-ea-tab" data-toggle="pill" href="#v-pills-ea" role="tab" aria-controls="v-pills-ea" aria-selected="false">共通スキルEA</a>
                        </div>
                    </div>
                    <div class="col-9">
                        <div class="tab-content" id="v-pills-tabContent">
                            <div class="tab-pane fade show active" id="v-pills-all" role="tabpanel" aria-labelledby="v-pills-all-tab">
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
                                        <?php while($result = $stmt_np->fetch(PDO::FETCH_ASSOC)){ ?>
                                            <th scope="row"><?php echo $result['id']; ?></th>
                                            <td><?php echo $result['server']; ?></td>
                                            <td><?php echo $result['item']; ?></td>
                                            <td><?php echo $result['price']; ?></td>
                                            <td><?php echo $result['date']; ?></td>
                                        <?php } ?>
                                    </tr>
                                </tbody>
                                </table>
                            </div>
                            <div class="tab-pane fade" id="v-pills-sw" role="tabpanel" aria-labelledby="v-pills-sw-tab">
                            <p>SW</p>
                            </div>
                            <div class="tab-pane fade" id="v-pills-pa" role="tabpanel" aria-labelledby="v-pills-pa-tab">
                            <p>PA</p>
                            </div>
                            <div class="tab-pane fade" id="v-pills-eb" role="tabpanel" aria-labelledby="v-pills-eb-tab">
                            <p>EB</p>
                            </div>
                            <div class="tab-pane fade" id="v-pills-ea" role="tabpanel" aria-labelledby="v-pills-ea-tab">
                            <p>EA</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>