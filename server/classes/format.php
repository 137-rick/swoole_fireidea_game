<?php

class FormatChecker
{
    public $template;

    function __construct()
    {
        $this->template = array(
            TYPE_MESSAGE::HELLO => array('s', 'n', 'n'),
            TYPE_MESSAGE::MOVE => array('n', 'n'),
            TYPE_MESSAGE::LOOTMOVE => array('n', 'n', 'n'),
            TYPE_MESSAGE::AGGRO => array('n'),
            TYPE_MESSAGE::ATTACK => array('n'),
            TYPE_MESSAGE::HIT => array('n'),
            TYPE_MESSAGE::HURT => array('n'),
            TYPE_MESSAGE::CHAT => array('s'),
            TYPE_MESSAGE::LOOT => array('n'),
            TYPE_MESSAGE::TELEPORT => array('n', 'n'),
            TYPE_MESSAGE::ZONE => array(),
            TYPE_MESSAGE::OPEN => array('n'),
            TYPE_MESSAGE::CHECK => array('n'),
        );
    }

    public function check($msg)
    {
        if (!is_array($msg))
            return false;

        $type = array_shift($msg);
        //如果类型在范围内
        if ($this->template[$type]) {
            if (count($this->template[$type]) != count($msg)) {
                return false;
            }
            foreach ($this->template[$type] as $k => $titem) {
                if ($titem == 'n' && !is_numeric($msg[$k])) {
                    return false;
                }
                if ($titem == 's' && !is_string($msg[$k])) {
                    return false;
                }
            }
            return true;
        } else if ($type == TYPE_MESSAGE::WHO) {
            //not sure how many ,but must number
            if (count($msg) > 0) {
                foreach ($msg as $item) {
                    if (!is_numeric($item)) {
                        return false;
                    }
                }

            } else {
                return false;
            }
        } else {
            echo "unknow message type :" . $type . "\r\n";
            return false;
        }
        return false;

    }

}