<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2017/6/1
 * Time: 下午9:03
 */

namespace Inhere\Process\IPC;

/**
 * Interface IPCInterface
 * @package Inhere\Process\IPC
 */
interface IPCInterface
{
    public function receive(): string;

    public function send(string $data);

    /**
     * @return bool
     */
    public static function isSupported(): bool;

    /**
     * @return string
     */
    public static function getName(): string;
}
