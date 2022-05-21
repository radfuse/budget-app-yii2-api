<?php
namespace app\components;

use app\components\Cors;
use yii\rest\Controller;
use yii\filters\ContentNegotiator;
use yii\web\Response;
use sizeg\jwt\JwtHttpBearerAuth;

/**
 * Base controller class for API
 */
class RestController extends Controller
{
	/** {@inheritdoc} */
	public $enableCsrfValidation = false;
	/** @var array */
	public $authOptional = [];
	
	/**
     * {@inheritdoc}
	 */
    public function behaviors()
    {
        return [
            'contentNegotiator' => [
				'class' => ContentNegotiator::class,
				'formats' => [
					'application/json' => Response::FORMAT_JSON,
				],
			],
			'authenticator' => [
				'class' => JwtHttpBearerAuth::class,
				'except' => ['options'],
				'optional' => $this->authOptional,
			],
			'corsFilter'  => [
				'class' => Cors::class,
				'cors'  => [
					'Origin' => ['*'],
					'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
					'Access-Control-Request-Headers' => ['Origin', 'X-Requested-With', 'Content-Type', 'accept', 'Authorization'],
				],
			],
        ];
    }
}
