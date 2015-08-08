<?php

namespace Hazzard\Web\Docs;

use Markdown;

class FlatDocsRepository extends DocsRepository
{
	/**
	 * Get manual's table of contents file, if it exists.
	 *
	 * @param  string $version
	 * @return string
	 */
	public function getToc($manual, $version)
	{
		$tocFile = $this->storagePath.'/'.$manual.'/'.$version.'/toc.md';

		if ($this->files->exists($tocFile)) {
			return $this->cached("$manual.$version.toc",
				$this->parse($this->files->get($tocFile), $manual.'/'.$version)
			);
		}

		return null;
	}

	/**
	 * Get the given documentation page.
	 *
	 * @param  string $manual
	 * @param  string $version
	 * @param  string $page
	 * @return string
	 */
	public function get($manual, $version, $page)
	{
		$pageFile = $this->storagePath.'/'.$manual.'/'.$version.'/'.$page.'.md';

		if ($this->files->exists($pageFile)) {
			return $this->cached("$manual.$version.$pageFile",
				$this->parse($this->files->get($pageFile), $manual.'/'.$version.'/'.dirname($page))
			);
		}

		abort(404);
	}

	/**
	 * Search manual for given string.
	 *
	 * @param  string $manual
	 * @param  string $version
	 * @param  string $needle
	 * @return array
	 */
	public function search($manual, $version, $needle = '')
	{
		$results = [];
		$directory = $this->storagePath.'/'.$manual.'/'.$version;
		$files = preg_grep('/toc\.md$/', $this->files->allFiles($directory), PREG_GREP_INVERT);

		if (! empty($needle)) {
			foreach ($files as $file) {
				$haystack = file_get_contents($file);

				if (strpos(strtolower($haystack), strtolower($needle)) !== false) {
					$results[] = [
						'title' => $this->getPageTitle((string)$file),
						'url' => str_replace([$this->storagePath, '.md'], '', (string) $file),
					];
				}
			}
		}

		return $results;
	}
}
