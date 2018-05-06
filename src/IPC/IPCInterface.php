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
    /**
     * @param int $size
     * <= 0 Read all data
     * > 0 Read data of this size
     * @return string
     */
    public function receive(int $size = 0): string;

    /**
     * @param string $data
     * @return bool
     */
    public function send(string $data): bool;

    /**
     * @return bool
     */
    public function clear(): bool;

    /**
     * @return bool
     */
    public static function isSupported(): bool;

    /**
     * @return string
     */
    public static function getName(): string;
}
