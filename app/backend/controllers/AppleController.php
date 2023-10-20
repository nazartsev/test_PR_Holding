<?php

namespace backend\controllers;

use common\exception\ActionException;
use common\models\Apple;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AppleController implements the CRUD actions for Apple model.
 */
class AppleController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::class,
                    'rules' => [
                        [
                            'actions' => [
                                'index',
                                'create',
                                'to-ground',
                                'to-eat',
                            ],
                            'allow' => true,
                            'roles' => ['@'],
                        ],
                    ],
                ],
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Apple models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Apple::find()->where(['is_hidden' => 0]),
            'pagination' => false
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate()
    {
        $randomApplesAdd = rand(1, 20);
        for ($position = 1; $position <= $randomApplesAdd; $position ++) {
            $model = new Apple(Apple::generateColor());

            if (!$model->save()) {
                dd($model->errors);
            }
        }
        return $this->redirect(['index']);
    }

    public function actionToGround(int $id)
    {
        try {
            $apple = $this->findModel($id);
            $apple->fallToGround();
            $apple->save();
            \Yii::$app->session->setFlash('success', 'Яблоко упало');
        } catch (ActionException $exception) {
            \Yii::$app->session->setFlash('error', $exception->getMessage());
        }

        return $this->redirect(['index']);
    }

    public function actionToEat(int $id, $size = 0)
    {
        try {
            if (is_null($size)) {
                $size = 0;
            }
            if ((string)((int)$size) !== $size) {
                throw new ActionException('Ошибка с размером');
            }
            $apple = $this->findModel($id);
            if ($apple->isTimeToSpoiled()) {
                $apple->fallToSpoiled();
                $apple->save();
                $apple->refresh();
            }
            $apple->eat($size);
            $apple->save();
            \Yii::$app->session->setFlash('success', 'Яблоко откусили');
        } catch (ActionException $exception) {
            \Yii::$app->session->setFlash('error', $exception->getMessage());
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the Apple model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Apple the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Apple::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Яблоко не найдено');
    }
}
