<!DOCTYPE html>
<html>
<head>
  <?= snippet('meta') ?>
</head>
<body>
  <h1><?= $page->title() ?></h1>
  <div><?= $page->text()->esc() ?></div>
</body>
</html>