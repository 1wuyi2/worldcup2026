<?php

/**
 * 模块 C 对阵、比分与进球一致性测试。
 * @author lzs
 * @date 2026-11-20
 */

namespace common\tests\unit\models;

use Codeception\Test\Unit;
use common\models\FootballMatch;
use common\models\FootballMatchGoal;
use common\models\FootballTeam;
use Yii;

class FootballMatchTest extends Unit
{
    private $match;
    private $thirdTeam;
    private $transaction;

    protected function _before()
    {
        $this->transaction = Yii::$app->db->beginTransaction();
        $home = $this->saveTeam();
        $away = $this->saveTeam();
        $this->thirdTeam = $this->saveTeam();
        $this->match = new FootballMatch([
            'home_team_id' => $home->id,
            'away_team_id' => $away->id,
            'stage' => '小组赛',
            'match_time' => '2026-06-15 23:30:00',
        ]);
        $this->assertTrue($this->match->save(), json_encode($this->match->errors));
    }

    protected function _after()
    {
        if ($this->transaction !== null && $this->transaction->isActive) {
            $this->transaction->rollBack();
        }
    }

    public function testShowsUnknownScoreAndConvertsUtc()
    {
        $this->assertSame('暂无比分', $this->match->scoreLabel);
        $this->assertSame('2026-06-16 07:30', $this->match->shanghaiKickoff);
        $this->match->home_score = 0;
        $this->match->away_score = 0;
        $this->assertTrue($this->match->save());
        $this->assertSame('0 - 0', $this->match->scoreLabel);
    }

    public function testRejectsSameTeamAndNegativeScore()
    {
        $this->match->away_team_id = $this->match->home_team_id;
        $this->match->home_score = -1;
        $this->assertFalse($this->match->validate());
        $this->assertTrue($this->match->hasErrors('away_team_id'));
        $this->assertTrue($this->match->hasErrors('home_score'));
    }

    public function testRejectsGoalForThirdTeamAndProtectsExistingGoalOnEdit()
    {
        $goal = new FootballMatchGoal([
            'match_id' => $this->match->id,
            'team_id' => $this->thirdTeam->id,
            'scorer_name' => '示例球员',
            'minute_text' => '90+3',
        ]);
        $this->assertFalse($goal->save());
        $this->assertTrue($goal->hasErrors('team_id'));

        $goal->team_id = $this->match->away_team_id;
        $this->assertTrue($goal->save(), json_encode($goal->errors));
        $this->match->away_team_id = $this->thirdTeam->id;
        $this->assertFalse($this->match->save());
        $this->assertTrue($this->match->hasErrors('away_team_id'));
    }

    public function testOrdersGoalsWithStoppageTime()
    {
        foreach (['90+10', '90+3', '7', null] as $minute) {
            $goal = new FootballMatchGoal([
                'match_id' => $this->match->id,
                'team_id' => $this->match->home_team_id,
                'scorer_name' => '示例球员',
                'minute_text' => $minute,
            ]);
            $this->assertTrue($goal->save(), json_encode($goal->errors));
        }

        $minutes = array_map(static function (FootballMatchGoal $goal) {
            return $goal->minute_text;
        }, $this->match->orderedGoals);
        $this->assertSame(['7', '90+3', '90+10', null], $minutes);
    }

    private function saveTeam(): FootballTeam
    {
        $team = new FootballTeam(['name' => '测试球队', 'country_code' => bin2hex(random_bytes(4))]);
        $this->assertTrue($team->save(), json_encode($team->errors));
        return $team;
    }
}
