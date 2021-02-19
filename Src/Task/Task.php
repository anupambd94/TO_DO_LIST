<?php
namespace TO_DO_LIST\Task;

use mysqli;

class Task
{
    // variables for databse connection
    public $dbhost = 'localhost';
    public $dbname = 'test';
    public $dbuser = 'root';
    public $dbpassword = '';
    public $dbtable = 'to_do_list';
// -------------------------------------------
    public $connection = "";
    public $data = "";
    public $totalCompleted = "";
    public $totalActive = "";
    public $id = "";
    public $name = "";
    public $status = "";
    public $option = "all";
    public function __construct()
    {
        session_start();
        $mysqli = new mysqli($this->dbhost, $this->dbuser, $this->dbpassword, $this->dbname);
        if ($mysqli->connect_errno) {
            $message = "Error: <br>" . $mysqli->connect_errno;
            $_SESSION['msg'] = '<font color="red">' . $message . '</font>';
            header("location: index.php");
        }
        $this->connection = $mysqli;
    }

    public function prepare($data = '')
    {
        if (array_key_exists('name', $data) && !empty($data['name'])) {
            $this->name = $data['name'];
        }
        if (array_key_exists('status', $data) && !empty($data['status'])) {
            $this->status = $data['status'];
        }
        if (array_key_exists('id', $data) && !empty($data['id'])) {
            $this->id = $data['id'];
        }
        // print_r($data);
        return $this;
    }
    public function filter($data = '')
    {
        if (array_key_exists('option', $data)) {
            $this->option = $data['option'];
        }
        // print_r($data);
        return $this;
    }

    public function store()
    {
        $mysqli = $this->connection;
        if (isset($this->name) && !empty($this->name)) {
            $sql = "INSERT INTO `$this->dbtable` (`task_name`,`status`) VALUES ('$this->name','$this->status')";
            if ($mysqli->query($sql) === true) {
                $this->index();
                header("location: index.php");
            } else {
                header("location: index.php");
            }
        } else {
            $_SESSION['msg'] = '<font color="red">set all values</font>';
            header("location: index.php");
        }
        return $this;
    }

    public function index()
    {
        $mysqli = $this->connection;
        $option = $this->option;
        $_SESSION['option'] = $option;

        $condition = "";
        if ($option == 'active') {
            $condition = "WHERE `status`= 1 ";
        } else if ($option == 'completed') {
            $condition = "WHERE `status`= 3 AND `is_completed` = 1 ";
        } else {
            $condition = "";
        }

        $sql = "SELECT * FROM `$this->dbtable` $condition";
        $result = $mysqli->query($sql);

        if ($result->num_rows > 0) {
            // output data of each row
            $index = 0;
            while ($rows[] = $result->fetch_array(MYSQLI_ASSOC)) {
                $rows[$index]['option'] = $this->option;
                $this->data = $rows;
                $index++;
            }
            $_SESSION['data'] = $this->data;
            return $this->data;
        } else {
            $_SESSION['data'] = $this->data;
            return $this->data;

        }
        $mysqli->close();

        return $this;
    }

    public function getTotalCompleted()
    {
        $mysqli = $this->connection;
        $sql = "SELECT COUNT(*) AS Total FROM `$this->dbtable` WHERE `is_completed` = 1 ";
        $result = $mysqli->query($sql);

        if ($result->num_rows > 0) {
            // output data of each row
            while ($rows[] = $result->fetch_array(MYSQLI_ASSOC)) {
                $this->totalCompleted = $rows;
            }
            return $this->totalCompleted[0]['Total'];
        } else {
            // echo "0 results";
        }
        $mysqli->close();
        return $this;
    }
    public function getTotalActive()
    {
        $mysqli = $this->connection;
        $sql = "SELECT COUNT(*) AS Total FROM `$this->dbtable` WHERE `status` = 1 ";
        $result = $mysqli->query($sql);

        if ($result->num_rows > 0) {
            // output data of each row
            while ($rows[] = $result->fetch_array(MYSQLI_ASSOC)) {
                $this->getTotalActive = $rows;
            }
            return $this->getTotalActive[0]['Total'];
        } else {
            // echo "0 results";
        }
        $mysqli->close();
        return $this;
    }

    public function show()
    {
        $mysqli = $this->connection;
        $sql = "SELECT * FROM `$this->dbtable` WHERE `id` = '$this->id'";
        $result = $mysqli->query($sql);

        if ($result->num_rows > 0) {
            // output data of each row
            while ($rows[] = $result->fetch_array(MYSQLI_ASSOC)) {
                $this->data = $rows;
            }
            return $this->data;
        } else {
            // echo "0 results";
        }
        $mysqli->close();
        return $this;
    }
    public function update()
    {
        $mysqli = $this->connection;
        $sql = "UPDATE `$this->dbtable` SET `task_name` = '$this->name', `status`  = '$this->status' WHERE id = '$this->id'";

        if ($mysqli->query($sql) === true) {
            $this->index();
            header("location: index.php");
        } else {
            $message = "Error: " . $sql . "<br>" . $mysqli->error;
            $_SESSION['msg'] = '<font color="red">' . $message . '</font>';
            header("location: index.php");
        }
        $mysqli->close();
        return $this;
    }
    public function complete_task()
    {
        $mysqli = $this->connection;
        $sql = "UPDATE `$this->dbtable` SET `is_completed` = 1, `status`  = '$this->status' WHERE id = '$this->id'";

        if ($mysqli->query($sql) === true) {
            $this->index();
            header("location: index.php");
        } else {
            $message = "Error: " . $sql . "<br>" . $mysqli->error;
            $_SESSION['msg'] = '<font color="red">' . $message . '</font>';
            header("location: index.php");
        }
        $mysqli->close();
        return $this;
    }
    public function delete()
    {
        $mysqli = $this->connection;
        $sql = "DELETE FROM `$this->dbtable` WHERE id = '$this->id'";

        if ($mysqli->query($sql) === true) {
            header("location: index.php");
        } else {
            $message = "Error: " . $sql . "<br>" . $mysqli->error;
            $_SESSION['msg'] = '<font color="red">' . $message . '</font>';
            header("location: index.php");
        }
        $mysqli->close();
        return $this;
    }

    public function clear_completed()
    {

        $mysqli = $this->connection;
        $sql = "DELETE FROM `$this->dbtable` WHERE `is_completed` = 1";

        if ($mysqli->query($sql) === true) {
            $this->index();
        } else {
            $message = "Error: " . $sql . "<br>" . $mysqli->error;
            $_SESSION['msg'] = '<font color="red">' . $message . '</font>';
            header("location: index.php");
        }
        $mysqli->close();
        return $this;
    }
}
