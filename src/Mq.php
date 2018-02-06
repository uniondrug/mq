<?php
/**
 * @author wsfuyibing <websearch@163.com>
 * @date 2018-02-05
 */
namespace UniondrugMq;

use UniondrugMq\Publishes\PromotionMq;
use UniondrugMq\Publishes\Abstracts\AbstractMq;
use UniondrugMq\Publishes\OrderMq;
use UniondrugMq\Publishes\RightsMq;
use UniondrugMq\Publishes\TestMq;

/**
 * 消息队列入口
 * @property OrderMq     $order 订单MQ
 * @property PromotionMq $promotion 优惠券MQ
 * @property RightsMq    $rights 权益MQ
 * @package UniondrugMq
 */
class Mq
{
    private static $instances = [];
    private static $suffixInstance = 'Mq';

    /**
     * 按属性以Magic方式读取共享MQ
     *
     * @param string $name
     *
     * @return object
     * @throws \Exception
     */
    public function __get($name)
    {
        /**
         * return with shared
         */
        $name = ucfirst($name).static::$suffixInstance;
        if (isset(static::$instances[$name])) {
            return static::$instances[$name];
        }
        /**
         * 实例化
         */
        try {
            $class = "\\UniondrugMq\\Publishes\\{$name}";
            $instance = new $class();
            if ($instance instanceof AbstractMq) {
                self::$instances[$name] = $instance;
                return $instance;
            }
            throw new \Exception("call invalid '{$name}' MQ");
        } catch(\Throwable $e) {
            throw new \Exception("call undefined '{$name}' MQ");
        }
    }
}
