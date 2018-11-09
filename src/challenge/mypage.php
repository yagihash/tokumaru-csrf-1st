<?php // mypage.php : ログインしたことにする確認用のスクリプト
  session_start();
  if (empty($_SESSION['id'])) { // ログインしたことにしてメールアドレスも初期化
    $_SESSION['id'] = 'alice';
    $_SESSION['mail'] = 'alice@example.com';
  }
?><body>
ログイン中(id:<?php echo
  htmlspecialchars($_SESSION['id'], ENT_QUOTES, 'UTF-8'); ?>)<br>
メールアドレス:<?php echo
  htmlspecialchars($_SESSION['mail'], ENT_QUOTES, 'UTF-8'); ?><br>
<a href="chgmailform.php">メールアドレス変更</a><br>
</body>
