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
$this->registerJs('
  $(function () {
    $(document).on(
        "click",
        "a.to-eat",
        function(event) {
            let $link = $(this);
            if (!$link.data("href")) {
                $link.data("href", $link.attr("href"))
            }
            let eatSize = $("input#size-"+$link.data("appleid")).val();
            $link.attr("href", $link.data("href"));
            $link.attr("href", $link.data("href") + "&size=" + eatSize);
        }
    )
  });
');
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
            [
                'attribute' => 'color',
                'label' => 'Цвет',
            ],
            [
                'attribute' => 'created_at',
                'label' => 'Дата созревания',
                'value' => function (Apple $apple) {
                    return $apple->created_at ? date('d.m.Y H:i:s', $apple->created_at) : '';
                },
                'filter' => false
            ],
            [
                'attribute' => 'dropped_at',
                'label' => 'Дата падения',
                'value' => function (Apple $apple) {
                    return $apple->dropped_at ? date('d.m.Y H:i:s', $apple->dropped_at) : '';
                },
                'filter' => false
            ],
            [
                'attribute' => 'status',
                'label' => 'Состояние',
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
                'label' => 'Размер',
                'value' => function (Apple $apple): string {
                    return $apple->size;
                }
            ],
            [
                'label' => 'Уронить яблоко',
                'format' => 'raw',
                'value' => function (Apple $apple) {
                    return Html::a(
                            'Уронить яблоко',
                        ['to-ground', 'id' => $apple->id]
                    );
                }
            ],
            [
                'label' => 'Откусить яблоко',
                'format' => 'raw',
                'value' => function (Apple $apple) {

                    return

                        Html::textInput('size', '', ['id' => 'size-'.$apple->id]).
                        Html::a(
                            'Откусить яблоко',
                            ['to-eat', 'id' => $apple->id],
                            [
                                'class' => 'to-eat',
                                'data-appleId' => $apple->id
                            ]
                        )
                        ;

                }
            ],
        ],
    ]); ?>


</div>
