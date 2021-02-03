<?php

class database
{
    protected $db_server;
    protected $db_user;
    protected $db_pass;
    protected $db_name;

    public $con;

    public function __construct()
    {
        $db_server = 'localhost';
        $db_user = 'root';
        $db_pass = '';
        $db_name = 'tugasbesar_19552011111';
        global $con;

        $con = mysqli_connect($db_server, $db_user, $db_pass, $db_name);
    }

    public function query($sql)
    {
        global $con;
        $result = mysqli_query($con, $sql);
        return $result;
    }
}
