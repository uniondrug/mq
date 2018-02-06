# mq

> MQ( `Message Queue` ), 即消息队列；模块/服务/业务之间异常通信的中间件。

```php
class ExampleController ... 
{
    public function indexAction()
    {
        $order = OrderStruct::init(...);
        $mq = new Mq();
        $mq->order->afterAudit($order);
    }
}
```


### Producter

* **OrderMq**
    * `afterAudit`(`$data`) - 订单审核完成之后
    * `afterAuditFailure`(`$data`) - 订单审核失败之后
    * `afterCreated`(`$data`) - 订单创建完成之后
    * `afterCancelled`(`$data`) - 订单被取消之后
    * `afterCompleted`(`$data`) - 订单支付完成之后
* **PromotionMq**
    * `afterConsumed`(`$data`) - 优惠券完成消费之后
    * `afterCreated`(`$data`) - 优惠券创建之后
    * `afterLocked`(`$data`) - 优惠券锁定之后
    * `afterUnlock`(`$data`) - 优惠券解锁之后
* **RightsMq**
    * `afterActived`(`$data`) - 权益激活之后
    * `afterConsumed`(`$data`) - 权益消费完成之后
    * `afterCreated`(`$data`) - 权益创建完成之后
    * `afterLocked`(`$data`) - 权益被锁定之后
    * `afterUnlock`(`$data`) - 权益解除锁定之后



### Consumer

> MQ消费方, 使用`getPayloadBody()`方法获取数据，获取到的数据有以下二种可能的来源

1. 自MQ服务器转过的异步POST请求。
2. 来自RestfulAPI的Request请求。

```php
class ExampleController ... 
{
    public function testAction()
    {
        // 提取数据, 以下二种来源
        // 1. 来自MQ
        // 2. API请求
        $payload = $this->getPayloadBody();
        
        // 打印结果如下:
        // {
        //     "key" => "value"
        // }
        print_r (json_encode($payload, true));
    }
}
```