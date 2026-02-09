<?php

namespace ZigZag\Actions;

class TransferMessages
{
    public static function actions($message)
    {
        if($_ENV['NEED_AUTHOR_ID'] && $_ENV['NEED_AUTHOR_ID'] != $message->author->id) {
            return;
        }

        $query = ["message" => $message->content];

        $ch = curl_init($_ENV['WEBHOOK_URL']);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_exec($ch);
    }
}
