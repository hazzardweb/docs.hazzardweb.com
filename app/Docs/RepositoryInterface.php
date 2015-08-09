<?php

namespace Hazzard\Web\Docs;

interface RepositoryInterface
{
	/**
	 * Get manual's table of contents file.
	 *
	 * @param  string $manual
	 * @param  string $version
	 * @return string|null
	 */
	public function getToc($manual, $version);

	/**
	 * Get the given documentation page.
	 *
	 * @param  string $manual
	 * @param  string $version
	 * @param  string $page
	 * @return string|null
	 */
	public function get($manual, $version, $page);

	/**
	 * Get the given documentation page modification time.
	 *
	 * @param  string $manual
	 * @param  string $version
	 * @param  string $page
	 * @return string|null
	 */
	public function getUpdatedTimestamp($manual, $version, $page);

	/**
	 * Get all manuals from storage directory.
	 *
	 * @return array
	 */
	public function getManuals();

	/**
	 * Get all versions for the given manual.
	 *
	 * @param  string $manual
	 * @return array
	 */
	public function getVersions($manual);

	/**
	 * Get the default version for the given manual.
	 *
	 * @param  string $manual
	 * @return string|null
	 */
	public function getDefaultVersion($manual);

	/**
	 * Search manual for given string.
	 *
	 * @param  string $manual
	 * @param  string $version
	 * @param  string $needle
	 * @return array
	 */
	public function search($manual, $version, $needle = '');
}
