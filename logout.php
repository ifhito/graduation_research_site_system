<?php
session_start();

if (isset($_SESSION['NAME'])) {
    $errorMessage = '実験協力ありがとうございました。（ログアウトしたため、セッション情報が削除されました。もう一度行う場合は下記リンクからログインしてください）';
} else {
    $errorMessage = 'セッションがタイムアウトしました。';
}

// セッションの変数のクリア
$_SESSION = array();

// セッションクリア
@session_destroy();
?>

<!doctype html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>ログアウト</title>
    </head>
    <body>
        <h1>ログアウト画面</h1>

        <div><?php echo htmlspecialchars($errorMessage, ENT_QUOTES); ?></div>
        <ul>
            <li><a href="index.php">ログイン画面に戻る</a></li>
        </ul>
    </body>
</html>