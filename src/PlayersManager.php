<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 14.08.2018
 * Time: 19:46
 */

namespace App;


use Ratchet\ConnectionInterface;

class PlayersManager
{
    // first (waiting) player always plays as 'x', second - as 'o'
    protected $waitingPlayer;

    protected $playingPairs = [];

    public function addPlayer(ConnectionInterface $conn)
    {
        // there is a waiting player
        if ($this->waitingPlayer === null) {
            $this->waitingPlayer = $conn;
            echo "Player is waiting...\n";
        } // no waiting player
        else {
            $this->playingPairs[] = new PlayingPair($this->waitingPlayer, $conn);
            $this->waitingPlayer = null;
            echo "Pair of players was created!\n";
        }
    }

    public function sendData(ConnectionInterface $from, $msg)
    {
        foreach($this->playingPairs as $playingPair) {
            if ( $playingPair->checkPairAndSendMessage($from, $msg) )
                break;
        }
    }
}