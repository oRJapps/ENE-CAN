
        $.ajaxSetup({
            beforeSend: function(xhr) {
                xhr.overrideMimeType("text/html;charset=Shift_JIS");
            }
        });


        $("#drop-area").on("drop", function(e) {
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
            reader.onload = function(e) {
                // 取得した結果を表示
                var res = e.target.result;
                var str = res.split("</br>")
                var strArray = new Array();
                var mobCount = 0;
                var total = 0;
                var exp = 0;
                var flg = true;
                var dateA = "";
                var dateB = "";
                var dateC = "";
                var all = 0;
                for (var i = 0; i < str.length; i++) {
                    strArray[i] = str[i];
                    if (strArray[i].indexOf("経験値が") > 0) {
                        if (strArray[i].indexOf("ルーン経験値が") > 0) {
                            continue;
                        }
                        mobCount += 1;

                        if (flg == true) {
                            dateA = new Date(oneDay.getFullYear(), oneDay.getMonth(), oneDay.getDate(), strArray[i].substr(strArray[i].indexOf("[") + 1, 2), strArray[i].substr(strArray[i].indexOf("[") + 5, 2), strArray[i].substr(strArray[i].indexOf("[") + 9, 2));
                            dateB = new Date(dateA);
                            dateB.setHours(dateA.getHours() + 1);
                            flg = false;
                            console.log(dateA);
                            console.log(dateB);
                        }
                        dateC = new Date(oneDay.getFullYear(), oneDay.getMonth(), oneDay.getDate(), strArray[i].substr(strArray[i].indexOf("[") + 1, 2), strArray[i].substr(strArray[i].indexOf("[") + 5, 2), strArray[i].substr(strArray[i].indexOf("[") + 9, 2));


                        //console.log(parseInt(strArray[i].slice(strArray[i].indexOf("経験値が ")+4,strArray[i].lastIndexOf(" 上がりました。</font>"))));
                        //経験値
                        exp = total + parseInt(strArray[i].slice(strArray[i].indexOf("経験値が ") + 4, strArray[i].lastIndexOf(" 上がりました。</font>")), 10);
                        total = exp;

                        if (dateB > dateC) {
                            all = total;
                        }
                    }
                }
                $("#result").html("MOB討伐数は<span>" + String(mobCount).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, '$1,') + "</span>匹です");
                $("#exp").html("総取得経験値は、" + String(total).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, '$1,') + "expです<br>狩りをはじめてから１時間の取得経験値は、" +
                    String(all).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, '$1,') + "expです");
                $("span").attr("id", "count");
            };
            reader.readAsText(file, "SJIS");
        });

        $("#drop-area").on("dragover", function(e) {
            e.preventDefault();
        });
