<?php
/**
 * @author wsfuyibing <websearch@163.com>
 * @date 2018-02-05
 */
namespace UniondrugMq\Bases;

use GuzzleHttp\Client;
use Phalcon\Config;
use Phalcon\Di\Injectable;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use UniondrugMq\Bases\Abstracts\AbstractMessage;

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
class Publish extends Injectable
{
    /**
     * @var Body
     */
    private $body;
    private $sendBegin = 0;
    private $sendResult = null;
    private static $publishConfig = null;
    private static $publishDefaultHost = 'http://localhost';
    private static $publishDefaultPath = '/message/queue';
    private static $publishLoggerPath = 'mq';

    public function __construct(Body $body)
    {
        $this->body = $body;
    }

    /**
     * 读取配置信息
     */
    public function getConfig()
    {
        // 1. from static history
        if (self::$publishConfig instanceof Config) {
            return self::$publishConfig;
        }
        // 2. read file
        $config = $this->di->getConfig()->path('mq');
        if (!($config instanceof Config)) {
            $config = new Config();
        }
        // 3. default settings
        if (!isset($config->host)) {
            $config->host = static::$publishDefaultHost;
        }
        if (!isset($config->path)) {
            $config->path = static::$publishDefaultPath;
        }
        self::$publishConfig = $config;
        return $config;
    }

    /**
     * @return Response
     */
    public function send()
    {
        // 1. init and get configuration
        $config = $this->getConfig();
        $options = [];
        $response = new Response();
        // 2. prepare http options
        $params = [];
        $params[AbstractMessage::PUBLISH_TOPIC_NAME] = $this->body->getTopic();
        $params[AbstractMessage::PUBLISH_TAG_NAME] = $this->body->getTag();
        $params[AbstractMessage::PUBLISH_BODY_NAME] = $this->body->toJson();
        $options['form_params'] = $params;
        // 3. send http request with POST method
        $this->beforeSend();
        try {
            $url = $config->host.$config->path;
            $http = new Client();
            $send = $http->post($url, $options);
            // 3.1 response type error
            if (!($send instanceof ResponseInterface)) {
                throw new \Exception("invalid http response", 400);
            }
            // 3.2 get content
            $stream = $send->getBody();
            if (!($stream instanceof StreamInterface)) {
                throw new \Exception("invalid http stream", 400);
            }
            // 3.3 parse content
            $this->sendResult = $stream->getContents();
            $responseData = \GuzzleHttp\json_decode($this->sendResult, true);
            $responseData['errno'] = isset($responseData['errno']) ? (int) $responseData['errno'] : 0;
            if ($responseData['errno'] !== 0) {
                $responseData['error'] = isset($responseData['error']) ? (string) $responseData['error'] : '';
                throw new \Exception($responseData['error'], $responseData['errno']);
            }
            // 3.4 success
            $responseData['data'] = isset($responseData['data']) && is_array($responseData['data']) ? $responseData['data'] : [];
            $responseData['data']['messageId'] = isset($responseData['data']['messageId']) ? $responseData['data']['messageId'] : 'no-message-id';
            $responseData['data']['messageMD5'] = isset($responseData['data']['messageMD5']) ? $responseData['data']['messageMD5'] : 'no-message-md5';
            $response->setId($this->body->getId());
            $response->setMessageId($responseData['data']['messageId']);
            $response->setMessageMd5($responseData['data']['messageMD5']);
        } catch(\Throwable $e) {
            $response->setError($e->getCode(), $e->getMessage());
        }
        // 4. 返回结果
        $this->afterSend($response);
        return $response;
    }

    /**
     * 发送之后写入日志
     *
     * @param Response $response
     */
    private function afterSend($response)
    {
        $message = '用时【'.sprintf('%.03f', (microtime(true) - $this->sendBegin)).'】秒 - ';
        $message .= '发送【'.$this->body->getId().'】号消息, 到【'.$this->body->getTopic().'://'.$this->body->getTag().'】主题 - ';
        $message .= '内容为【'.$this->body->toJson().'】 - ';
        $message .= '服务器返回【'.$this->sendResult.'】';
        // 写入日志
        $response->hasError() ? $this->di->getLogger(static::$publishLoggerPath)->error($message) : $this->di->getLogger(static::$publishLoggerPath)->info($message);
    }

    private function beforeSend()
    {
        $this->sendBegin = microtime(true);
    }
}
