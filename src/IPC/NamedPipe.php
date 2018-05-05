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
     * pipe Handle
     * @var resource
     */
    protected $pipe;

    private $input = [
        'handle' => null,
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
    protected function createPipe(): bool
    {
        if (!$this->config['enablePipe']) {
            return false;
        }

        //创建管道
        $pipeFile = "/tmp/{$this->name}.pipe";

        if (!\file_exists($pipeFile) && !\posix_mkfifo($pipeFile, 0666)) {
            $this->stderr("Create the pipe failed! PATH: $pipeFile");
        }

        $this->pipe = \fopen($pipeFile, 'wrb');
        \stream_set_blocking($this->pipe, false);  //设置成读取非阻塞

        return true;
    }

    public function receive(): string
    {
        return $this->readMessage();
    }

    /**
     * @param int $bufferSize
     * @return string
     */
    protected function readMessage(int $bufferSize = 2048): string
    {
        if (!$this->pipe) {
            return false;
        }

        // 读取管道数据
        return \fread($this->pipe, $bufferSize);
    }

    public function send(string $data)
    {
        return $this->sendMessage($data);
    }

    /**
     * @param string $command
     * @param string|array $data
     * @return bool|int
     */
    protected function sendMessage(string $command, $data = null)
    {
        if (!$this->pipe) {
            return false;
        }

        // 写入数据到管道
        $len = fwrite($this->pipe, \json_encode([
            'command' => $command,
            'data' => $data,
        ]));

        return $len;
    }

    /**
     * @return bool
     */
    public static function isSupported(): bool
    {
        return \function_exists('posix_mkfifo');
    }
}
