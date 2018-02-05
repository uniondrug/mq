<?php
/**
 * @author wsfuyibing <websearch@163.com>
 * @date 2018-02-05
 */
namespace UniondrugMq\Bases\Abstracts;

/**
 * 消息抽象
 * @property string $uuid
 * @package UniondrugMq\Bases\Abstracts
 */
abstract class AbstractMessage
{
    const PAYLOAD_MESSAGE_ID = 'messageId';
    const PAYLOAD_MESSAGE_BODY = 'messageBody';
    const PAYLOAD_MESSAGE_TAG = 'topicMessageTag';
    const PAYLOAD_UNIQUE_ID = 'uuid';
    const PUBLISH_TAG_NAME = 'filterTag';
    const PUBLISH_TOPIC_NAME = 'topicName';
    const PUBLISH_BODY_NAME = 'messageBody';
    private $topic = '';
    private $tag = '';
    private $delay = 0;
    private $results = [];

    /**
     * AbstractMessage constructor.
     *
     * @param array $data
     */
    public function __construct($data)
    {
        $this->results = $data;
    }

    /**
     * 读取延迟时长
     * @return int
     */
    public function getDelay()
    {
        return $this->delay;
    }

    /**
     * 读取消息唯一ID
     * @return mixed|string
     */
    public function getId()
    {
        if (isset($this->results[static::PAYLOAD_UNIQUE_ID])) {
            return $this->results[static::PAYLOAD_UNIQUE_ID];
        }
        return '';
    }

    /**
     * 读取Tag名称
     * @return string
     */
    public function getTag()
    {
        return $this->tag;
    }

    /**
     * 读取Topic名称
     * @return string
     */
    public function getTopic()
    {
        return $this->topic;
    }

    /**
     * 设置延迟时长
     *
     * @param int $delay
     *
     * @return $this
     * @example $this->setDelay(900)
     */
    public function setDelay($delay)
    {
        $this->delay = $delay;
        return $this;
    }

    /**
     * 设置内容ID
     *
     * @param null|string $id
     *
     * @return $this
     */
    public function setId($id = null)
    {
        if ($id === null) {
            $id = 'mq-'.str_replace('.', '-', microtime(true)).'-'.mt_rand(10000001, 99999999);
        }
        $this->results[static::PAYLOAD_UNIQUE_ID] = $id;
        return $this;
    }

    /**
     * 设置标签名
     *
     * @param string $tag
     *
     * @return $this
     * @example $this->setTag('afterLock')
     */
    public function setTag($tag)
    {
        $this->tag = $tag;
        return $this;
    }

    /**
     * 设置Topic名称
     *
     * @param string $topic
     *
     * @return $this
     * @example $this->setTopic('example')
     */
    public function setTopic($topic)
    {
        $this->topic = $topic;
        return $this;
    }

    /**
     * 转为数组
     * @return array
     */
    public function toArray()
    {
        return $this->results;
    }
}
