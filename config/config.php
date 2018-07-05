<?php

return [

    /*
     * List of all the environment names that are considered as "production".
     */
    'productionEnvironments' => [
        'prod',
        'production',
    ],

    /*
     * Common checks that will be performed on all environments.
     */
	'checks' => [
		\BeyondCode\SelfDiagnosis\Checks\AppKeyIsSet::class,
        \BeyondCode\SelfDiagnosis\Checks\ComposerIsUpToDate::class,
        \BeyondCode\SelfDiagnosis\Checks\CorrectPhpVersionIsInstalled::class,
        \BeyondCode\SelfDiagnosis\Checks\DatabaseCanBeAccessed::class,
        \BeyondCode\SelfDiagnosis\Checks\MigrationsAreUpToDate::class,
        \BeyondCode\SelfDiagnosis\Checks\PhpExtensionsAreInstalled::class,
        \BeyondCode\SelfDiagnosis\Checks\EnvFileExists::class,
        \BeyondCode\SelfDiagnosis\Checks\ExampleEnvironmentVariablesAreSet::class,
        \BeyondCode\SelfDiagnosis\Checks\DirectoriesHaveCorrectPermissions::class,
        \BeyondCode\SelfDiagnosis\Checks\StorageDirectoryIsLinked::class,
	],

    /*
     * Production environment specific checks.
     */
    'production' => [
        \BeyondCode\SelfDiagnosis\Checks\Production\ConfigurationIsCached::class,
        \BeyondCode\SelfDiagnosis\Checks\Production\RoutesAreCached::class,
        \BeyondCode\SelfDiagnosis\Checks\Production\XDebugIsNotEnabled::class,
        \BeyondCode\SelfDiagnosis\Checks\Production\DebugModeIsNotEnabled::class,
    ],

    /*
     * Development environment specific checks.
     */
    'development' => [
        \BeyondCode\SelfDiagnosis\Checks\Development\ConfigurationIsNotCached::class,
        \BeyondCode\SelfDiagnosis\Checks\Development\RoutesAreNotCached::class,
    ],

];
