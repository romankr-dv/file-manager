<?php

namespace yamadote\FileManager\Folder;

/**
 * Interface InterfaceFolder
 * @package yamadote\FileManager\Folder
 */
interface InterfaceFolder
{
    /**
     * InterfaceFolder constructor.
     * @param string $name
     * @param $folder
     */
    public function __construct(string $name, $folder);

    /**
     * Set delete condition for folder
     */
    public function delete(): void;

    /**
     * @return array
     * Get list of children
     */
    public function getList(): array;

    /**
     * @param string $name
     * @return mixed|null
     * Find child by name
     */
    public function getChild(string $fullName);

    /**
     * @param string $name
     * @return bool
     * Check is containing folder child with name $name
     */
    public function contains(string $fullName): bool;

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return string
     */
    public function getPath(): string;

    /**
     * Update filesystem by item
     */
    public function push(): void;

    /**
     * Update item by filesystem
     */
    public function pull(): void;
}
