<?php
$dbUserName = 'root';
$dbPassword = 'password';
$pdo = new PDO(
    'mysql:host=mysql; dbname=tq_filter; charset=utf8',
    $dbUserName,
    $dbPassword
);
// キーワードのデータを変数に代入
$keyword = isset($_GET['search']) ? '%'. $_GET['search']. '%' : '%%';

// 作成日時のデータを取得
$date = isset($_GET['date']) ? $_GET['date'] : '';

// 指定された並び替えのデータを変数に代入
$order = isset($_GET['order']) ? $_GET['order'] : '';

// キーワードが含まれるデータを取得
$sql = 'SELECT * FROM pages WHERE name LIKE :search OR contents LIKE :search';

// 作成日時が含まれるデータを取得
if (!empty($date)) {
  $sql .= ' AND DATE(created_at) = :date';
}

// 並び替えの指定を設定
if ($order === 'asc') {
  $sql .= ' ORDER BY created_at ASC';
} else {
  $sql .= ' ORDER BY created_at DESC';
}

$statement = $pdo->prepare($sql);
$statement->bindValue(':search', $keyword, PDO::PARAM_STR);

if (!empty($date)) {
  $statement->bindValue(':date', $date, PDO::PARAM_STR);
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
    <!-- キーワード検索フォーム -->
    <form action="privatetop.php" method="get">
      <input type="text" name="search" placeholder="キーワードを入力"><br>
      <!-- 作成日時のフォーム -->
      <input type="date" name="date"><br>
      <!-- 並び替えフォーム -->
          <label>
            <input type="radio" name="order" value="desc" class="">
            <span>新着順</span>
          </label>
          <label>
            <input type="radio" name="order" value="asc" class="">
            <span>古い順</span>
          </label>
        </div>
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