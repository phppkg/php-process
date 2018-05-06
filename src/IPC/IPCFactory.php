<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2017/6/1
 * Time: 下午8:56
 */

namespace Inhere\Process\IPC;

/**
 * Class IPCFactory
 * @package Inhere\Process\IPC
 */
class IPCFactory
{
    const TYPE_PIPE = 'pipe'; // named pipe
    const TYPE_MQ = 'mq'; // message queue
    const TYPE_SEM = 'sem'; // Semaphore
    const TYPE_SHM = 'shm'; // shared memory
    const TYPE_SOCK = 'sock'; // sockets

    /**
     * @var resource
     */
    private $handle;

    private $config = [
        'type' => self::TYPE_PIPE,

        // mq
        'msgKey' => null,

        // pipe
        'name' => 'php_ipc',

        // sm
        'shmKey' => 'php_shm', // shared memory
        'semKey' => 'php_sem', // semaphore

        // sock
        'server' => '127.0.0.1:4455',

        // public
        'blocking' => true,
    ];

    const DRIVERS = [
        'pipe' => NamedPipe::class,
        'mq' => MsgQueue::class,
        'shm' => SharedMemory::class,
        'sock' => SocketPair::class,
        'domain' => UnixDomain::class,
    ];

    /**
     * @param array $config
     * @return IPCInterface
     * @throws \RuntimeException
     */
    public static function create(array $config = []): IPCInterface
    {
        $driver = '';

        if (isset($config['driver']) && ($name = $config['driver'])) {
            if (\strpos($name, '\\') && \class_exists($name)) {
                $driver = $name;
            } elseif (isset(self::DRIVERS[$name])) {
                $driver = self::DRIVERS[$name];
            }
        }

        // auto select
        if (!$driver) {
            /** @var IPCInterface $class */
            foreach (self::DRIVERS as $class) {
                if ($class::isSupported()) {
                    $driver = $class;
                    break;
                }
            }

            // not available
            if (!$driver) {
                throw new \RuntimeException('No available IPC driver for current environment!');
            }
        }

        return new $driver($config);
    }

    /**
     * @return bool
     */
    protected function createPipe(): bool
    {
        //创建管道
        $pipeFile = "/tmp/{$this->config['name']}.pipe";

        if (!file_exists($pipeFile) && !posix_mkfifo($pipeFile, 0666)) {
            throw new \RuntimeException("Create the pipe failed! PATH: $pipeFile", -200);
        }

        $this->handle = fopen($pipeFile, 'wr');
        // 设置成读取(非)阻塞
        stream_set_blocking($this->handle, (bool)$this->config['blocking']);

        return true;
    }

}
