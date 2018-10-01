<?php

namespace BeyondCode\SelfDiagnosis\Tests;

use BeyondCode\SelfDiagnosis\SystemFunctions;
use PHPUnit\Framework\TestCase;

class SystemFunctionsTest extends TestCase
{
    /**
     * @test
     */
    public function it_find_an_available_function()
    {
        $systemFunctions = new SystemFunctions();
        $this->assertFalse($systemFunctions->isFunctionAvailable('print'));
    }

    /**
     * @test
     */
    public function it_find_an_unavailable_function()
    {
        $systemFunctions = new SystemFunctions();
        $this->assertFalse($systemFunctions->isFunctionAvailable('function_that_doesnt_exist'));
    }
}
