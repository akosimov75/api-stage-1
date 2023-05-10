<?php
namespace App\Repositories;
use \App\Interfaces\TodoRepositoryInterface;
use \App\Models\Todo;
use \App\Storage\JsonStorage;
use App\Exceptions\NotFoundException;


class TodoRepository implements TodoRepositoryInterface
{
    private JsonStorage $storage;

    public function __construct(JsonStorage $storage)
    {
        $this->storage = $storage;
    }

    public function get_all(): array
    {
        $todos = $this->storage->read();
        $todoArray = [];

        foreach ($todos as $item) {
            $todoObject = new Todo();
            $todoObject->set_id($item["id"]);
            $todoObject->set_description($item["description"]);
            $todoObject->set_completed($item["completed"]);
            $todoArray[] = $todoObject;

        }

        return $todoArray;

    }

    public function get_by_id(int $id): Todo
    {
        $todosData = $this->storage->search('id', $id);
        if ($todosData === null) {
            throw new NotFoundException();
        }
        $todo = new Todo();
        $todo->set_id($todosData['id']);
        $todo->set_description($todosData['description']);
        $todo->set_completed($todosData['completed']);

        return $todo;
    }




    public function add(mixed $data): Todo
    {
        $todoData = $this->sanitizeTodoData($data);

        $todosData = $this->storage->read();

        $maxId = 0;
        foreach ($todosData as $todoData) {
            if ($todoData['id'] > $maxId) {
                $maxId = $todoData['id'];
            }
        }

        $newId = $maxId + 1;

        $todo = new Todo();
        $todo->set_id($newId);
        $todo->set_description($data['description']);
        $todo->set_completed($data['completed']);

        $todoData['id'] = $newId;
        $todoData['description'] = $todo->get_description();
        $todoData['completed'] = $todo->is_completed();

        $todosData[] = $todoData;
        $this->storage->write($todosData);

        return $todo;
    }



    private function sanitizeTodoData(mixed $data): array
    {
        if (is_array($data)) {
            return $data;
        }

        return [];
    }


    public function update(int $id, mixed $data): Todo
    {
        $todoData = $this->sanitizeTodoData($data);

        $todosData = $this->storage->read();

        $updatedTodo = null;
        $found = false;
        foreach ($todosData as &$todo) {
            if ($todo['id'] === $id) {
                $found = true;
                $todo['description'] = $todoData['description'] ?? $todo['description'];
                $todo['completed'] = $todoData['completed'] ?? $todo['completed'];

                $updatedTodo = new Todo();
                $updatedTodo->set_id($todo['id']);
                $updatedTodo->set_description($todo['description']);
                $updatedTodo->set_completed($todo['completed']);

                break;
            }
        }
        if (!$found) {
            throw new NotFoundException();
        }
        $this->storage->write($todosData);

        return $updatedTodo;
    }



    public function delete($id)
    {
        $todosData = $this->storage->read();

        $found = false;

        foreach ($todosData as $key => $todoData) {
            if ($todoData['id'] == $id) {
                unset($todosData[$key]);
                $found = true;
                break;
            }
        }

        if ($found) {
            $this->storage->write($todosData);
            return "Deleted";
        } else {
            throw new NotFoundException();
        }
    }


}
