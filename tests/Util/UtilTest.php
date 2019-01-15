<?php

namespace App\Tests\Util;

use App\Rst\RstParser;
use PHPUnit\Framework\TestCase;

class UtilTest extends TestCase
{
    /**
     * @test
     *
     * @dataProvider cleanProvider
     */
    public function clean(string $expected, string $string)
    {
        $this->assertSame($expected, RstParser::clean($string));
    }

    public function cleanProvider()
    {
        return [
            [
                '.. code-block:: php',
                '.. code-block:: php',
            ],
            [
                '.. code-block:: php',
                '  .. code-block:: php  ',
            ],
            [
                '',
                '\r',
            ],
            [
                '',
                '\n',
            ],
        ];
    }

    /**
     * @test
     *
     * @dataProvider isDirectiveProvider
     */
    public function isDirective(bool $expected, string $string)
    {
        $this->assertSame($expected, RstParser::isDirective($string));
    }

    public function isDirectiveProvider()
    {
        return [
            [true, 'the following code is php::'],
            [true, '.. code-block:: php'],
            [true, ' .. code-block:: php'],
            [false, 'foo'],
        ];
    }

    /**
     * @test
     *
     * @dataProvider directiveIsProvider
     */
    public function directiveIs(bool $expected, string $string, string $directive)
    {
        $this->assertSame($expected, RstParser::directiveIs($string, $directive));
    }

    public function directiveIsProvider()
    {
        return [
            [false, '.. note::', RstParser::DIRECTIVE_CODE_BLOCK],
            [true, '.. note::', RstParser::DIRECTIVE_NOTE],
            [true, 'the following code is php::', RstParser::DIRECTIVE_CODE_BLOCK],
            [true, '.. code-block:: php', RstParser::DIRECTIVE_CODE_BLOCK],
            [true, ' .. code-block:: php', RstParser::DIRECTIVE_CODE_BLOCK],
            [false, 'foo', RstParser::DIRECTIVE_CODE_BLOCK],
        ];
    }

    /**
     * @test
     *
     * @dataProvider codeBlockDirectiveIsTypeOfProvider
     */
    public function codeBlockDirectiveIsTypeOf(bool $expected, string $string, string $type)
    {
        $this->assertSame($expected, RstParser::codeBlockDirectiveIsTypeOf($string, $type));
    }

    public function codeBlockDirectiveIsTypeOfProvider()
    {
        return [
            [false, '.. note::', RstParser::CODE_BLOCK_PHP],
            [true, 'the following code is php::', RstParser::CODE_BLOCK_PHP],
            [true, '.. code-block:: php', RstParser::CODE_BLOCK_PHP],
            [true, ' .. code-block:: php', RstParser::CODE_BLOCK_PHP],
            [false, 'foo', RstParser::CODE_BLOCK_PHP],
        ];
    }

    /**
     * @test
     *
     * @dataProvider isBlankLineProvider
     */
    public function isBlankLine(bool $expected, string $string)
    {
        $this->assertSame($expected, RstParser::isBlankLine($string));
    }

    public function isBlankLineProvider()
    {
        return [
            [true, '\r\n'],
            [true, ''],
            [true, ' '],
            [false, 'foo'],
        ];
    }
}
