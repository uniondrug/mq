<?php
/**
 * @author wsfuyibing <websearch@163.com>
 * @date 2018-02-05
 */
namespace UniondrugMq\Bases;

use UniondrugMq\Bases\Abstracts\AbstractMessage;

/**
 * 发送消息到MQ服务器
 * @package UniondrugMq\Bases
 */
class Body extends AbstractMessage
{
    /**
     * Body constructor.
     *
     * @param array|object $body 待发送的数据
     */
    public function __construct($body)
    {
        $data = $this->parseBody($body);
        parent::__construct($data);
        $this->setId();
    }

    /**
     * 转为Publish消息内容
     * @return string
     */
    public function toMessage()
    {
        $result = [];
        $result[parent::PUBLISH_TAG_NAME] = $this->getTag();
        $result[parent::PUBLISH_TOPIC_NAME] = $this->getTopic();
        $result[parent::PUBLISH_BODY_NAME] = $this->toArray();
        return json_encode($result, true);
    }

    /**
     * 解析入参
     *
     * @param array|object $body
     *
     * @return array
     */
    private function parseBody($body)
    {
        // 1. is array
        if (is_array($body)) {
            foreach ($body as & $value) {
                if (is_object($value)) {
                    $value = $this->parseBody($value);
                }
            }
            return $body;
        }
        // 2. is object
        if (is_object($body)) {
            if (method_exists($body, 'toArray')) {
                return $body->toArray();
            }
            return json_decode(json_encode($body, true), true);
        }
        // 3. other
        return $body;
    }
}
