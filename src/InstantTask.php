<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2017-06-26
 * Time: 18:17
 */

namespace Inhere\Process;

/**
 * Class InstantTask
 * @package Inhere\Process
 */
class InstantTask
{
    private $workerNum = 1;

    public function runScript($script, $callback = null)
    {

    }

    public function setWorkerNum(int $number)
    {
        $this->workerNum = $number;

        return $this;
    }

    public function getWorkerNum(): int
    {
        return $this->workerNum;
    }
}
