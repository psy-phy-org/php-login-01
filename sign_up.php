<?php

session_start();

require_once('functions.php');

// ステイタス変数の初期化
$status = '';

// サインアップボタンが押された場合
if (! empty($_POST["signup"])) {
    // ユーザ名・パスワードの入力チェック
    if (empty($_POST["uname"])) {
        $status = 'An username is required!';
    } elseif (empty($_POST["upassword"])) {
        $status = 'An password is required!';
    } elseif (! empty($_POST['uname']) && ! empty($_POST['upassword'])) {
        try {
            // データベースに接続
            $dbh = new PDO(
                'mysql:host=localhost;dbname=login_01;charset=utf8',
                'root',
                'root',
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]
            );

            // パスワードはハッシュ化する
            $password = password_hash($_POST['upassword'], PASSWORD_DEFAULT);

            // ユーザ入力を使用するのでプリペアドステートメントを使用
            $stmt = $dbh->prepare('INSERT INTO users VALUES (?, ?)');
            $stmt->bindParam(1, h($_POST['uname']), PDO::PARAM_STR);
            $stmt->bindParam(2, h($password), PDO::PARAM_STR);
            if ($stmt->execute()) {
                $status = 'Successfully registered.';
            } else {
                // 既に存在するユーザ名だった場合INSERTに失敗する
            }
        } catch (PDOException $e) {
            echo 'ERR! : ' . $e->getMessage();
            $status = 'User name that already exists.';
        } finally {
            $dbh = null;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="utf-8">
  <title>sign up</title>
  <link rel="stylesheet" href="styles.css">
</head>

<body>
  <main>
    <div class="form-container">
      <section>
        <h1>Sign up</h1>
      </section>
      <hr>
      <div><?= $status ?></div>
      <form action="" method="post">
        <fieldset>
          <div><input type="text" name="uname" value="<?= $_POST['uname'] ?>" placeholder="Name"></div>
          <div><input type="password" name="upassword" value="<?= $_POST['upassword'] ?>" placeholder="Password"></div>
          <div class="text-align-right"><input type="submit" id="signup" name="signup" value="Sign up"></div>
        </fieldset>
      </form>
      <hr>
      <div class="text-align-center">have an account ! <a href="index.php"><b>Login</b></a></div>
    </div>
    </main>
</body>

</html>
