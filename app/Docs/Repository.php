<?php

namespace Hazzard\Web\Docs;

use DateTime;
use Parsedown;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Contracts\Cache\Repository as Cache;

abstract class Repository implements RepositoryInterface
{
	/**
	 * @var array
	 */
	protected $config;

	/**
	 * @var \Illuminate\Filesystem\Filesystem
	 */
	protected $files;

	/**
	 * @var \Illuminate\Contracts\Cache\Repository
	 */
	protected $cache;

	/**
	 * @var \Parsedown
	 */
	protected $parsedown;

	/**
	 * @var string
	 */
	protected $storagePath;

	/**
	 * Create a new instance.
	 *
	 * @param array $config
	 * @param \Illuminate\Filesystem\Filesystem $files
	 * @param \Illuminate\Contracts\Cache\Repository $cache
	 */
	public function __construct($config, Filesystem $files, Cache $cache, Parsedown $parsedown)
	{
		$this->files = $files;
		$this->cache = $cache;
		$this->config = $config;
		$this->parsedown = $parsedown;
		$this->storagePath = $config['storage_path'];
	}

    /**
     * Gets the given documentation page modification time.
     *
     * @param  string $manual
     * @param  string $version
     * @param  string $page
     * @return string|null
     */
    public function getUpdatedTimestamp($manual, $version, $page)
    {
        $page = $this->storagePath.'/'.$manual.'/'.$version.'/'.$page.'.md';

        if ($this->files->exists($page)) {
            $timestamp = DateTime::createFromFormat('U', filemtime($page));

            return $timestamp->format('l, F d, Y');
        }
    }

    /**
     * Get all manuals from documentation directory.
     *
     * @return array
     */
    public function getManuals()
    {
        return $this->getDirectories($this->storagePath);
    }


    /**
     * Get all versions for the given manual.
     *
     * @param  string $manual
     * @return array
     */
    public function getVersions($manual)
    {
        $manualDir = $this->storagePath.'/'.$manual;

        $versions = $this->getDirectories($manualDir);

        return array_reverse($versions);
    }

	/**
	 * Get the default version for the given manual.
	 *
	 * @param  string $manual
	 * @return string|null
	 */
	public function getDefaultVersion($manual)
	{
		$versions = $this->getVersions($manual);

        if (count($versions) === 1) {
            return $versions[0];
        }

        if (count($versions) > 1) {
            if ($versions[0] === 'master') {
                return $versions[1];
            }

            return $versions[0];
        }
	}


	/**
	 * Return an array of folders within the supplied path.
	 *
	 * @param  string $path
	 * @return array
	 */
	protected function getDirectories($path)
	{
		if (! $this->files->exists($path)) {
			return [];
		}

		$folders = [];
		$directories = $this->files->directories($path);

		foreach ($directories as $dir) {
			$dir       = str_replace('\\', '/', $dir);
			$folder    = explode('/', $dir);
			$folders[] = end($folder);
		}

		return $folders;
	}

	/**
	 * Return the first line of the supplied page. This will (or rather should)
	 * always be an <h1> tag.
	 *
	 * @param  string $page
	 * @return string
	 */
	protected function getPageTitle($page)
	{
		$file  = fopen($page, 'r');
		$title = fgets($file);

		fclose($file);

		return $title;
	}

	/**
	 * Returns the cached content if NOT running locally.
	 *
	 * @param  string $key
	 * @param  mixed  $value
	 * @param  int    $minutes
	 * @return mixed
	 */
	protected function cached($key, $value, $minutes = 5)
	{
		if (app()->environment('local')) {
			return $value;
		}

		return $this->cache->remember($key, $minutes, function () use ($value) {
			return $value;
		});
	}

	/**
	 * Convert text from Markdown to HTML.
	 *
	 * @param  string $text
	 * @return string
	 */
	protected function parse($text, $pathPrefix = '')
	{
		$basePath = url('/' . ltrim($pathPrefix, '/'));
		$rendered = $this->parsedown->text($text);

		// Replace absolute relative paths (paths that start with / but not //).
		$rendered = preg_replace('/href=\"(\/[^\/].+?).md(#?.*?)\"/', "href=\"$basePath$1$2\"", $rendered);

		// Replace relative paths (paths that don't start with / or http://, https://, //, etc).
		$rendered = preg_replace('/href=\"(?!.*?\/\/)(.+?).md(#?.*?)\"/', "href=\"$basePath/$1$2\"", $rendered);

		return $rendered;
	}
}
