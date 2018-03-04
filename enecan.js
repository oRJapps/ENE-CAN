$.ajaxSetup({
    beforeSend: function (xhr) {
        xhr.overrideMimeType("text/html;charset=Shift_JIS");
    }
});


$("#drop-area").on("drop", function (e) {
    var oneDay = "";
    e.preventDefault();
    var files = e.originalEvent.dataTransfer.files
    for (var i = 0; i < files.length; i++) {
        var file = files[i];
        $("#file").html("TWチャットログファイル名：" + "<strong>" + file.name + "</strong>");
        oneDay = new Date((file.name).substr(10, 4), (file.name).substr(15, 2) - 1, (file.name).substr(18, 2));


    }
    var reader = new FileReader();
    // onloadハンドラ
    reader.onload = function (e) {
        // 取得した結果を表示
        var res = e.target.result.replace(/\r\n|\r/g, "\n");
        var str = res.split("\n");
        var strArray = new Array();
        var mobCount = 0;
        var total = 0;
        var exp = 0;
        var flg = true;
        var firstlog = true;
        var dateA = "";
        var dateB = "";
        var dateC = "";
        var firstPlayTime = ""; //ログ取得時タイム
        var lastPlayTime = ""; //最終ログアウト時間
        var all = 0;
        var play = 0;
        var last = 0;

        for (var i = 0; i < str.length; i++) {
            last = (str.length) - 1;
            strArray[i] = str[i];
            //console.log("i:" + i + " strArray:" + strArray[i]);

            //ログ取得開始時間取得
            if (strArray[i].indexOf("時") > 0 && strArray[i].indexOf("分") > 0) {
                if (firstlog) {
                    play = i;
                    firstlog = false;
                }
                //プレイ時間ラスト取得
                last = i;

            }
            if (strArray[last] == "") {
                last = i - 1;
            }
            if (strArray[i].indexOf("経験値が") > 0) {
                //以下の条件の場合は読み飛ばす
                if (strArray[i].indexOf("ルーン経験値が") > 0) {
                    continue;
                }
                if (strArray[i].indexOf("修練の石") > 0) {
                    continue;
                }
                if (strArray[i].indexOf("レベルアップ") > 0) {
                    continue;
                }
                mobCount += 1;

                if (flg == true) {
                    dateA = new Date(oneDay.getFullYear(), oneDay.getMonth(), oneDay.getDate(), strArray[i].substr(strArray[i].indexOf("[") + 1, 2), strArray[i].substr(strArray[i].indexOf("[") + 5, 2), strArray[i].substr(strArray[i].indexOf("[") + 9, 2));
                    dateB = new Date(dateA);
                    dateB.setHours(dateA.getHours() + 1);
                    flg = false;
                }
                dateC = new Date(oneDay.getFullYear(), oneDay.getMonth(), oneDay.getDate(), strArray[i].substr(strArray[i].indexOf("[") + 1, 2), strArray[i].substr(strArray[i].indexOf("[") + 5, 2), strArray[i].substr(strArray[i].indexOf("[") + 9, 2));

                //経験値
                exp = total + parseInt(strArray[i].slice(strArray[i].indexOf("経験値が ") + 4, strArray[i].lastIndexOf(" 上がりました。</font>")), 10);
                total = exp;

                if (dateB > dateC) {
                    all = total;
                }
            }
        }
        firstPlayTime = new Date(oneDay.getFullYear(), oneDay.getMonth(), oneDay.getDate(), parseInt(strArray[play].substring(strArray[play].indexOf("[") + 1, strArray[play].indexOf("時")), 10), parseInt(strArray[play].substring(strArray[play].indexOf("時") + 1, strArray[play].indexOf("分")), 10), parseInt(strArray[play].substring(strArray[play].indexOf("分") + 1, strArray[play].indexOf("秒")), 10));



        lastPlayTime = new Date(oneDay.getFullYear(), oneDay.getMonth(), oneDay.getDate(), parseInt(strArray[last].substring(strArray[last].indexOf("[") + 1, strArray[last].indexOf("時")), 10), parseInt(strArray[last].substring(strArray[last].indexOf("時") + 1, strArray[last].indexOf("分")), 10), parseInt(strArray[last].substring(strArray[last].indexOf("分") + 1, strArray[last].indexOf("秒")), 10));


        var playTimeTotal = lastPlayTime - firstPlayTime;
        var h = String(Math.floor(playTimeTotal / 3600000) + 100).substring(1);
        var m = String(Math.floor((playTimeTotal - h * 3600000) / 60000) + 100).substring(1);
        var s = String(Math.round((playTimeTotal - h * 3600000 - m * 60000) / 1000) + 100).substring(1);


        $("#result").html("MOB討伐数は<span>" + String(mobCount).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, '$1,') + "</span>匹です");
        $("#playtime").html("総プレイ時間は、" + h + "時間" + m + "分" + s + "秒です");
        $("#exp").html("総取得経験値は、" + String(total).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, '$1,') + "expです<br>狩りをはじめてから１時間の取得経験値は、" +
            String(all).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, '$1,') + "expです");
        $("span").attr("id", "count");
        $("#ss").html('<center><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" id="btn">画像プレビュー</button></center>');
        html2canvas($("#ss-area"), {
            onrendered: function (canvas) {
                //$("#img").append(canvas);
                var img = new Image();
                img.src = canvas.toDataURL("image/png");
                img.width = 450;
                document.getElementsByClassName('modal-body')[0].appendChild(img);
                //$("#btn-ss").attr("download", "enecan.png").attr("href", canvas.toDataURL("image/png"));
            }
        });

    };
    reader.readAsText(file, "SJIS");
});

$("#drop-area").on("dragover", function (e) {
    e.preventDefault();
});

/*var element = $("#ss-area"); // 画像化したい要素をセレクタに指定
var getCanvas; 
  
    //プレビュー
    $("#ss").on('click', function () {
         html2canvas($("#ss-area"), {
         onrendered: function (canvas) {
               $("#btn").attr("download", "enecan.png").attr("href", canvas.toDataURL("image/png"));
             }
         });
    });
 
    // コンバートしてダウンロード
 /* $("a#btn").on('click', function () {
    var imgageData = getCanvas.toDataURL("image/png");
    var newData = imgageData.replace(/^data:image\/png/, "data:application/octet-stream");
    //ファイル名を設定
    $("a#btn").attr("download", "enecan.png").attr("href", newData);
  }); 
*/
