<?php
    session_start();

    require('connect.php');

    // データベースの接続
    try {
        $dbh = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASSWORD, $options);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


        //form登録
        // 変数の初期化
        $page_flag = 0;
        echo $page_flag;
        
        if(!empty($_POST['btn_confirm']) ) {
            $page_flag = 1;
            echo $page_flag;
            $_SESSION['server'] =htmlspecialchars($_POST['server'], ENT_QUOTES, "UTF-8");
            $_SESSION['item'] =htmlspecialchars($_POST['item'], ENT_QUOTES, "UTF-8");
            $_SESSION['buyspot']=htmlspecialchars($_POST['buyspot'], ENT_QUOTES, "UTF-8");
            $_SESSION['buyname'] =htmlspecialchars($_POST['buyname'], ENT_QUOTES, "UTF-8");
            $_SESSION['price'] =htmlspecialchars($_POST['price'], ENT_QUOTES, "UTF-8");
            $_SESSION['date'] =htmlspecialchars($_POST['date'], ENT_QUOTES, "UTF-8");
            
        }elseif( !empty($_POST['btn_submit']) ) {

            $page_flag = 2;
        }



    } catch (PDOException $e) {
        echo $e->getMessage();
        exit;
    }


?>

<!doctype html>
<html>
    <head>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    </head>
    <body>
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
        
        <h1>エネルギッシュな缶詰-登録フォーム-</h1>
        <!--登録page-->
                <form method="POST">
                    <!--server -->
                    <div class="form-group row">
                        <label for="server" class="col-sm-2 col-form-label">サーバ</label>
                        <div class="col-sm-10">
                            <select id="inputserver" class="form-control">
                                <option selected>ローゼンバーグ</option>
                                <option>エルフィンタ</option>
                                <option>ミストラル</option>
                                <option>ゼルナ</option>
                                <option>モエン</option>
                            </select>
                        </div>
                    </div>
                     <!--item -->
                    <div class="form-group row">
                        <label for="inputPassword" class="col-sm-2 col-form-label">アイテム名</label>
                        <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputitem" placeholder="正式名称で入力：ゲシュ⇒ゲシュタルトの破片">
                        </div>
                    </div>
                    <!-- price -->
                    <div class="form-group row">
                        <label for="inputPassword" class="col-sm-2 col-form-label">価格</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="inputnumber" placeholder="価格を半角で入力。kとmと入力しても自動で0を追加します">
                        </div>
                    </div>
                    <!-- buyspot  -->
                    <div class="form-group row">
                        <label for="inputPassword" class="col-sm-2 col-form-label">売場</label>
                        <div class="col-sm-10">
                            <select id="inputbuyspot" class="form-control">
                                <option selected>露店</option>
                                <option>OM(=TOM)</option>
                            </select>
                        </div>
                    </div>
                    <!-- buyname  -->
                    <div class="form-group row">
                        <label for="inputbuyname" class="col-sm-2 col-form-label">販売者名</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="inputbuyname" placeholder="販売者名をキャラクター名で入力">
                        </div>
                    </div>
                    <!-- date  -->
                    <div class="form-group row">
                        <label for="inputbuyname" class="col-sm-2 col-form-label">日付</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="inputbuyname" placeholder="露店の場合は登録した日付をOMの場合は掲載期限の日付を登録してください">
                        </div>
                    </div>
                    <input type="submit" class="btn btn-primary" name="btn_confirm" value="確認する">
                </form>      

    </body>
</html>

<?php 
    //接続修了
    $dbh=null;
?>
