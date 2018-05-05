<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2017/6/1
 * Time: 下午9:03
 */

namespace Inhere\Process\IPC;

/**
 * class SharedMemory
 * @package Inhere\Process\IPC
 */
class SharedMemory extends AbstractIPC
{
    /** @var string  */
    protected static $name = 'sharedMemory';

    /**
     * @return bool
     */
    public static function isSupported(): bool
    {
        // TODO: Implement isSupported() method.
    }
}
