<?php

use Kirby\Toolkit\A;

if ($content = option('adamkiss.seo-lite.robots.content')) {
	if (is_callable($content)) {
		$content = $content();
	}

	if (is_array($content)) {
		$str = [];

		foreach ($content as $ua => $data) {
			$str[] = 'User-agent: ' . $ua;
			foreach ($data as $type => $values) {
				foreach ($values as $value) {
					$str[] = $type . ': ' . $value;
				}
			}
		}

		$content = A::join($str, PHP_EOL);
	}

	echo $content;
} else {
	// output default
	echo "User-agent: *\n";

	$index = option('adamkiss.seo-lite.robots.index');
	if (is_callable($index)) {
		$index = $index();
	}

	if ($index) {
		echo 'Allow: /';
		echo "\nDisallow: /panel";
	} else {
		echo 'Disallow: /';
	}
}

if (($sitemap = option('adamkiss.seo-lite.robots.sitemap')) || ($sitemapModule = option('adamkiss.seo-lite.sitemap.active'))) {
	// Allow closure to be used
	if (is_callable($sitemap)) {
		$sitemap = $sitemap();
	}

	// Use default sitemap if none is set
	if (!$sitemap && $sitemapModule) {
		$sitemap = site()->canonicalFor('/sitemap.xml');
	}

	// Check again, so falsy values can't be used
	if ($sitemap) {
		echo "\n\nSitemap: {$sitemap}";
	}
}
