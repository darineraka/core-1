<?php namespace NewUp\Tests\IO;

use NewUp\Templates\Parsers\FileSystemPathNameParser;
use NewUp\Templates\Renderers\TemplateRenderer;

class PathNameParserTest extends \PHPUnit_Framework_TestCase {

    /**
     * @return \NewUp\Contracts\Templates\PathNameParser
     */
    private function getParser()
    {
        $renderer = new TemplateRenderer();
        return new FileSystemPathNameParser($renderer);
    }

    public function testAddingNewCharactersMergesCharacterArray()
    {
        $p = $this->getParser();
        $p->addCharactersToRemove(['\\']);
        $this->assertEquals(['\\'], $p->getCharactersToRemove());
        $p->addCharactersToRemove(['/']);
        $this->assertEquals(['\\', '/'], $p->getCharactersToRemove());
    }

    public function testResettingCharactersClearsCharacterArray()
    {
        $p = $this->getParser();
        $p->addCharactersToRemove(['a', 'b']);
        $p->resetCharactersToRemove();
        $this->assertEmpty($p->getCharactersToRemove());
    }

    public function testParserDoesNotAffectNonSpecialStrings()
    {
        $p = $this->getParser();
        $this->assertEquals('not special', $p->processPath('not special'));
    }

    public function testParserRemovesCharactersToRemove()
    {
        $p = $this->getParser();
        $p->addCharactersToRemove([' ']);
        $this->assertEquals('somestring', $p->processPath('some string'));
    }

    public function testParserRemovesMultipleCharactersToRemove()
    {
        $p = $this->getParser();
        $p->addCharactersToRemove([' ', '/']);
        $this->assertEmpty($p->processPath('  //'));
    }

    public function testEscapingOfCurlyBracketsWorksCorrectly()
    {
        $p = $this->getParser();
        $this->assertEquals('{}', $p->processPath('{{}}'));
    }

    public function testOpeningSquareBracketsAreNotAffectedOutsideOfGroups()
    {
        $p = $this->getParser();
        $this->assertEquals('[[[[', $p->processPath('[[[['));
    }

}