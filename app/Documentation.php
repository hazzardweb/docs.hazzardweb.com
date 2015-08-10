<?php

namespace Hazzard\Web;

use DateTime;
use Parsedown;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Contracts\Cache\Repository as Cache;

class Documentation
{
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
	 * Create a new instance.
	 *
	 * @param \Illuminate\Filesystem\Filesystem $files
	 * @param \Illuminate\Contracts\Cache\Repository $cache
	 */
	public function __construct(Filesystem $files, Cache $cache, Parsedown $parsedown)
	{
        $this->files = $files;
		$this->cache = $cache;
		$this->parsedown = $parsedown;
	}

    /**
     * Get manual's table of contents file.
     *
     * @param  string $version
     * @return string|null
     */
    public function getToc($manual, $version)
    {
        $tocFile = $this->getStoragePath($manual, $version, 'toc.md');

        if ($this->files->exists($tocFile)) {
            return $this->remember("$manual.$version.toc",
                $this->parse($this->files->get($tocFile), $manual.'/'.$version)
            );
        }
    }

    /**
     * Get the given documentation page.
     *
     * @param  string $manual
     * @param  string $version
     * @param  string $page
     * @return string|null
     */
    public function get($manual, $version, $page)
    {
        $pageFile = $this->getStoragePath($manual, $version, $page.'.md');

        if ($this->files->exists($pageFile)) {
            return $this->remember("$manual.$version.$page",
                $this->parse($this->files->get($pageFile), $manual.'/'.$version.'/'.dirname($page))
            );
        }
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
        $page = $this->getStoragePath($manual, $version, $page.'.md');

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
        return $this->getDirectories($this->getStoragePath());
    }


    /**
     * Get all versions for the given manual.
     *
     * @param  string $manual
     * @return array
     */
    public function getVersions($manual)
    {
        $manualDir = $this->getStoragePath($manual);

        $versions = $this->getDirectories($manualDir);

        sort($versions, SORT_NATURAL);

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
		$versions = array_values($this->getVersions($manual));

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

    /**
     * Get an item from the cache, or store the default value forever.
     *
     * @param  string $key
     * @param  mixed  $value
     * @return mixed
     */
    protected function remember($key, $value)
    {
        if (app()->environment('local')) {
            return $value;
        }

        return $this->cache->rememberForever($key, function () use ($value) {
            return $value;
        });
    }

    /**
     * Get the storage path.
     *
     * @return string
     */
    protected function getStoragePath()
    {
        $path = storage_path('docs');

        if (func_num_args() === 0) {
            return $path;
        }

        return $path.'/'.implode('/', func_get_args());
    }
}
