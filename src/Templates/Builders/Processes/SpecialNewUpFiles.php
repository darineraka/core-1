<?php namespace NewUp\Templates\Builders\Processes;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Symfony\Component\Console\Helper\ProgressBar;

class SpecialNewUpFiles {

    protected $fileSystem;

    protected $output;

    const DIRECTORY_PRESERVE_FILE = 'newup.keep';

    public function __construct(Filesystem $fileSystem)
    {
        $this->fileSystem = $fileSystem;
    }

    public function setOutput($output)
    {
        $this->output = $output;
    }

    public function process($baseDirectory)
    {
        $files = $this->fileSystem->allFiles($baseDirectory);

        $progress = new ProgressBar($this->output, count($files));
        $this->output->writeln('Processing special file names...');
        foreach ($files as $file)
        {
            $progress->advance();
            if ($file->getFileName() == self::DIRECTORY_PRESERVE_FILE)
            {
                $this->fileSystem->delete($file->getRealPath());
            }
        }
        $this->output->writeln('');
        $this->output->writeln('Processing special file names... done.');
        $progress->finish();

    }

}