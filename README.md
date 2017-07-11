# process

php 的进程创建、停止、信号等管理工具


## php进程间通信

php中的进程是以扩展的形式来完成。通过这些扩展，我们能够很轻松的完成进程的一系列动作。

- pcntl扩展：主要的进程扩展，完成进程创建于等待操作。
- posix扩展：完成posix兼容机通用api,如获取进程id,杀死进程等。
- sysvmsg扩展：实现system v方式的进程间通信之消息队列。
- sysvsem扩展：实现system v方式的信号量。
- sysvshm扩展：实现system v方式的共享内存。
- sockets扩展：实现socket通信。

> 这些扩展只能在linux/mac中使用，window下是不支持。最后建议php版本为5.5+。


## 项目地址

- **git@osc** https://git.oschina.net/inhere/php-process.git
- **github** https://github.com/inhere/php-process.git

**注意：**

- master 分支是要求 `php >= 7` 的

## 安装

- 使用 composer

编辑 `composer.json`，在 `require` 添加

```
"inhere/process": "dev-master"
```

然后执行: `composer update`

- 直接拉取

```
git clone https://git.oschina.net/inhere/php-process.git // git@osc
git clone https://github.com/inhere/php-process.git // github
```


## License

MIT
