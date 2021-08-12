<?php

namespace ZigZag;

use Discord\Discord;
use Discord\Parts\User\Activity;
use Discord\WebSockets\Event;
use ZigZag\Actions\Recruitment;

class Initialisation
{

    private $botId;
    private $discord;
    private $activityType;
    private $activityName;

    public function __construct()
    {
        $this->botId = $_ENV['BOT_ID'];
        $this->discord = new Discord([
            'token' => $_ENV['BOT_TOKEN'],
            'socket_options' => [
                'dns' => '1.1.1.2', // can change dns
            ],

        ]);
        $this->activityType = $_ENV['ACTIVITY_TYPE'];
        $this->activityName = $_ENV['ACTIVITY_NAME'];
    }

    public function __invoke()
    {
        $this->discord->on('ready', function ($discord) {

            $this->updatePresence();

            $this->discord->on(Event::MESSAGE_CREATE, function ($message, $discord) {
                if (in_array($message->content, Recruitment::WORD_DETECTION)) {
                    Recruitment::actions($discord, $message);
                }
            });
        });

        $this->discord->run();
    }

    private function updatePresence()
    {
        $activity = $this->discord->factory(Activity::class, [
            'type' => (int) $this->activityType,
            'name' => $this->activityName
        ]);
        $this->discord->updatePresence($activity);
    }
}
