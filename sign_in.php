<?php

session_start();

require_once('functions.php');

// セッションが保存されていたらログイン済み
if (! empty($_SESSION['user'])) {
    $status = 'loggedin';
}

// ステイタス変数の初期化
$status = '';

// ログインボタンが押された場合
if (! empty($_POST["login"])) {
    // ユーザ名・パスワードの入力チェック
    if (! empty($_POST['uname']) && ! empty($_POST['upassword'])) {
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

            // 入力したユーザ名と一致するレコードを検索
            $sth = $dbh->prepare('SELECT upassword FROM users WHERE uname = ?');
            $sth->bindParam(1, h($_POST['uname']), PDO::PARAM_STR);
            $sth->execute();
            // 結果セットの行数が1だったら成功
            if ($sth->rowCount() == 1) {
                // 結果セットのupasswordカラムを$hashに関連付ける
                $sth->bindColumn('upassword', $hash);
                while ($sth->fetch()) {
                    // upasswordカラムの値が$_POST['upassword']で取得したパスワードにマッチするかどうか
                    if (password_verify($_POST['upassword'], $hash)) {
                        $status = 'success';
                        // $_POSTで取得したリクエストパラメータの値をセッションに保存
                        $_SESSION['user'] = $_POST;
                        header('Location: index.php');
                        exit();
                    } else {
                        $status = 'Password mismatch.';
                    }
                }
            } else {
                    $status = 'Login failed';
            }
        } catch (PDOException $e) {
            echo 'ERR! : ' . $e->getMessage();
        } finally {
            $dbh = null;
        }
    }
}
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>Sign in</title>
  <link rel="stylesheet" href="styles.css">
</head>

<body>
  <main>
    <div class="form-container">
      <section>
        <h1>Login</h1>
      </section>
      <hr>
      <div><?= $status ?></div>
      <form action="" method="post">
        <fieldset>
          <div><input type="text" name="uname" value="<?= $_POST['uname'] ?>" placeholder="Name"></div>
          <div><input type="password" name="upassword" value="<?= $_POST['upassword'] ?>" placeholder="Password"></div>
          <div class="text-align-right"><input type="submit" id="login" name="login" value="Login"></div>
        </fieldset>
      </form>
      <hr>
      <div class="text-align-center">Don't have account yet ! <a href="sign_up.php"><b>Sign Up</b></a></div>
    </div>
  </main>
</body>

</html>
