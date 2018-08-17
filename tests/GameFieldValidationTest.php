<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 17.08.2018
 * Time: 19:25
 */

class GameFieldValidationTest extends \PHPUnit\Framework\TestCase
{
    /** @test */
    public function that_user_can_win_horizontally()
    {
        $field = [['x', 'x', 'x'], ['o']];
        $validator = new \App\GameFieldValidator();

        $result = $validator->validate($field);

        $this->assertEquals('x', $result);
    }

    /** @test */
    public function that_user_can_win_vertically()
    {
        $field = [['o', 'x', 'x'], ['o', 'x'], ['o']];

        $validator = new \App\GameFieldValidator();

        $result = $validator->validate($field);

        $this->assertEquals('o', $result);
    }

    /** @test */
    public function that_user_can_win_main_diagonal()
    {
        $field = [['x','o','x'],['o','x','o'],['o','o','x']];

        $validator = new \App\GameFieldValidator();

        $result = $validator->validate($field);

        $this->assertEquals('x', $result);
    }

    /** @test */
    public function that_user_can_win_collateral_diagonal()
    {
        $field = [['o','o','x'],['o','x','o'],['x','o','o']];

        $validator = new \App\GameFieldValidator();

        $result = $validator->validate($field);

        $this->assertEquals('x', $result);
    }

    /** @test */
    public function that_match_can_finish_in_a_draw()
    {
        $field = [['o','o','x'],['x','x','o'],['o','x','o']];

        $validator = new \App\GameFieldValidator();

        $result = $validator->validate($field);

        $this->assertEquals(false, $result);
    }
}