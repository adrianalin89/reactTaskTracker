<?php

class ModelTasks {

    private $conn;
    private $table = 'tasks';

    public $id;
    public $task;
    public $owner;
    public $doing;
    public $done;
    public $time;

    public function __construct(
        $db
    ) {
        $this->conn = $db;
    }

    /**
     * Add new task
     * @return $this|false
     */
    public function create()
    {
        $this->sanitize();
        $query = 'INSERT INTO ' . $this->table . '
                    SET 
                        task = :task,
                        owner= :owner,
                        doing= :doing,
                        done = :done,
                        time = :time';

        try {
            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(':task', $this->task);
            $stmt->bindParam(':owner', $this->owner);
            $stmt->bindParam(':doing', $this->doing);
            $stmt->bindParam(':done', $this->done);
            $stmt->bindParam(':time', $this->time);

            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $this->id = $this->conn->lastInsertId();
                return $this;
            } else {
                echo json_encode(
                    array(
                        'message' => 'Add task failed !',
                        'err' => true
                    )
                );
                return false;
            }

        } catch (PDOException $e) {
            echo json_encode(
                array(
                    'message' => printf("Error: %s.\n", $e->getMessage()),
                    'err' => true
                )
            );
            return false;
        }
    }

    /**
     * Get All or One task
     * @return $this|false
     */
    public function read()
    {
        $this->sanitize();

        $query = 'SELECT * FROM ' . $this->table .
            ($this->id ? " WHERE id=$this->id LIMIT 1" : '');

        $stmt = $this->conn->prepare($query);

        try {

            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                if($this->id) {
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                    if(!isset($row['id'])) {
                        echo json_encode(
                            array(
                                'message' => 'No task whit the id of ' . $this->id . '!',
                                'err' => true
                            )
                        );
                        return false;
                    }

                    $this->id = $row['id'];
                    $this->task = $row['task'];
                    $this->owner = $row['owner'];
                    $this->doing = $row['doing'];
                    $this->done = $row['done'];
                    $this->time = $row['time'];
                    return $this;
                }
                    return $stmt;
            } else {
                echo json_encode(
                    array(
                        'message' => 'No task! ' . $this->id,
                        'err' => true
                    )
                );
                return false;
            }

        } catch (PDOException $e) {
            echo json_encode(
                array(
                    'message' => printf("Error: %s.\n", $e->getMessage()),
                    'err' => true
                )
            );
            return false;
        }

    }

    /**
     * Update existing task
     * @return $this|false
     */
    public function update()
    {
        $this->sanitize();

        if(!$this->id){
            echo json_encode(
                array(
                    'message' => 'Task id is required!',
                    'err' => true
                )
            );
            return false;
        }

        $query = 'UPDATE ' . $this->table . '
                    SET 
                        task = :task,
                        owner= :owner,
                        doing= :doing,
                        done = :done,
                        time = :time
                    WHERE
                        id = :id';


        try {

            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(':id', $this->id);
            $stmt->bindParam(':task', $this->task);
            $stmt->bindParam(':owner', $this->owner);
            $stmt->bindParam(':doing', $this->doing);
            $stmt->bindParam(':done', $this->done);
            $stmt->bindParam(':time', $this->time);


            if ($stmt->execute()) {
                return $this;
            } else {
                echo json_encode(
                    array(
                        'message' => 'No task whit the id of ' . $this->id . '!',
                        'err' => true
                    )
                );
                return false;
            }

        } catch (PDOException $e) {
            echo json_encode(
                array(
                    'message' => printf("Error: %s.\n", $e->getMessage()),
                    'err' => true
                )
            );
            return false;
        }
    }

    /**
     * Delete Task by id
     * @return bool
     */
    public function delete()
    {
        $this->sanitize();

        if(!$this->id){
            echo json_encode(
                array(
                    'message' => 'Task id is required!',
                    'err' => true
                )
            );
            return false;
        }

        $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':id', $this->id);

        try {
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                return true;
            } else {
                echo json_encode(
                    array(
                        'message' => 'No task whit the id of ' . $this->id . '!',
                        'err' => true
                    )
                );
                return false;
            }
        } catch (PDOException $e) {
            echo json_encode(
                array(
                    'message' => printf("Error: %s.\n", $e->getMessage()),
                    'err' => true
                )
            );
            return false;
        }
    }

    /**
     * Sanitize model params
     * @return void
     */
    private function sanitize()
    {
        $this->id = $this->id ?: htmlspecialchars(strip_tags($this->id));
        $this->task = $this->task ?: htmlspecialchars(strip_tags($this->task));
        $this->owner = $this->owner ?: htmlspecialchars(strip_tags($this->owner));
        $this->doing = $this->doing ?: htmlspecialchars(strip_tags($this->doing));
        $this->done = $this->done ?: htmlspecialchars(strip_tags($this->done));
        $this->time = $this->time ?: htmlspecialchars(strip_tags($this->time));
    }

}