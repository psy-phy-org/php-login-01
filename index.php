<?php

session_start();

include('functions.php');

if (!isset($_SESSION['user'])) {
    header('Location: sign_out.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="utf-8">
  <title>welcome - <?= h($_SESSION['user']['uname']) ?></title>
  <link rel="stylesheet" href="styles.css">
</head>

<body>
  <header>
    <div class="left">
      <p><a>PHP Authentication sample</a></p>
    </div>
    <div class="right">
      <p><a href="sign_out.php">sign out</a></p>
    </div>
  </header>
  <div class="content">welcome : <?= h($_SESSION['user']['uname']) ?></div>
</body>

</html>
