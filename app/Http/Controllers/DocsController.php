<?php

namespace HazzardWeb\Http\Controllers;

use Illuminate\Http\Request;
use HazzardWeb\Docs\DocsRepositoryContract;
use HazzardWeb\Http\Controllers\Controller;

class DocsController extends Controller
{
	/**
	 * @var \HazzardWeb\Docs\DocsRepositoryContract
	 */
	protected $docs;

	/**
	 * @param \HazzardWeb\Docs\DocsRepositoryContract $docs
	 */
	public function __construct(DocsRepositoryContract $docs)
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

		$toc            = $this->docs->getToc($manual, $version);
		$content        = $this->docs->get($manual, $version, $page ?: 'introduction');
		$lastUpdated    = $this->docs->getUpdatedTimestamp($manual, $version, $page ?: 'introduction');
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
			'versions'
		));
	}
}
