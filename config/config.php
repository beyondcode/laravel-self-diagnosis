<?php

return [

    /*
     * A list of environment aliases mapped to the actual environment configuration.
     */
    'environment_aliases' => [
        'prod' => 'production',
        'live' => 'production',
    ],

    /*
     * Common checks that will be performed on all environments.
     */
    'checks' => [
        \BeyondCode\SelfDiagnosis\Checks\AppKeyIsSet::class,
        \BeyondCode\SelfDiagnosis\Checks\CorrectPhpVersionIsInstalled::class,
        \BeyondCode\SelfDiagnosis\Checks\DatabaseCanBeAccessed::class,
        \BeyondCode\SelfDiagnosis\Checks\DirectoriesHaveCorrectPermissions::class,
        \BeyondCode\SelfDiagnosis\Checks\EnvFileExists::class,
        \BeyondCode\SelfDiagnosis\Checks\ExampleEnvironmentVariablesAreSet::class,
        \BeyondCode\SelfDiagnosis\Checks\MigrationsAreUpToDate::class,
        \BeyondCode\SelfDiagnosis\Checks\PhpExtensionsAreInstalled::class,
        \BeyondCode\SelfDiagnosis\Checks\StorageDirectoryIsLinked::class,
    ],

    /*
     * Environment specific checks that will only be performed for the corresponding environment.
     */
    'environment_checks' => [
        'development' => [
            \BeyondCode\SelfDiagnosis\Checks\Development\ComposerWithDevDependenciesIsUpToDate::class,
            \BeyondCode\SelfDiagnosis\Checks\Development\ConfigurationIsNotCached::class,
            \BeyondCode\SelfDiagnosis\Checks\Development\RoutesAreNotCached::class,
        ],
        'production' => [
            \BeyondCode\SelfDiagnosis\Checks\Production\ComposerWithoutDevDependenciesIsUpToDate::class,
            \BeyondCode\SelfDiagnosis\Checks\Production\ConfigurationIsCached::class,
            \BeyondCode\SelfDiagnosis\Checks\Production\DebugModeIsNotEnabled::class,
            \BeyondCode\SelfDiagnosis\Checks\Production\RoutesAreCached::class,
            \BeyondCode\SelfDiagnosis\Checks\Production\XDebugIsNotEnabled::class,
        ],
    ],

];
