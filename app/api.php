<?php
require_once 'storage.php';

$blank_todo = [
    'id' => 0,
    'description'=> '',
    'completed' => "0"
];

/**
 * Read list of items
 *
 * @return array
 */
function listTodo(): array {
    return read_data();
}


/**
 * Create and return item
 *
 * @param $todoData mixed
 * @return mixed
 */
function createTodo (mixed $todoData): mixed {
    global $blank_todo;

    $todoes = read_data();

    $newTodo = array_merge($blank_todo, $todoData);
    $newTodo['id'] = uniqid();

    $todoes[] = $newTodo;

    write_data($todoes);

    return $newTodo;


}

/**
 * Edit and return item by id
 *
 * @param $todoId
 * @param $todoData
 * @return mixed
 */
function editTodo($todoId, $todoData) {
    $todoes = listTodo();

    $key = array_search($todoId, array_column($todoes, 'id'));
    $newTodo = array_merge($todoes[$key], $todoData);

    $todoes[$key] = $newTodo;

    write_data($todoes);

    return $newTodo;
}


/**
 * Read item by id
 *
 * @param $todoId
 * @return void
 */
function readTodo($todoId) {
    $todoes = listTodo();

    $key = array_search($todoId, array_column($todoes, 'id'));

    if(array_key_exists($key, $todoes)){
        return $todoes[$key];
    }
    return false;

}


/**
 * Delete item
 *
 * @param $todoId
 * @return bool
 */
function deleteTodo($todoId) {
    $todoes = listTodo();
    $key = array_search ($todoId, array_column($todoes, 'id'));

    if($key != false){
        unset($todoes[$key]);

        write_data($todoes);

        return true;

    }

    return false;
}
