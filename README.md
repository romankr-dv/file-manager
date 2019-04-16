# File Manager
Library for working with local files and folders.<br> Repository for homework on GeekHub Advanced PHP.

## How to use


### Create or open folder

Create or open folder in $path folder:
```php
$path = './';
$folder = new Folder('folder', $path);
$folder->push();
```

Create or open folder in object folder:
```php
$folder = new Folder('folder', $folder);
$folder->push();
```

### Create or open file

Create or open file in $path folder:
```php
$path = './';
$file = new File('file.txt', $path[, $content]);
$file->push();
```

Create or open file in object folder:
```php
$file = new File('file.txt', $folder[, $content]);
$file->push();
```

### Read or write file

Read file:
```php
$path = './';
$file = new File('file.txt', $path);
$file->pull();
echo $file->read();
```

Write file:
```php
$path = './';
$file = new File('file.txt', $path);
$file->rewrite($content);
$file->push();
```

### Navigate folder

Get child:
```php
$path = './';
$folder = new Folder('folder', $path);
$folder->pull();
if($folder->contains('file.txt')) {
  $folder->getChild('file.txt');
}
```

Get list of children:
```php
$path = './';
$folder = new Folder('folder', $path);
$folder->pull();
$folder->getList();
```

### Delete folder or file

Delete folder:
```php
$path = './';
$folder = new Folder('folder', $path);
$folder->delete();
$folder->push();
```

Delete file:
```php
$path = './';
$folder = new Folder('file.txt', $path);
$folder->delete();
$folder->push();
```















