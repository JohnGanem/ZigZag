<?php

namespace ZigZag\Actions;

class Recruitment
{
    public const WORD_DETECTION = [
        "!recrutement"
    ];

    public static function actions($message)
    {
        echo "message received from: " . $message->author->username . $message->author->id . ' msg id:' . $message->id . ' msg:' . $message->content . ' channel id:' . $message->channel_id, PHP_EOL;
    }
}
