<?php

use Services\URL;;

require_once __DIR__ . '/../../../config.php';

class URLTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
    }

    public function tearDown()
    {
    }

    /** @test */
    public function lettersAndDigitsRemainTheSame()
    {
        $this->assertEquals(
            'size123',
            URL::linkify('size123')
        );
    }

    /** @test */
    public function capitalLettersAreMadeLower()
    {
        $this->assertEquals(
            'size123',
            URL::linkify('SiZe123')
        );
    }

    /** @test */
    public function nonAlphaNumericAreMadeHyphens()
    {
        $this->assertEquals(
            'growth-of-200',
            URL::linkify('Growth Of £200')
        );
    }

    /** @test */
    public function multipleHyphensAreMadeOne()
    {
        $this->assertEquals(
            'size-matters-evaluating-prosperity-and-growth-workshop-on-may-31st-brussels',
            URL::linkify('Size Matters? Evaluating prosperity and growth – workshop on May 31st – Brussels')
        );
    }
}