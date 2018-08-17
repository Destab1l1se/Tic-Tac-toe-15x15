<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 14.08.2018
 * Time: 20:13
 */

namespace App;


use Ratchet\ConnectionInterface;

class PlayingPair
{
    const CROSS_TYPE = 'x';
    const CIRCLE_TYPE = 'o';

    private const WIN_MESSAGE = 'win';
    private const LOSS_MESSAGE = 'loss';
    /**
     * @var ConnectionInterface
     */
    protected $crossPlayer;
    /**
     * @var ConnectionInterface
     */
    protected $circlePlayer;

    protected $gameField = [];

    protected $validator;

    /**
     * PlayingPair constructor.
     * @param ConnectionInterface $crossPlayer
     * @param ConnectionInterface $circlePlayer
     */
    public function __construct(ConnectionInterface $crossPlayer, ConnectionInterface $circlePlayer)
    {
        $this->crossPlayer = $crossPlayer;
        $this->crossPlayer->send(self::CROSS_TYPE);
        $this->circlePlayer = $circlePlayer;
        $this->circlePlayer->send(self::CIRCLE_TYPE);

        $this->validator = new GameFieldValidator;
    }

    /**
     * @param ConnectionInterface $from
     * @param string $msg
     * @return bool
     */
    public function checkPairAndSendMessage(ConnectionInterface $from, $msg): bool
    {
        if ($this->crossPlayer->resourceId === $from->resourceId ||
            $this->circlePlayer->resourceId === $from->resourceId) {

            $this->getOpponent($from)->send($msg);

            $this->updateGameState($msg, $this->getPlayerType($from));

            if ($winner = $this->validator->validate($this->gameField))
                $this->sendFinishMessages($winner);

            return true;
        } else
            return false;
    }

    /**
     * @param ConnectionInterface $player
     * @return bool|ConnectionInterface
     */
    private function getOpponent(ConnectionInterface $player)
    {
        if ($player->resourceId === $this->circlePlayer->resourceId)
            return $this->crossPlayer;

        elseif ($player->resourceId === $this->crossPlayer->resourceId)
            return $this->circlePlayer;

        else
            return false;
    }

    /**
     * @param ConnectionInterface $player
     * @return bool|string
     */
    private function getPlayerType(ConnectionInterface $player)
    {
        if ($player->resourceId === $this->circlePlayer->resourceId)
            return self::CIRCLE_TYPE;

        elseif ($player->resourceId === $this->crossPlayer->resourceId)
            return self::CROSS_TYPE;

        else
            return false;
    }

    /**
     * @param string $msg
     * @param string $type
     */
    private function updateGameState($msg, $type)
    {
        $this->gameField[(int)$msg[0]][(int)$msg[1]] = $type;

        for ($i = 0; $i < 3; $i++) {
            for ($j = 0; $j < 3; $j++) {
                if (isset($this->gameField[$i][$j]))
                    echo $this->gameField[$i][$j];
                else
                    echo ' ';
                if ($j != 2)
                    echo '|';
            }
            echo "\n";
            if ($i != 2)
                echo "-----\n";
        }
        echo "\n";
    }

    private function sendFinishMessages($winner)
    {
        if ($winner == self::CROSS_TYPE) {
            $this->crossPlayer->send(self::WIN_MESSAGE);
            $this->circlePlayer->send(self::LOSS_MESSAGE);
        }
        elseif ($winner == self::CIRCLE_TYPE) {
            $this->circlePlayer->send(self::WIN_MESSAGE);
            $this->crossPlayer->send(self::LOSS_MESSAGE);
        }
    }
}