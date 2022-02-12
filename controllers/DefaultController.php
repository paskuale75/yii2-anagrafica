<?php

namespace paskuale75\anagrafica\controllers;

use Yii;
use CodiceFiscale\Calculator;
use CodiceFiscale\Subject;
use paskuale75\anagrafica\models\Anagrafica;
use paskuale75\comuni\models\Citta;
use paskuale75\comuni\models\Nazione;
use PDO;
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


    public function actionCalculateCf()
    {

        $module = $this->module;
        $posts = Yii::$app->request->get();

        $flgNazione = $posts['flgNazione'];
        if ($flgNazione == "false") {
            $modelName = Citta::class;
        } else {
            $modelName = Nazione::class;
        }

        $place = $modelName::findOne($posts['id_comune']);

        $birthDate = $posts['birthDate'];
        $gender = strtoupper($posts['gender']);
        $cognome =  $posts['surname'];
        $nome = $posts['name'];
        $currAnagrafica = $posts['currAnagrafica'];
        $codiceFiscale = "";
        $tag = $posts['tag'];

        if ($place && $gender && $birthDate && $cognome && $nome) {
            $belfioreCode = $place[$module->belFioreColumn];

            $subject = new Subject(
                [
                    "name" => $posts['name'],
                    "surname" => $posts['surname'],
                    "birthDate" => $birthDate, //"1985-12-10",
                    "gender" => $gender,
                    "belfioreCode" => $belfioreCode
                ]
            );

            $calculator = new Calculator($subject);
            $codiceFiscale = $calculator->calculate();
        }
        
        //tabelle esterne con cui fare join
        $className = new $module->externalTableConstant[$tag];
        $tabellaJoin = $className::tableName();

        $object = Anagrafica::find()
            ->where([
                'codfis' => $codiceFiscale
            ])
            ->innerJoin($tabellaJoin, $tabellaJoin . '.anagrafica_id = tbl_anagrafica_anagrafiche.id')
            ->andWhere(['<>', 'tbl_anagrafica_anagrafiche.id', $currAnagrafica])
            ->one();

        $exists = false;
        $idPaziente = 0;
        if ($object && $codiceFiscale) {

            $exists = true;
            $idPaziente = $object->primaryKey;
        }

        $return = [
            'exists' => $exists,
            'codfis' => $codiceFiscale,
            'idPaziente' => $idPaziente
        ];

        echo json_encode($return);
        Yii::$app->end();
    }



    public function actionAutocomplete()
    {
        $results = [];
        $res = [];

        if (isset($_GET['term'])) {
            $qtxt = 'SELECT id,ragione_sociale_1,ragione_sociale_2,codfis FROM ' . Anagrafica::tableName() . ' WHERE ragione_sociale_1 LIKE :ragso1';
            $command = Yii::$app->db->createCommand($qtxt);
            $command->bindValue(":ragso1", '%' . $_GET['term'] . '%', PDO::PARAM_STR);
            $res = $command->queryAll();

            foreach ($res as $p) {
                $results[] = [
                    'label' => $p['ragione_sociale_1'] . ' ' . $p['ragione_sociale_2'] . ' ' . $p['codfis'],
                    'value' => $p['ragione_sociale_1'],
                    'id' => $p['id'],
                ];
            }
        }
        echo json_encode($results);
        Yii::$app->end();
    }
}
