<?php // chgmailform.php メールアドレス変更フォーム
  session_start();
  if (empty($_SESSION['id'])) {
    die('ログインしてください');
  }
  $token = bin2hex(random_bytes(24)); // ワンタイムトークン生成
  $_SESSION['token'] = $token;
?><body>
<form action="chgmail.php" method="POST">
メールアドレス<input name="mail"><BR>
<input type=submit value="メールアドレス変更">
<input type="hidden" name="token" value="<?php
 echo htmlspecialchars($token, ENT_COMPAT, 'UTF-8'); ?>">
</form>
</body>
