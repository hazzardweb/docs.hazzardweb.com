<?php

namespace App\Console\Commands;

use App\Documentation;
use Cz\Git\GitRepository;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class UpdateDocs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'docs:update {doc?} {version?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update docs from GitHub.';

    /**
     * Execute the console command.
     *
     * @param  \App\Documentation $docs
     * @param  \PHPGit\Git $git
     * @param  Illuminate\Filesystem\Filesystem $files
     * @return mixed
     */
    public function handle(Documentation $docs, Filesystem $files)
    {
        $this->docs = $docs;
        $this->files = $files;

        $doc = $this->argument('doc');
        $version = $this->argument('version');

        if ($doc) {
            $docs = [$doc];
        } else {
            $docs = array_keys($this->docs->getDocs());
        }

        foreach ($docs as $doc) {
            $this->updateDoc($doc, $version);
        }

        $this->info('Docs updated!');
    }

    /**
     * Update documentation.
     *
     * @param  string $doc
     * @param  string $version
     * @return void
     */
    protected function updateDoc($doc, $version = null)
    {
        $path = config('docs.path');

        if (! $data = Arr::get($this->docs->getDocs(), $doc)) {
            return;
        }

        if (! $this->files->exists("$path/$doc")) {
            GitRepository::cloneRepository($data['repository'], "$path/$doc");
        }

        $repo = new GitRepository("$path/$doc");

        $repo->checkout('master');
        $repo->pull('origin');

        if ($version) {
            $versions = [$version];
        } else {
            $versions = $this->getVersions($repo->getBranches());
        }

        foreach ($versions as $version) {
            $repo->checkout($version);

            $repo->pull('origin', [$version]);

            $repo->checkout($version);

            $storagePath = storage_path("docs/$doc/$version");

            $this->files->copyDirectory("$path/$doc", $storagePath);

            $this->docs->clearCache($doc, $version);
        }
    }

    /**
     * Get documentation versions from the repository.
     *
     * @param  array $branches
     * @return array
     */
    protected function getVersions(array $branches)
    {
        $versions = [];

        foreach ($branches as $branch) {
            preg_match('/origin\/(.*)/', $branch, $matches);

            if (isset($matches[1]) && ! Str::contains($matches[1], 'HEAD ->')) {
                $versions[] = $matches[1];
            }
        }

        return $versions;
    }
}
