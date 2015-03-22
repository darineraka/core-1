<?php

use NewUp\Foundation\Application;
use NewUp\Templates\Renderers\TemplateRenderer;

class RendererTest extends PHPUnit_Framework_TestCase {

    public function getRenderer()
    {
        $renderer = new TemplateRenderer();

        return $renderer;
    }

    public function getRendererWithTestTemplates()
    {
        $r = $this->getRenderer();
        $r->addPath(__DIR__ . '/Templates/');

        return $r;
    }

    public function testSystemCorePathIsAvailableByDefault()
    {
        $r = $this->getRenderer();

        $paths = $r->getPaths();
        $this->assertCount(1, $paths);
        $this->assertStringEndsWith('system/core', $paths[0]);
    }

    public function testAddPathsWork()
    {
        $r = $this->getRenderer();

        $r->addPath(__DIR__ . '/../../templates');
        $this->assertCount(2, $r->getPaths());

        $r->addPath(__DIR__ . '/../../templates/store');
        $this->assertCount(3, $r->getPaths());
    }

    /**
     * @expectedException \NewUp\Templates\Renderers\InvalidPathException
     */
    public function testAddPathsThrowsExceptionWhenAnInvalidPathIsAdded()
    {
        $r = $this->getRenderer();
        $r->addPath('this path does not exist!');
    }

    public function testDefaultEnvironmentVariablesArePresent()
    {
        $r = $this->getRenderer();
        $this->assertArrayHasKey('newup_version', $r->getData());
    }

    public function testSettingEnvironmentDataWorks()
    {
        $r = $this->getRenderer();
        $r->setData('custom', 'value');
        $this->assertArrayHasKey('custom', $r->getData());

        $this->assertEquals('value', $r->getData()['custom']);
    }

    public function testEnvironmentAndSystemDataVariablesAreMerged()
    {
        $r = $this->getRenderer();
        $r->setData('custom', 'value');

        $this->assertArrayHasKey('newup_version', $r->getData());
        $this->assertArrayHasKey('custom', $r->getData());

        $this->assertEquals('value', $r->getData()['custom']);
        $this->assertEquals(Application::VERSION, $r->getData()['newup_version']);
    }

    public function testRenderFileTemplatesWorks()
    {
        $r = $this->getRendererWithTestTemplates();
        $this->assertEquals('Not affected.', $r->render('Test_Simple'));
    }

    public function testDefaultFiltersWork()
    {
        $r = $this->getRendererWithTestTemplates();
        $this->assertEquals('fooBar', $r->render('Test_Filter_Camel'));
        $this->assertEquals('foobar', $r->render('Test_Filter_Lower'));
        $this->assertEquals('cars', $r->render('Test_Filter_Plural'));
        $this->assertEquals('car', $r->render('Test_Filter_Singular'));
        $this->assertEquals('this-is-a-slug', $r->render('Test_Filter_Slug'));
        $this->assertEquals('foo_bar', $r->render('Test_Filter_Snake'));
        $this->assertEquals('FooBar', $r->render('Test_Filter_Studly'));
        $this->assertEquals('FOOBAR', $r->render('Test_Filter_Upper'));
    }

    public function testDataIsPassedToTemplates()
    {
        $r = $this->getRendererWithTestTemplates();
        $r->setData('custom', 'Hello world');
        $r->setData('another', '!');
        $this->assertEquals('Hello world', $r->render('Test_Data'));
        $this->assertEquals('Hello world!', $r->render('Test_Data_Multiple'));
        $this->assertEquals('Hello world'.Application::VERSION, $r->render('Test_Data_Merged'));
    }

    /**
     * @expectedException \NewUp\Templates\Renderers\InvalidTemplateException
     */
    public function testIncorrectTemplateThrowsException()
    {
        $r = $this->getRendererWithTestTemplates();
        $r->render('This does not exist!');
    }

    /**
     * @expectedException \NewUp\Templates\Renderers\InvalidSyntaxException
     */
    public function testIncorrectSyntaxThrowsException()
    {
        $r = $this->getRendererWithTestTemplates();
        $r->render('Test_Syntax_Error');
    }

    public function testRenderStringAllowsRegularStrings()
    {
        $r = $this->getRenderer();
        $this->assertEquals('Not affected.', $r->renderString('Not affected.'));
    }

    public function testRenderStringWorksWithData()
    {
        $r = $this->getRenderer();
        $r->setData('custom', 'value');
        $this->assertEquals('value', $r->renderString('{{ custom }}'));
    }

    /**
     * @expectedException \NewUp\Templates\Renderers\InvalidSyntaxException
     */
    public function testRenderStringThrowSyntaxException()
    {
        $r = $this->getRenderer();
        $r->renderString('{{');
    }

}