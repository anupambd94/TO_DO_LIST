# TO_DO_LIST

WeDevs Assignment

Details: This project is created in PHP OOP with MySQl Database.

    . PHP Version 5.6.30
    . Apache Version Apache/2.4.25 (Win32) OpenSSL/1.0.2j PHP/5.6.30
    . Exported table script will be found in TO_DO_LIST\DB directory

Installation Instruction:
-> Set or change the database connection variables like as follows in the class named "Task" Located in :(TO_DO_LIST\Src\Task\Task.php).

    // Cridentials for databse connection
    public $dbhost = 'localhost';
    public $dbname = 'test';
    public $dbuser = 'root';
    public $dbpassword = '';
    public $dbtable = 'to_do_list';

    <!-- change as yours -->
    $dbhost = 'yourdbhostname';
    $dbname = 'yourdbname';
    $dbuser = 'yourdbusername';
    $dbpassword = 'yourdbpassword';
    $dbtable = 'yourtablename';
