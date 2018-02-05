# mq


### Producer

*1. 增加入口*

```php
class OrderService ... 
{
    public function setStatusAsAudit(...)
    {
        ...
        ...
        $order = Order::findFirst("orderNo = '123456'"); 
        ...
        ...

        // 订单审核完成之后
        // 调用MQ发送afterAudit消息
        // 需要对该消息进行后续处理子业务系统开始操作
        $mq = new OrderMq();
        $mq->afterAudit($order);
    }
}
```


### Consumer

*1. 获取消息*

```
$message = $this->request->getRawbody();
```

*2. 获取结构*

```php

$struct = Consumer::order($message);

```

