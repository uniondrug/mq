<?php
/**
 * @author wsfuyibing <websearch@163.com>
 * @date 2018-02-05
 */
namespace UniondrugMq;

use Phalcon\Di\Injectable;
use UniondrugMq\Bases\Abstracts\AbstractMessage;

/**
 * 接收来自MQ发起的请求
 * <code>
 * $mqr = MqRequest::init();
 * $logic = new ExampleLogic();
 * if ($mqr->is()){
 *     $logic->run($mqr->payload);
 * } else {
 *     $logic->run($this->getJsonRawBody());
 * }
 * </code>
 * @property int       $errno 错误编号
 * @property string    $error 错误原因
 * @property string    $uuid 系统UUID
 * @property string    $contents 消息原文
 * @property string    $messageId 消息ID
 * @property string    $filterTag 来自Tag
 * @property string    $topicName 来自Topic
 * @property \stdClass $payload 消息原文
 * @package UniondrugMq
 */
class MqRequest extends Injectable
{
    private $results = [
        'errno' => 0,
        'error' => '',
        'uuid' => '',
        'contents' => '',
        'messageId' => '',
        'filterTag' => '',
        'topicName' => '',
        'payload' => null
    ];
    private static $requestLoggerPath = 'mqr';

    public function __construct()
    {
        $this->results['payload'] = new \stdClass();
    }

    public function __get($name)
    {
        if (isset($this->results[$name])) {
            return $this->results[$name];
        }
        return null;
    }

    public function is()
    {
        return $this->results['errno'] === 0;
    }

    /**
     * @param string $key
     * @param mixed  $value
     *
     * @return $this
     */
    public function set($key, $value)
    {
        $this->results[$key] = $value;
        return $this;
    }

    /**
     * 检查是否为MQ异步发起的请求
     */
    public static function init()
    {
        $request = new MqRequest();
        // 1. 基础验证
        if (!static::initMethod($request) || !static::initVerify($request)) {
            static::afterInited($request);
            return $request;
        }
        // 2. 正文处理
        static::initMessage($request);
        static::afterInited($request);
        return $request;
    }

    /**
     * 初始化完成之后
     *
     * @param MqRequest $request
     */
    private static function afterInited(MqRequest $request)
    {
        // 1. 无MessageId
        if ($request->messageId === '') {
            return;
        }
        // 2. 写日志
        $message = '收到【'.$request->uuid.'】号消息, 从【'.$request->topicName.'://'.$request->filterTag.'】主题 - ';
        $message .= '内容为【'.$request->contents.'】';
        $request->getDI()->getLogger(static::$requestLoggerPath)->info($message);
    }

    /**
     * 字段处理
     *
     * @param MqRequest $request
     * @param           $messages
     */
    private static function initFields(MqRequest $request, & $messages)
    {
        $options = [
            'MessageId' => 'messageId',
            'MessageTag' => 'filterTag',
            'TopicName' => 'topicName'
        ];
        foreach ($options as $key => $field) {
            if (isset($messages[$key])) {
                $request->set($field, $messages[$key]);
            }
        }
        if (isset($messages['Message'])) {
            $request->set('contents', $messages['Message']);
            $payload = json_decode($messages['Message'], false);
            if ($payload instanceof \stdClass) {
                $request->set('payload', $payload);
                $uuid = AbstractMessage::PAYLOAD_UNIQUE_ID;
                if (isset($payload->$uuid)) {
                    $request->set('uuid', $payload->$uuid);
                    unset($payload->$uuid);
                }
            }
        }
    }

    /**
     * 正文处理
     *
     * @param MqRequest $request
     *
     * @return bool
     */
    private static function initMessage(MqRequest $request)
    {
        // 1. 消息正文
        $message = json_decode($_POST['messageBody'], true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $request->set('errno', 3);
            $request->set('error', 'MQ消息正文错误');
            return false;
        }
        // 2. 消息结构
        static::initFields($request, $message);
        return true;
    }

    /**
     * 限POST请求
     *
     * @param MqRequest $request
     *
     * @return bool
     */
    private static function initMethod(MqRequest $request)
    {
        if (!isset($_SERVER['REQUEST_METHOD']) || strtoupper($_SERVER['REQUEST_METHOD']) !== 'POST') {
            $request->set('errno', 1);
            $request->set('error', 'MQ限POST请求');
            return false;
        }
        return true;
    }

    /**
     * 必须字段与MD5
     *
     * @param MqRequest $request
     *
     * @return bool
     */
    private static function initVerify(MqRequest $request)
    {
        if (isset($_POST['messageBody']) && $_POST['messageBodyMD5'] && strtolower($_POST['messageBodyMD5']) === md5($_POST['messageBody'])) {
            return true;
        }
        $request->set('errno', 2);
        $request->set('error', '消息正文可能被修改了');
        return false;
    }
}
