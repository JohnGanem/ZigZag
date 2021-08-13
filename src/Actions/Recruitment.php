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
        $name = ($member->nick == "") ? $member->username : $member->nick;

        $name =  strtolower(str_replace(' ', '-', $name));

        print("Nouveau recrutement : {$name}\n");

        $guildId = $message->channel->guild_id;
        $guild = $discord->guilds->get('id', $guildId);

        $channel = $guild->channels->create([
            'name' => "recrutement-{$name}",
            'type' => 0,
            'parent_id' => $_ENV['RECRUITMENT_CATEGORIE_ID'],
            'is_private' => true
        ]);

        $guild->channels->save($channel)->done(function ($channel) use ($member, $guild) {
            $channel->setPermissions($member, [
                'send_messages',
                'view_channel',
                'read_message_history'
            ]);
            $channel->sendMessage("Hello {$member}\n" .
                "Peux-tu te présenter en quelques points :\n" .
                "1) Comment as-tu connu la corporation et pourquoi souhaites-tu la rejoindre ?\n" .
                "2) Comment a été ton expérience de Eve jusqu'à maintenant ? Quel est ton objectif en jeu actuellement ? As-tu déjà rejoint d'autres corporations ? Pourquoi les as-tu rejoint et les as-tu quitté ? Joues-tu à d'autres jeux à part Eve ?\n" .
                "3) As-tu déjà PvP auparavant ? Quels sont les ships que tu aimes piloter ?\n" .
                "4) Quels sont tes horaires de jeu ? A quelles heures es-tu le plus souvent connecté ?\n" .
                "5) Comment te décris-tu ? Quel est ton caractère ?\n");
            $guild->channels->fetch($_ENV['NOTIFICATIONS_CHANNEL_ID'])->done(function ($notifChannel) use ($channel) {
                $notifChannel->sendMessage("@everyone Une nouvelle recrue est arrivée, n'hésitez pas à intervenir ! <#{$channel->id}>");
            });
        });

        $message->delete();
    }
}
