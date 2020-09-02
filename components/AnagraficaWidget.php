<?php
namespace paskuale75\anagrafica\components;

use dlds\metronic\widgets\Button;
use dlds\metronic\widgets\GridView;
use kartik\builder\TabularForm;
use kartik\datecontrol\DateControl;
use kartik\form\ActiveForm;
use kartik\helpers\Html;
use kartik\select2\Select2;
use kartik\widgets\Typeahead;
use Yii;
use kartik\base\Widget;
use kartik\builder\Form;
use yii\helpers\Url;
use yii\helpers\VarDumper;

class AnagraficaWidget extends Widget{

    public $customForm;
    public $model;
    public $form;
    public $formName;
    public $ajaxUrlCodiceFiscale;
    public $ajaxUrlComuni;

    public $submitButton = false;

    public $options = [
        'giuridica' => [
            'showMe'        => true,
            'nascitaData'   => false,
            'contatti'      => false,
            'indirizzi'     => false
        ],
        'fisica' => [
            'showMe'        => true,
            'nascitaData'   => true,
            'contatti'      => true,
            'indirizzi'     => true
        ]
    ];


    public function init(){
        // your logic here
        parent::init();

        $this->ajaxUrlCodiceFiscale = Url::to(['//mensa/anagrafica/default/calculate-cf']);
        $this->ajaxUrlComuni = '//mensa/anagrafica/default/comuni-list';
        //$modelliCollegati = $this->customForm->getAllModels();
        /*VarDumper::dump($modelliCollegati,10,true);
        die();*/
    }




    private function prepareFieldsForFisica()
    {
        $view = $this->getView();

        $this->customForm->anagrafica->scenario = Anagrafica::SCENARIO_FISICA;

        $form = $this->form;

        $btnCalcolaCF = Button::widget([
            'id'    => 'btn_cf_calculator',
            'label' => 'Calcola...',
            'icon'  => 'fa fa-gear',
            'type'  => Button::TYPE_INFO
        ]);

        $content = $this->customForm->errorSummary($form);

        /*VarDumper::dump($this->model->genitore1->anagrafica,10,true);
        die();*/

        $content .= Form::widget([
            'model' => $this->customForm->anagrafica,
            'form' => $form,
            'columns' => 1,
            'attributes' => [
                'scenario'=>[
                    'type'=>Form::INPUT_HIDDEN,
                    'options'=>[],
                    'value'=>Anagrafica::SCENARIO_FISICA
                ]
                ,
                'ragione_sociale_1'  => [
                    'type'=>Form::INPUT_TEXT,
                    'options'=>['placeholder'=>'']
                ],
                'ragione_sociale_2'  => [
                    'type'=>Form::INPUT_TEXT,
                    'options'=>['placeholder'=>'']
                ],
            ]
        ]);

        $content .= Form::widget([
            'model' => $this->customForm->nascita,
            'form' => $form,
            'columns' => 12,
            'attributes' => [
                'birthdate' => [
                    'type' => Form::INPUT_WIDGET,
                    'widgetClass'   => DateControl::className(),
                    'options'       => [
                        'type'          =>  DateControl::FORMAT_DATE,
                        'hashVarLoadPosition' => $view::POS_READY
                    ],
                    'columnOptions'=>['colspan'=>6],
                    //'label' => $this->customForm->nascita->getAttributeLabel('data'),
                ],
                'comuneNome' => [
                    'type' => Form::INPUT_WIDGET,
                    'widgetClass' => Typeahead::className(),
                    'columnOptions'=>['colspan'=>6],
                    'options' => [
                        'scrollable' => true,

                        'hashVarLoadPosition' => $view::POS_READY,
                        'dataset' => [
                            [
                                'datumTokenizer' => "Bloodhound.tokenizers.obj.whitespace('value')",
                                'display' => 'value',
                                'remote' => [
                                    'url' => Url::to([$this->ajaxUrlComuni]) . '?q=%QUERY',
                                    'wildcard' => '%QUERY',
                                ],
                                'limit' => 10,
                            ]
                        ],
                        'pluginEvents' => [
                            "typeahead:selected" => "function(obj, item) { 
                                console.dir(item); 
                                $('#anagraficanascita-comune_hidden').val(item.id);
                                return true; 
                                }",
                            "typeahead:render" => "function() { 
                                console.log('Whatever...'); 
                                }",
                        ]
                    ]
                ],
                'comune_hidden' => ['type' => Form::INPUT_HIDDEN],
                'prov'      => ['type' => Form::INPUT_HIDDEN,'options'=>['value'=>'none']],
                'cap'       => ['type' => Form::INPUT_HIDDEN,'options'=>['value'=>'none']],
                'nazione_id' => ['type' => Form::INPUT_HIDDEN,'options'=>['value'=>'1']],
                'user_id'   => ['type' => Form::INPUT_HIDDEN,'options'=>['value'=>'1']],

            ]
        ]);

        $content .= Form::widget([
            'model' => $this->customForm->anagrafica,
            'form' => $form,
            'columns' => 12,
            'attributes' => [
                'sex'   => [
                    'type'=>Form::INPUT_WIDGET,
                    'widgetClass'=>Select2::className(),
                    'options' => [
                        'theme' => SELECT2::THEME_BOOTSTRAP,
                        'data'=> ['M' => 'Uomo','F' => 'Donna'],
                        'hashVarLoadPosition' => $view::POS_READY,
                        'pluginOptions' => [
                            'prompt'=> "Seleziona Sesso",
                        ],
                    ],
                    'columnOptions'=>['colspan'=>6]
                ],
                'codfis' => [
                    'type' => Form::INPUT_TEXT,
                    'fieldConfig' => [
                        'addon' => [
                            'append' => [
                                'content' => $btnCalcolaCF,
                                'asButton' => true
                            ]
                        ]
                    ],
                    'columnOptions'=>['colspan'=>6]
                ],

            ]
        ]);

        $content .= '<div class="row">'; // apro sezioni ((contatti + indirizzi))


        /*
         * Se abilito i contatti....
         */

        if($this->options['fisica']['contatti'])
        {

            $content .= $this->render('_contatti',[
                    'model'=>$this->customForm->anagrafica,
                    'widget_ID' => $this->id,
                    'form'=> $form]
            );
        }
        if($this->options['fisica']['indirizzi']){

            $content .= $this->render('_indirizzi',[
                    'model'=>$this->customForm->anagrafica,
                    'widget_ID' => $this->id,
                    'form'=> $form]
            );
        }

        $content .= '</div>'; // chiudo sezioni ((contatti + indirizzi))

        return $content;
    }

    private function prepareItemsForTabs(){
        $items = [];
        if($this->options['fisica']['showMe'])
        {
            $items [] = [
                'label'=>'<i class="glyphicon glyphicon-user"></i> Fisica',
                'content'=>$this->render('_fisica',[
                    'fields' => $this->prepareFieldsForFisica(),
                    'ajaxUrlCodiceFiscale'  => $this->ajaxUrlCodiceFiscale,
                    'submitButton'          => $this->submitButton,
                ]),
                'active'=>true
            ];
        }

        if($this->options['giuridica']['showMe'])
        {
            $items [] = [
                'label'=>'<i class="fa fa-briefcase"></i> Giuridica',
                'content'=>$this->render('_giuridica',[
                    'fields' => $this->prepareFieldsForGiuridica(),
                    'submitButton'          => $this->submitButton,
                ]),
                'active'=> false
            ];
        }

        return $items;
    }

    public function run(){
        // you can load & return the view or you can return the output variable
        return $this->render('_anagrafica',
            [
                //'fields'               => $fields,
                'ajaxUrlCodiceFiscale'  => $this->ajaxUrlCodiceFiscale,
                'items'                 => $this->prepareItemsForTabs()
            ]
        );

    }
}
?>