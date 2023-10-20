<?php

namespace common\models;

use common\exception\ActionException;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "{{%apple}}".
 *
 * @property int $id
 * @property string $color
 * @property int $created_at
 * @property int|null $dropped_at
 * @property int $status
 * @property int $eat_percent
 * @property int $is_hidden
 */
class Apple extends \yii\db\ActiveRecord
{
    public const STATUS_ON_TREE = 1;
    public  const STATUS_DROPPED = 2;
    public  const STATUS_SPOILED = 3;
    public  const TIME_TO_SPOILED = 5 * 60 * 60;
    private const TIME_TO_CREATED = 2 * 24 * 60 * 60;

    public static function generateColor(): string
    {
        $colors = [
            'red',
            'green',
            'yellow'
        ];

        return $colors[rand(1, count($colors)) - 1];
    }

    public function __construct(mixed $color = '')
    {
        if (is_string($color)) {
            $timeNow = time();
            parent::__construct([
                'color' => $color,
                'created_at' => rand($timeNow - self::TIME_TO_CREATED, $timeNow),
            ]);
            return;
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%apple}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['color', 'created_at'], 'required'],
            [['created_at', 'dropped_at', 'status', 'eat_percent', 'is_hidden'], 'integer'],
            [['color'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'color' => 'Color',
            'created_at' => 'Created At',
            'dropped_at' => 'Dropped At',
            'status' => 'Status',
            'eat_percent' => 'Eat Percent',
            'is_hidden' => 'Is Hidden',
        ];
    }

    public function isOnTree(): bool
    {
        return $this->status === self::STATUS_ON_TREE;
    }

    public function isDropped(): bool
    {
        return $this->status === self::STATUS_DROPPED;
    }

    public function isSpoiled(): bool
    {
        return $this->status === self::STATUS_SPOILED;
    }

    public function isEatted(): bool
    {
        return $this->isDropped() && !$this->isSpoiled() && ($this->getSize() === 0.0);
    }

    public function fallToGround(): void
    {
        if ($this->isDropped()) {
            throw new ActionException('Яблоко уже упало');
        }
        if ($this->isSpoiled()) {
            throw new ActionException('Яблоко уже испортилось');
        }
        $this->status = self::STATUS_DROPPED;
        $this->dropped_at = time();
    }

    public function fallToSpoiled(): void
    {
        if ($this->isOnTree()) {
            return;
        }
        if ($this->isSpoiled()) {
            return;
        }
        if (!$this->isTimeToSpoiled()) {
            return;
        }
        $this->status = self::STATUS_SPOILED;
    }

    public function fallToTrash(): void
    {
        if ($this->isOnTree()) {
            throw new ActionException('Яблоко еще висит');
        }

        if (!$this->isTimeToSpoiled()) {
            return;
        }
        $this->status = self::STATUS_SPOILED;
    }

    public function eat(int $percent): void
    {
        if ($percent === 0) {
            throw new ActionException('Нельзя укусить воздух');
        }
        if ($percent < 0) {
            throw new ActionException('Нельзя вернуть откусанное яблоко');
        }
        if ($this->isOnTree()) {
            throw new ActionException('Яблоко еще висит');
        }
        if ($this->isSpoiled()) {
            throw new ActionException('Яблоко уже испортилось');
        }
        if ($this->is_hidden === 1) {
            throw new ActionException('Яблоко уже съели');
        }
        if ($percent > 100) {
            throw new ActionException('Пальцы не съедобные');
        }
        $eatPercent = $this->eat_percent + $percent;
        if ($eatPercent > 100) {
            throw new ActionException('Нельзя скушать больше чем есть');
        }

        $this->eat_percent = $eatPercent;
        if ($this->eat_percent === 100) {
            $this->is_hidden = 1;
        }
    }

    public function getSize(): float
    {
        return (100 - $this->eat_percent) / 100;
    }

    public function isTimeToSpoiled(): bool
    {
        $timeSpoiled = $this->dropped_at + self::TIME_TO_SPOILED;
        return time() >= $timeSpoiled;
    }
}
