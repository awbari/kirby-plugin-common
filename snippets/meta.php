<?php
  $title = $site->title();
  $title = $page->title()->isNotEmpty() ? $page->title() . ' | ' . $title : $title;

  $desc = $site->description()->isNotEmpty() ? $site->description() : '';
  $desc = $site->desc()->isNotEmpty() ? $site->desc() : $desc;
  $desc = $page->description()->isNotEmpty() ? $page->description() : $desc;
  $desc = $page->desc()->isNotEmpty() ? $page->desc() : $desc;

  $tags = $site->metatags()->isNotEmpty() ? $site->metatags()->toString() : '';

  $ogimage = $site->ogimage()->isNotEmpty() ? $site->ogimage()->toFile()->url() : null;
  $ogimage = $page->cover()->isNotEmpty() ? $page->cover()->toFile()->resize(1200, 630)->url() : $ogimage;
?>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
<title><?= $title ?></title>
<meta name="description" content="<?= $desc ?>">
<meta name="keywords" content="<?= $tags ?>">
<meta property="og:type" value="website" />
<meta property="og:url" content="<?= $site->url() ?>">
<meta property="og:site_name" content="<?= $site->title() ?>">
<meta property="og:title" content="<?= $title ?>">
<meta property="og:description" content="<?= $desc ?>">
<?php if ($ogimage): ?>
<meta property="og:image" content="<?= $ogimage ?>">
<meta property="og:image:type" content="image/png">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<?php endif; ?>
<meta name="twitter:card" content="<?= $ogimage ? 'summary_large_image' : 'summary' ?>" />
<meta name="twitter:title" content="<?= $title ?>" />
<meta name="twitter:description" content="<?= $desc ?>" />
<?php if ($ogimage): ?>
<meta name="twitter:image" content="<?= $ogimage ?>" />
<?php endif; ?>
