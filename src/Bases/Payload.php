<?php
/**
 * @author wsfuyibing <websearch@163.com>
 * @date 2018-02-05
 */
namespace UniondrugMq\Bases;

use UniondrugMq\Bases\Abstracts\AbstractMessage;

/**
 * 解析从MQ发过来的消息
 * @package UniondrugMq\Bases
 */
class Payload extends AbstractMessage
{
    /**
     * Payload constructor.
     *
     * @param array $body
     */
    public function __construct($body)
    {
        // 1. 解析入参
        if (is_string($body)) {
            $body = json_decode($body, true);
            if (!is_array($body)) {
                $body = [];
            }
        }
        $data = $this->parsePayloadBody($body);
        // 2. 消息Tag
        if (isset($data[parent::PAYLOAD_MESSAGE_TAG])) {
            $this->setId($data[parent::PAYLOAD_MESSAGE_TAG]);
        }
        // 3. 消息结构
        $message = isset($data[parent::PAYLOAD_MESSAGE_BODY]) && is_array($data[parent::PAYLOAD_MESSAGE_BODY]) ? $data[parent::PAYLOAD_MESSAGE_BODY] : [];
        $message['data'] = isset($message['data']) ? $message['data'] : [];
        if (isset($message[parent::PAYLOAD_UNIQUE_ID])) {
            $this->setId($data[parent::PAYLOAD_UNIQUE_ID]);
        }
        parent::__construct($message['data']);
    }

    /**
     * 解析入参
     *
     * @param array|object $body
     *
     * @return array
     */
    public function parsePayloadBody($body)
    {
        // 1. is array
        if (is_array($body)) {
            foreach ($body as & $value) {
                if (is_object($value)) {
                    $value = $this->parsePayloadBody($value);
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
