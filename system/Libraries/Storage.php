<?php

/**
 * @package    TT
 * @author  Samir Rustamov <rustemovv96@gmail.com>
 * @link https://github.com/srustamov/TT
 * @subpackage    Library
 * @category    Storage
 */

namespace System\Libraries;

use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
use System\Facades\File;
use System\Engine\Http\UploadedFile;
use Exception;

class Storage
{
    protected $path = 'storage/public';


    public function put(String $path, $content)
    {
        if (is_string($content)) {
            return file_put_contents($this->fixPath($path), $content);
        } elseif ($content instanceof UploadedFile) {
            return $content->move($this->fixPath($path));
        } elseif (is_file($content)) {
            return @move_uploaded_file($this->fixPath($path), $content);
        } else {
            throw new Exception("File write content type wrong!");
        }
    }

    public function prepend(String $file, $content)
    {
        return File::prepend($this->fixPath($file), $content);
    }

    public function get($file, callable $callback = null)
    {
        if ($this->exists($file)) {
            if (!is_null($callback)) {
                return call_user_func($callback, file_get_contents($this->fixPath($file)));
            } else {
                return file_get_contents($this->fixPath($file));
            }
        }
        return false;
    }

    public function exists($file)
    {
        return file_exists($this->fixPath($file));
    }

    public function append(String $file, $content)
    {
        return File::append($this->fixPath($file), $content);
    }

    public function directories($path)
    {
        $directories = [];

        $storagePrefix = $this->fixPath();

        foreach (glob(rtrim($this->fixPath($path), '/') . "/*") as $item) {
            if (is_dir($item)) {
                $fullPathParts = explode($storagePrefix, $item, 2);

                $directories[] = array_pop($fullPathParts);
            }
        }

        return $directories;
    }

    public function allDirectories($path)
    {
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($this->fixPath($path))
        );

        $directories = [];

        $storagePrefix = $this->fixPath();

        foreach ($iterator as $dir) {
            if ($dir->isDir()) {
                $nameParts = explode($storagePrefix, $dir->getPathName());

                $value = rtrim(array_pop($nameParts), '.');

                if (array_search($value, $directories) === false) {
                    $directories[] = $value;
                }
            }
        }

        return array_values(array_filter($directories));
    }

    public function allFiles($path)
    {
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($this->fixPath($path))
        );

        $files = [];

        $storagePrefix = $this->fixPath();

        foreach ($iterator as $file) {
            if (!$file->isDir()) {
                $fullPathParts = explode($storagePrefix, $file->getPathname(), 2);

                $files[] = array_pop($fullPathParts);
            }
        }

        return $files;
    }

    public function files($path)
    {
        $files = [];

        $storagePrefix = $this->fixPath();

        foreach (glob(rtrim($this->fixPath($path), '/') . "/*") as $item) {
            if (is_file($item)) {
                $fullPathParts = explode($storagePrefix, $item, 2);

                $files[] = array_pop($fullPathParts);
            }
        }

        return $files;
    }

    public function delete($file)
    {
        return File::delete($this->fixPath($file));
    }

    public function rmdir($directorie)
    {
        return File::deleteDirectory($this->fixPath($directorie));
    }

    public function size($file)
    {
        return File::size($this->fixPath($file));
    }

    public function copy($source, $copy)
    {
        return File::copy($this->fixPath($source), $this->fixPath($copy));
    }

    public function move($source, $copy)
    {
        return File::move($this->fixPath($source), $this->fixPath($copy));
    }

    public function mkdir($dir, $mode = 0777)
    {
        return File::setDir($this->fixPath($dir), $mode);
    }

    public function touch($file)
    {
        return File::create($this->fixPath($file));
    }

    public function modifiedTime($file)
    {
        return File::lastModifiedTime($this->fixPath($file));
    }

    public function path($path)
    {
        $this->path = $path;

        return $this;
    }

    protected function fixPath($path = '')
    {
        $path =  path(trim($this->path, '/'). '/' . trim($path, '/'));

        if (is_dir($dir = pathinfo($path, PATHINFO_DIRNAME))) {
            @mkdir($dir, 0755, true);
        }

        return $path;
    }
}
