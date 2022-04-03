<?php

class AutoVmClass
{
    protected $serviceId;
    const AUTOVM_BASE = 'http://baa.test.autovm.net';
    const AUTOVM_ADMIN_TOKEN = 'o8jga6bmpu3rvclcm3p5';
    private $request;

    public function __construct($serviceId)
    {
        $this->request = new Api(self::AUTOVM_BASE, self::AUTOVM_ADMIN_TOKEN);
        $this->serviceId = $serviceId;
    }

    public function sendPoolsRequest()
    {
        $headers = ['token' => self::AUTOVM_ADMIN_TOKEN];

        $address = [
            self::AUTOVM_BASE, 'candy', 'backend', 'common', 'pools'
        ];

        return Request::instance()->setAddress($address)->setHeaders($headers)->getResponse()->asObject();
    }

    public function poolsList()
    {

        return $this->request->get('/candy/backend/pool/index');
    }

    public function templates()
    {
        $response = $this->sendTemplatesRequest();

        $this->response($response);
    }

    public function sendTemplatesRequest()
    {
        $headers = ['token' => self::AUTOVM_ADMIN_TOKEN];

        $address = [
            self::AUTOVM_BASE, 'candy', 'backend', 'common', 'templates'
        ];

        return Request::instance()->setAddress($address)->setHeaders($headers)->getResponse()->asObject();
    }

    public function sendCreateRequest($poolId, $templateId, $memorySize, $diskSize, $cpuCore, $email)
    {
        $params = [
            'poolId'     => $poolId,
            'templateId' => $templateId,
            'memorySize' => $memorySize,
            'diskSize'   => $diskSize,
            'cpuCore'    => $cpuCore,
            'email'      => $email
        ];

        return $this->request->post('/candy/backend/machine/smart/pool', $params);
    }

    public function show($machineId)
    {

        return $this->request->get('/candy/backend/machine/show/' . $machineId);
    }

    public function sendShowRequest($machineId)
    {
        $headers = ['token' => self::AUTOVM_ADMIN_TOKEN];

        $address = [
            self::AUTOVM_BASE, 'candy', 'backend', 'machine', 'show', $machineId
        ];

        return Request::instance()->setAddress($address)->setHeaders($headers)->getResponse()->asObject();
    }

    public function setup()
    {
        $machineId = $this->getMachineIdFromService();

        // Send request
        $response = $this->sendSetupRequest($machineId);

        $this->response($response);
    }

    public function sendSetupRequest($machineId)
    {
        $headers = ['token' => self::AUTOVM_ADMIN_TOKEN];

        $address = [
            self::AUTOVM_BASE, 'candy', 'backend', 'machine', 'setup', $machineId
        ];

        return Request::instance()->setAddress($address)->setHeaders($headers)->getResponse()->asObject();
    }

    public function start($machineId)
    {

        return $this->request->get('/candy/backend/machine/start/' . $machineId);
    }

    public function sendStartRequest($machineId)
    {
        $headers = ['token' => self::AUTOVM_ADMIN_TOKEN];

        $address = [
            self::AUTOVM_BASE, 'candy', 'backend', 'machine', 'start', $machineId
        ];

        return Request::instance()->setAddress($address)->setHeaders($headers)->getResponse()->asObject();
    }

    public function stop($machineId)
    {

        return $this->request->get('/candy/backend/machine/stop/' . $machineId);
    }

    public function sendStopRequest($machineId)
    {
        $headers = ['token' => self::AUTOVM_ADMIN_TOKEN];

        $address = [
            self::AUTOVM_BASE, 'candy', 'backend', 'machine', 'stop', $machineId
        ];

        return Request::instance()->setAddress($address)->setHeaders($headers)->getResponse()->asObject();
    }

    public function reboot($machineId)
    {

        return $this->request->get('/candy/backend/machine/reboot/' . $machineId);
    }

    public function sendRebootRequest($machineId)
    {
        $headers = ['token' => self::AUTOVM_ADMIN_TOKEN];

        $address = [
            self::AUTOVM_BASE, 'candy', 'backend', 'machine', 'reboot', $machineId
        ];

        return Request::instance()->setAddress($address)->setHeaders($headers)->getResponse()->asObject();
    }

    public function sendSuspendRequest($machineId)
    {

        return $this->request->get('/candy/backend/machine/suspend/' . $machineId);
    }

    public function sendUnsuspendRequest($machineId)
    {

        return $this->request->get('/candy/backend/machine/unsuspend/' . $machineId);
    }

    public function snapshot()
    {
        $machineId = $this->getMachineIdFromService();

        // Send request
        $response = $this->sendSnapshotRequest($machineId);

        $this->response($response);
    }

    public function sendSnapshotRequest($machineId)
    {
        $headers = ['token' => self::AUTOVM_ADMIN_TOKEN];

        $address = [
            self::AUTOVM_BASE, 'candy', 'backend', 'machine', 'snapshot', $machineId
        ];

        return Request::instance()->setAddress($address)->setHeaders($headers)->getResponse()->asObject();
    }

    public function revert()
    {
        $machineId = $this->getMachineIdFromService();

        // Send request
        $response = $this->sendRevertRequest($machineId);

        $this->response($response);
    }

    public function sendRevertRequest($machineId)
    {
        $headers = ['token' => self::AUTOVM_ADMIN_TOKEN];

        $address = [
            self::AUTOVM_BASE, 'candy', 'backend', 'machine', 'revert', $machineId
        ];

        return Request::instance()->setAddress($address)->setHeaders($headers)->getResponse()->asObject();
    }

    public function console()
    {
        $machineId = $this->getMachineIdFromService();

        // Send request
        $response = $this->sendConsoleRequest($machineId);

        $this->response($response);
    }

    public function sendConsoleRequest($machineId)
    {
        $headers = ['token' => self::AUTOVM_ADMIN_TOKEN];

        $address = [
            self::AUTOVM_BASE, 'candy', 'backend', 'machine', 'console', $machineId
        ];

        return Request::instance()->setAddress($address)->setHeaders($headers)->getResponse()->asObject();
    }

    public function sendDestroyRequest($machineId)
    {

        return $this->request->get('/candy/backend/machine/destroy/' . $machineId);
    }

    public function change()
    {
        $machineId = $this->getMachineIdFromService();

        // Find the template identity
        $templateId = autovm_get_query('avmTemplateId');

        // Send request
        $response = $this->sendChangeRequest($machineId, $templateId);

        $this->response($response);
    }

    public function sendChangeRequest($machineId, $templateId)
    {
        $headers = ['token' => self::AUTOVM_ADMIN_TOKEN];

        $address = [
            self::AUTOVM_BASE, 'candy', 'backend', 'machine', 'change', $machineId
        ];

        $params = ['templateId' => $templateId];

        return Request::instance()->setAddress($address)->setHeaders($headers)->setParams($params)->getResponse()->asObject();
    }

    public function memoryUsage()
    {
        $machineId = $this->getMachineIdFromService();

        // Send request
        $response = $this->sendMemoryUsageRequest($machineId);

        $this->response($response);
    }

    public function sendMemoryUsageRequest($machineId)
    {
        $headers = ['token' => self::AUTOVM_ADMIN_TOKEN];

        $address = [
            self::AUTOVM_BASE, 'candy', 'backend', 'graph', 'machine', $machineId, 'memory', 'daily'
        ];

        return Request::instance()->setAddress($address)->setHeaders($headers)->getResponse()->asObject();
    }

    public function cpuUsage()
    {
        $machineId = $this->getMachineIdFromService();

        // Send request
        $response = $this->sendCpuUsageRequest($machineId);

        $this->response($response);
    }

    public function sendCpuUsageRequest($machineId)
    {
        $headers = ['token' => self::AUTOVM_ADMIN_TOKEN];

        $address = [
            self::AUTOVM_BASE, 'candy', 'backend', 'graph', 'machine', $machineId, 'cpu', 'daily'
        ];

        return Request::instance()->setAddress($address)->setHeaders($headers)->getResponse()->asObject();
    }

    public function bandwidthUsage()
    {
        $machineId = $this->getMachineIdFromService();

        // Send request
        $response = $this->sendBandwidthUsageRequest($machineId);

        $this->response($response);
    }

    public function sendBandwidthUsageRequest($machineId)
    {
        $headers = ['token' => self::AUTOVM_ADMIN_TOKEN];

        $address = [
            self::AUTOVM_BASE, 'candy', 'backend', 'graph', 'machine', $machineId, 'bandwidth', 'daily'
        ];

        return Request::instance()->setAddress($address)->setHeaders($headers)->getResponse()->asObject();
    }

    public function sendUpgradeRequest($machineId, $memorySize, $diskSize, $cpuCore)
    {
        $params = [
            'memorySize' => $memorySize, 'diskSize' => $diskSize, 'cpuCore' => $cpuCore
        ];

        $headers = ['token' => self::AUTOVM_ADMIN_TOKEN];

        $address = [
            self::AUTOVM_BASE, 'candy', 'backend', 'machine', 'upgrade', $machineId
        ];

        return Request::instance()->setAddress($address)->setHeaders($headers)->setParams($params)->getResponse()->asObject();
    }

    public function response($response)
    {
        header('Content-Type: application/json');

        $response = json_encode($response);

        exit($response);
    }

    public function getMachineIdFromService()
    {
        $machineId = $this->getMachineIdFromServiceCurrentVersion();

        if (!$machineId) {

            $machineId = $this->getMachineIdFromServiceOldVersion();
        }

        return $machineId;
    }

    public function getMachineIdFromServiceCurrentVersion()
    {
        $params = [
            'serviceId' => $this->serviceId
        ];

        $machine = Capsule::selectOne('SELECT machine_id FROM autovm_order WHERE order_id = :serviceId', $params);

        // The first value
        return current($machine);
    }

    public function getMachineIdFromServiceOldVersion()
    {
        $params = [
            'name' => 'ID', 'serviceId' => $this->serviceId
        ];

        $machine = Capsule::selectOne('SELECT a.value FROM tblcustomfieldsvalues a INNER JOIN tblcustomfields b ON b.id = a.fieldid WHERE b.fieldname = :name AND a.relid = :serviceId', $params);

        // The first value
        return current($machine);
    }

    public function handle($action)
    {
        $class = new ReflectionClass($this);

        $method = $class->getMethod($action);

        if ($method) {
            return $method->invoke($this);
        }
    }
}
