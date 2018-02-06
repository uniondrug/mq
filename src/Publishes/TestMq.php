<?php
/**
 * @author wsfuyibing <websearch@163.com>
 * @date 2018-02-05
 */
namespace UniondrugMq\Publishes;

use UniondrugMq\Publishes\Abstracts\AbstractMq;

/**
 * 测试MQ
 * @package UniondrugMq\Mqs
 */
class TestMq extends AbstractMq
{
    const TOPIC_NAME = 'orders';

    /**
     * @param $data
     *
     * @return \UniondrugMq\Bases\Response
     */
    public function queue($data){
        return $this->publish($data, static::TOPIC_NAME, 'queue');
    }
}
