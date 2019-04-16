<?php

namespace yamadote\FileManager\Folder;

use yamadote\FileManager\Item;

/**
 * Class Folder
 * @package yamadote\FileManager\Folder
 */
class Folder extends Item implements InterfaceFolder
{
    /**
     * @var array
     */
    private $list;

    /**
     * Folder constructor.
     * @param string $name
     * @param $folder
     */
    public function __construct(string $name, $folder)
    {
        $this->list = [];
        parent::__construct($name, $folder);
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return '{ ' .
            $this->path . $this->name . ': ' . implode(' ; ', $this->list) .
        ' }';
    }

    /**
     * @return array
     * Get list of children
     */
    public function getList(): array
    {
        return $this->list;
    }

    /**
     * @param string $name
     * @return bool
     * Check is containing folder child with name $name
     */
    public function contains(string $name): bool
    {
        foreach ($this->list as $item) {
            if ($item->getName() === $name) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param Item $item
     * Just add item to list of children
     */
    public function addToList(Item $item): void
    {
        if ($this->contains($item)) {
            throw new \LogicException('File already exist!');
        }
        $this->list[] = $item;
    }

    /**
     * @param Item $item
     * Remove child from list
     */
    public function removeFromList(Item $item): void
    {
        foreach ($this->list as $key => $eachItem) {
            if ($eachItem->getName() === $item->getName()) {
                array_splice($this->list, $key, 1);
                break;
            }
        }
    }

    /**
     * Set delete condition for folder
     */
    public function delete(): void
    {
        foreach ($this->list as $item) {
            $item->delete();
        }
        parent::delete();
    }

    /**
     * @param string $name
     * @return mixed|null
     * Find child by name
     */
    public function getChild(string $name)
    {
        foreach ($this->list as $item) {
            if ($item->getName() === $name) {
                return $item;
            }
        }
        return null;
    }

    /**
     * Update filesystem by item
     */
    public function push(): void
    {
        if ($this->deleted) {
            foreach ($this->list as $item) {
                $item->push();
            }
            if ($this->parent) {
                $this->parent->removeFromList($this);
                $this->parent = null;
            }
            if (is_dir($this->path.$this->name)) {
                rmdir($this->path.$this->name);
            }
            return;
        }
        $delTree = function ($dir) use (&$delTree) {
            $files = array_diff(scandir($dir), array('.', '..'));
            foreach ($files as $file) {
                (is_dir($dir . DIRECTORY_SEPARATOR . $file))
                ? $delTree($dir . DIRECTORY_SEPARATOR . $file)
                : unlink($dir . DIRECTORY_SEPARATOR . $file);
            }
            return rmdir($dir);
        };
        if (is_dir($this->getPath().$this->getName())) {
            $delTree($this->getPath().$this->getName());
        }
        $this->pushOnlyFolder();
        foreach ($this->list as $item) {
            $item->push();
        }
    }

    /**
     * Update item by filesystem
     */
    public function pull(): void
    {
        foreach ($this->list as $item) {
            $item->delete();
        }
        if (is_dir($this->getPath().$this->getName())) {
            $list = scandir(
                $this->getPath().$this->getName().DIRECTORY_SEPARATOR,
                SCANDIR_SORT_ASCENDING
            );
            $list = array_diff($list, array('.','..'));
            foreach ($list as $item) {
                if (
                    is_dir(
                        $this->getPath().$this->getName().
                        DIRECTORY_SEPARATOR.$item
                    )
                ) {
                    $folder = new Folder($item, $this);
                    $folder->pull();
                } else {
                    new File($item, $this);
                }
            }
        }
    }

    /**
     * Create only folder without children
     */
    public function pushOnlyFolder(): void
    {
        if (!is_dir($this->getPath())) {
            $this->parent->pushOnlyFolder();
        }
        if (
            !mkdir($this->getPath().$this->getName()) &&
            !is_dir($this->getPath().$this->getName())
        ) {
            throw new \LogicException("Can't create new folder!");
        }
    }
}
