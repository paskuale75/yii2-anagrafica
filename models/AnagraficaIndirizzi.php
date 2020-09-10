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

    /*
     * Questo viene usato per selezionare il valore
     * ma l'id del comune scelto viene memorizzato
     * in $comune_hidden
     */
    public $comunenome;



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
            [['last_mod','comunenome'], 'safe'],
            [['indirizzo'], 'string', 'max' => 100],
            [['comune_hidden'], 'string', 'max' => 65],
            [['cab'], 'string', 'max' => 6],
            //prov se vuoto viene impostato 'xx'
            [['prov'], 'default', 'value' => 'xx'], 
            [['cap'], 'string', 'max' => 5],
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
            'comunenome' => 'Comune',
            'comuneNome' => 'Comune',
            'prov' => 'Prov',
            'nazione_id' => 'Nazione',
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
        return $this->hasOne(AnagraficaIndirizziTipo::class,['id' =>'indirizzo_tipo_id']);
    }

    public function getComune(){
        return $this->hasOne(Citta::class,['istat' => 'comune_hidden']);
    }
    
    public function getComuneNome(){
        return ArrayHelper::getValue($this->comune,'comune');
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