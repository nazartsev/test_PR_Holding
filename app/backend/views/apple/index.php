<?php

use common\models\Apple;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Apples';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="apple-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Apple', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'color',
            'created_at',
            'dropped_at',
            [
                'attribute' => 'status',
                'value' => function (Apple $apple): string {
                    return match (true) {
                        $apple->isOnTree() => 'На дереве',
                        $apple->isDropped() => 'Упало',
                        $apple->isSpoiled() => 'Испортилось',
                        $apple->isEatted() => 'Скушано',
                        default => '',
                    };
                }
            ],
            [
                'format' => 'raw',
                'value' => function (Apple $apple) {
                    return Html::a(
                            'Уронить яблоко',
                        ['to-ground', 'id' => $apple->id]
                    );
                }
            ],
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Apple $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
