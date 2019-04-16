<?php

namespace yamadote\FileManager;

use yamadote\FileManager\Folder\Folder;

/**
 * Class Item
 * @package yamadote\FileManager
 */
abstract class Item
{
    /**
     * @var string
     */
    protected $path;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var Folder
     */
    protected $parent;

    /**
     * @var bool
     */
    protected $deleted;

    /**
     * Item constructor.
     * @param string $name
     * @param mixed $folder
     */
    public function __construct(string $name, $folder)
    {
        $this->name = $name;
        $this->deleted = false;
        if ($folder instanceof Folder) {
            $this->path = $folder->getPath().$folder->getName().DIRECTORY_SEPARATOR;
            $folder->addToList($this);
            $this->parent = $folder;
        } elseif (\is_string($folder)) {
            $this->path = $folder;
        }
    }

    /**
     * Set delete condition for item
     */
    public function delete(): void
    {
        $this->deleted = true;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return Folder
     */
    public function getParent(): Folder
    {
        return $this->parent;
    }

    /**
     * Update filesystem by item
     */
    abstract public function push(): void;

    /**
     * Update item by filesystem
     */
    abstract public function pull(): void;
}
