<?php

namespace paskuale75\anagrafica\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;

/**
 * This is the model class for table "tbl_anagrafica_contatti".
 *
 * @property integer $id
 * @property integer $anagrafica_id
 * @property integer $contatto_tipo_id
 * @property string $valore
 * @property string $descri
 * @property integer $user_id
 * @property string $last_mod
 */
class AnagraficaContatti extends \yii\db\ActiveRecord
{
    const EMAIL_CODE = 4;
    const PEC_CODE = 5;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_anagrafica_contatti';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            //[['valore'], 'required'],
            [['anagrafica_id', 'contatto_tipo_id', 'user_id'], 'integer'],
            [['last_mod','valore','contatto_tipo_id'], 'safe'],
            [['valore', 'descri'], 'string', 'max' => 45],
            [['valore'], 'myValoreRule','skipOnEmpty'=> false],
            [['contatto_tipo_id'], 'myContattoTipoRule','skipOnEmpty'=> false],
            //[['contatto_tipo_id'], 'myValoreRule','skipOnEmpty'=> false],
            [['anagrafica_id'], 'exist',
                'skipOnError' => true,
                'targetClass' => Anagrafica::class,
                'targetAttribute' => ['anagrafica_id' => 'id']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'anagrafica_id' => 'Anagrafica ID',
            'contatto_tipo_id' => 'Tipo',
            'valore' => 'Valore',
            'descri' => 'Note',
            'user_id' => 'User ID',
            'last_mod' => 'Last Mod',
        ];
    }

    public function myValoreRule($attribute,$params)
    {
        if(!empty($this->contatto_tipo_id)){
            if(empty($this->$attribute))
                $this->addError($attribute, 'Valore è richiesto.');
        }

        if(!empty($this->$attribute) && ($this->contatto_tipo_id == self::EMAIL_CODE || 
                $this->contatto_tipo_id == self::PEC_CODE)){
            $this->$attribute = filter_var($this->$attribute, FILTER_SANITIZE_EMAIL);
            if(!filter_var($this->$attribute, FILTER_VALIDATE_EMAIL)) {
                $this->addError($attribute, 'Formato indirizzo email non corretto.');
            }
        }
        
    }

    public function myContattoTipoRule($attribute,$params)
    {
        if(!empty($this->valore)){
            if(empty($this->$attribute)){
                $this->addError($attribute, 'Tipo Contatto è richiestp.');
            }
        }

    }

    /*
     * RELATIONS
     */

    public function getAnagrafica(){
        return $this->hasOne(Anagrafica::class,['anagrafica_id'=>'anagrafica_id']);
    }
    public function getTipo(){
        return $this->hasOne(AnagraficaContattiTipo::class,['contatto_tipo_id'=>'contatto_tipo_id']);
    }

    public static function getTipiFilter()
    {
        $types = AnagraficaContattiTipo::find()->all();
        $listData = ArrayHelper::map($types,'contatto_tipo_id','descri');
        return $listData;
    }
    
    public static function getTipiFilterAsJson()
    {
        $types = AnagraficaContattiTipo::find()->all();
        foreach ($types as $type){
            $tmpArray[]= ['id' => $type->contatto_tipo_id,'text'=>$type->descri];
        }
        $json = json_encode($tmpArray);
        return $json;
    }
}
