<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 16.08.2018
 * Time: 6:42
 */

namespace App;


class GameFieldValidator
{
    protected $gameField;

    private const GAME_DIMENSION = 3;
    private const WIN_CONDITION = 3;

    /**
     * @param $gameField
     * @return bool|string
     */
    public function validate($gameField)
    {

        $this->gameField = $gameField;

        for ($i = 0; $i < self::GAME_DIMENSION; $i++) {
            for ($j = 0; $j < self::GAME_DIMENSION; $j++) {

                if (isset($this->gameField[$i][$j])) {

                    if ($winner = $this->validateRightDirection($i, $j))
                        return $winner;
                    elseif ($winner = $this->validateBottomDirection($i, $j))
                        return $winner;
                    elseif ($winner = $this->validateMainDiagonal($i, $j))
                        return $winner;
                    elseif ($winner = $this->validateCollateralDiagonal($i, $j))
                        return $winner;
                }
            }
        }

        return false;
    }

    private function validateRightDirection($row, $column)
    {
        $sign = $this->gameField[$row][$column];

        for ($i = 0; $i < self::WIN_CONDITION; $i++) {

            if (!isset($this->gameField[$row][$column + $i])
                || $this->gameField[$row][$column + $i] !== $sign)
                return false;

        }
        return $sign;
    }

    private function validateBottomDirection($row, $column)
    {
        $sign = $this->gameField[$row][$column];

        for ($i = 0; $i < self::WIN_CONDITION; $i++) {

            if (!isset($this->gameField[$row + $i][$column])
                || $this->gameField[$row + $i][$column] !== $sign)
                return false;

        }
        return $sign;
    }

    private function validateMainDiagonal($row, $column)
    {
        $sign = $this->gameField[$row][$column];

        for ($i = 0; $i < self::WIN_CONDITION; $i++) {

            if (!isset($this->gameField[$row + $i][$column + $i])
                || $this->gameField[$row + $i][$column + $i] !== $sign)
                return false;

        }
        return $sign;
    }

    private function validateCollateralDiagonal($row, $column)
    {
        $sign = $this->gameField[$row][$column];

        for ($i = 0; $i < self::WIN_CONDITION; $i++) {

            if (!isset($this->gameField[$row + $i][$column - $i])
                || $this->gameField[$row + $i][$column - $i] !== $sign)
                return false;

        }
        return $sign;
    }
}