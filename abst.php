<?php
session_start();

// ログイン状態チェック
if (!isset($_SESSION['NAME'])) {
    header('Location: logout.php');
    exit;
}

?>
<!DOCTYPE html>
<html>

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width">
<link href="css/style.css" rel="stylesheet" type="text/css">
<link href="css/style1.css" rel="stylesheet" type="text/css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<!--<script type="text/javascript" src="post.js"></script>-->
<!--<script type="text/javascript" src="time.js"></script>-->
<script type="text/javascript" src="js/jquery.modal_scroll.js"></script>
<title>testサイト</title>
</head>

<body>

<div class="container">



  <div class="content">
<div class="main">

<?php
//戻れないようにする

//no_returnがTrueだと戻れない
if ($_SESSION['no_return'] == true) {
    echo <<<EOM
    <script>

    location.href = 'check_abst.php';
    </script>

EOM;
}
//$start = microtime(true) * 1000;
//$limit_time = 25000;
//ファイルの取得
function getFiles($path)
{
    $result = array();

    foreach (glob($path.'/*') as $file) {
        if (is_dir($file)) {
            $result = array_merge($result, getFiles($file));
        }

        $result[] = $file;
    }

    return $result;
}
//ここは何回行ったか(データベースのNum_of_times)の値になる
//$path = $_SESSION['Num_of_times'];
if (isset($_SESSION['test'])) {
    $path = 'text/'.'test3';
} else {
    $path = 'text/'.$_SESSION['Num_of_times'];
}

//ファイルがないとlogout
if (!(file_exists($path))) {
    echo <<<EOM
        <script>

        location.href = 'logout.php';
        </script>

EOM;
}
//result1にファイルのパスを代入
$result1 = array();
$lines = array();
$result1 = getFiles($path);

//var_dump($result1);
//ファイルの文章を一行づつ読み込み
for ($i = 0; $i < count($result1); ++$i) {
    $filename = $result1[$i];
    $lines[$i] = file($filename);
}
//文章のシャッフル
shuffle($lines);
//文章をセクションに保存(並び取得のため)

// ファイルを変数に格納
//echo count($result);
//var_dump($lines);

//文章の表示lines[][0]がabstractのタイプlines[][1]はタイトル[2][3][4]は要約文、その後が本文
for ($i = 0; $i < count($lines); ++$i) {
    $_SESSION['lines'][] = $lines[$i][1].'['.$lines[$i][0].']';
    $line_s = $lines[$i][1].'+rfr,sd+'.$lines[$i][0];
    $line_s = str_replace(PHP_EOL, '', $line_s);
    echo '<div class=all>';
    echo '<button id=btn data-target='.strval($i).' class='.'modal-open'.' name='.'texts'.' value='.$line_s.'>'.$lines[$i][1].'</button>';
    echo '<div class='.'box'.'>'.'<p class='.'nomove'.'>'.$lines[$i][2].'<br><br>'.$lines[$i][3].'<br><br>'.$lines[$i][4].'<br>'.'</p>'.'</div>';
    echo '</div>';
    echo '<div id='.strval($i).' class='.'modal-content'.'>';

    //オーバーレイで隠れているやつ
    for ($t = 0; $t < count($lines[$i]) - 5; ++$t) {
        echo '<p>'.$lines[$i][$t + 5].'<br>'.'</p>';
        //print "<a href=\"php_test2.php\">".$lines[$i]."</a><br>";
    }
    //オーバーレイの閉じる文章
    echo <<<EOM
	    <p><button class="modal-close" name="close" >閉じる</button></p>
    </div>

EOM;
}

//被りを消す
$_SESSION['lines'] = array_unique($_SESSION['lines']);
//配列の番号を振り直す
$_SESSION['lines'] = array_values($_SESSION['lines']);

?>

<?php
//データベース処理ーーーーーーーーーーーーーーーーーーーー

$db['host'] = 'mysql134.phy.lolipop.lan'; // DBサーバのURL
$db['user'] = 'LAA1023346'; // ユーザー名
$db['pass'] = 'Amu19970208'; // ユーザー名のパスワード
$db['dbname'] = 'LAA1023346-udoncat'; // データベース名

$errorMessage = '';
//$read_content = array();

//header('Content-type: text/plain; charset= UTF-8');
//second(文を見ていた時間)とtitle(文のタイトル)がPOSTされているかの確認(ここのエラーはjsのjquery.model_scroll内のajaxのpost参照)
if (isset($_POST['second']) && isset($_POST['title'])) {
    //それぞれを変数に代入する。
    $second = $_POST['second'];
    $title = $_POST['title'];
    //echo $title;
    //$read_content[] = $title;
    //文選択のための読んだ文章をsessionに追加する。
    $_SESSION['read_content'][] = $title;

    //echo "$read_content";

    //$str = "AJAX REQUEST SUCCESS\nuserid:".$id."\npassward:".$pas."\n";
    //$result = nl2br($str);
    //echo $result;

    //データベース(MySQLに接続)
    $dsn = sprintf('mysql:host=%s;dbname=%s;charset=utf8;unix_socket=/tmp/mysql.sock', $db['host'], $db['dbname']);

    try {
        //PDOを使ってデータベース接続
        $pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

        //$title = $_POST["title"];
        //$len = mb_strlen($title,"utf-8");

        //sportというデータベースに対してSQLを実行する
        //$abst = join(",", $abst);
        //if (isset($_POST['second']) && is_array($_POST['second']) && isset($_POST['btn']) && is_array($_POST['btn'])) {
        //$abst = implode('/', $_POST['abst']);
        //$abst_user = implode('/', $_POST['abst_user']);
        //}
        //インサートの準備

        //値の代入(インサート)
        if (isset($_SESSION['test'])) {
            $stmt = $pdo->prepare('INSERT INTO second(id, userName, Num_of_times,second,title) VALUES (?, ?, ?, ?, ?)');
            $stmt->execute(array(10000 + $_SESSION['ID'], 'test_'.$_SESSION['NAME'], $_SESSION['Num_of_times'], $second, $title));
        } else {
            $stmt = $pdo->prepare('INSERT INTO second(id, userName, Num_of_times,second,title) VALUES (?, ?, ?, ?, ?)');
            $stmt->execute(array($_SESSION['ID'], $_SESSION['NAME'], $_SESSION['Num_of_times'], $second, $title));
        }

        //$sql = 'UPDATE userData SET Num_of_times = :Num_of_times WHERE id = :id';

        // 更新する値と該当のIDは空のまま、SQL実行の準備をする
        //$stmt = $pdo->prepare($sql);

        // 更新する値と該当のIDが入った変数をexecuteにセットしてSQLを実行
        //$stmt->execute(array(':Num_of_times' => $_SESSION['Num_of_times'] + 1, ':id' => $_SESSION['ID']));
        //secondとtitleの値の初期化
        $_POST['second'] = 0;
        $_POST['title'] = '';
        exit;
    } catch (PDOException $e) { //データベースエラーの場合
        $errorMessage = 'データベースエラー';
        $errorMessage = $sql;
        // $e->getMessage() でエラー内容を参照可能（デバッグ時のみ表示）
        echo $e->getMessage();
    }
}
?>

</div>
<!--時間の表示、カウントはjs-->
<div class="side"><div id="countDown">
    <span id="m">07</span>:<span id="s">00</span>
</div>

</div>
  </div>


</div>




</body>
</html>