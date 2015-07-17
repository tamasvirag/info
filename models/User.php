<?php

namespace app\models;
use yii\data\ActiveDataProvider;

use Yii;

class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    public $roles;
    
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return null;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return $this->auth_key;
    }

    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }
    
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }
    
    public function validatePassword($password)
    {
        return $this->password === $password;
    }
    
    public static function tableName()
    {
        return 'user';
    }

    public function rules()
    {
        return [
            [['username', 'password', 'full_name', 'auth_key'], 'string', 'max' => 255],
            [['active','office_id'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'username' => Yii::t('app', 'Username'),
            'password' => Yii::t('app', 'Password'),
            'active' => Yii::t('app', 'Active'),
            'full_name' => Yii::t('app', 'Full Name'),
            'auth_key' => Yii::t('app', 'Auth Key'),
            'office_id' => Yii::t('app', 'Office ID'),
            
            'roles' => Yii::t('app', 'Roles'),
        ];
    }

    public function getClients()
    {
        return $this->hasMany(Client::className(), ['user_id' => 'id']);
    }
    
    public function getName()
    {
        return $this->full_name;
    }
    
    public function getInvoices()
    {
        return $this->hasMany(Invoice::className(), ['user_id' => 'id']);
    }

    public function getNews()
    {
        return $this->hasMany(News::className(), ['user_id' => 'id']);
    }
    
    public function getOffice()
    {
        return $this->hasOne(Office::className(), ['id' => 'office_id']);
    }
    
    public function search($params)
    {
        $query = User::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'password', $this->password])
            ->andFilterWhere(['like', 'full_name', $this->full_name])
            ->andFilterWhere(['like', 'auth_key', $this->auth_key]);

        return $dataProvider;
    }
    
    public function getRoles() {
        $auth = Yii::$app->authManager;
        $userAssignments = $auth->getAssignments($this->id);
        return $userAssignments;
    }
    
    public function getRolesText() {
        $rolesText = [];
        $roles = $this->getRoles();
        if ( is_array($roles) && count($roles)) {
            foreach($roles as $key => $role) {
                $rolesText[] = \Yii::t('app',$key);
            }
        }
        return implode(", ", $rolesText);
    }
    
}
