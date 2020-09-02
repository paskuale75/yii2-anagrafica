<?php

namespace paskuale75\anagrafica\models;

use Yii;
use yii\helpers\ArrayHelper;

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
            [['valore'], 'required'],
            [['anagrafica_id', 'contatto_tipo_id', 'user_id'], 'integer'],
            [['last_mod'], 'safe'],
            [['valore', 'descri'], 'string', 'max' => 45],
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