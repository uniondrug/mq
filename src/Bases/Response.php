<?php
/**
 * @author wsfuyibing <websearch@163.com>
 * @date 2018-02-05
 */
namespace UniondrugMq\Bases;

/**
 * 消息发送结果
 * @package UniondrugMq\Bases
 */
class Response
{
    private $results = [
        'errno' => 0,
        'error' => '',
        'uuid' => '',
        'messageId' => '',
        'messageMd5' => ''
    ];

    public function getErrno()
    {
        return $this->results['errno'];
    }

    public function getError()
    {
        return $this->results['error'];
    }

    public function hasError()
    {
        return $this->results['errno'] !== 0;
    }

    public function setMessageId($id)
    {
        $this->results['messageId'] = $id;
    }

    public function setMessageMd5($md5)
    {
        $this->results['messageMd5'] = $md5;
    }

    public function setId($uuid)
    {
        $this->results['uuid'] = $uuid;
    }

    public function setError($errno, $error)
    {
        $this->results['errno'] = $errno;
        $this->results['error'] = $error;
    }

    public function toArray()
    {
        return $this->results;
    }
}
