<?php

namespace BeyondCode\SelfDiagnosis\Checks;

use Illuminate\Support\Collection;

class PhpExtensionsAreInstalled implements Check
{
    /** @var Collection */
    private $extensions;

    /**
     * The name of the check.
     *
     * @return string
     */
    public function name(): string
    {
        return 'The required PHP extensions are installed';
    }

    /**
     * The error message to display in case the check does not pass.
     *
     * @return string
     */
    public function message() : string
    {
        return 'The following extensions are missing: '.PHP_EOL.$this->extensions->implode(PHP_EOL);
    }

    /**
     * Perform the actual verification of this check.
     *
     * @return bool
     */
    public function check(): bool
    {
        $this->extensions = Collection::make([
            'openssl',
            'PDO',
            'mbstring',
            'tokenizer',
            'xml',
            'ctype',
            'json'
        ]);

        $this->extensions = $this->extensions->reject(function ($ext) {
            return extension_loaded($ext);
        });

        return $this->extensions->isEmpty();
    }
}