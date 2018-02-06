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
    const TAG_AFTER_CONSUMED = 'promoteConsumed';   // 优惠券完成消费
    const TAG_AFTER_CREATED = 'promoteCreated';     // 优惠券创建完成
    const TAG_AFTER_LOCKED = 'promoteLocked';       // 优惠券已被锁定
    const TAG_AFTER_UNLOCK = 'promoteUnlock';       // 优惠券解除锁定
    const TOPIC_NAME = 'orders';

    /**
     * 优惠券消费后触发
     *
     * @param array|object $data 待发送数据
     *
     * @return \UniondrugMq\Bases\Response
     */
    public function afterConsumed($data)
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
    public function afterCreated($data)
    {
        return $this->publish($data, static::TOPIC_NAME, static::TAG_AFTER_CREATED);
    }

    /**
     * 优惠券锁定之后触发
     *
     * @param array|object $data 待发送数据
     *
     * @return \UniondrugMq\Bases\Response
     */
    public function afterLocked($data)
    {
        return $this->publish($data, static::TOPIC_NAME, static::TAG_AFTER_LOCKED);
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
