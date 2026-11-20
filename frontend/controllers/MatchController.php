<?php

/**
 * 公开赛程、比分与比赛详情。
 * @author lzs
 * @date 2026-11-20
 */

namespace frontend\controllers;

use common\models\FootballMatch;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class MatchController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index', [
            'provider' => new ActiveDataProvider([
                'query' => FootballMatch::find()->with(['homeTeam', 'awayTeam', 'stadium']),
                'sort' => ['defaultOrder' => ['match_time' => SORT_ASC, 'id' => SORT_ASC]],
            ]),
        ]);
    }

    public function actionView($id)
    {
        if (!is_scalar($id)) {
            throw new NotFoundHttpException('未找到该比赛。');
        }

        $match = FootballMatch::find()
            ->with(['homeTeam', 'awayTeam', 'stadium', 'goals.team'])
            ->where(['id' => $id])
            ->one();
        if ($match === null) {
            throw new NotFoundHttpException('未找到该比赛。');
        }

        return $this->render('view', ['match' => $match]);
    }
}
