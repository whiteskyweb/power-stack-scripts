<?php

declare(strict_types=1);

namespace PowerStack\Monorepo;

use PharIo\Version\Version;

final class SetNextMutualDependenciesReleaseWorker extends AbstractMutualDependencyReleaseWorker
{
    public function getPriority(): int
    {
        return 200;
    }

    public function work(Version $version): void
    {
        $versionInString = $this->versionUtils->getRequiredNextFormat($version);

        $this->dependencyUpdater->updateFileInfosWithPackagesAndVersion(
            $this->composerJsonProvider->getPackagesFileInfos(),
            $this->packageNamesProvider->provide(),
            $versionInString
        );
    }

    public function getDescription(Version $version): string
    {
        $versionInString = $this->versionUtils->getRequiredNextFormat($version);

        return sprintf('Set packages mutual dependencies to "%s" (alias of dev version)', $versionInString);
    }
}
