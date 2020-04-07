<?php
namespace EasySwoole\EasySwoole;

use App\Lib\Cache\Video;
use App\Lib\Elastic\Elastic;
use App\Lib\Pool\MysqlPool;
use App\Lib\Redis\Redis;
use App\Lib\Process\ConsumerTest;
use EasySwoole\EasySwoole\Config as GlobalConfig;
use EasySwoole\Component\Di;
use EasySwoole\EasySwoole\Swoole\EventRegister;
use EasySwoole\EasySwoole\AbstractInterface\Event;
use EasySwoole\FastCache\Cache;
use EasySwoole\FastCache\CacheProcessConfig;
use EasySwoole\FastCache\SyncData;
use EasySwoole\Http\Request;
use EasySwoole\Http\Response;
use EasySwoole\ORM\Db\Config;
use EasySwoole\ORM\Db\Connection;
use EasySwoole\ORM\DbManager;
use EasySwoole\Pool\Manager;
use EasySwoole\Utility\File;

class EasySwooleEvent implements Event
{

    public static function initialize()
    {
        // TODO: Implement initialize() method.
        date_default_timezone_set('Asia/Shanghai');

        $orm_config = new Config();
        $orm_config->setDatabase("easyswoole");
        $orm_config->setUser("printemps");
        $orm_config->setPassword("lovelive");
        $orm_config->setHost("127.0.0.1");
        $orm_config->setPort(22331);
        // 连接池配置
        $orm_config->setGetObjectTimeout(2.0); //设置获取连接池对象超时时间
        $orm_config->setIntervalCheckTime(30*1000); //设置检测连接存活执行回收和创建的周期
        $orm_config->setMaxIdleTime(20); //连接池对象最大闲置时间(秒)
        $orm_config->setMaxObjectNum(200); //设置最大连接池存在连接对象数量
        $orm_config->setMinObjectNum(32); //设置最小连接池存在连接对象数量
        DbManager::getInstance()->addConnection(new Connection($orm_config));
        // 载入自定义配置
        self::loadConf(EASYSWOOLE_ROOT . '/Config');
    }

    public static function mainServerCreate(EventRegister $register)
    {
        // TODO: Implement mainServerCreate() method.
        
        $register->add($register::onWorkerStart,function (){
            //链接预热
            DbManager::getInstance()->getConnection()->getClientPool()->keepMin();
        });
        Di::getInstance()->set("REDIS", Redis::getInstance());
        Di::getInstance()->set("ES", Elastic::getInstance());
        // Di::getInstance()->set("MYSQLI", \App\Lib\MysqlDb::getInstance());
        
        $config = new \EasySwoole\Pool\Config();
        $config->setMaxIdleTime(20);
        $config->setMaxObjectNum(200);
        $config->setMinObjectNum(64);
        $redisConfig1 = new \EasySwoole\Redis\Config\RedisConfig(GlobalConfig::getInstance()->getConf('REDIS_CONF'));
        // $redisConfig2 = new \EasySwoole\Redis\Config\RedisConfig(GlobalConfig::getInstance()->getConf('REDIS_CONF'));
        Manager::getInstance()->register(new \App\Lib\Pool\RedisPool($config,$redisConfig1),'REDIS_POOL');
        // Manager::getInstance()->register(new \App\Lib\Pool\RedisPool($config,$redisConfig2),'REDIS_POOL');
        Manager::getInstance()->register(new \App\Lib\Pool\MysqlPool($config),'MYSQL_POOL');

        // ================
        
        $allNum = 4;
        for ($i = 0 ;$i < $allNum;$i++){
            ServerManager::getInstance()->getSwooleServer()->addProcess((new ConsumerTest("consumer_{$i}"))->getProcess());
        }
        $video = new Video();
        $register->add(EventRegister::onWorkerStart, function (\swoole_server $server, $workerId) use ($video) {
            if ($workerId == 0) {
                \EasySwoole\Component\Timer::getInstance()->loop(60 * 1000, function () use ($video) {
                    $video->setIndexVideo('table');
                    // \EasySwoole\Component\Timer::getInstance()->after(3 * 1000, function () {
                    //     //为了防止因为任务阻塞，引起定时器不准确，把任务给异步进程处理
                    //     Logger::getInstance()->console("time 3", false);
                    // });
                });
            }
        });
        // -----------------------------------------------------Cache-----------------------------------------------------
        // 每隔5秒将数据存回文件
        Cache::getInstance()->setTickInterval(9 * 1000);//设置定时频率
        Cache::getInstance()->setOnTick(function (SyncData $SyncData, CacheProcessConfig $cacheProcessConfig) {
            $data = [
                'data'  => $SyncData->getArray(),
                'queue' => $SyncData->getQueueArray(),
                'ttl'   => $SyncData->getTtlKeys(),
                // queue支持
                'jobIds'     => $SyncData->getJobIds(),
                'readyJob'   => $SyncData->getReadyJob(),
                'reserveJob' => $SyncData->getReserveJob(),
                'delayJob'   => $SyncData->getDelayJob(),
                'buryJob'    => $SyncData->getBuryJob(),
            ];
            $path = EASYSWOOLE_TEMP_DIR . '/FastCacheData/' . $cacheProcessConfig->getProcessName();
            File::createFile($path,serialize($data));
        });

        // 启动时将存回的文件重新写入
        Cache::getInstance()->setOnStart(function (CacheProcessConfig $cacheProcessConfig) {
            $path = EASYSWOOLE_TEMP_DIR . '/FastCacheData/' . $cacheProcessConfig->getProcessName();
            if(is_file($path)){
                $data = unserialize(file_get_contents($path));
                $syncData = new SyncData();
                $syncData->setArray($data['data']);
                $syncData->setQueueArray($data['queue']);
                $syncData->setTtlKeys(($data['ttl']));
                // queue支持
                $syncData->setJobIds($data['jobIds']);
                $syncData->setReadyJob($data['readyJob']);
                $syncData->setReserveJob($data['reserveJob']);
                $syncData->setDelayJob($data['delayJob']);
                $syncData->setBuryJob($data['buryJob']);
                return $syncData;
            }
        });

        // 在守护进程时,php easyswoole stop 时会调用,落地数据
        Cache::getInstance()->setOnShutdown(function (SyncData $SyncData, CacheProcessConfig $cacheProcessConfig) {
            $data = [
                'data'  => $SyncData->getArray(),
                'queue' => $SyncData->getQueueArray(),
                'ttl'   => $SyncData->getTtlKeys(),
                // queue支持
                'jobIds'     => $SyncData->getJobIds(),
                'readyJob'   => $SyncData->getReadyJob(),
                'reserveJob' => $SyncData->getReserveJob(),
                'delayJob'   => $SyncData->getDelayJob(),
                'buryJob'    => $SyncData->getBuryJob(),
            ];
            $path = EASYSWOOLE_TEMP_DIR . '/FastCacheData/' . $cacheProcessConfig->getProcessName();
            File::createFile($path,serialize($data));
        });
        Cache::getInstance()->setTempDir(EASYSWOOLE_TEMP_DIR)->attachToServer(ServerManager::getInstance()->getSwooleServer());




    }

    public static function loadConf($conf_path)
    {
        $conf = GlobalConfig::getInstance();
        $files = File::scanDirectory($conf_path);
        foreach ($files['files'] as $file) {
            $data = require_once $file;
            $conf->setConf(strtolower(basename($file, ".php")), $data);
        }

    }

    public static function onRequest(Request $request, Response $response): bool
    {
        // TODO: Implement onRequest() method.
        return true;
    }

    public static function afterRequest(Request $request, Response $response): void
    {
        // TODO: Implement afterAction() method.
    }
}