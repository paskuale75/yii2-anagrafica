<?php

namespace paskuale75\anagrafica\models;

use paskuale75\comuni\models\Citta;
use paskuale75\comuni\models\Nazione;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "tbl_anagrafica_nascita".
 *
 * @property integer $id
 * @property string $birthdate
 * @property string $comune_hidden
 * @property string $prov
 * @property integer $nazione_id
 * @property string $cap
 * @property integer $anagrafica_id
 * @property string $last_mod
 */
class AnagraficaNascita extends \yii\db\ActiveRecord
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
        return 'tbl_anagrafica_nascita';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            //[['data', 'comune_hidden', 'prov', 'nazione_id', 'cap'], 'required'],
            [['birthdate', 'comune_hidden','comunenome'], 'required', 'message' => '{attribute} è obbligatorio'],
            [['birthdate','last_mod', 'comune_hidden','comunenome'], 'safe'],
            //[['nazione_id', 'anagrafica_id', 'user_id'], 'integer'],
            //[['comune_hidden'], 'string', 'max' => 65],
            [['prov'], 'string', 'max' => 4],
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
            'birthdate' => 'Nato il',
            'comune_hidden' => 'Nato a',
            'comunenome' => 'Nato a',
            'comuneNome' => 'Nato a',
            'prov' => 'Prov',
            'nazione_id' => 'Nazione ID',
            'cap' => 'Cap',
            'anagrafica_id' => 'Anagrafica ID',
            'last_mod' => 'Last Mod'
        ];
    }

    /*
     * RELATIONS....
     */

    public function getComune(){
        
        $comuneFind = Citta::findOne(['istat' => $this->comune_hidden]);

        if($comuneFind){
            $comune = $this->hasOne(Citta::class,['istat' => 'comune_hidden']);
        } else {
            $comune = $comune = $this->hasOne(Nazione::class,['id' => 'comune_hidden']);
        }

        return $comune;
    }
    
    public function getComuneNome(){
        $comuneFind = Citta::findOne(['istat' => $this->comune_hidden]);
        
        if($comuneFind){
            return ArrayHelper::getValue($this->comune,'comune');
        } 
        
        return ArrayHelper::getValue($this->comune,'nome_stati');
    }
    
    

    public function getAge(){
        $dateOfBirth = $this->birthdate;
        $today = date("Y-m-d");
        $diff = date_diff(date_create($dateOfBirth), date_create($today));
        $age = $diff->format('%y');
        return $age;
    }
}
