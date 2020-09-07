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