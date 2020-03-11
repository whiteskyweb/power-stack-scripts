<?php

declare(strict_types=1);

namespace PowerStack\Monorepo;

use PharIo\Version\Version;
use Symplify\MonorepoBuilder\Release\Contract\ReleaseWorker\ReleaseWorkerInterface;
use Symplify\MonorepoBuilder\Release\Process\ProcessRunner;
use Symplify\MonorepoBuilder\Utils\VersionUtils;

final class PushNextDevReleaseWorker implements ReleaseWorkerInterface
{
    /**
     * @var ProcessRunner
     */
    private $processRunner;

    /**
     * @var VersionUtils
     */
    private $versionUtils;

    public function __construct(ProcessRunner $processRunner, VersionUtils $versionUtils)
    {
        $this->processRunner = $processRunner;
        $this->versionUtils = $versionUtils;
    }

    public function getPriority(): int
    {
        return 50;
    }

    public function work(Version $version): void
    {
        $versionInString = $this->getVersionDev($version);

        $this->processRunner->run(
            sprintf('git add . && git commit -m "open %s" && git push origin master', $versionInString)
        );
    }

    public function getDescription(Version $version): string
    {
        $versionInString = $this->getVersionDev($version);

        return sprintf('Push "%s" open to remote repository', $versionInString);
    }

    private function getVersionDev(Version $version): string
    {
        return $this->versionUtils->getNextAliasFormat($version);
    }
}
