<?php

class MfAutoVm_Module extends ServerModule
{
    private $api;
    private AutoVmClass $autoVm;
    const VM_PENDING_STATUS = 'pending';
    const VM_ACTIVE_STATUS = 'active';

    function __construct($server,$options=[])
    {
        $this->_name = __CLASS__;
        include __DIR__ . '/init.php';
        $this->autoVm = new AutoVmClass(1);
        parent::__construct($server,$options);
    }

    protected function define_server_info($server=[])
    {
        /*
        if(!class_exists("SampleApi")) include __DIR__.DS."api.class.php";
        $this->api = new SampleApi(
            $server["name"],
            $server["ip"],
            $server["username"],
            $server["password"],
            $server["access_hash"],
            $server["port"],
            $server["secure"],
            $server["port"]
        );
        */
    }

    public function testConnect(){

        try
        {
            $connect    = 'OK';  #$this->api->checkConnect();
        }
        catch(Exception $e){
            $this->error = $e->getMessage();
            return false;
        }

        if($connect != 'OK'){
            $this->error = $connect;
            return false;
        }

        return true;
    }

    public function create(array $order_options=[])
    {

        try
        {
            $result = '';
            $pool = $this->order['options']['creation_info']['pool'];
            $ram = $this->order['options']['creation_info']['ram'];
            $hdd = $this->order['options']['creation_info']['hard'];
            $cpu = $this->order['options']['creation_info']['cpu'];
            $template = $this->val_of_requirements['osId'];
            $email = $this->user["email"];
            $create = $this->autoVm->sendCreateRequest($pool, $template, $ram, $hdd, $cpu, $email);
        }
        catch (Exception $e){
            $this->error = $e->getMessage();
            self::save_log(
                'Servers',
                $this->_name,
                __FUNCTION__,
                ['order' => $this->order],
                $e->getMessage(),
                $e->getTraceAsString()
            );
            return false;
        }

        if(isset($create['data'])) {
            WDB::insert('mf_autovm', [
                'order_id'   => $this->order['id'],
                'machine_id' => $create['data']['id'],
                'status'     => self::VM_PENDING_STATUS,
            ]);

            return [
                'ip'           => '',
                'assigned_ips' => [],
                'login'        => [
                    'username' => '',
                    'password' => '',
                ],
                'config' => ['machineId' => $create['data']['id']],
            ];
        }  else
        {
            $this->error = $create['message'];
            return false;
        }
    }

    public function suspend()
    {
        try
        {
            /*
             * $this->order["options"]
            * for parameters: https://docs.wisecp.com/en/kb/parameters
            * Here are the codes to be sent to the API...
            */
            $machineId = $this->order['options']['config']['machineId'];
            $request = $this->autoVm->sendSuspendRequest($machineId);
        }
        catch (Exception $e){
            $this->error = $e->getMessage();
            self::save_log(
                'Servers',
                $this->_name,
                __FUNCTION__,
                ['order' => $this->order],
                $e->getMessage(),
                $e->getTraceAsString()
            );
            return false;
        }
        /*
        * Error Result:
        * $result             = "Error Message";
        */

        if(isset($request['data']))
            return true;
        else
        {
            $this->error = $request['message'];
            return false;
        }
    }

    public function unsuspend()
    {
        try
        {
            /*
             * $this->order["options"]
            * for parameters: https://docs.wisecp.com/en/kb/parameters
            * Here are the codes to be sent to the API...
            */
            $machineId = $this->order['options']['config']['machineId'];
            $request = $this->autoVm->sendUnsuspendRequest($machineId);
        }
        catch (Exception $e){
            $this->error = $e->getMessage();
            self::save_log(
                'Servers',
                $this->_name,
                __FUNCTION__,
                ['order' => $this->order],
                $e->getMessage(),
                $e->getTraceAsString()
            );
            return false;
        }
        /*
        * Error Result:
        * $result             = "Error Message";
        */

        if(isset($request['data']))
            return true;
        else
        {
            $this->error = $request['message'];
            return false;
        }
    }

    public function terminate()
    {
        try
        {
            $machineId = $this->order['options']['config']['machineId'];
            $request = $this->autoVm->sendDestroyRequest($machineId);
        }
        catch (Exception $e){
            $this->error = $e->getMessage();
            self::save_log(
                'Servers',
                $this->_name,
                __FUNCTION__,
                ['order' => $this->order],
                $e->getMessage(),
                $e->getTraceAsString()
            );
            return false;
        }
        /*
        * Error Result:
        * $result             = "Error Message";
        */

        if(isset($request['data']))
            return true;
        else
        {
            $this->error = $request['message'];
            return false;
        }
    }

    public function apply_updowngrade($params=[])
    {
        /*
        parent::udgrade(); // You can use it to delete the previous virtual server and create the virtual server with new features.
        */

        try
        {
            /*
             * $this->order["options"]
            * for parameters: https://docs.wisecp.com/en/kb/parameters
            * Here are the codes to be sent to the API...
            */
            $result             = "OK"; #$this->api->upgrade();
        }
        catch (Exception $e){
            $this->error = $e->getMessage();
            self::save_log(
                'Servers',
                $this->_name,
                __FUNCTION__,
                ['order' => $this->order],
                $e->getMessage(),
                $e->getTraceAsString()
            );
            return false;
        }
        /*
        * Error Result:
        * $result             = "Error Message";
        */

        if($result == 'OK')
            return true;
        else
        {
            $this->error = $result;
            return false;
        }
    }

    public function get_status()
    {
        try
        {
            /*
             * $this->order["options"]
            * for parameters: https://docs.wisecp.com/en/kb/parameters
            * Here are the codes to be sent to the API...
            */
            $result = "running"; # $this->api->status();
            //$result = "stopped"; # $this->api->status();
        }
        catch (Exception $e){
            $this->error = $e->getMessage();
            self::save_log(
                'Servers',
                $this->_name,
                __FUNCTION__,
                ['order' => $this->order],
                $e->getMessage(),
                $e->getTraceAsString()
            );
            return false;
        }

        /*
        * Error Result:
        * $result             = "Error Message";
        */

        if($result == 'running')
            return true;
        elseif($result == 'stopped')
            return false;
        else
        {
            $this->error = $result;
            return false;
        }
    }

    public function clientArea()
    {
        $content    = '';
        $_page      = $this->page;
        $_data      = [];

        // memory Usage
        $machineId = $this->order['options']['config']['machineId'];
        $show = $this->autoVm->show($machineId);
        $listTemplates = $this->autoVm->templates();

        if(!$_page) $_page = 'home';

        if($_page == "home")
        {
            $_data = ['templates' => $listTemplates['data'], 'serverData' => $show['data']];
        }

        $content .= $this->clientArea_buttons_output();

        $content .= $this->get_page('clientarea-home',$_data);

        return  $content;
    }

    public function use_clientArea_change_os()
    {
        $os = $_POST['os'];
        $machineId = $this->order['options']['config']['machineId'];
        $response = $this->autoVm->change($machineId, $os);
        if (isset($response['data'])){
            echo  Utility::jencode([
                'status' => "successful",
                'message' => 'your request was successfully sent',
            ]);
        }
        else
        {
            print json_encode($response);
            die();
        }

        return true;
    }

    public function clientArea_buttons()
    {
        $buttons    = [];
        if($this->page && $this->page != "home")
        {
            $buttons['home'] = [
                'text' => 'turn back',
                'type' => 'page-loader',
            ];
        } else {
            $buttons['restart'] = [
                'text' => 'restart',
                'type' => 'transaction',
            ];
            $buttons['start'] = [
                'text' => 'start',
                'type' => 'transaction',
            ];
            $buttons['stop'] = [
                'text' => 'stop',
                'type' => 'transaction',
            ];
        }

        return $buttons;
    }

    public function start()
    {
        try
        {
            $machineId = $this->order['options']['config']['machineId'];
            $request = $this->autoVm->start($machineId);
        }
        catch (Exception $e){
            $this->error = $e->getMessage();
            self::save_log(
                'Servers',
                $this->_name,
                __FUNCTION__,
                ['order' => $this->order],
                $e->getMessage(),
                $e->getTraceAsString()
            );
            echo Utility::jencode([
                'status' => "error",
                'message' => $this->error,
            ]);
            return false;
        }

        if(isset($request['data']))
        {
            echo Utility::jencode([
                'status' => "successful",
                'message' => 'the machine was started successfully',
                'timeRedirect' => [
                    'url' => $this->area_link,
                    'duration' => 1000
                ],
            ]);
            return true;
        }
        else
        {
            $this->error = $request['message'];
            echo Utility::jencode([
                'status' => "error",
                'message' => $this->error,
            ]);
            return false;
        }

    }

    public function stop()
    {
        try
        {
            $machineId = $this->order['options']['config']['machineId'];
            $request = $this->autoVm->stop($machineId);
        }
        catch (Exception $e){
            $this->error = $e->getMessage();
            self::save_log(
                'Servers',
                $this->_name,
                __FUNCTION__,
                ['order' => $this->order],
                $e->getMessage(),
                $e->getTraceAsString()
            );
            echo Utility::jencode([
                'status' => "error",
                'message' => $this->error,
            ]);
            return false;
        }
        /*
        * Error Result:
        * $result             = "Error Message";
        */

        if(isset($request['data']))
        {
            echo Utility::jencode([
                'status' => "successful",
                'message' => 'the machine was stopped successfully',
                'timeRedirect' => [
                    'url' => $this->area_link,
                    'duration' => 1000
                ],
            ]);
            return true;
        }
        else
        {
            $this->error = $request['message'];
            echo Utility::jencode([
                'status' => "error",
                'message' => $this->error,
            ]);
            return false;
        }
    }

    public function restart()
    {
        try
        {
            $machineId = $this->order['options']['config']['machineId'];
            $request = $this->autoVm->reboot($machineId);
        }
        catch (Exception $e){
            $this->error = $e->getMessage();
            self::save_log(
                'Servers',
                $this->_name,
                __FUNCTION__,
                ['order' => $this->order],
                $e->getMessage(),
                $e->getTraceAsString()
            );
            echo Utility::jencode([
                'status' => "error",
                'message' => $this->error,
            ]);
            return false;
        }

        if(isset($request['data']))
        {
            echo Utility::jencode([
                'status' => "successful",
                'message' => 'the machine was restarted successfully',
                'timeRedirect' => [
                    'url' => $this->area_link,
                    'duration' => 1000
                ],
            ]);
            return true;
        }
        else
        {
            $this->error = $request['message'];
            echo Utility::jencode([
                'status' => "error",
                'message' => $this->error,
            ]);
            return false;
        }

    }


    public function use_clientArea_start()
    {
        if($this->start()){
            $u_data     = UserManager::LoginData('member');
            $user_id    = $u_data['id'];
            User::addAction($user_id,'transaction','The command "start" has been sent for service #'.$this->order["id"].' on the module.');
            Orders::add_history($user_id,$this->order["id"],'server-order-start');
            return true;
        }
        return false;
    }
    public function use_clientArea_stop()
    {
        if($this->stop()){
            $u_data     = UserManager::LoginData('member');
            $user_id    = $u_data['id'];
            User::addAction($user_id,'transaction','The command "stop" has been sent for service #'.$this->order["id"].' on the module.');
            Orders::add_history($user_id,$this->order["id"],'server-order-stop');
            return true;
        }
        return false;
    }
    public function use_clientArea_restart()
    {
        if($this->restart()){
            $u_data     = UserManager::LoginData('member');
            $user_id    = $u_data['id'];
            User::addAction($user_id,'transaction','The command "restart" has been sent for service #'.$this->order["id"].' on the module.');
            Orders::add_history($user_id,$this->order["id"],'server-order-restart');
            return true;
        }
        return false;
    }
    public function use_clientArea_reboot()
    {
        if($this->reboot()){
            $u_data     = UserManager::LoginData('member');
            $user_id    = $u_data['id'];
            User::addAction($user_id,'transaction','The command "reboot" has been sent for service #'.$this->order["id"].' on the module.');
            Orders::add_history($user_id,$this->order["id"],'server-order-reboot');
            return true;
        }
        return false;
    }

    public function use_clientArea_custom_function()
    {
        if(Filter::POST("var2"))
        {
            echo  Utility::jencode([
                'status' => "successful",
                'message' => 'Successful message',
            ]);
        }
        else
        {
            echo "Content Here...";
        }

        return true;
    }

    public function use_adminArea_custom_function()
    {
        if(Filter::POST("var2"))
        {
            echo  Utility::jencode([
                'status' => "successful",
                'message' => 'Successful message',
            ]);
        }
        else
        {
            echo "Content Here...";
        }

        return true;
    }

    public function adminArea_service_fields(){
        $c_info                 = $this->options["creation_info"];
        $field1                 = isset($c_info["field1"]) ? $c_info["field1"] : NULL;
        $field2                 = isset($c_info["field2"]) ? $c_info["field2"] : NULL;

        return [
            'field1'                => [
                'name'              => "Field 1",
                'description'       => "Field 1 Description",
                'type'              => "text",
                'value'             => $field1,
                'placeholder'       => "sample placeholder",
            ],
            'field2'                => [
                'wrap_width'        => 100,
                'name'              => "Field 2",
                'type'              => "output",
                'value'             => '<input type="text" name="creation_info[field2]" value="'.$field2.'">',
            ],
        ];
    }


    public function save_adminArea_service_fields($data=[])
    {
        $login          = $this->options["login"];
        $c_info         = $data['creation_info'];
        $config         = $data['config'];

        if(isset($c_info["new_password"]) && $c_info["new_password"] != '')
        {
            $new_password = $c_info["new_password"];

            unset($c_info["new_password"]);

            if(strlen($new_password) < 5)
            {
                $this->error = 'Password is too short!';
                return false;
            }
            /*
            *  Place the codes to be transmitted to the api here.
            */

            $login["password"] = $this->encode_str($new_password);
        }

        return [
            'creation_info'     => $c_info,
            'config'            => $config,
            'login'             => $login,
        ];
    }

    public function adminArea_buttons()
    {
        $buttons    = [];
        $buttons['restart'] = [
            'text' => 'restart',
            'type' => 'transaction',
        ];
        $buttons['start'] = [
            'text' => 'start',
            'type' => 'transaction',
        ];
        $buttons['stop'] = [
            'text' => 'stop',
            'type' => 'transaction',
        ];

        return $buttons;
    }

    public function use_adminArea_start()
    {
        $this->area_link .= '?content=automation';
        if($this->start()){
            $u_data     = UserManager::LoginData('admin');
            $user_id    = $u_data['id'];
            User::addAction($user_id,'transaction','The command "start" has been sent for service #'.$this->order["id"].' on the module.');
            Orders::add_history($user_id,$this->order["id"],'server-order-start');
            return true;
        }
        return false;
    }
    public function use_adminArea_stop()
    {
        $this->area_link .= '?content=automation';
        if($this->stop()){
            $u_data     = UserManager::LoginData('admin');
            $user_id    = $u_data['id'];
            User::addAction($user_id,'transaction','The command "stop" has been sent for service #'.$this->order["id"].' on the module.');
            Orders::add_history($user_id,$this->order["id"],'server-order-stop');
            return true;
        }
        return false;
    }
    public function use_adminArea_restart()
    {
        $this->area_link .= '?content=automation';
        if($this->restart()){
            $u_data     = UserManager::LoginData('admin');
            $user_id    = $u_data['id'];
            User::addAction($user_id,'transaction','The command "restart" has been sent for service #'.$this->order["id"].' on the module.');
            Orders::add_history($user_id,$this->order["id"],'server-order-restart');
            return true;
        }
        return false;
    }
    public function use_adminArea_reboot()
    {
        $this->area_link .= '?content=automation';
        if($this->reboot()){
            $u_data     = UserManager::LoginData('admin');
            $user_id    = $u_data['id'];
            User::addAction($user_id,'transaction','The command "reboot" has been sent for service #'.$this->order["id"].' on the module.');
            Orders::add_history($user_id,$this->order["id"],'server-order-reboot');
            return true;
        }
        return false;
    }

    public function poolsList()
    {

        return $this->autoVm->poolsList();
    }


}

Hook::add("PerMinuteCronJob",1,function($params=[]){

    $query = WDB::select("mf_autovm.id, mf_autovm.order_id, mf_autovm.machine_id, mf_autovm.status");
    $query->from("mf_autovm");
    $query->where('status', '=', MfAutoVm_Module::VM_PENDING_STATUS);
    $query = $query->build(true)->fetch_object();
    if ($query){
        require_once __DIR__ . '/../../Servers/MfAutoVm/init.php';
        $autoVm = new AutoVmClass(1);
        foreach ($query as $item){
            print json_encode($item);
            $get = $autoVm->show($item->machine_id);
            $ip = $get['data']['reserves'][0]['address']['address'];
            $username = $get['data']['template']['username'];
            $password = Crypt::encode($get['data']['password'],Config::get("crypt/user"));
            if ($ip != null){
                $query = WDB::select("users_products.*");
                $query->where('users_products.id', '=', $item->order_id);
                $query->from("users_products");
                $query = $query->build(true)->fetch_assoc();
                $options = json_decode($query[0]['options'], true);
                $options['ip'] = $ip;
                $options['login']['username'] = $username;
                $options['login']['password'] = $password;

                $operation = WDB::update('users_products');
                $operation->set(['options' => json_encode($options)]);
                $operation->where('users_products.id', '=', $item->order_id);
                if ($operation->save()){

                    $operation = WDB::update('mf_autovm');
                    $operation->set(['status' => MfAutoVm_Module::VM_ACTIVE_STATUS]);
                    $operation->where('mf_autovm.id', '=', $item->id);
                    $operation->save();
                }
            }
        }
    }
});