<?php

namespace paskuale75\anagrafica\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "tbl_anagrafica_indirizzi".
 *
 * @property integer $id
 * @property string $indirizzo
 * @property string $comune_hidden
 * @property string $prov
 * @property integer $nazione_id
 * @property string $cap
 * @property integer $indirizzo_tipo_id
 * @property string $cab
 * @property integer $posta
 * @property integer $anagrafica_id
 * @property integer $user_id
 * @property string $last_mod
 */
class AnagraficaIndirizzi extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_anagrafica_indirizzi';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            //[['indirizzo', 'comune_hidden', 'nazione_id', 'indirizzo_tipo_id', 'cab'], 'required'],
            [['indirizzo', 'indirizzo_tipo_id'], 'required'],
            [['nazione_id', 'indirizzo_tipo_id', 'posta', 'anagrafica_id', 'user_id'], 'integer'],
            [['last_mod'], 'safe'],
            [['indirizzo'], 'string', 'max' => 100],
            [['comune_hidden'], 'string', 'max' => 65],
            [['cab'], 'string', 'max' => 6],
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
            'indirizzo' => 'Indirizzo',
            'comune_hidden' => 'Comune Hidden',
            'prov' => 'Prov',
            'nazione_id' => 'Nazione ID',
            'cap' => 'Cap',
            'indirizzo_tipo_id' => 'Tipo',
            'cab' => 'Cab',
            'posta' => 'Posta',
            'anagrafica_id' => 'Anagrafica ID',
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
        return $this->hasOne(AnagraficaIndirizziTipo::className(),['id' =>'indirizzo_tipo_id']);
    }



    public static function getTipiFilter()
    {
        $types = AnagraficaIndirizziTipo::find()->all();
        $listData = ArrayHelper::map($types,'id','descri');
        return $listData;
    }
    public static function getTipiFilterAsJson()
    {
        $types = AnagraficaIndirizziTipo::find()->all();
        foreach ($types as $type){
            $tmpArray[]= ['id' => $type->id,'text'=>$type->descri];
        }
        $json = json_encode($tmpArray);
        return $json;
    }
}