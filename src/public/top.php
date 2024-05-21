<?php
$dbUserName = 'root';
$dbPassword = 'password';
$pdo = new PDO(
    'mysql:host=mysql; dbname=tq_filter; charset=utf8',
    $dbUserName,
    $dbPassword
);

$keyword = isset($_GET['search']) ? $_GET['search'] : '';
// 日付選択のデータを取得
$date = isset($_GET['date']) ? $_GET['date'] : '';

$name = '%' . $keyword . '%';
$contents = '%' . $keyword . '%';

$sql = 'SELECT * FROM pages WHERE (name LIKE :name OR contents LIKE :contents)';

// 日付のSQL条件を追加
if (!empty($date)) {
  $sql .= ' AND DATE(created_at) = :date';
}

$statement = $pdo->prepare($sql);
$statement->bindValue(':name', $name, PDO::PARAM_STR);
$statement->bindValue(':contents', $contents, PDO::PARAM_STR);
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
    <form action="top.php" method="get">
      <input type="text" name="search" placeholder="キーワードを入力"><br>
      <!-- 日付の選択フォームの追加 -->
      <input type="date" name="date" value="<?php echo $date; ?>"><br>
      <input type="submit">
    </form>
    <div>
      <form action="index.php" method="get">
        <div>
          <label>
            <input type="radio" name="order" value="desc">
            <span>新着順</span>
          </label>
          <label>
            <input type="radio" name="order" value="asc">
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