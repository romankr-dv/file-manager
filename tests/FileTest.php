<?php

use PHPUnit\Framework\TestCase;
use yamadote\FileManager\File\File;

class FileTest extends TestCase
{

    private $name;
    private $content;
    private $path;
    private $file;

    protected function setUp()
    {
        $this->name = 'uniqueFileForTest.type';
        $this->content = 'Content';
        $this->path = '.' . DIRECTORY_SEPARATOR . 'folderForTesting' . DIRECTORY_SEPARATOR;
        mkdir($this->path);
        $this->file = new File($this->name, $this->path, $this->content);
        $this->file->push();
    }

    protected function tearDown()
    {
        $delTree = function ($dir) use (&$delTree) {
            $files = array_diff(scandir($dir), array('.', '..'));
            foreach ($files as $file) {
                (is_dir("$dir/$file")) ? $delTree("$dir/$file") : unlink("$dir/$file");
            }
            return rmdir($dir);
        };
        $delTree($this->path);
    }

    public function testCanBeCreated(): void
    {
        $this->assertInstanceOf(File::class, $this->file);
        $this->assertFileExists($this->path . $this->name);
        $this->assertEquals($this->name, $this->file->getName());
        $this->assertEquals($this->path, $this->file->getPath());
    }

    public function testCanBeRead(): void
    {
        $this->assertEquals($this->content, $this->file->read());
    }

    public function testCanBeWritten(): void
    {
        $content = 'Rewritten content';
        $this->file->rewrite($content);
        $this->assertEquals($content, $this->file->read());
    }

    public function testCanBeDeleted(): void
    {
        $this->file->delete();
        $this->file->push();
        $this->assertFileNotExists($this->path.$this->name);
    }

}