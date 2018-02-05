<?php
/**
 * @author wsfuyibing <websearch@163.com>
 * @date 2018-02-05
 */
namespace UniondrugMq\Publishes\Abstracts;

use UniondrugMq\Bases\Body;
use UniondrugMq\Bases\Publish;
use UniondrugMq\Bases\Response;

/**
 * MQ模块基类
 * @package UniondrugMq\Mqs\Abstracts
 */
abstract class AbstractMq extends \stdClass
{
    /**
     * 发送消息
     *
     * @param array|object $data 待发送数据
     * @param string       $topic Top名称
     * @param string       $tag Tag名称
     * @param int          $delaySeconds 延迟时长
     *
     * @return Response
     */
    protected function publish($data, $topic, $tag, $delaySeconds = 0)
    {
        $body = new Body($data);
        $body->setTag($tag);
        $body->setTopic($topic);
        $body->setDelay($delaySeconds);
        return (new Publish($body))->send();
    }
}
