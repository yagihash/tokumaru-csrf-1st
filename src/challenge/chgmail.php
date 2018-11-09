<?php // chgmail.php メールアドレス変更実行
  session_start();
  if (empty($_SESSION['id'])) {
    die('ログインしてください');
  }
  $id = $_SESSION['id']; // ユーザIDの取り出し
  if ($_POST['token'] !== $_SESSION['token']) { // ワンタイムトークン確認
    die('正規の画面からご使用ください');
  }
  unset($_SESSION['token']); // 使用済みトークンの削除
  $mail = $_POST['mail'];
  $_SESSION['mail'] = $mail;
?>
<body>
<?php echo htmlspecialchars($id, ENT_COMPAT, 'UTF-8'); ?>さんのメールアドレスを<?php
 echo htmlspecialchars($mail, ENT_COMPAT, 'UTF-8'); ?>に変更しました<br>
<a href="mypage.php">マイページ</a>
</body>
