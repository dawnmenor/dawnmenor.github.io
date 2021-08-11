<?php

class Todo {

    protected $user_todos;
    protected $uri;
    protected $request_method;
    public function __construct()
    {
        $this->user_todos = json_decode(file_get_contents('user_todos.json'));
        $this->uri = explode( '/', $_SERVER['REQUEST_URI']);
        $this->request_method = $_SERVER["REQUEST_METHOD"];
    }

    /**
     * desc: list all users todo
     * param: uri = user_id/todo_id
     * @return string
     */
    public function list($user_id) {
        $this->validateParam($user_id);

        if ($this->request_method == "GET") {
            $return = [];
            foreach ($this->user_todos->data as $user_todo) {
                if ($user_todo->id == $user_id) {
                    $return =  $user_todo;
                }
            }
            echo json_encode($return);
        }
        else {
            echo "Invalid request type.";
        }
    }

    /**
     * desc: view user todo
     * param: uri = user_id/todo_id
     * @return string
     */
    public function view($user_id, $todo_id) {
        $this->validateParam($user_id, $todo_id);

        if ($this->request_method == "GET") {
            try{
                $return = [];
                foreach ($this->user_todos->data as $user_todo) {
                    if ($user_todo->id == $user_id) {
                        foreach ($user_todo->todo as $k => $todo) {
                            if ($todo->id == $todo_id) {
                                $new_todo = new stdClass();
                                $new_todo->id = $todo_id;
                                $new_todo->description = $todo->description;
                                $new_todo->due_date = $todo->due_date;
                                $user_todo->todo = $new_todo;
                                $return = $user_todo;
                            }
                        }
                    }
                }
                echo json_encode($return);

            } catch (Error $error) {
                echo $error->getMessage();
            }
        } else {
            echo "Invalid request type.";
        }

    }

    /**
     * desc: add user new todo
     * param: uri = user_id
     * @return string
     */
    public function add($user_id) {
        $this->validateParam($user_id);

        if ($this->request_method == "POST") {
            $data = json_decode(file_get_contents("php://input"));

            try{
                foreach ($this->user_todos->data as $user_todo) {
                    if ($user_todo->id == $user_id) {
                        if(!empty($data)) {
                            $err_field = [];
                            $new_todo = new stdClass();
                            $new_todo->id = count($user_todo->todo) + 1;
                            $new_todo->description = !empty($data->description) ? $data->description : $err_field[] = 'description';
                            $new_todo->due_date = !empty($data->due_date) ? $data->due_date : $err_field[] = 'due_date';
                            $user_todo->todo[] = $new_todo;

                            if(!empty($err_field)) {
                                foreach ($err_field as $err) {
                                    echo ucfirst($err) . " is empty. \n";
                                }
                            } else {
                                file_put_contents('user_todos.json', json_encode($this->user_todos));
                                echo "Successfully saved.";
                                return false;
                            }

                        } else {
                            echo "Invalid Parameter/s";
                            return false;
                        }
                    }
                }
            } catch (Error $error) {
                echo $error->getMessage();
            }


        } else {
            echo "Invalid request type.";
        }

    }

    /**
     * desc: update user todo
     * param: uri = user_id/todo_id
     * @return string
     */
    public function update($user_id, $todo_id) {
        $this->validateParam($user_id, $todo_id);

        if ($this->request_method == "PATCH") {
            $data = json_decode(file_get_contents("php://input"));

            try{
                foreach ($this->user_todos->data as $user_todo) {
                    if ($user_todo->id == $user_id) {
                        foreach ($user_todo->todo as $k => $todo) {
                            if ($todo->id == $todo_id) {
                                if(!empty($data)) {
                                    $err_field = [];
                                    $new_todo = new stdClass();
                                    $new_todo->id = $todo_id;
                                    $new_todo->description = !empty($data->description) ? $data->description : $err_field[] = 'description';
                                    $new_todo->due_date = !empty($data->due_date) ? $data->due_date : $err_field[] = 'due_date';
                                    $user_todo->todo[$k] = $new_todo;

                                    if(!empty($err_field)) {
                                        foreach ($err_field as $err) {
                                            echo ucfirst($err) . " is empty. \n";
                                        }
                                    } else {
                                        file_put_contents('user_todos.json', json_encode($this->user_todos));
                                        echo "Successfully updated.";
                                        return false;
                                    }

                                } else {
                                    echo "Invalid Parameter/s";
                                    return false;
                                }
                            }
                        }
                    }
                }
            } catch (Error $error) {
                echo $error->getMessage();
            }

        } else {
            echo "Invalid request type.";
        }
    }

    /**
     * desc: delete user todo
     * param: uri = user_id/todo_id
     * @return string
     */
    public function delete($user_id, $todo_id) {
        $this->validateParam($user_id, $todo_id);

        if ($this->request_method == "DELETE") {
            try{
                foreach ($this->user_todos->data as $user_todo) {
                    if ($user_todo->id == $user_id) {
                        foreach ($user_todo->todo as $k => $todo) {
                            if ($todo->id == $todo_id) {
                                unset($user_todo->todo[$k]);
                                $user_todo->todo = array_values($user_todo->todo);
                                file_put_contents('user_todos.json', json_encode($this->user_todos));
                                echo "Successfully deleted.";
                                return false;
                            }
                        }
                    }
                }
            } catch (Error $error) {
                echo $error->getMessage();
            }

        } else {
            echo "Invalid request type.";
        }
    }

    private function validateParam($user_id = 0, $todo_id = 0) {
        $err_msg = [];
        if (is_null($user_id) || empty($user_id)) {
            $err_msg[] = "User id is empty.";
        }
        if (is_null($todo_id)) {
            $err_msg[] = "Todo id is empty.";
        }

        if (!empty($err_msg)) {
            die(json_encode($err_msg));
        }
    }
}

