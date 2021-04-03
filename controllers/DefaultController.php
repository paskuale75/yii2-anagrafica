<?php
namespace paskuale75\anagrafica\controllers;

use Yii;
use CodiceFiscale\Calculator;
use CodiceFiscale\Subject;
use paskuale75\anagrafica\models\Anagrafica;
use paskuale75\comuni\models\Citta;
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




    public function actionCalculateCf($flag_nazione = false)
    {

        $module = $this->module;
        $posts = Yii::$app->request->post();

        if ($flag_nazione) {
            $modelName = "Nazione"; //Nazione::class;
        } else {
            $modelName = Citta::class;
        }

        

        $comune = $modelName::findOne($posts['id_comune']);

        //$birthDate = '1975-04-18';
        //$_d = explode('/',$posts['birthDate']); // creo l’oggeto data
        $birthDate = $posts['birthDate'];
        $gender = strtoupper($posts['gender']);
        $belfioreCode = $comune[$module->belFioreColumn];

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

        echo $codiceFiscale; //"RSSMRA85T10A562S"

        //echo json_encode($return);
    }


    public function actionAutocomplete() {
        $results = [];
        $res = [];

        if (isset($_GET['term'])) {
            $qtxt = 'SELECT id,ragione_sociale_1,ragione_sociale_2,codfis FROM '.Anagrafica::tableName().' WHERE ragione_sociale_1 LIKE :ragso1';
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
