<?php

class WorldServer
{
    private $server = null;

    function __construct()
    {

        $this->server = new swoole_websocket_server("0.0.0.0", 8000);

        $config = array(
            'dispatch_mode' => 3,
            'package_max_length' => 1024 * 1024 * 2,
            'buffer_output_size' => 1024 * 1024 * 3,
            'pipe_buffer_size' => 1024 * 1024 * 32,
            'open_tcp_nodelay' => 1,
            'heartbeat_check_interval' => 5,
            'heartbeat_idle_time' => 10,

            'reactor_num' => 32,
            'worker_num' => 40,
            'task_worker_num' => 20,

            'max_request' => 0,
            'task_max_request' => 0,

            'backlog' => 2000,
            'log_file' => '/tmp/fireidea.log',
            'task_tmpdir' => '/tmp/fireidea/',
        );
        $this->server->on('workerstart', array($this, "onWorkerStart"));

        $this->server->on('open', array($this, "onConnected"));

        $this->server->on('message', array($this, "onMessage"));

        $this->server->on('task', array($this, "onTask"));

        $this->server->on('finish', array($this, "onFinish"));

        $this->server->on('close', array($this, "onClosed"));

        $this->server->start();

    }

    public function onWorkerStart($server, $worker_id)
    {
        $istask = $server->taskworker;
        if (!$istask) {
            //worker
            swoole_set_process_name("phpworker|{$worker_id}");
        } else {
            //task
            swoole_set_process_name("phptask|{$worker_id}");
        }
    }

    public function onTask($serv, $task_id, $from_id, $data)
    {

    }

    public function onFinish($serv, $task_id, $data)
    {

    }

    public function onConnected($ws, $request)
    {
        //start go
        $ws->push($request->fd, "go");
    }

    public function onMessage($ws, $frame)
    {
        $data = json_decode($frame->data, true);

        var_dump($data);
        $type = $data[0];
        if ($type == TYPE_MESSAGE::HELLO) {
            $name = substr($data[1], 0, 30);

            $data = array(
                TYPE_MESSAGE::WELCOME,//type
                $frame->fd,//fd
                substr($data[1], 0, 30),//name
                80,  //x
                200,  //y
                20,//hitpoint
            );
            $this->sendMsg($frame->fd, $data);
        }


        //$ws->push($frame->fd, "server: {$frame->data}");
    }

    public function onClosed($ws, $fd)
    {
        echo "client-{$fd} is closed\n";
    }

    public function sendMsg($fd, $array)
    {
        return $this->server->push($fd, json_encode($array));
    }
}

