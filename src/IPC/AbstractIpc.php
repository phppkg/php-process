<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2017/6/1
 * Time: 下午9:04
 */

namespace Inhere\Process\IPC;

use Inhere\Library\Traits\LiteConfigTrait;

/**
 * Class BaseIpc
 * @package Inhere\Process\IPC
 */
abstract class AbstractIpc
{
    use LiteConfigTrait;

    /**
     * @var string
     */
    protected $driver;

    /**
     * The queue id(name)
     * @var string|int
     */
    protected $id;

    /**
     * @var int
     */
    protected $errCode = 0;

    /**
     * @var string
     */
    protected $errMsg;

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
     */
    public function __construct(array $config = [])
    {
        $this->setConfig($config);

        $this->init();
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
}
