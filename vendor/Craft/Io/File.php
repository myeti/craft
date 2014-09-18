<?php

namespace Craft\Io;

class File extends \ArrayObject implements Resource
{

    /** @var resource */
    protected $file;

    /** @var string */
    protected $filename;

    /** @var string */
    protected $break = "\n";


    /**
     * Open file
     * @param string $filename
     * @param string $mode
     * @param string $break
     */
    public function __construct($filename, $mode = 'a+', $break = "\n")
    {
        $this->break = $break;
        $this->open($filename, $mode);
    }


    /**
     * Open IO resource
     * @param mixed $filename
     * @param string $mode
     * @return bool
     */
    public function open($filename, $mode = 'a+')
    {
        if($this->file) {
            $this->close();
        }

        $this->filename = $filename;
        $this->file = fopen($filename, $mode);
        $this->read();
        return (bool)$this->file;
    }


    /**
     * Read data from resource
     * @return string
     */
    public function read()
    {
        $content = stream_get_contents($this->file);
        $this->exchangeArray(explode($this->break, $content));
        return $content;
    }


    /**
     * Write data on resource
     * @return bool
     */
    public function write(...$contents)
    {
        $written = true;
        foreach($contents as $content) {
            $written &= fwrite($this->file, $content);
        }

        $this->read();
        return $written;
    }


    /**
     * Save array into file
     * @return bool
     */
    public function save()
    {
        $content = implode($this->break, $this->getArrayCopy());
        $written = file_put_contents($this->filename, $content);
        $this->read();
        return $written;
    }


    /**
     * Close IO resource
     * @return bool
     */
    public function close()
    {
        $this->save();
        return fclose($this->file);
    }


    /**
     * Get file size
     * @return int
     */
    public function size()
    {
        return filesize($this->filename);
    }


    /**
     * Clear content
     * @return int
     */
    public function clear()
    {
        $this->exchangeArray([]);
        return $this->save();
    }


    /**
     * Copy file
     * @param $to
     * @return int
     */
    public function copy($to)
    {
        return copy($this->filename, $to);
    }


    /**
     * Rename file
     * @param string $filename
     * @return int
     */
    public function name($filename)
    {
        $this->close();

        $path = dirname($this->filename);
        $path = rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        $renamed = rename($this->filename, $path . $filename);

        $this->open($path . $filename);
        return $renamed;
    }


    /**
     * Move file
     * @param string $to
     * @return int
     */
    public function move($to)
    {
        $this->close();

        $to = rtrim($to, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        $basename = basename($this->filename);
        $renamed = rename($this->filename, $to . $basename);

        $this->open($to . $basename);
        return $renamed;
    }


    /**
     * Clear content
     * @return int
     */
    public function delete()
    {
        $deleted = unlink($this->filename);
        $this->close();
        return $deleted;
    }


    /**
     * Save on unset
     * @param mixed $index
     */
    public function offsetUnset($index)
    {
        parent::offsetUnset($index);
        $this->save();
    }


    /**
     * Save on set
     * @param mixed $index
     * @param mixed $value
     */
    public function offsetSet($index, $value)
    {
        parent::offsetSet($index, $value);
        $this->save();
    }


    /**
     * Get content
     */
    public function __toString()
    {
        return $this->read();
    }


    /**
     * Close file
     */
    public function __destruct()
    {
        $this->close();
    }

}