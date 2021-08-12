<?php

namespace ZigZag\Actions;

use Discord\Parts\Channel\Channel;
use Discord\Parts\Permissions\ChannelPermission;

class Recruitment
{
    public const WORD_DETECTION = [
        "!recrutement"
    ];

    public static function actions($discord, $message)
    {
        $member = $message->author;
        $nickname = strtolower(str_replace(' ', '-', $member->nick));
        $guildId = $message->channel->guild_id;
        $guild = $discord->guilds->get('id', $guildId);
        // $guild->createRole([
        //     'name' => "recrutement-{$nickname}"
        // ]);
        $channel = $guild->channels->create([
            'name' => "recrutement-{$nickname}",
            'type' => 0,
            'parent_id' => $_ENV['RECRUITMENT_CHANNEL_ID'],
            'is_private' => true
        ]);
        $guild->channels->save($channel)->done(function ($channel) use ($member) {
            $channel->setPermissions($member, [
                'send_messages',
                'view_channel',
                'read_message_history'
            ]);
            $channel->sendMessage("Hello {$member}\n".
            "Peux-tu te présenter en quelques points :\n".
            "1) Comment as-tu connu la corporation et pourquoi souhaites-tu la rejoindre ?\n".
            "2) Comment a été ton expérience de Eve jusqu'à maintenant ? Quel est ton objectif en jeu actuellement ? As-tu déjà rejoint d'autres corporations ? Pourquoi les as-tu rejoint et les as-tu quitté ? Joues-tu à d'autres jeux à part Eve ?\n".
            "3) As-tu déjà PvP auparavant ? Quels sont les ships que tu aimes piloter ?\n".
            "4) Quels sont tes horaires de jeu ? A quelles heures es-tu le plus souvent connecté ?\n".
            "5) Comment te décris-tu ? Quel est ton caractère ?\n");
        });
    }
}
