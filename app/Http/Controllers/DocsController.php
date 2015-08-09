<?php

namespace Hazzard\Web\Http\Controllers;

use Illuminate\Http\Request;
use Hazzard\Web\Http\Controllers\Controller;
use Hazzard\Web\Docs\RepositoryInterface as Docs;

class DocsController extends Controller
{
	/**
	 * @var \Hazzard\Web\Docs\RepositoryInterface
	 */
	protected $docs;

	/**
	 * @param \Hazzard\Web\Docs\RepositoryInterface $docs
	 */
	public function __construct(Docs $docs)
	{
		$this->docs = $docs;
	}

	/**
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
		$defaultManual  = $this->docs->getDefaultManual();
		$defaultVersion = $this->docs->getDefaultVersion($defaultManual);

		return redirect()->route('docs.show', [$defaultManual, $defaultVersion]);
	}

	/**
	 * @param  string      $manual
	 * @param  string|null $version
	 * @param  string|null $page
	 * @return \Illuminate\Http\Response
	 */
	public function show($manual, $version = null, $page = null)
	{
        if (is_null($version)) {
			$version = $this->docs->getDefaultVersion($manual);

			return redirect()->route('docs.show', [$manual, $version]);
		}

        if (is_null($page)) {
            $page = 'installation';
        }

		$toc            = $this->docs->getToc($manual, $version);
		$content        = $this->docs->get($manual, $version, $page);
		$lastUpdated    = $this->docs->getUpdatedTimestamp($manual, $version, $page);
		$currentManual  = $manual;
		$currentVersion = $version;
		$manuals        = $this->docs->getManuals();
		$versions       = $this->docs->getVersions($manual);

		return view('docs.show', compact(
			'toc',
			'content',
			'lastUpdated',
			'currentManual',
			'currentVersion',
			'manuals',
			'versions',
            'page'
		));
	}
}
