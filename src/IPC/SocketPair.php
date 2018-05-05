<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2017/6/1
 * Time: 下午9:03
 */

namespace Inhere\Process\IPC;

use Inhere\Library\Helpers\Sys;

/**
 * class SocketPair
 * @package Inhere\Process\IPC
 * @link http://php.net/manual/zh/function.socket-create-pair.php
 */
class SocketPair extends AbstractIPC
{
    /** @var string  */
    protected static $name = 'socketPair';

    public function create()
    {
        /* On Windows we need to use AF_INET */
        $domain = Sys::isWindows() ? \AF_INET : \AF_UNIX;

        $ary = array();
        $strone = 'Message From Parent.';
        $strtwo = 'Message From Child.';

        if (socket_create_pair($domain, SOCK_STREAM, 0, $ary) === false) {
            echo 'socket_create_pair() failed. Reason: ' . socket_strerror(socket_last_error());
        }

        $pid = pcntl_fork();
        if ($pid === -1) {
            echo 'Could not fork Process.';
        } elseif ($pid) {
            /*parent*/
            socket_close($ary[0]);
            if (socket_write($ary[1], $strone, \strlen($strone)) === false) {
                echo 'socket_write() failed. Reason: ' . socket_strerror(socket_last_error($ary[1]));
            }
            if (socket_read($ary[1], \strlen($strtwo), PHP_BINARY_READ) === $strtwo) {
                echo "Recieved $strtwo\n";
            }
            socket_close($ary[1]);
        } else {
            /*child*/
            socket_close($ary[1]);
            if (socket_write($ary[0], $strtwo, \strlen($strtwo)) === false) {
                echo 'socket_write() failed. Reason: ' . socket_strerror(socket_last_error($ary[0]));
            }
            if (socket_read($ary[0], \strlen($strone), PHP_BINARY_READ) === $strone) {
                echo "Recieved $strone\n";
            }
            socket_close($ary[0]);
        }
    }

    /**
     * @return bool
     */
    public static function isSupported(): bool
    {
        return \function_exists('socket_create_pair');
    }
}
