<?php

/**
 * 公开的球队、球员和场馆资料。
 * @author lzs
 * @date 2026-11-20
 */

namespace frontend\controllers;

use common\models\FootballPlayer;
use common\models\FootballStadium;
use common\models\FootballTeam;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class FootballController extends Controller
{
    public function actionTeams()
    {
        return $this->render('teams', [
            'provider' => new ActiveDataProvider([
                'query' => FootballTeam::find(),
                'sort' => ['defaultOrder' => ['name' => SORT_ASC, 'id' => SORT_ASC]],
            ]),
        ]);
    }

    public function actionPlayers($team_id = null)
    {
        $team = null;
        $query = FootballPlayer::find()->with('team');
        if ($team_id !== null && $team_id !== '') {
            $team = is_scalar($team_id) ? FootballTeam::findOne(['id' => $team_id]) : null;
            if ($team === null) {
                throw new NotFoundHttpException('未找到该球队。');
            }
            $query->andWhere(['team_id' => $team->id]);
        }

        return $this->render('players', [
            'team' => $team,
            'provider' => new ActiveDataProvider([
                'query' => $query,
                'sort' => ['defaultOrder' => ['name' => SORT_ASC, 'id' => SORT_ASC]],
            ]),
        ]);
    }

    public function actionStadiums()
    {
        return $this->render('stadiums', [
            'provider' => new ActiveDataProvider([
                'query' => FootballStadium::find(),
                'sort' => ['defaultOrder' => ['city' => SORT_ASC, 'name' => SORT_ASC, 'id' => SORT_ASC]],
            ]),
        ]);
    }
}
