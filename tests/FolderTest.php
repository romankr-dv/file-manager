<?php

use PHPUnit\Framework\TestCase;
use yamadote\FileManager\File\File;
use yamadote\FileManager\Folder\Folder;

class FolderTest extends TestCase
{

    private $name;
    private $content;
    private $path;
    private $folder;

    protected function setUp()
    {
        $this->name = 'uniqueFolderForTest';
        $this->content = 'Content';
        $this->path = '.' . DIRECTORY_SEPARATOR . 'folderForTesting' . DIRECTORY_SEPARATOR;
        mkdir($this->path);
        $this->folder = new Folder($this->name, $this->path);
        $this->folder->push();
    }

    protected function tearDown()
    {
        $delTree = function ($dir) use (&$delTree) {
            $files = array_diff(scandir($dir), array('.', '..'));
            foreach ($files as $file) {
                (is_dir($dir . DIRECTORY_SEPARATOR . $file)) ? $delTree($dir . DIRECTORY_SEPARATOR . $file) : unlink($dir . DIRECTORY_SEPARATOR . $file);
            }
            return rmdir($dir);
        };
        $delTree($this->path);
    }

    public function testCanBeCreated(): void
    {
        $this->assertInstanceOf(Folder::class, $this->folder);
        $this->assertFileExists($this->path . $this->name);
        $this->assertEquals($this->name, $this->folder->getName());
        $this->assertEquals($this->path, $this->folder->getPath());
    }

    public function testSubFolder(): void
    {
        $this->assertEquals(null, $this->folder->getChild($this->name));
        $this->assertFileNotExists($this->path.$this->name.DIRECTORY_SEPARATOR.$this->name);
        $folder = new Folder($this->name, $this->folder);
        $this->assertEquals($folder, $this->folder->getChild($this->name));
        $this->folder->push();
        $this->assertFileExists($this->path.$this->name.DIRECTORY_SEPARATOR.$this->name);
        $this->assertEquals($this->folder, $folder->getParent());
        $folder->delete();
        $folder->push();
        $this->assertEquals(null, $this->folder->getChild($this->name));
        $this->assertFileNotExists($this->path.$this->name.DIRECTORY_SEPARATOR.$this->name);
    }

    public function testSubFile(): void
    {
        $this->assertEquals(null, $this->folder->getChild($this->name));
        $this->assertFileNotExists($this->path.$this->name.DIRECTORY_SEPARATOR.$this->name);
        $file = new File($this->name, $this->folder);
        $this->assertEquals($file, $this->folder->getChild($this->name));
        $file->push();
        $this->assertFileExists($this->path.$this->name.DIRECTORY_SEPARATOR.$this->name);
        $this->assertEquals($this->folder, $file->getParent());
        $file->delete();
        $file->push();
        $this->assertEquals(null, $this->folder->getChild($this->name));
        $this->assertFileNotExists($this->path.$this->name.DIRECTORY_SEPARATOR.$this->name);
    }

    public function testCanBeDeleted(): void
    {
        $this->folder->delete();
        $this->folder->push();
        $this->assertFileNotExists($this->path.$this->name);
    }

}