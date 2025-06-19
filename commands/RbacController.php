<?php
namespace app\commands;

use Yii;
use yii\console\Controller;

class RbacController extends Controller
{
    public function actionInit()
    {
        $auth = Yii::$app->authManager;

        // Роли
        $guest = $auth->createRole('guest');
        $auth->add($guest);

        $user = $auth->createRole('user');
        $auth->add($user);
    }
}