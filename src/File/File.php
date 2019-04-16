<?php

namespace yamadote\FileManager\File;

use yamadote\FileManager\Item;

/**
 * Class File
 * @package yamadote\FileManager\File
 */
class File extends Item implements InterfaceFile
{
    /**
     * @var string
     */
    private $content;

    /**
     * File constructor.
     * @param string $name
     * @param $folder
     * @param string $content
     */
    public function __construct(string $name, $folder, string $content = '')
    {
        parent::__construct($name, $folder);
        $this->content = $content;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getPath().$this->getName();
    }

    /**
     * @param string $content
     * Rewrite content of this file
     */
    public function rewrite(string $content): void
    {
        $this->content = $content;
    }

    /**
     * @return string
     * Read content of this file
     */
    public function read(): string
    {
        return $this->content;
    }

    /**
     * Set delete condition for folder
     */
    public function delete(): void
    {
        parent::delete();
    }

    /**
     * Update filesystem by item
     */
    public function push(): void
    {
        if ($this->deleted) {
            if ($this->parent) {
                $this->parent->removeFromList($this);
                $this->parent = null;
            }
            if (file_exists($this->path.$this->name)) {
                unlink($this->path.$this->name);
            }
            clearstatcache();
            return;
        }
        if (!is_dir($this->getPath())) {
            $this->parent->pushOnlyFolder();
        }
        $handle = fopen($this->getPath().$this->getName(), 'wb');
        fwrite($handle, $this->content);
        fclose($handle);
        clearstatcache();
    }

    /**
     * Update item by filesystem
     */
    public function pull(): void
    {
        $handle = fopen($this->getPath().$this->getName(), 'rb');
        $content = fread($handle, filesize($this->getPath().$this->getName()));
        fclose($handle);
        $this->content = $content;
    }
}
