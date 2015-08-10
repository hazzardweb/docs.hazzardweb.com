<?php

namespace Hazzard\Web\Console\Commands;

use PHPGit\Git;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class UpdateDocs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'docs:update {manual?} {version?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update manuals from GitHub.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Git $git, Filesystem $files)
    {
        $this->git = $git;
        $this->files = $files;

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $manual = $this->argument('manual');
        $version = $this->argument('version');

        if ($manual) {
            $manuals = [$manual];
        } else {
            $manuals = $this->getDirectories(config('docs.path'));
        }

        foreach ($manuals as $manual) {
            $this->updateManual($manual, $version);
        }

        $this->info('Docs updated!');

        $this->call('cache:clear');
    }

    /**
     * Update manual.
     *
     * @param  string $manual
     * @param  string $version
     * @return void
     */
    protected function updateManual($manual, $version = null)
    {
        $path = config('docs.path');

        $this->git->setRepository($path.'/'.$manual);

        if ($version) {
            $this->git->pull('origin', $version);
            $versions = [$version];
        } else {
            $this->git->pull('origin');
            $versions = $this->getVersions();
        }

        foreach ($versions as $version) {
            $storagePath = storage_path("docs/$manual/$version");

            $this->files->copyDirectory($path.'/'.$manual, $storagePath);
        }
    }

    /**
     * Get versions.
     *
     * @return array
     */
    protected function getVersions()
    {
        $versions = [];

        $branches = $this->git->branch(['all' => true]);

        foreach ($branches as $branch) {
            preg_match('/origin\/(.*)/', $branch['name'], $matches);

            if (isset($matches[1]) && $matches[1] !== 'HEAD') {
                $versions[] = $matches[1];
            }
        }

        return $versions;
    }

    /**
     * Get directories.
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
}
