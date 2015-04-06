<?php namespace NewUp\Tests\Renderer;

use NewUp\Contracts\DataCollectorInterface;
use NewUp\Templates\Renderers\Collectors\FileNameCollector;

class RenderCollectionTest extends RenderTestBase {

    public function testGetCollectorsReturnsArray()
    {
        $this->assertInternalType('array', $this->getRenderer()->getCollectors());
    }

    public function testRendererAcceptsCollectors()
    {
        $r = $this->getRenderer();
        $collector = new FileNameCollector;
        $r->addCollector($collector);
        $this->assertCount(1, $r->getCollectors());
    }

    public function testRendererDataCollectionReturnsArray()
    {
        $r = $this->getRenderer();
        $this->assertInternalType('array', $r->collectData());
    }

    public function testRenderDataCollectionReturnsDataFromCollectors()
    {
        $r = $this->getRenderer();
        $firstCollector = new FileNameCollector;
        $firstCollector->addFileNames(['sample' => 'file']);

        $r->addCollector($firstCollector);
        $r->addCollector(new DummyCollector);

        $collectedData = $r->collectData();
        $this->assertArrayHasKey('dummy', $collectedData);
        $this->assertArrayHasKey('sys_pathNames', $collectedData);
    }

    /**
     * @expectedException ErrorException
     */
    public function testRenderOnlyAcceptsDataCollectors()
    {
        $r = $this->getRenderer();
        $r->addCollector((new \stdClass()));
    }

}

class DummyCollector implements DataCollectorInterface {

    /**
     * Returns an array of data that should be merged with the rendering environment.
     *
     * @return array
     */
    public function collect()
    {
        return ['dummy' => 'data'];
    }


}