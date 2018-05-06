<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2017/6/1
 * Time: 下午9:02
 */

namespace Inhere\Process\IPC;

/**
 * Class NamedPipe
 * @package Inhere\Process\IPC
 * @link http://php.net/manual/zh/function.posix-mkfifo.php
 */
class NamedPipe extends AbstractIPC
{
    /** @var string  */
    protected static $name = 'namedPipe';

    /**
     * pipe handle for write
     * @var resource
     */
    protected $writePipe;

    /**
     * pipe handle for read
     * @var resource
     */
    protected $readPipe;

    /** @var string  */
    protected $pipeFile = '/tmp/php-ipc.pipe';

    /** @var bool */
    protected $blocking = true;

    private $input = [
        'handle' => '/tmp/php-ipc.pipe',
        'mode' => 0660,
        'name' => '',
        'use' => 'r',
    ];

    private $output = [
        'handle' => null,
        'mode' => 0660,
        'name' => '',
        'use' => 'w',
    ];

    /**
     * @return bool
     */
    public function isBlocking(): bool
    {
        return $this->blocking;
    }

    /**
     * @param bool $blocking
     */
    public function setBlocking($blocking)
    {
        $this->blocking = (bool)$blocking;
    }

    /**
     * @return bool
     * @throws \RuntimeException
     */
    protected function createPipe(): bool
    {
        //创建管道
        $pipeFile = "/tmp/{$this->name}.pipe";

        if (!\file_exists($pipeFile) && !\posix_mkfifo($pipeFile, 0666)) {
            throw new \RuntimeException("Create the pipe failed! PATH: $pipeFile");
        }

        $this->readPipe = \fopen($pipeFile, 'rb');
        $this->writePipe = \fopen($pipeFile, 'wb');

        // mode - false 设置成读取非阻塞 true 设置成读取阻塞
        \stream_set_blocking($this->readPipe, $this->blocking);  //设置成读取非阻塞

        return true;
    }

    /**
     * @param int $size
     * @return string
     */
    public function receive(int $size = 0): string
    {
        if ($size <= 0) {
            return $this->readAll();
        }

        return $this->read();
    }

    /**
     * @param int $bufferSize
     * @return string
     */
    protected function read(int $bufferSize = 2048): string
    {
        if (!$this->readPipe) {
            return false;
        }

        // 读取管道数据
        return \fread($this->readPipe, $bufferSize);
    }

    /**
     * @return string
     */
    public function readAll(): string
    {
        $data = '';

        while (!\feof($this->readPipe)) {
            $data .= \fread($this->readPipe, 1024);
        }

        return $data;
    }

    public function send(string $data): bool
    {
        return $this->sendMessage($data);
    }

    /**
     * @param string $command
     * @param string|array $data
     * @return bool
     */
    protected function sendMessage(string $command, $data = null): bool
    {
        if (!$this->writePipe) {
            return false;
        }

        // 写入数据到管道
        $len = fwrite($this->writePipe, \json_encode([
            'command' => $command,
            'data' => $data,
        ]));

        return $len !== false;
    }


    public function closePipe()
    {
        if ($this->writePipe) {
            \fclose($this->writePipe);
        }

        if ($this->readPipe) {
            \fclose($this->readPipe);
        }
    }

    /**
     * @return bool
     */
    public static function isSupported(): bool
    {
        return \function_exists('posix_mkfifo');
    }
}
