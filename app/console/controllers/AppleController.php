<?php

namespace console\controllers;

use common\exception\ActionException;
use common\models\Apple;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\console\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AppleController implements the CRUD actions for Apple model.
 */
class AppleController extends Controller
{
    /**
     * Lists all Apple models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $apples = Apple::find()
            ->where(
                [
                    'status' => Apple::STATUS_DROPPED,

                ]
            )
            ->andWhere(['<=', 'dropped_at', time() - Apple::TIME_TO_SPOILED])
            ->all();
        foreach ($apples as $apple) {
            try {
                $apple->fallToSpoiled();
                $apple->save();
            } catch (\Exception) {}
        }

    }

}
