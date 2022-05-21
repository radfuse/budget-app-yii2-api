<?php

namespace app\models;

use sizeg\jwt\Jwt;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * User indentity class
 * 
 * @property integer $id
 * @property string $email
 * @property string $password_hash
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $last_login_at
 */
class User extends ActiveRecord implements IdentityInterface
{
    /** @var string */
    public $password;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
				'class' => TimestampBehavior::class,
				'createdAtAttribute' => 'created_at',
				'updatedAtAttribute' => 'updated_at',
			],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['password', 'email'], 'required'],
            [['status', 'created_at', 'updated_at', 'last_login_at'], 'integer'],
            [['password', 'email', 'auth_key'], 'string', 'max' => 255],
            [['email'], 'email'],
            [['email'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function beforeSave($insert)
    {
        if ($insert) {
            $this->setAttribute('auth_key', Yii::$app->security->generateRandomString());
        }

        if (!empty($this->password)) {
            $this->setAttribute('password_hash', Yii::$app->security->generatePasswordHash($this->password));
        }

        return parent::beforeSave($insert);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * {@inheritdoc}
     * @param \Lcobucci\JWT\Token $token
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['email' => $token->getClaim('sub')]);
    }

    /**
     * Generates JWT access token
     *
     * @return string
     */
    public function generateJwtToken()
    {
        $accessExpireInSeconds = Yii::$app->params['access-token-expire'];
        $accessExpireTime = $accessExpireInSeconds ? (time() + $accessExpireInSeconds) : 0;

        /** @var Jwt $jwt */
        $jwt = Yii::$app->jwt;
        $signer = $jwt->getSigner('HS256');
        $key = $jwt->getKey();
        $time = time();
        
        $token = $jwt->getBuilder()
            ->issuedAt($time) // Configures the time that the token was issue (iat claim)
            ->expiresAt($time + $accessExpireTime) // Configures the expiration time of the token (exp claim)
            ->relatedTo($this->email) // Configures a new subject, called "sub"
            ->getToken($signer, $key); // Retrieves the generated token

        return [
            'token' => (string)$token,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getAttribute('id');
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->getAttribute('auth_key');
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAttribute('auth_key') === $authKey;
    }

    /**
     * Returns user by email
     * @param string $email
     * @return User|boolean
     */
    public static function findByEmail($email)
    {
        return static::findOne(['email' => $email]);
    }

    /**
     * Validates password
     * @param string $password
     * @return boolean
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }
}