<?php

class User {

    protected $user;
    protected $uri;
    protected $request_method;
    public function __construct()
    {
        $this->user = json_decode(file_get_contents('user_todos.json'));
        $this->uri = explode( '/', $_SERVER['REQUEST_URI']);
        $this->request_method = $_SERVER["REQUEST_METHOD"];
    }

    public function list() {
        if ($this->request_method == "GET") {
            foreach ($this->user->data as $k => $user) {
               unset($this->user->data[$k]->todo);
            }
            echo json_encode($this->user->data);
        }
    }
}