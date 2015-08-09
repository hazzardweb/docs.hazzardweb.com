<?php

namespace Hazzard\Web\Http\Controllers;

use Hazzard\Web\Docs\RepositoryInterface;

class DocsController extends Controller
{
	/**
	 * @var \Hazzard\Web\Docs\RepositoryInterface
	 */
	protected $docs;

	/**
     * Create a new controller instance.
     *
	 * @param \Hazzard\Web\Docs\RepositoryInterface $docs
	 */
	public function __construct(RepositoryInterface $docs)
	{
		$this->docs = $docs;
	}

	/**
     * Show all manuals.
     *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
        return view('docs.manuals', ['manuals' => $this->docs->getManuals()]);
	}

	/**
     * Show a documentation page.
     *
	 * @param  string      $manual
	 * @param  string|null $version
	 * @param  string|null $page
	 * @return \Illuminate\Http\Response
	 */
	public function show($manual, $version = null, $page = null)
	{
        if (is_null($version)) {
			$version = $this->docs->getDefaultVersion($manual);

            if (is_null($version)) {
                abort(404);
            }

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

        if (is_null($content)) {
            abort(404);
        }

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
