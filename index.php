<?php
//require 'password.php';   // password_verfy()はphp 5.5.0以降の関数のため、バージョンが古くて使えない場合に使用
// セッション開始
session_start();

$db['host'] = 'mysql134.phy.lolipop.lan'; // DBサーバのURL
$db['user'] = 'LAA1023346'; // ユーザー名
$db['pass'] = 'Amu19970208'; // ユーザー名のパスワード
$db['dbname'] = 'LAA1023346-udoncat'; // データベース名

// エラーメッセージの初期化
$errorMessage = '';

// ログインボタンが押された場合
if (isset($_POST['login'])) {
    // 1. ユーザIDの入力チェック
    if (empty($_POST['userid'])) { // emptyは値が空のとき
        $errorMessage = 'ユーザーIDが未入力です。';
    } elseif (empty($_POST['password'])) {
        $errorMessage = 'パスワードが未入力です。';
    }

    if (!empty($_POST['userid']) && !empty($_POST['password'])) {
        // 入力したユーザIDを格納
        $userid = $_POST['userid'];

        // 2. ユーザIDとパスワードが入力されていたら認証する
        $dsn = sprintf('mysql:host=%s;dbname=%s;charset=utf8;unix_socket=/tmp/mysql.sock', $db['host'], $db['dbname']);

        // 3. エラー処理
        try {
            $pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

            $stmt = $pdo->prepare('SELECT * FROM userData WHERE name = ?'); //のちの置き換えのための準備、？にexecuteで置き換え
            $stmt->execute(array($userid)); //上記nameをuseridで置き換え

            $password = $_POST['password'];

            if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { //stmtのPODクラスのfetchメソッドを実行(全てのカラムを取り出す)
                if (password_verify($password, $row['password'])) { //passがマッチしていたら
                    session_regenerate_id(true); //セッションidの置き換え

                    // 入力したIDのユーザー名を取得
                    $id = $row['id'];
                    $sql = "SELECT * FROM userData WHERE id = $id"; //入力したIDからユーザー名を取得
                    $stmt = $pdo->query($sql);
                    foreach ($stmt as $row) {
                        $row['name']; // ユーザー名
                        $row['Num_of_times'];
                        $row['id'];
                    }
                    $_SESSION['ID'] = $row['id'];
                    $_SESSION['NAME'] = $row['name'];
                    $_SESSION['Num_of_times'] = $row['Num_of_times'];
                    if (isset($_POST['test'])) {
                        $_SESSION['test'] = $_POST['test'];
                    }
                    header('Location: abst.php'); // メイン画面へ遷移
                    exit(); // 処理終了
                } else {
                    // 認証失敗
                    $errorMessage = 'ユーザーIDあるいはパスワードに誤りがあります。';
                }
            } else {
                // 4. 認証成功なら、セッションIDを新規に発行する
                // 該当データなし
                $errorMessage = 'ユーザーIDあるいはパスワードに誤りがあります。';
            }
        } catch (PDOException $e) {
            $errorMessage = 'データベースエラー';
            //$errorMessage = $sql;
            // $e->getMessage() でエラー内容を参照可能（デバッグ時のみ表示）
            // echo $e->getMessage();
        }
    }
}
?>

<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>ログイン</title>
<link href="css/login.css" rel="stylesheet" type="text/css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
</head>
<body>
<?php
if ($_SESSION['no_return'] == true) {
    echo <<<EOM
    <script>

    location.href = 'check_abst.php';
    </script>

EOM;
}
?>
<h2>実験説明</h2>
    <font size="4">
        <b>実験へのご参加、ありがとうございます。</b><br>
        本ページでは実験のご説明をさせていただきます。<br>
    </font>
<h2>実験の全体的な概要</h2>
    <font size="4">
        この実験は、二つのフェイズに別れています。<br>
        <b>まず、一つ目のフェイズでは、提示されるタイトル、要約文、本文を制限時間7分間で読んでいただきます。<br>
        そして、次のフェイズで、より、本文に適切であると感じられる、要約文を三つ選択していただきます。</b><br>
        詳しい、実験方法は以下で説明いたします。<br>
    </font>
<br>
<h2>ログイン画面(実験説明画面)</h2>
<font size="4">
    本ページの一番下にログインフォームがあります。そこから、新規登録で登録したユーザとパスワードでログインしていただきます。
</font>
<h2>実験画面(要約表示画面)</h2>
<font size="4">
    ログインをしていただくとすぐに実験画面に移動します。<br>
    実験画面は以下のように10個の記事の要約とタイトルの羅列となっております。
    <div align="center">
        <img src="画像/要約選択画面(要約あり).png" width="50%" height="50%">
    </div>
    以下で実験画面の操作方法の説明をいたします。
    <ol>
        <li><b>１０個の文の中から、自分が「興味が湧いた」順に、「要約とタイトル」を読んでいただきます。</b></li>
        <li>それらを読み終わり、本文が読みたいと感じた場合は色が変わるタイトルをクリックしてください。</li>
        <div align="center">
            <img src="画像/色が変わる.png" width="50%" height="50%">
        </div>
        <li>すると、以下のように本文が表示されます。<b>この本文をしっかりと読んでください(後述しますが、この本文の自分なりの要約を記述していただきます)。</b>読み終わったら閉じるボタンで本文を閉じてください。</li>
        <div align="center">
            <img src="画像/本文1.png" width="50%" height="50%">
            <img src="画像/本文2.png" width="50%" height="50%">
        </div>
        <li>以上の操作を行い、<b>制限時間7分(なお、残り30秒になると以下のように制限時間が表示されます。)の中で記事を３つ以上</b>読んでください。</li>
        <div align="center">
            <img src="画像/limit.png" width="50%" height="50%">
        </div>
    </ol>
</font>
<h2>実験画面(選択画面)</h2>
<font size="4">
    7分経つと自動的に以下の画面に飛びます。<br>
    <div align="center">
        <img src="画像/選択画面(タイトルのみ).png" width="50%" height="50%">
    </div>
    この画面では前の画面で読んだ文章の中から<b>本文に対して、要約が適切であると感じたものを３つ選んで</b>いただきます。<br>
    選択画面は上記の画像のように「前画面であなたが本文を読んだ文章のタイトル」がチェックボックスと一緒に並んでいます。<br>
    あなたは以上のタイトルから<b>本文に対して、要約文が適切だと感じたものを選択</b>し、チェックしていただきます。<br>
    チェックすると以下のようにその下にテキストエリアが表示されます。<br>
    <div align="center">
        <img src="画像/選択画面.png" width="50%" height="50%">
    </div>
    あなたはこのテキストエリアに、選択したタイトルの記事の要約を自分の言葉で20〜100文字で記述していただきます。<br>
    これを３つの記事において行なってください。<br>
    終了したら、結果送信ボタンを押してください(なお、三つの要約を20字以上書き終わらないと送信ボタンは押せません)<br>
    <br>
    <br>
    以上で一回の実験は終了となります。
</font>
<h2>ログアウト画面</h2>
<font size="4">
    結果を送信するとログアウト画面に進みます。
</font>
<div align="center">
    <img src="画像/ログアウト画面.png" width="50%" height="50%">
</div>
<h2>実験回数と注意点</h2>
<font color="red" size="5">
    実験は明日から初めて頂き、来週の木曜日まで行っていただきます。<br>
    実験は一日最低３回行うようにしてください。(それ以上行う場合は、一日、6回までにしてください。)<br>
    なるべく時間を離し行うようにし、1日に大量の回数を行わないようにお願いいたします。<br>
    使うブラウザはchromeを使用し、全画面表示で行うようにしてください。<br>
    一回の実験は集中して行うようにしてください。また、一度実験を始めた場合、ログアウト画面になるまで行ってください。<br>
    <br>
    <br>
</font>
<div><center><font color="#ff0000"><?php echo htmlspecialchars($errorMessage, ENT_QUOTES); ?></font></center></div>
<fieldset>
<h1>ログイン</h1>
<form id="loginForm" name="loginForm" action="" method="POST">
<div class="iconUser"></div>

        <input placeholder="Username" type="text" id="userid" name="userid" value="<?php if (!empty($_POST['userid'])) {
    echo htmlspecialchars($_POST['userid'], ENT_QUOTES);
}?>" required>
        <br>
        <div class="iconPassword"></div>
        <input type="password" id="password" name="password" value="" placeholder="Password" required>
        <br>
        <input type="submit" id="login" name="login" value="ログイン">
        <input type="checkbox" name="test" value="test"> test
        <br>
</form>

</fieldset>

</body>
</html>
