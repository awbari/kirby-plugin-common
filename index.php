<?php

use Kirby\Cms\App;
use Kirby\Http\Response;
use Kirby\Toolkit\Xml;

function robots(bool $disallow, bool $sitemapEnabled)
{
  $robots = 'User-agent: *' . PHP_EOL;
  $robots .= ($disallow ? 'Disallow: /panel/' : 'Disallow: /') . PHP_EOL;

  if ($sitemapEnabled) {
    $robots .= $disallow ? '' : 'Sitemap: ' . url('sitemap.xml');
  }

  return App::instance()
    ->response()
    ->type('text')
    ->body($robots);
}

function sitemap()
{
  $kirby = App::instance();
  $sitemap = [];
  $cache = kirby()->cache('pages');
  $id = 'sitemap.xml';

  if (!$sitemap = $cache->get($id)) {
    $sitemap[] = '<?xml version="1.0" encoding="UTF-8"?>';
    $sitemap[] = '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

    $templates = option('stat0.common.exclude.templates', []);
    $pages = option('stat0.common.exclude.pages', []);

    foreach ($kirby->site()->index() as $p) {
      if (in_array($p->intendedTemplate()->name(), $templates) === true) {
        continue;
      }

      if (in_array($p->uri(), $pages)) {
        continue;
      }

      $sitemap[] = '<url>';
      $sitemap[] = '  <loc>' . Xml::encode($p->url()) . '</loc>';
      $sitemap[] = '  <lastmod>' . $p->modified('c', 'date') . '</lastmod>';
      $sitemap[] = '  <priority>' . (($p->isHomePage()) ? 1 : number_format(0.5 / $p->depth(), 1)) . '</priority>';
      $sitemap[] = '</url>';
    }

    $sitemap[] = '</urlset>';
    $sitemap = implode(PHP_EOL, $sitemap);

    $cache->set($id, $sitemap);
  }

  return new Response($sitemap, 'application/xml');
}

App::plugin('stat0/common', [
  'options' => [
    'sitemap.enabled' => false,
    'robots.enabled' => false,
    'robots.disallow' => false,
  ],
  'snippets' => [
    'meta' => __DIR__ . '/snippets/meta.php'
  ],
  'blueprints' => [
    'sections/meta' => __DIR__ . '/blueprints/sections/meta.yml',
    'files/opengraph-image' => __DIR__ . '/blueprints/files/opengraph-image.yml',
    'files/cover' => __DIR__ . '/blueprints/files/cover.yml',
    'fields/cover' => __DIR__ . '/blueprints/fields/cover.yml',
  ],
  'routes' => [
    [
      'pattern' => 'robots.txt',
      'method' => 'ALL',
      'action' => fn() => option('stat0.common.robots.enabled') === true
        ? robots(option('stat0.common.robots.disallow'), option('stat0.common.sitemap.enabled'))
        : false,
    ],
    [
      'pattern' => 'sitemap.xml',
      'action' => fn() => option('stat0.common.sitemap.enabled') === true
        ? sitemap()
        : false,
    ],
    [
      'pattern' => 'sitemap',
      'action' => fn() => option('stat0.common.sitemap.enabled') === true
        ? go('sitemap.xml', 301)
        : false
    ]
  ],
]);
