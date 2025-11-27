<?php

namespace App\Services;

use PhpMqtt\Client\MqttClient;
use PhpMqtt\Client\ConnectionSettings;

class MqttService
{
    public function publish($topic, $payload)
    {
        $mqtt = new MqttClient(
            config('mqtt.host'),
            config('mqtt.port'),
            config('mqtt.client_id')
        );

        $settings = (new ConnectionSettings)
            ->setUsername(config('mqtt.username'))
            ->setPassword(config('mqtt.password'))
            ->setKeepAliveInterval(60)
            ->setUseTls(config('mqtt.use_tls', false)); // ✅ Tambahkan ini untuk SSL

        $mqtt->connect($settings, true);
        $mqtt->publish($topic, json_encode($payload), config('mqtt.qos', 1));
        $mqtt->disconnect();
    }
}
