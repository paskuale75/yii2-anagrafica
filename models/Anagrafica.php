<?php

namespace paskuale75\anagrafica\models;

use Yii;
use yii\base\Model;
use common\modules\mensa\modules\users\models\User;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "tbl_anagrafica_anagrafiche".
 *
 * @property integer $id
 * @property string $sex
 * @property string $ragione_sociale_1
 * @property string $ragione_sociale_2
 * @property string $codfis
 * @property string $codiva
 * @property string $ruolo
 * @property integer $titoli_id
 * @property string $image
 * @property string $lang
 * @property integer $nazione_id
 * @property integer $user_id
 * @property string $last_mod
 */
class Anagrafica extends \yii\db\ActiveRecord
{
    const SCENARIO_FISICA = 'fisica';
    const SCENARIO_GIURIDICA = 'giuridica';
    const MEN_COLOR = 'blue-soft';
    const WOMEN_COLOR = 'red-pink';
    const MEN_COLOR_CODE = '#4B77BE';
    const WOMEN_COLOR_CODE = '#E26A6A';
    const MEN_ICON = 'fa fa-male';
    const WOMEN_ICON = 'fa fa-female';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_anagrafica_anagrafiche';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['ragione_sociale_1', 'ragione_sociale_2', 'codfis'], 'required','on' => self::SCENARIO_FISICA],
            [['ragione_sociale_1', 'codiva'], 'required','on' => self::SCENARIO_GIURIDICA],
            [['titoli_id', 'nazione_id', 'user_id'], 'integer'],
            [['last_mod','ragione_sociale_1', 'ragione_sociale_2','codfis','scenario'], 'safe'],
            [['sex'], 'string', 'max' => 1],
            [['ruolo', 'image'], 'string', 'max' => 45],
            [['codfis'], 'string', 'max' => 16,'on' => self::SCENARIO_FISICA],
            [['codiva'], 'string', 'max' => 11,'on' => self::SCENARIO_GIURIDICA],
            [['lang'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        switch ($this::getScenario()){
            default:
                return [
                    'id' => 'ID',
                    'sex' => 'Sesso',
                    'ragione_sociale_1' => 'Cognome',
                    'ragione_sociale_2' => 'Nome',
                    'codfis' => 'Cod.Fiscale',
                    'codiva' => 'Partita Iva',
                    'ruolo' => 'Ruolo',
                    'titoli_id' => 'Titoli ID',
                    'image' => 'Image',
                    'lang' => 'Lang',
                    'nazione_id' => 'Nazione ID',
                    'user_id' => 'User ID',
                    'last_mod' => 'Last Mod',
                    'fullName' => 'Nominativo'
                ];
                break;
            case $this::SCENARIO_GIURIDICA:
                return [
                    'id' => 'ID',
                    'sex' => 'Sesso',
                    'ragione_sociale_1' => 'Ragione Sociale',
                    'ragione_sociale_2' => 'Nome',
                    'codfis' => 'Cod.Fiscale',
                    'codiva' => 'Partita Iva',
                    'ruolo' => 'Ruolo',
                    'titoli_id' => 'Titoli ID',
                    'image' => 'Image',
                    'lang' => 'Lang',
                    'nazione_id' => 'Nazione ID',
                    'user_id' => 'User ID',
                    'last_mod' => 'Last Mod',
                    'fullName' => 'Nominativo'
                ];
                break;
        }

    }


    /*
     * RELATIONS
     */


    public function getUser(){
        return $this->hasOne(User::className(),['id' => 'user_id']);
    }

    public function getNascita(){
        return $this->hasOne(AnagraficaNascita::className(),['anagrafica_id'=>'id']);
    }

    public function getContatti(){
        return $this->hasMany(AnagraficaContatti::className(),['anagrafica_id'=>'id']);
    }

    public function getIndirizzi(){
        return $this->hasMany(AnagraficaIndirizzi::className(),['anagrafica_id'=>'id']);
    }


    public function getFullName(){
        $cognome = ucfirst($this->ragione_sociale_1);
        $nome = ucfirst($this->ragione_sociale_2);
        return $cognome.' '.$nome;
    }

    public function getPivaCf(){
        $ret[] = ArrayHelper::getValue($this,'codiva', false);
        $ret[] = ArrayHelper::getValue($this,'codfis', false);
        $filtered = array_filter($ret);
        $final = implode(' - ',$filtered);
        return $final;
    }


    public function getContattiList(){
        $elenco = [];
        foreach ($this->contatti as $contatto)
        {
            $elenco[] = ArrayHelper::getValue($contatto,'tipo.descri').': '.ArrayHelper::getValue($contatto,'valore');
        }
        return implode('<br>',$elenco);
    }

    public function getIndirizziList(){
        $elenco = [];
        foreach ($this->indirizzi as $indirizzo)
        {
            $tipoI = $indirizzo->tipo->descri;
            $elenco[] = $tipoI.': '.$indirizzo->indirizzo;
        }
        return implode('<br>',$elenco);
    }
}