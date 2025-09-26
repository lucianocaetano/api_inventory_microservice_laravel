<?php

namespace Src\shared\infrastructure\impl\out;

use RdKafka\Producer;
use RdKafka\ProducerTopic;
use Src\shared\application\contracts\out\EventPublisher;

class KafkaEventPublisher implements EventPublisher
{

    private array $config = require_once('config/kafka.php');
    private Producer $producer;
    private ProducerTopic $topic;

    public function __construct()
    {
        $config = new \RdKafka\Conf();

        $config->set('bootstrap.servers', $this->config['bootstrap.servers']);
        $this->producer = new Producer($config);
        $this->topic = $this->producer->newTopic($this->config['topic']);
    }

    public function publish(string $eventName, array $payload): void
    {
        $this->topic->produce(-1, 0, json_encode([
            'event' => $eventName,
            'payload' => $payload
        ]));

        $this->producer->flush(10000);
    }
}
