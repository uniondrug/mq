<?php
/**
 * @author wsfuyibing <websearch@163.com>
 * @date 2018-02-05
 */
namespace UniondrugMq\Publishes;

use UniondrugMq\Publishes\Abstracts\AbstractMq;

/**
 * 订单MQ
 * @package UniondrugMq\Mqs
 */
class OrderMq extends AbstractMq
{
    const TAG_AFTER_AUDIT = 'orderAudit';
    const TAG_AFTER_AUDIT_FAILURE = 'orderAuditFail';
    const TAG_AFTER_CREATED = 'orderCreated';
    const TAG_AFTER_CANCELLED = 'orderCancelled';
    const TAG_AFTER_COMPLETED = 'orderCompleted';
    const TOPIC_NAME = 'orders';

    /**
     * 订单审核完成之后业务
     *
     * @param array|object $data
     *
     * @return \UniondrugMq\Bases\Response
     */
    public function afterAudit($data)
    {
        return $this->publish($data, static::TOPIC_NAME, static::TAG_AFTER_AUDIT);
    }

    /**
     * 订单审核失败之后业务
     *
     * @param array|object $data
     *
     * @return \UniondrugMq\Bases\Response
     */
    public function afterAuditFailure($data)
    {
        return $this->publish($data, static::TOPIC_NAME, static::TAG_AFTER_AUDIT_FAILURE);
    }

    /**
     * 订单创建之后业务
     *
     * @param array|object $data
     *
     * @return \UniondrugMq\Bases\Response
     */
    public function afterCreated($data)
    {
        return $this->publish($data, static::TOPIC_NAME, static::TAG_AFTER_CREATED);
    }

    /**
     * 订单取消之后
     *
     * @param array|object $data
     *
     * @return \UniondrugMq\Bases\Response
     */
    public function afterCancelled($data)
    {
        return $this->publish($data, static::TOPIC_NAME, static::TAG_AFTER_CANCELLED);
    }

    /**
     * 订单完成(支付成功)之后业务
     *
     * @param array|object $data
     *
     * @return \UniondrugMq\Bases\Response
     */
    public function afterCompleted($data)
    {
        return $this->publish($data, static::TOPIC_NAME, static::TAG_AFTER_COMPLETED);
    }
}
