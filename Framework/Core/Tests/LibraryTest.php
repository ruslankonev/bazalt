<?php

use Framework\Core\Library;

class LibraryTest extends Tests\BaseCase
{
    protected $namespace;

    public function setUp()
    {
        $this->namespace = new Library('Framework.System');
    }

    public function tearDown()
    {
        unset($this->namespace);
    }

    public function testGetLibrary()
    {
        $lib = new Library('core');
        $this->assertEquals($lib->getPath(), CORE_DIR . PATH_SEP);
    }

    /**
     * @expectedException Framework\Core\Exception\InvalidNamespace
     */
    public function testLibraryParam1Exception()
    {
        new Library('-');
    }

    public function testGetByFileName()
    {
        $this->assertEquals($this->namespace->getPath(), FRAMEWORK_DIR . PATH_SEP . 'System');

        $namespace = new Library('Unknown.namespace');
        $this->assertEquals($namespace->getPath(), null);
    }

    public function testGetAutoloadPrefix()
    {
        $this->assertEquals($this->namespace->getAutoloadPrefix(), 'System');
    }

    public function testIsValid()
    {
        $this->assertTrue(Library::isValid('Framework.System'));

        $this->assertFalse(Library::isValid('Framework System'));
    }
}