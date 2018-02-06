<?php
/**
 * @author wsfuyibing <websearch@163.com>
 * @date 2018-02-05
 */
namespace UniondrugMq\Publishes;

use UniondrugMq\Publishes\Abstracts\AbstractMq;

/**
 * 优惠券MQ
 * @package UniondrugMq\Mqs
 */
class PromotionMq extends AbstractMq
{
    const TAG_AFTER_CONSUMED = 'afterConsumed';
    const TAG_AFTER_CREATE = 'afterCreate';
    const TAG_AFTER_LOCK = 'afterLock';
    const TAG_AFTER_UNLOCK = 'afterUnlock';
    const TOPIC_NAME = 'orders';

    /**
     * 优惠券消费后触发
     *
     * @param array|object $data 待发送数据
     *
     * @return \UniondrugMq\Bases\Response
     */
    public function afterConsumed(\stdClass $data)
    {
        return $this->publish($data, static::TOPIC_NAME, static::TAG_AFTER_CONSUMED);
    }

    /**
     * 优惠券创建后触发
     *
     * @param array|object $data 待发送数据
     *
     * @return \UniondrugMq\Bases\Response
     */
    public function afterCreate(\stdClass $data)
    {
        return $this->publish($data, static::TOPIC_NAME, static::TAG_AFTER_CREATE);
    }

    /**
     * 优惠券锁定之后触发
     *
     * @param array|object $data 待发送数据
     *
     * @return \UniondrugMq\Bases\Response
     */
    public function afterLock($data)
    {
        return $this->publish($data, static::TOPIC_NAME, static::TAG_AFTER_LOCK);
    }

    /**
     * 优惠券解锁之后触发
     *
     * @param array|object $data 待发送数据
     *
     * @return \UniondrugMq\Bases\Response
     */
    public function afterUnlock($data)
    {
        return $this->publish($data, static::TOPIC_NAME, static::TAG_AFTER_UNLOCK);
    }
}
