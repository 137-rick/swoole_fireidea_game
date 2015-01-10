<?php

class Item extends Entity
{
    public $isStatic;
    public $isFromChest;

    public $respawn_callback;

    public $blinkTimeout;

    function __construct($id, $kind, $x, $y)
    {
        parent::__construct($id, "item", $kind, $x, $y);
        $this->isStatic = false;
        $this->isFromChest = false;
    }

    //销毁后执行操作
    public function destroy()
    {
        //todo:set time out玩废了
    }

    //多少秒后回收
    public function  handleDespawn($params)
    {
        //todo:set time out玩废了
        //https://github.com/LinkedDestiny/swoole-doc/blob/c34c4b5f833b5ab9ebd555d8626a40fcd5f3183c/doc/03.swoole_server%E5%87%BD%E6%95%B0%E5%88%97%E8%A1%A8.md

        //swoole_timer_after
        //swoole_server::after(int $after_time_ms, mixed $callback_function);
        //swoole_server::after($after_time_ms, function ($serv, $fd){
        //    echo "Client:Connect.\n";
        //});

        //https://github.com/swoole/swoole-src/blob/db6b6bf7e530a993c71aac13148987c57e466e1c/examples/timer.php
    }

    //指定周期重新生成
    public function scheduleRespawn($delay)
    {
        //todo:set time out玩废了
    }

    //定义重新生成回调
    public function  onRespawn($callback)
    {
        $this->respawn_callback = $callback;
    }

}
/*
    handleDespawn: function(params) {
        var self = this;

        this.blinkTimeout = setTimeout(function() {
            params.blinkCallback();
            self.despawnTimeout = setTimeout(params.despawnCallback, params.blinkingDuration);
        }, params.beforeBlinkDelay);
    },

    destroy: function() {
        if(this.blinkTimeout) {
            clearTimeout(this.blinkTimeout);
        }
        if(this.despawnTimeout) {
            clearTimeout(this.despawnTimeout);
        }

        if(this.isStatic) {
            this.scheduleRespawn(30000);
        }
    },

    scheduleRespawn: function(delay) {
        var self = this;
        setTimeout(function() {
            if(self.respawn_callback) {
                self.respawn_callback();
            }
        }, delay);
    },

    onRespawn: function(callback) {
        this.respawn_callback = callback;
    }
});
 */