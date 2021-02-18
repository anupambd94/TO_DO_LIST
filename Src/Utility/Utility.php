<?php
namespace TO_DO_LIST\Utility;

class Utility
{

    public function debug($data = '')
    {
        echo '<pre>';
        print_r($data);
        echo '</pre>';
    }

}