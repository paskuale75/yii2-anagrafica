<?php

namespace paskuale75\anagrafica\controllers;

use CodiceFiscale\Calculator;
use CodiceFiscale\Subject;
use common\modules\mensa\models\Alunno;
use common\modules\mensa\models\Genitore;
use common\modules\mensa\modules\anagrafica\models\Anagrafica;
use common\modules\mensa\modules\anagrafica\models\Comuni;
use Faker\Provider\DateTime;
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


    /**
     * Your controller action to fetch the list
     */
    public function actionComuniList($q = null) {
        $query = new Query;
        $tableName = Comuni::tableName();

        $query->select('idgen_comune, comune, provincia')
            ->from($tableName)
            ->where('comune LIKE "%' . $q .'%"')
            ->orderBy('comune');
        $command = $query->createCommand();
        $data = $command->queryAll();
        $out = [];
        foreach ($data as $d) {
            $out[] = [
                'id'=>$d['idgen_comune'],
                'value' => $d['comune'].' ('.$d['provincia'].')'
            ];
        }
        echo Json::encode($out);
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
    public function actionAlunniListConCfClasse($q = null) {
        $query = new Query;
        $tableName = Alunno::tableName();
        $anagraficaTable = Anagrafica::tableName().' anag'; //anag is alias

        $query->select('anag.id, anagrafica_id,mns_alunni.id, anag.ragione_sociale_1, anag.ragione_sociale_2,codfis')
            ->from($tableName)
            ->leftJoin($anagraficaTable, 'anag.id = anagrafica_id')
            ->where(
                'CONCAT_WS(" ",anag.ragione_sociale_1,anag.ragione_sociale_2)'
                .' LIKE "%' . $q . '%"'
            )
            ->orderBy('anag.ragione_sociale_1');
        $command = $query->createCommand();
        $data = $command->queryAll();
        $out = [];
        foreach ($data as $d) {
            $model = Alunno::findOne($d['id']);
            $option = $model->option;
            
            $classe = isset($option)?$option->classe:'';
            $nomeClasse = isset($classe->fullName)?$classe->fullName:'';
            $scuola = isset($option)?$option->scuola:'';
            $nomeScuola = isset($scuola->nome)?$scuola->nome:'';
            $out[] = [
                'id'=>$d['id'],
                'value' => $d['ragione_sociale_1'].' '.$d['ragione_sociale_2'].' - '.$d['codfis'].' - '.$nomeClasse.' - '.$nomeScuola
            ];
        }
        echo Json::encode($out);
    }

    public function actionGenitoriList($q = null) {
        $query = new Query;
        $tableName = Genitore::tableName();
        $anagraficaTable = Anagrafica::tableName().' anag'; //anag is alias

        $query->select('anag.id, anagrafica_id, genitore_id, anag.ragione_sociale_1, anag.ragione_sociale_2')
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
                'value' => $d['ragione_sociale_1'].' '.$d['ragione_sociale_2']
            ];
        }
        echo Json::encode($out);
    }


    public function actionAlunniList($q = null) {
        $query = new Query;        
        $tableName = Alunno::tableName().' alu';
        $alunnoKey = 'alu.ID';
        $anagraficaTable = Anagrafica::tableName().' anag'; //anag is alias

        $query->select('anag.id, anagrafica_id, '.$alunnoKey.' AS alunnoID, anag.ragione_sociale_1, anag.ragione_sociale_2')
            ->from($tableName)
            ->leftJoin($anagraficaTable, 'anag.id = anagrafica_id')
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
                'id'=>$d['alunnoID'],
                'value' => $d['ragione_sociale_1'].' '.$d['ragione_sociale_2']
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