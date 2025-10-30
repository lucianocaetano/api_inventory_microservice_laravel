<?php

namespace Src\shared\infrastructure\impl\out;

use RdKafka\Producer;
use RdKafka\ProducerTopic;
use Src\shared\application\contracts\out\EventPublisher;

class KafkaEventPublisher implements EventPublisher
{

    private array $config;
    private Producer $producer;
    private ProducerTopic $topic;

    public function __construct(
        array $config
    )
    {
        $this->config = $config;

        $config_kafka = new \RdKafka\Conf();

        $securityProtocol = $this->config['security.protocol'];
        $saslMechanism   = $this->config['sasl.mechanisms'];
        $saslUsername    = $this->config['sasl.username'];
        $saslPassword    = $this->config['sasl.password'];

        if ($securityProtocol && $saslMechanism && $saslUsername && $saslPassword) {
            $config_kafka->set('security.protocol', $securityProtocol);
            $config_kafka->set('sasl.mechanisms', $saslMechanism);
            $config_kafka->set('sasl.username', $saslUsername);
            $config_kafka->set('sasl.password', $saslPassword);
        }

        $config_kafka->set('bootstrap.servers', $this->config['bootstrap.servers']);
        $this->producer = new Producer($config_kafka);
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
