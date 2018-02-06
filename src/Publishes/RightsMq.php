<?php
/**
 * @author wsfuyibing <websearch@163.com>
 * @date 2018-02-05
 */
namespace UniondrugMq\Publishes;

use UniondrugMq\Publishes\Abstracts\AbstractMq;

/**
 * 权益MQ
 * @package UniondrugMq\Mqs
 */
class RightsMq extends AbstractMq
{
    const TAG_AFTER_ACTIVED = 'rightsActived';      // 权益激活成功
    const TAG_AFTER_CONSUMED = 'rightsConsumed';    // 权益完成消费
    const TAG_AFTER_CREATED = 'rightsCreated';      // 权益创建完成
    const TAG_AFTER_LOCKED = 'rightsLocked';        // 权益被锁定了
    const TAG_AFTER_UNLOCK = 'rightsUnlock';        // 权益被解锁了
    const TOPIC_NAME = 'orders';                    // TOP名称

    /**
     * 权益消费后触发
     *
     * @param array|object $data 待发送数据
     *
     * @return \UniondrugMq\Bases\Response
     */
    public function afterActived($data)
    {
        return $this->publish($data, static::TOPIC_NAME, static::TAG_AFTER_ACTIVED);
    }

    /**
     * 权益消费后触发
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
     * 权益创建后触发
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
     * 权益锁定之后触发
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
     * 权益解锁之后触发
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
