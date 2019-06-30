<?php
session_start();
//戻れないためにTrueに
$_SESSION['no_return'] = 'True';
// ログイン状態チェック
if (isset($_POST['abst']) && is_array($_POST['abst'])) {
    $abst = implode('/', $_POST['abst']);
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width">
<link href="css/checkbox.css" rel="stylesheet" type="text/css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script type="text/javascript" src="js/checkbox.js"></script>
<!--<script type="text/javascript" src="textbox.js"></script>-->
<!--<script type="text/javascript" src="js/jquery.modal_scroll.js"></script>-->
<!--<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js" type="text/javascript"></script>-->

<title>testサイト</title>

</head>
<body>

<h1>タイトル選択画面</h1>
<font size="5">
この画面では説明画面でも述べたように、<b>「要約文が本文に対して適切だと感じた記事のタイトル」</b>を選択していただきます。<br>
そして、選択後その下に出てくるテキストエリアにその記事の自分なりの要約を記述していただきます。<br>
</font>
<br>
<br>
<br>
<?php
if (!(isset($_SESSION['read_content']) && is_array($_SESSION['read_content']))) {
    echo '<script>';

    echo "location.href = 'logout.php';";
    echo '</script>';
}
?>


<div>
<!--チェックボックスのフォーム(return checkはcheckbox.js参照)-->
<form name="form1" method="post" action="" onSubmit="return check()" >
<?php

//読んだ文章の取得
$lines = $_SESSION['read_content'];
//print_r($_SESSION['lines']);

//被りを消す
$lines = array_unique($lines);
//配列の番号を振り直す
$lines = array_values($lines);
//文章の表示
//print_r($lines);
for ($i = 0; $i < count($lines); ++$i) {
    list($titles[], $abst_type[]) = explode('+rfr,sd+', $lines[$i]);
}

for ($i = 0; $i < count($titles); ++$i) {
    //$lines_use = $lines[$i];
    //print_r($lines[$i]);

    echo <<<EOM

    <input id ="$i" type="checkbox" name="abst[]" value="$lines[$i]" />
    <label for="$i">
    $titles[$i]
    </label><br>

EOM;
    //echo "<div class="."arrow_box".">"."<p>".$lines[1]."<br>".$lines[2]."<br>".$lines[3]."<br>"."</p>"."</div>";
}
?>
<!--結果送信ボタン-->
<input type="submit" id="signUp" name="signUp" value="結果送信">
</form>
<?php
$db['host'] = 'mysql134.phy.lolipop.lan'; // DBサーバのURL
$db['user'] = 'LAA1023346'; // ユーザー名
$db['pass'] = 'Amu19970208'; // ユーザー名のパスワード
$db['dbname'] = 'LAA1023346-udoncat'; // データベース名

$errorMessage = '';

if (isset($_POST['signUp'])) {
    //データベース(MySQLに接続)
    $dsn = sprintf('mysql:host=%s;dbname=%s;charset=utf8;unix_socket=/tmp/mysql.sock', $db['host'], $db['dbname']);
    //print_r($_POST['abst']);

    try {
        $pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        $abst = $_POST['abst'];
        //print_r($abst);

        //$title = $_POST["title"];
        //$len = mb_strlen($title,"utf-8");

        if (count($abst) == 3) {
            //sportというデータベースに対してSQLを実行する
            //$abst = join(",", $abst);

            if (isset($_POST['abst']) && is_array($_POST['abst'])) {
                $abst = implode('/', $_POST['abst']);
            }
            if (isset($_POST['abst_user']) && is_array($_POST['abst_user'])) {
                $abst_user = implode('/', $_POST['abst_user']);
            }
            if (isset($_SESSION['lines']) && is_array($_SESSION['lines'])) {
                $sentences = implode('/', $_SESSION['lines']);
            }
            //print_r($abst);
            //print_r($abst_user);
            //print_r($sentences);
            $stmt = $pdo->prepare('INSERT INTO result(id, userName, Num_of_times,results,abst,sentences) VALUES (?, ?, ?, ?, ?, ?)');
            if (isset($_SESSION['test'])) {
                $stmt->execute(array(10000 + $_SESSION['ID'], 'test_'.$_SESSION['NAME'], $_SESSION['Num_of_times'], $abst, $abst_user, $sentences));
            } else {
                $stmt->execute(array($_SESSION['ID'], $_SESSION['NAME'], $_SESSION['Num_of_times'], $abst, $abst_user, $sentences));
                $sql = 'UPDATE userData SET Num_of_times = :Num_of_times WHERE id = :id';
                // 更新する値と該当のIDは空のまま、SQL実行の準備をする
                $stmt = $pdo->prepare($sql);
                // 更新する値と該当のIDが入った変数をexecuteにセットしてSQLを実行
                $stmt->execute(array(':Num_of_times' => $_SESSION['Num_of_times'] + 1, ':id' => $_SESSION['ID']));
            }

            echo <<<EOM
        <script>

        location.href = 'logout.php';
        </script>

EOM;

            exit;
        } else {
            echo <<<EOM
        <script>
      alert('3つ選んでください');
    </script>



EOM;
        }
    } catch (PDOException $e) {
        $errorMessage = 'データベースエラー';
        $errorMessage = $sql;
        // $e->getMessage() でエラー内容を参照可能（デバッグ時のみ表示）
        echo $e->getMessage();
    }
}

//var_dump($_POST["abst"]);
?>
<!--<textarea id="iis" name="iui" maxlength="100">ここに要約を入力してください</textarea>-->
<div><font color="#ff0000"><?php echo htmlspecialchars($errorMessage, ENT_QUOTES); ?></font></div>
</div>


</body>
</html>