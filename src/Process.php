<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2017-06-26
 * Time: 18:17
 */

namespace Inhere\Process;

use Toolkit\PhpUtil\Php;
use Toolkit\Sys\ProcessUtil;

/**
 * Class Process
 * @package Inhere\Process
 */
class Process
{
    private $started = false;
    private $inChild = false;
    protected $id = 0;
    protected $pid = 0;
    protected $handler;
    protected $stop = false;
    protected $startTime = 0;
    protected $lifetime = 0;
    protected $options = [];

    public static function create(callable $handler, array $options = []): Process
    {
        return new self($handler);
    }

    public function __construct(callable $handler, array $options = [])
    {
        $this->handler = $handler;
        $this->options = $options;
    }

    public function start($id = 0): int
    {
        $this->id = (int)$id;
        $this->started = true;
        $this->startTime = time();
        $pid = pcntl_fork();

        if ($pid > 0) {// at parent, get forked child info
            $this->pid = $pid;
        } elseif ($pid === 0) { // at child
            $this->inChild = true;
            $this->pid = getmypid();

            if ($this->handler) {
                Php::call($this->handler, $this);
            }

        } else {
            throw new \RuntimeException('Fork child process failed!', __LINE__);
        }

        return $pid;
    }

    public function wait($blocking = true): int
    {
        $code = 0;
        while (!$this->stop) {
            pcntl_signal_dispatch(); // receive and dispatch sig

            if ($this->stop) {
                break;
            }

            $cb($this);

            if ($this->stop) {
                break;
            }
        }

        return $code;
    }

    /**
     * @param int $pid
     * @param bool $force
     * @return bool
     */
    public static function kill(int $pid, bool $force = false): bool
    {
        return ProcessUtil::kill($pid, $force);
    }

    public function exec($cmd, array $args = []): string
    {
        return exec($cmd, $args);
    }

    public function stop()
    {
        # code...
    }

    public function exit($status = 0)
    {
        exit((int)$status);
    }

    // public function closePipe()
    public function closeIPC()
    {
        # code...
    }

    public function setName($name)
    {
        ProcessUtil::setName($name);
    }
}
