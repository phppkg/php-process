<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2017/6/1
 * Time: 下午9:03
 */

namespace Inhere\Process\IPC;

/**
 * class MsgQueue
 * @package Inhere\Process\IPC
 */
class MsgQueue extends AbstractIPC
{
    /** @var string  */
    protected static $name = 'msgQueue';

    /**
     * @var string
     */
    protected $msgKey = '';

    private $handle;

    protected function init()
    {
        parent::init();

        if ($this->msgKey) {
            $this->msgKey = (int)$this->msgKey;
        } else {
            $this->msgKey = ftok(__FILE__, 0);
        }

        $this->handle = msg_get_queue($this->msgKey);
    }

    /**
     * @return bool
     */
    public static function isSupported(): bool
    {
        // TODO: Implement isSupported() method.
    }
}
