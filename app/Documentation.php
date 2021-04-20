<?php

namespace App;

use Closure;
use DateTime;
use Parsedown;
use Illuminate\Support\Arr;
use Symfony\Component\Yaml\Yaml;
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
     * @var array
     */
    protected $docs;

    /**
     * Create a new docs repository instance.
     *
     * @param Filesystem $files
     * @param Cache      $cache
     * @param Parsedown  $parsedown
     */
    public function __construct(Filesystem $files, Cache $cache, Parsedown $parsedown)
    {
        $this->files = $files;
        $this->cache = $cache;
        $this->parsedown = $parsedown;
    }

    /**
     * Get all the available documentations from cache or file.
     *
     * @return array
     */
    public function all()
    {
        if (! $this->docs) {
            $this->docs = $this->remember('docs.yml', function () {
                return $this->getDocs();
            });
        }

        return $this->docs;
    }

    /**
     * Get all the available documentations from file.
     *
     * @return array
     */
    public function getDocs()
    {
        $file = base_path('docs.yml');

        if ($this->files->exists($file)) {
            $docs = Yaml::parse($this->files->get($file));

            foreach ($docs as $id => &$doc) {
                $doc['id'] = $id;
            }

            return $docs;
        }

        return [];
    }

    /**
     * Get the documentation table of contents.
     *
     * @param  string $doc
     * @param  string $version
     * @return string|null
     */
    public function getToc($doc, $version)
    {
        $prefix = "$doc/$version";
        $tocFile = $this->getStoragePath($doc, $version, 'toc.md');

        if ($this->files->exists($tocFile)) {
            return $this->remember("$doc.$version.toc", function () use ($tocFile, $prefix) {
                return $this->parse($this->files->get($tocFile), $prefix);
            });
        }
    }

    /**
     * Get the documentation page content.
     *
     * @param  string $doc
     * @param  string $version
     * @param  string $page
     * @return string|null
     */
    public function getContent($doc, $version, $page)
    {
        $prefix = "$doc/$version/".dirname($page);
        $pageFile = $this->getStoragePath($doc, $version, $page.'.md');

        if ($this->files->exists($pageFile)) {
            return $this->remember("$doc.$version.$page", function () use ($pageFile, $prefix) {
                return $this->parse($this->files->get($pageFile), $prefix);
            });
        }
    }

    /**
     * Get the documentation versions.
     *
     * @param  string $doc
     * @return array
     */
    public function getVersions($doc)
    {
        if ($versions = Arr::get($this->all(), "$doc.versions")) {
            return $versions;
        }

        $versions = $this->getDirectories(
            $this->getStoragePath($doc)
        );

        sort($versions, SORT_NATURAL);

        return array_reverse($versions);
    }

    /**
     * Get the default documentation version.
     *
     * @param  string $doc
     * @return string|null
     */
    public function getDefaultVersion($doc)
    {
        if ($version = Arr::get($this->all(), "$doc.default_version")) {
            return $version;
        }

        $versions = array_values($this->getVersions($doc));

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
     * Get the default documentation page.
     *
     * @param  string $doc
     * @param  string|null $default
     * @return string|null
     */
    public function getDefaultPage($doc, $default = null)
    {
        return Arr::get($this->all(), "$doc.default_page", $default);
    }

    /**
     * Return an array of folders within the supplied path.
     *
     * @param  string $path
     * @return array
     */
    public function getDirectories($path)
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

    /**
     * Get an item from the cache, or store the default value forever.
     *
     * @param  string   $key
     * @param  \Closure $callback
     * @return mixed
     */
    protected function remember($key, Closure $callback)
    {
        if (app()->environment('local')) {
            return $callback();
        }

        return $this->cache->rememberForever($key, $callback);
    }

    /**
     * Remove the documentation cache.
     *
     * @param  string $doc
     * @param  string|null $version
     * @return void
     */
    public function clearCache($doc, $version = null)
    {
        $this->docs = null;
        $this->cache->forget('docs.yml');

        if ($version) {
            $versions = [$version];
        } else {
            $versions = array_keys($this->getVersions($doc));
        }

        foreach ($versions as $version) {
            $path = $this->getStoragePath($doc, $version);

            $pages = $this->files->files($path);

            foreach ($pages as $page) {
                $page = substr(basename($page), 0, -3);

                $this->cache->forget("$doc.$version.$page");
            }
        }
    }
}
