<?php
/**
 * @author wsfuyibing <websearch@163.com>
 * @date 2018-02-05
 */
namespace UniondrugMq\Bases;

use GuzzleHttp\Client;

/**
 * 发送消息
 * <code>
 * $body = new Body();
 * $body->setTag('afterPay');
 * $body->setTopic('order');
 * $publish = new Publish($body);
 * $publish->send()
 * </code>
 * @package UniondrugMq\Bases
 */
class Publish
{
    /**
     * @var Body
     */
    private $body;

    public function __construct(Body $body)
    {
        $this->body = $body;
    }

    /**
     * @return Response
     */
    public function send()
    {


        $message = $this->body->toMessage();
        echo $message;

        $response = new Response();
        return $response;
    }
}
