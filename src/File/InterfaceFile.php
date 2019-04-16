<?php

namespace yamadote\FileManager\File;

/**
 * Interface InterfaceFile
 * @package yamadote\FileManager\File
 */
interface InterfaceFile
{
    /**
     * InterfaceFile constructor.
     * @param string $name
     * @param $folder
     * @param string $content
     */
    public function __construct(string $name, $folder, string $content = '');

    /**
     * @return string
     * Read content of this file
     */
    public function read(): string;

    /**
     * @param string $content
     * Rewrite content of this file
     */
    public function rewrite(string $content): void;

    /**
     * Set delete condition for folder
     */
    public function delete(): void;

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
