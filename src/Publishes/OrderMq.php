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
    const TOPIC_NAME = 'orders';
}
