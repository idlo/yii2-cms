<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tag".
 *
 * @property int $id
 * @property string $name 名称
 * @property int $freq 引用频率
 */
class Tag extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tag';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['freq'], 'integer'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'freq' => Yii::t('app', 'Freq'),
        ];
    }

    /**
     * string transform array
     *
     * @param $str
     * @return array
     */
    public static function str2Array($str)
    {
        if (empty($str)) {
            return [];
        }
        return preg_split('/\s*,\s*/', trim($str), -1, PREG_SPLIT_NO_EMPTY);
    }

    /**
     * update tags frequency
     *
     * @param $oldTags
     * @param $newTags
     */
    public static function updateFreq($oldTags, $newTags)
    {
        if (empty($oldTags) && empty($newTags)) {
            return;
        }

        $oldTagArr = self::str2Array($oldTags);
        $newTagArr = self::str2Array($newTags);

        self::incFreq(array_values(array_diff($newTagArr, $oldTagArr)));
        self::decFreq(array_values(array_diff($oldTagArr, $newTagArr)));

        return;
    }

    /**
     * increase tags frequency
     *
     * @param array $tags
     */
    public static function incFreq(array $tags)
    {
        if (empty($tags)) {
            return;
        }
        foreach ($tags as $tagName) {
            $tag = Tag::find()->where(['name' => $tagName])->one();
            if (!$tag) {
                $tag = new Tag();
                $tag->name = $tagName;
                $tag->freq = 1;
                $tag->save();
            } else {
                $tag->freq += 1;
                $tag->save();
            }
        }
        return;
    }

    /**
     * decrease tags frequency
     *
     * @param array $tags
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public static function decFreq(array $tags)
    {
        if (empty($tags)) {
            return;
        }

        foreach ($tags as $tagName) {
            $tag = Tag::find()->where(['name' => $tagName])->one();
            if (!$tag) {
                continue;
            }
            if ($tag->freq == 1) {
                $tag->delete();
                continue;
            }
            $tag->freq -= 1;
            $tag->save();
        }
        return;
    }

}
