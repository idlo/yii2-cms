<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use common\models\Admin;

/**
 * Reset password form
 */
class ResetPasswordForm extends Model
{
    public $password;
    public $password_repeat;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['password', 'required'],
            ['password', 'string', 'min' => 6],

            ['password_repeat', 'required'],
            ['password_repeat', 'compare', 'compareAttribute' => 'password'],
        ];
    }

    /**
     * {@inheritDoc}
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'password' => Yii::t('app', 'Password'),
            'password_repeat' => Yii::t('app', 'Password Repeat'),
        ];
    }

    /**
     * Reset admin password.
     *
     * @return bool whether the  account password was reset successful
     */
    public function resetPassword($id)
    {
        if (!$this->validate()) {
            return null;
        }

        $user = Admin::findOne($id);
        $user->setPassword($this->password);
        return $user->save();

    }

}
