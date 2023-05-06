<?php
namespace App\Storage;
use App\Interfaces\StorageInterface;

class JsonStorage implements StorageInterface
{

    private $filePath;

    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
    }

    public function write(mixed $data)
    {
        $jsonData = json_encode($data);
        file_put_contents($this->filePath, $jsonData);
    }

    public function read()
    {
        if (!file_exists($this->filePath)) {
            return null;
        }

        $jsonData = file_get_contents($this->filePath);
        return json_decode($jsonData, true);
    }

    public function update(mixed $data = null, int $index = -1)
    {
        $todoList = $this->read();
        if (!$todoList) {
            return false;
        }

        if ($index < 0 || $index >= count($todoList)) {
            return false;
        }

        if ($data !== null) {
            $todoList[$index] = $data;
        }

        $this->write($todoList);
        return true;
    }
    public function search($key, $value)
    {
        $todoList = $this->read();
        if (!$todoList) {
            return null;
        }

        $results = [];
        foreach ($todoList as $index => $item) {
            if (isset($item[$key]) && $item[$key] === $value) {
                $results[] = $item;
            }
        }

        return $results;
    }
    public function delete(int $index)
    {
        $todoList = $this->read();
        if (!$todoList) {
            return false;
        }

        if ($index < 0 || $index >= count($todoList)) {
            return false;
        }

        array_splice($todoList, $index, 1);
        $this->write($todoList);
        return true;
    }
}