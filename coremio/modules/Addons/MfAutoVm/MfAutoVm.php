<?php

class MfAutoVm extends AddonModule
{
    public $version = "1.0";
    function __construct(){
        $this->_name = __CLASS__;
        parent::__construct();
    }

    public function fields(){
        return [

        ];
    }

    public function save_fields($fields=[]){
    }

    public function activate(){
        /*
         * Here, you can perform any intervention before the module is activate.
         * If you return boolean (true), the module will be activate.
        */

        $table = WDB::hasTable('mf_autovm');
        if ($table){
            return true;
        }

        $operation = WDB::exec("CREATE table mf_autovm(
        id INT( 11 ) AUTO_INCREMENT PRIMARY KEY,
        order_id INTEGER NOT NULL,
        machine_id INTEGER NOT NULL,
        status VARCHAR(255) NOT NULL);");

        if($operation)
        {
            return true;
        }

        return false;
    }

    public function deactivate(){
        /*
         * Here, you can perform any intervention before the module is deactivate.
         * If you return boolean (true), the module will be deactivate.
        */
        return true;
    }

    public function adminArea()
    {

    }

    public function clientArea()
    {

    }

    public function upgrade(){

    }

    public function main()
    {


    }
}