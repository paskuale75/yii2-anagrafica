<?php

namespace paskuale75\anagrafica\controllers;

use CodiceFiscale\Calculator;
use CodiceFiscale\Subject;
use common\modules\mensa\models\Alunno;
use common\modules\mensa\models\Genitore;
use Faker\Provider\DateTime;
use paskuale75\anagrafica\models\Anagrafica;
use Yii;
use yii\db\Query;
use yii\helpers\Json;
use yii\helpers\VarDumper;
use yii\web\Controller;

/**
 * Default controller for the `anagrafica` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }


    

    public function actionGenitoriListConCf($q = null) {
        $query = new Query;
        $tableName = Genitore::tableName();
        $anagraficaTable = Anagrafica::tableName().' anag'; //anag is alias

        $query->select('anag.id, anagrafica_id, genitore_id, anag.ragione_sociale_1, anag.ragione_sociale_2,codfis')
            ->from($tableName)
            ->leftJoin($anagraficaTable, 'id = anagrafica_id')
            ->where(
                'CONCAT_WS(" ",anag.ragione_sociale_1,anag.ragione_sociale_2)'
                .' LIKE "%' . $q . '%"'
            )
            ->orderBy('anag.ragione_sociale_1');
        $command = $query->createCommand();
        $data = $command->queryAll();
        $out = [];
        foreach ($data as $d) {
            $out[] = [
                'id'=>$d['genitore_id'],
                'value' => $d['ragione_sociale_1'].' '.$d['ragione_sociale_2'].' - '.$d['codfis']
            ];
        }
        echo Json::encode($out);
    }
    

    


    
    public function actionCalculateCf($flag_nazione=false){

        if($flag_nazione){
            $modelName = "Nazione"; //Nazione::class;
        }else{
            $modelName = Comuni::class;
        }

        $posts = Yii::$app->request->post();

        $comune = $modelName::findOne($posts['id_comune']);

        //$birthDate = '1975-04-18';
        //$_d = explode('/',$posts['birthDate']); // creo l’oggeto data
        $birthDate = $posts['birthDate'];


        $gender = strtoupper($posts['gender']);
        $belfioreCode = $comune['belfiore'];

        $subject = new Subject(
            array(
                "name" => $posts['name'],
                "surname" => $posts['surname'],
                "birthDate" => $birthDate, //"1985-12-10",
                "gender" => $gender,
                "belfioreCode" => $belfioreCode
            )
        );

        $calculator = new Calculator($subject);
        $codiceFiscale = $calculator->calculate();

        echo $codiceFiscale; //"RSSMRA85T10A562S"

        //echo json_encode($return);
    }
}