<?php

use Kirby\Http\Url;
use Kirby\Toolkit\Str;
use Adamkiss\Seo\SchemaSingleton;

return [
	'schema' => fn ($type) => SchemaSingleton::getInstance($type),
	'schemas' => fn () => SchemaSingleton::getInstances(),
	'lang' => fn () => Str::replace(option('adamkiss.seo-lite.default.lang')($this->homePage()), '_', '-'),
	'canonicalFor' => function (string $url) {
		$base = option('adamkiss.seo-lite.canonicalBase');
		if (is_callable($base)) {
			$base = $base($url);
		}

		if ($base === null) {
			$base = $this->url(); // graceful fallback to site url
		}

		if (Str::startsWith($url, $base)) {
			return $url;
		}

		$path = Url::path($url);
		return url($base . '/' . $path);
	}
];
