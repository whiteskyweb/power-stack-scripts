<?php

declare(strict_types=1);

namespace PowerStack\Monorepo;

use PharIo\Version\Version;
use Symplify\MonorepoBuilder\Release\Contract\ReleaseWorker\ReleaseWorkerInterface;
use Symplify\MonorepoBuilder\Release\Process\ProcessRunner;

final class PushTagReleaseWorker implements ReleaseWorkerInterface
{
    /**
     * @var ProcessRunner
     */
    private $processRunner;

    public function __construct(ProcessRunner $processRunner)
    {
        $this->processRunner = $processRunner;
    }

    public function getPriority(): int
    {
        return 300;
    }

    public function work(Version $version): void
    {
        $this->processRunner->run('git push --tags');
    }

    public function getDescription(Version $version): string
    {
        return sprintf('Push "%s" tag to remote repository', $version->getVersionString());
    }
}
