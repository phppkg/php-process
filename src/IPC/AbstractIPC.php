<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2017/6/1
 * Time: 下午9:04
 */

namespace Inhere\Process\IPC;

use Toolkit\PhpUtil\Php;

/**
 * Class BaseIPC
 * @package Inhere\Process\IPC
 */
abstract class AbstractIPC implements IPCInterface
{
    /** @var string The driver name */
    protected static $name = '';

    /**
     * The queue id(name)
     * @var string|int
     */
    protected $id;

    /** @var int */
    protected $errCode = 0;

    /** @var string */
    protected $errMsg = '';

    /** @var bool Clear on exit */
    protected $clearOnExit = false;

    /** @var int The read/write buffer size. */
    protected $bufferSize = 8096;

    /**
     * @var array
     */
    protected $config = [
        'id' => null,
        'serialize' => true,
    ];

    /**
     * @var array
     */
    private $_events = [];

    /**
     * MsgQueue constructor.
     * @param array $config
     * @throws \RuntimeException
     */
    public function __construct(array $config = [])
    {
        if (!static::isSupported()) {
            throw new \RuntimeException(
                \sprintf('Driver %s is not supported in the current environment!', static::$name)
            );
        }

        Php::initObject($this, $config);

        $this->init();
    }

    public function __destruct()
    {
        if ($this->clearOnExit) {
            $this->clear();
        }
    }

    /**
     * @return bool
     */
    public function clear(): bool
    {
        $this->errCode = 0;
        $this->errMsg = '';

        return true;
    }

    /**
     * init
     */
    protected function init()
    {
        $this->config['serialize'] = (bool)$this->config['serialize'];

        if (isset($this->config['id'])) {
            $this->id = $this->config['id'];
        }
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return self::$name;
    }

    /**
     * @return bool
     */
    public function isClearOnExit(): bool
    {
        return $this->clearOnExit;
    }

    /**
     * @param bool $clearOnExit
     */
    public function setClearOnExit($clearOnExit)
    {
        $this->clearOnExit = (bool)$clearOnExit;
    }

    /**
     * @return int
     */
    public function getErrCode(): int
    {
        return $this->errCode;
    }

    /**
     * @return string
     */
    public function getErrMsg(): string
    {
        return $this->errMsg;
    }

    /**
     * @return int
     */
    public function getBufferSize(): int
    {
        return $this->bufferSize;
    }

    /**
     * @param int $bufferSize
     */
    public function setBufferSize(int $bufferSize)
    {
        $this->bufferSize = $bufferSize;
    }
}
