<?php
$dbUserName = 'root';
$dbPassword = 'password';
$pdo = new PDO(
    'mysql:host=mysql; dbname=tq_filter; charset=utf8',
    $dbUserName,
    $dbPassword
);

// キーワードのデータを変数に代入
$keyword = isset($_GET['search']) ? '%' . $_GET['search'] .'%' : '%%';

// 期間の作成日フォームの変数
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';


// キーワードが含まれるデータを取得
$sql = 'SELECT * FROM pages WHERE (name LIKE :search OR contents LIKE :search)';

// 期間の作成日が送信された場合のデータ取得
if (!empty($start_date) && !empty($end_date)) {
  $sql .= ' AND created_at BETWEEN :start_date AND :end_date';
}

$statement = $pdo->prepare($sql);
$statement->bindValue(':search', $keyword, PDO::PARAM_STR);

// 期間の作成日が送信された場合のバインド
if (!empty($start_date) && !empty($end_date)) {
  $statement->bindValue(':start_date', $start_date, PDO::PARAM_STR);
  $statement->bindValue(':end_date', $end_date, PDO::PARAM_STR);
}

$statement->execute();
$pages = $statement->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>top画面</title>
</head>

<body>
  <div>
    <form action="mytop.php" method="get">
      <!-- 検索フォーム -->
      <input type="text" name="search" placeholder="検索ワードを入力"><br>
        <!-- 期間の作成日フォーム -->
          <label>期間指定：</label>
          <input type="date" name="start_date" value="<?php echo $start_date; ?>" required>
          <label>～</label>
          <input type="date" name="end_date" value="<?php echo $end_date; ?>" required>
        <button type="submit">送信</button>
      </form>
  </div>

    <div>
      <table border="1">
        <tr>
          <th>タイトル</th>
          <th>内容</th>
          <th>作成日時</th>
        </tr>
        <?php foreach ($pages as $page): ?>
          <tr>
            <td><?php echo $page['name']; ?></td>
            <td><?php echo $page['contents']; ?></td>
            <td><?php echo $page['created_at']; ?></td>
          </tr>
        <?php endforeach; ?>
      </table>
    </div>
  </div>
</body>

</html>