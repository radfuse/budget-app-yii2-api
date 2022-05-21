<?php
namespace app\controllers;

use Yii;
use app\components\RestController;
use app\models\User;
use app\models\LoginForm;
use app\models\RegisterForm;

/**
 * Auth controller
 */
class AuthController extends RestController
{
	/** {@inheritdoc} */
	public $authOptional = ['login', 'register'];

	/**
	 * Action for logging in
	 * @return array
	 */
	public function actionLogin()
	{
		$model = new LoginForm;

		if ($model->load(Yii::$app->getRequest()->getBodyParams(), '') && $model->login()) {
			/** @var User $user */
			$user = Yii::$app->user->getIdentity();
			return $user->generateJwtToken();
		} else {
			$model->validate();
			return $model;
		}
	}
	/**
	 * Action for logging in
	 * @return array
	 */
	public function actionRegister()
	{
		$model = new RegisterForm;

		if ($model->load(Yii::$app->getRequest()->getBodyParams(), '') && ($user = $model->signup())) {
			return $user->generateJwtToken();
		} else {
			$model->validate();
			return $model;
		}
	}

	/**
	 * Ping for server availability
	 * @return array
	 */
	public function actionPing()
	{
		return ['pong' => 1];
	}
}
