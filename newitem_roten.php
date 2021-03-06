<?php
    session_start();

    require('connect.php');

    // データベースの接続
    try {
        $dbh = new PDO('mysql:host='.DB_HOST.';charset=utf8;dbname='.DB_NAME, DB_USER, DB_PASSWORD, $options);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        
        // 変数の初期化
        $page_flag = 0;

        
        if(!empty($_POST['btn_confirm']) ) {
            //$page_flag = 1;
            
        }elseif( !empty($_POST['btn_submit']) ) {

            $page_flag = 2;
    
        }

        if(isset($_POST['btn_confirm'])){
            
            if ($_POST['server'] !== "▼サーバを選択してください" &&!empty($_POST['item'])&& $_POST['buyspot'] !== "▼売場を選択してください" && !empty($_POST['buyname']) && !empty($_POST['date']) && !empty($_POST['price'])) { 
                 
                 $_SESSION['server'] =htmlspecialchars($_POST['server'], ENT_QUOTES, "UTF-8");
                 $_SESSION['item'] =htmlspecialchars($_POST['item'], ENT_QUOTES, "UTF-8");
                 $_SESSION['buyspot']=htmlspecialchars($_POST['buyspot'], ENT_QUOTES, "UTF-8");
                 $_SESSION['buyname'] =htmlspecialchars($_POST['buyname'], ENT_QUOTES, "UTF-8");
            
                //kとm入力に限り0を自動追加
                if(strpos($_POST['price'],'m') !== false || strpos($_POST['price'],'M') !== false){
                    $_POST['price'] = floatval($_POST['price'])*1000*1000;
                }elseif(strpos($_POST['price'],'k') !== false || strpos($_POST['price'],'K') !== false){
                    $_POST['price'] = floatval($_POST['price'])*1000;
                }
                $_SESSION['price'] =htmlspecialchars($_POST['price'], ENT_QUOTES, "UTF-8");

                $_SESSION['date'] =htmlspecialchars($_POST['date'], ENT_QUOTES, "UTF-8"); 
                
                $page_flag = 1;
            }else{
                $page_flag = 0;
                
                $_SESSION['server'] =htmlspecialchars($_POST['server'], ENT_QUOTES, "UTF-8");
                 $_SESSION['item'] =htmlspecialchars($_POST['item'], ENT_QUOTES, "UTF-8");
                 $_SESSION['buyspot']=htmlspecialchars($_POST['buyspot'], ENT_QUOTES, "UTF-8");
                 $_SESSION['buyname'] =htmlspecialchars($_POST['buyname'], ENT_QUOTES, "UTF-8");
            
                //kとm入力に限り0を自動追加
                if(strpos($_POST['price'],'m') !== false || strpos($_POST['price'],'M') !== false){
                    $_POST['price'] = floatval($_POST['price'])*1000*1000;
                }elseif(strpos($_POST['price'],'k') !== false || strpos($_POST['price'],'K') !== false){
                    $_POST['price'] = floatval($_POST['price'])*1000;
                }
                $_SESSION['price'] =htmlspecialchars($_POST['price'], ENT_QUOTES, "UTF-8");

                $_SESSION['date'] =htmlspecialchars($_POST['date'], ENT_QUOTES, "UTF-8"); 
                
            }
           
            
            
           
            
            
            
        }elseif(isset($_POST['btn_submit'])){
            //form登録
            $sql = "INSERT INTO items (item,server,buyspot,buyname,price,date) VALUES (?,?,?,?,?,?)";
            $stmt = $dbh -> query("SET NAMES utf8;");
            $stmt = $dbh ->prepare($sql);
            $stmt->bindValue(1, $_SESSION['item'], PDO::PARAM_STR);
            $stmt->bindValue(2, $_SESSION['server'], PDO::PARAM_STR);
            $stmt->bindValue(3, $_SESSION['buyspot'], PDO::PARAM_STR);
            $stmt->bindValue(4, $_SESSION['buyname'], PDO::PARAM_STR);
            $stmt->bindValue(5, $_SESSION['price'], PDO::PARAM_INT);
            $stmt->bindValue(6, date("Y-m-d",strtotime($_SESSION['date'])));
            
            $stmt->execute();
           
            
            $_POST['server']=null;
            $_POST['item']=null;
            $_POST['buyspot']=null;
            $_POST['buyname']=null;
            $_POST['price']=null;
            $_POST['date']=null;

            session_destroy();
            
        }elseif(isset($_POST['btn_cancel'])){
            header("location:enecan.php");
            session_destroy();
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
        <style rel="stylesheet" type="text/css">
body {
	padding: 20px;
}

h1 {
	margin-bottom: 20px;
	padding: 20px 0;
	color: #209eff;
	font-size: 200%;
	border-top: 2px solid #999;
	border-bottom: 2px solid #999;
}

input[type=text],select {
	padding: 5px 10px;
	border-radius: 3px;
}

input[type="text"]:forcus{
    outline:0;
    border-bottom:3px solid #999;
    border-radius: 3px;
}

input[name=btn_confirm],
input[name=btn_submit],
input[name=btn_back],
input[name=btn_cancel]{
	margin-top: 10px;
	padding: 5px 20px;
	font-size: 100%;
	color: #fff;
	cursor: pointer;
	border: none;
	border-radius: 3px;
	box-shadow: 0 3px 0 #2887d1;
	background: #4eaaf1;
}

input[name=btn_back] {
	margin-right: 20px;
	box-shadow: 0 3px 0 #777;
	background: #999;
}


label {
	display: inline-block;
	margin-bottom: 10px;
	font-weight: bold;
	width: 150px;
}


</style>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    </head>
    <body>
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
        
        <!-- EnterキーでTOPページに戻るのを防止する -->
        <script>
            $(function(){
                $("input").on("keydown", function(e) {
                    if ((e.which && e.which === 13) || (e.keyCode && e.keyCode === 13)) {
                        return false;
                    } else {
                        return true;
                    }
                });
            });
        </script>
        
        
        <!--登録page-->

        <?php if( $page_flag === 1 ): ?>
            <h1>エネルギッシュな缶詰-確認フォーム-</h1>
            <form method="post" action="newitem_roten.php">

            <!--server -->
                <div class="form-group row">
                    <label for="server" class="col-sm-2 col-form-label">サーバ</label>
                        <div class="col-sm-10">
                            <p><?php echo $_SESSION['server']; ?></p>
                        </div>
                </div>
            <!--item -->
                <div class="form-group row">
                    <label for="item" class="col-sm-2 col-form-label">アイテム名</label>
                        <div class="col-sm-10">
                            <p><?php echo $_SESSION['item']; ?></p>
                        </div>
                </div>
                        <!-- price -->
                        <div class="form-group row">
                            <label for="price" class="col-sm-2 col-form-label">価格</label>
                            <div class="col-sm-10">
                                <p><?php echo $_SESSION['price']; ?></p>
                            </div>
                        </div>
                        <!-- buyspot  -->
                        <div class="form-group row">
                            <label for="buyspot" class="col-sm-2 col-form-label">売場</label>
                            <div class="col-sm-10">
                                <p><?php echo $_SESSION['buyspot']; ?></p>
                            </div>
                        </div>
                        <!-- buyname  -->
                        <div class="form-group row">
                            <label for="buyname" class="col-sm-2 col-form-label">販売者名</label>
                            <div class="col-sm-10">
                                <p><?php echo $_SESSION['buyname']; ?></p>
                            </div>
                        </div>
                        <!-- date  -->
                        <div class="form-group row">
                            <label for="date" class="col-sm-2 col-form-label">日付</label>
                            <div class="col-sm-10">
                                <p><?php echo $_SESSION['date']; ?></p>
                            </div>
                        </div>
                
                <input type="submit" class="btn btn-secondary" name="btn_back" value="戻る">
                <input type="submit" class="btn btn-primary" name="btn_submit" value="登録">
                
                <input type="hidden" name="server" value="<?php echo $_SESSION['server']; ?>">
                <input type="hidden" name="item" value="<?php echo $_SESSION['item']; ?>">
                <input type="hidden" name="buyspot" value="<?php echo $_SESSION['buyspot']; ?>">
                <input type="hidden" name="buyname" value="<?php echo $_SESSION['buyname']; ?>">
                <input type="hidden" name="price" value="<?php echo $_SESSION['price']; ?>">
                <input type="hidden" name="date" value="<?php echo $_SESSION['date']; ?>">
                
            </form>
        <?php elseif( $page_flag === 2 ): ?>
        <h1>エネルギッシュな缶詰(β)-登録完了フォーム-</h1>
            <p>登録が完了しました</p>
            <p><a href="enecan.php">TOPページへ戻る</a> | <a href="newitem_roten.php">続けて登録する</a></p>
        <?php else: ?>
        <h1>エネルギッシュな缶詰-登録フォーム-</h1>
                <form method="POST" action="newitem_roten.php">
                    <!--server -->
                    <div class="form-group row">
                        <label for="server" class="col-sm-2 col-form-label">サーバ</label>
                        <div class="col-sm-10">
                            <select id="server" class="form-control" name="server">
                                <option <?php if( !empty($_SESSION['server']) && $_SESSION['server'] === "▼サーバを選択してください" ){ echo 'selected'; } ?>>▼サーバを選択してください</option>
                                
                                <option  <?php if( !empty($_SESSION['server']) && $_SESSION['server'] === "ローゼンバーグ" ){ echo 'selected'; } ?>>ローゼンバーグ</option>
                                <option  <?php if( !empty($_SESSION['server']) && $_SESSION['server'] === "エルフィンタ" ){ echo 'selected'; } ?>>エルフィンタ</option>
                                <option  <?php if( !empty($_SESSION['server']) && $_SESSION['server'] === "ミストラル" ){ echo 'selected'; } ?>>ミストラル</option>
                                <option  <?php if( !empty($_SESSION['server']) && $_SESSION['server'] === "ゼルナ" ){ echo 'selected'; } ?>>ゼルナ</option>
                                <option  <?php if( !empty($_SESSION['server']) && $_SESSION['server'] === "モエン" ){ echo 'selected'; } ?>>モエン</option>
                            </select>
                            <?php if($_SESSION['server']==="▼サーバを選択してください" && $_POST['btn_confirm']){echo '<font color="red">*サーバを選択していません</font>';} ?>
                        </div>
                    </div>
                     <!--item -->
                    <div class="form-group row">
                        <label for="item" class="col-sm-2 col-form-label">アイテム名</label>
                        <div class="col-sm-10">
                        <input type="text" class="form-control" id="item" name="item" value="<?php if( !empty($_SESSION['item']) ){ echo $_SESSION['item']; } ?>" placeholder="正式名称で入力：ゲシュ⇒ゲシュタルトの破片">
                            <?php if($_SESSION['item']==="" && $_POST['btn_confirm']){echo '<font color="red">*アイテム名が空欄です</font>';} ?>
                        </div>
                    </div>
                    <!-- price -->
                    <div class="form-group row">
                        <label for="inputPassword" class="col-sm-2 col-form-label">価格</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="price" name="price" value="<?php if( !empty($_SESSION['price']) ){ echo $_SESSION['price']; } ?>" placeholder="価格を半角で入力。kとmと入力しても自動で0を追加します">
                            <?php if($_SESSION['price']==="" && $_POST['btn_confirm']){echo '<font color="red">*価格が空欄です</font>';} ?>
                        </div>
                    </div>
                    <!-- buyspot  -->
                    <div class="form-group row">
                        <label for="inputPassword" class="col-sm-2 col-form-label">売場</label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" name="buyspot" value="露店" readonly>
                        </div>
                    </div>
                    <!-- buyname  -->
                    <div class="form-group row">
                        <label for="buyname" class="col-sm-2 col-form-label">販売者名</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="buyname"  name="buyname"  value="<?php if( !empty($_SESSION['buyname']) ){ echo $_SESSION['buyname']; } ?>" placeholder="販売者名をキャラクター名で入力">
                             <?php if($_SESSION['buyname']==="" && $_POST['btn_confirm']){echo '<font color="red">*販売者名が空欄です</font>';} ?>
                        </div>
                    </div>
                    <!-- date  -->
                    <div class="form-group row">
                        <label for="date" class="col-sm-2 col-form-label">日付</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="date" name="date" value="<?php if( !empty($_SESSION['date']) ){ echo $_SESSION['date']; } ?>" placeholder="露店の場合は登録した日付をOMの場合は掲載期限の日付を登録してください">
                             <?php if($_SESSION['date']==="" && $_POST['btn_confirm']){echo '<font color="red">*日付が空欄です</font>';} ?>
                        </div>
                    </div>
                    <center>
                        <input type="submit" class="btn btn-secondary" name="btn_cancel" value="TOPページに戻る">
                        <input type="submit" class="btn btn-primary" name="btn_confirm"  value="確認する">
                    </center>
                </form>
        <?php endif; ?>     

    </body>
</html>

<?php 
    //接続修了
    $dbh=null;
?>
