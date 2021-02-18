<?php
namespace TO_DO_LIST\Task;

use mysqli;

class Task
{
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
        $mysqli = new mysqli("localhost", "root", "", "test");
        if ($mysqli->connect_errno) {
            echo "Failed to connect to MySQL: " . $mysqli->connect_error;
            exit();
        }

        // Return name of current default database
        if ($result = $mysqli->query("SELECT DATABASE()")) {
            $row = $result->fetch_row();
            // echo "Default database is " . $row[0];
            $result->close();
        }

        // Change db to "test" db
        $mysqli->select_db("test");

        // Return name of current default database
        if ($result = $mysqli->query("SELECT DATABASE()")) {
            $row = $result->fetch_row();
            // echo "Default database is " . $row[0];
            $result->close();
        }

        $mysqli->close();
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
        $mysqli = new mysqli("localhost", "root", "", "test");
        if (isset($this->name) && !empty($this->name)) {
            $sql = "INSERT INTO `to_do_list` (`task_name`,`status`) VALUES ('$this->name','$this->status')";
            if ($mysqli->query($sql) === true) {
                // $_SESSION['msg'] = '<font color="green">' . "Successfully Added.." . '</font>';
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
        $option = $this->option;
        $_SESSION['option'] = $option;

        $mysqli = new mysqli("localhost", "root", "", "test");

        $condition = "";
        if ($option == 'active') {
            $condition = "WHERE `status`= 1 ";
        } else if ($option == 'completed') {
            $condition = "WHERE `status`= 3 AND `is_completed` = 1 ";
        } else {
            $condition = "";
        }

        $sql = "SELECT * FROM `to_do_list` $condition";
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
        $mysqli = new mysqli("localhost", "root", "", "test");
        $sql = "SELECT COUNT(*) AS Total FROM `to_do_list` WHERE `is_completed` = 1 ";
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
        $mysqli = new mysqli("localhost", "root", "", "test");
        $sql = "SELECT COUNT(*) AS Total FROM `to_do_list` WHERE `status` = 1 ";
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
        $mysqli = new mysqli("localhost", "root", "", "test");
        $sql = "SELECT * FROM `to_do_list` WHERE `id` = '$this->id'";
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
        // echo $this->id;
        // echo $this->name;
        // echo $this->status;
        // echo 'this is update page';
        $mysqli = new mysqli("localhost", "root", "", "test");
        $sql = "UPDATE `to_do_list` SET `task_name` = '$this->name', `status`  = '$this->status' WHERE id = '$this->id'";

        if ($mysqli->query($sql) === true) {
            // $_SESSION['msg'] = '<font color="green">' . "Successfully Updated.." . '</font>';
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
        // echo $this->id;
        // echo $this->name;
        // echo $this->status;
        // echo 'this is update page';
        $mysqli = new mysqli("localhost", "root", "", "test");
        $sql = "UPDATE `to_do_list` SET `is_completed` = 1, `status`  = '$this->status' WHERE id = '$this->id'";

        if ($mysqli->query($sql) === true) {
            // $_SESSION['msg'] = '<font color="green">' . "Successfully Updated.." . '</font>';
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
        $mysqli = new mysqli("localhost", "root", "", "test");
        $sql = "DELETE FROM `to_do_list` WHERE id = '$this->id'";

        if ($mysqli->query($sql) === true) {
            // $_SESSION['msg'] = '<font color="green">' . "Successfully Deleted.." . '</font>';
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

        $mysqli = new mysqli("localhost", "root", "", "test");
        $sql = "DELETE FROM `to_do_list` WHERE `is_completed` = 1";

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