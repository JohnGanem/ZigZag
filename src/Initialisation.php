<?php

namespace ZigZag;

use Discord\Discord;
use Discord\Parts\User\Activity;
use Discord\WebSockets\Event;
use ZigZag\Actions\Recruitment;
use ZigZag\Actions\TransferMessages;

class Initialisation
{

    private $botId;
    private $discord;
    private $activityType;
    private $activityName;
    private $checkRecruitment;
    private $transferMessages;

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
        $this->checkRecruitment = $_ENV['CHECK_RECRUITMENT'];
        $this->transferMessages = $_ENV['TRANSFER_MESSAGES'];
    }

    public function __invoke()
    {
        $this->discord->on('ready', function ($discord) {

            $this->updatePresence();

            if ($this->checkRecruitment) {
                $this->discord->on(Event::MESSAGE_CREATE, function ($message, $discord) {
                    if (in_array($message->content, Recruitment::WORD_DETECTION)) {
                        Recruitment::actions($discord, $message);
                    }
                });
            }

            if ($this->transferMessages) {
                $this->discord->on(Event::MESSAGE_CREATE, function ($message, $discord) {
                    if ($message->author->id != $this->botId) {
                        TransferMessages::actions($message);
                    }
                });
            }
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
