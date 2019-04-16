<?php

require_once __DIR__ . '/vendor/autoload.php';

use yamadote\FileManager\File\File;
use yamadote\FileManager\Folder\Folder;

$folder = new Folder('folder', './');
$file = new File('test.txt', $folder, 'Content');
$file->push();
