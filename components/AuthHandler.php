<?php
namespace app\components;

use app\models\User;
use Yii;
use yii\authclient\ClientInterface;
use yii\helpers\ArrayHelper;

/**
 * AuthHandler handles successful authentication via Yii auth component
 */
class AuthHandler
{
    /**
     * @var ClientInterface
     */
    private $client;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    public function handle()
    {
        $attributes = $this->client->getUserAttributes();
        $email = ArrayHelper::getValue($attributes, 'name');
        $user = User::find()->where(['email' => $email])->one();
        if($user){
            Yii::$app->user->login($user);
        }
        else{
            $user = new User();
            $user->email = $attributes['name'];
            $user->username = $attributes['name'];
            $user->password_hash = Yii::$app->security->generateRandomString();
            $user->auth_key = Yii::$app->security->generateRandomString();
            $user->created_at = time();
            $user->updated_at = time();
            $user->save();
            Yii::$app->user->login($user);
        }
    }
}