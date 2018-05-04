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
class UnixDomain extends AbstractIpc
{
    protected function create()
    {
        // $socket = socket_create(AF_UNIX, SOCK_STREAM, SOL_TCP);
        $socket = socket_create(AF_UNIX, SOCK_DGRAM, SOL_UDP);
        $bindRes = socket_bind($socket, $this->socketfile);
        $listenRes = socket_listen($socket, 9999);
    }
}
