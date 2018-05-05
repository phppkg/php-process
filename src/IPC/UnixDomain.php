<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2017/6/1
 * Time: 下午9:03
 */

namespace Inhere\Process\IPC;

/**
 * class UnixDomain
 * @package Inhere\Process\IPC
 */
class UnixDomain extends AbstractIPC
{
    /** @var string  */
    protected static $name = 'unixDomain';

    private $sockFile;

    protected function create()
    {
        // $socket = socket_create(AF_UNIX, SOCK_STREAM, SOL_TCP);
        $socket = socket_create(AF_UNIX, SOCK_DGRAM, SOL_UDP);
        $bindRes = socket_bind($socket, $this->sockFile);
        $listenRes = socket_listen($socket, 9999);
    }

    /**
     * @return bool
     */
    public static function isSupported(): bool
    {
        return \function_exists('socket_create');
    }
}
