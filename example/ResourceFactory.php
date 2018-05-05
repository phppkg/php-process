<?php
/**
 * Created by PhpStorm.
 * User: inhere
 * Date: 2017/6/24
 * Time: 下午11:57
 */

namespace Inhere\Process\pool;

/**
 * Class ResourceFactory
 * @package Inhere\Process\pool
 */
class ResourceFactory implements ResourceInterface
{
    public function create()
    {
        return new \PDO('dsn');
    }

    public function destroy($obj)
    {
        // ...
    }
}
