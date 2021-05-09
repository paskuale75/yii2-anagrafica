<?php

namespace paskuale75\anagrafica;

use Yii;
use yii\base\InvalidConfigException;
use yii\base\Module as YiiModule;
use yii\base\BootstrapInterface;
use yii\console\Application as ConsoleApplication;
use yii\web\Application as WebApplication;
use yii\web\GroupUrlRule;
use yii\web\UserEvent;
use yii\helpers\ArrayHelper;

class Module extends YiiModule implements BootstrapInterface
{

    /**
     * @var string  yii\db\BaseActiveRecord class name; used as USER
     */
    public $userClass;

    public $belFioreColumn = 'cod_fisco';

    public $externalTableConstant = [];


    /**
     * {@inheritdoc}
     *
     * @throws InvalidConfigException
     */
    public function bootstrap($app)
    {
        if ($app instanceof WebApplication) {
            /* $rules = new GroupUrlRule([
                'prefix' => $this->id,
                'rules' => [
                    '<a:(confirm|recover)>/<token:[A-Za-z0-9_-]+>' => 'default/<a>',
                    '<a:[\w\-]+>/<id:\d+>' => 'default/<a>',
                    '<c:[\w\-]+>/<a:[\w\-]+>/<id:[\w\-]+>' => '<c>/<a>',
                    '<c:(user|role|permission)>' => '<c>/index',
                    '<a:[\w\-]+>' => 'default/<a>',
                ]
            ]);
            $app->getUrlManager()->addRules([$rules], false);

            $app->setComponents([
                'user' => ArrayHelper::merge($app->components['user'], [
                    'identityClass' => $this->identityClass,
                    'loginUrl' => [$this->id . '/default/login'],
                    'on beforeLogin' => [$this, 'beforeLogin']
                ]),
            ]);

            if ($this->fenceMode !== false) {
                $app->on(WebApplication::EVENT_BEFORE_ACTION, function ($event) {
                    $forbidden = false;
                    $user = Yii::$app->user;
                    if ($user->isGuest) {
                        $forbidden = true;
                    } else if (is_string($this->fenceMode) && !$user->can($this->fenceMode)) {
                        $user->logout();
                        $forbidden = true;
                    }
                    if ($forbidden) {
                        $action = $event->action->id;
                        if (
                            $event->action->controller->module->id != $this->id ||
                            !in_array($action, ['login', 'error', 'forgot', 'recover'])
                        ) {
                            $event->isValid = false;
                            return $user->loginRequired();
                        }
                    }
                    return null;
                });
            }

            if (empty($this->formClass)) $this->formClass = $this->bootstrapNamespace() . '\ActiveForm'; */
        } else {
            /* @var $app ConsoleApplication */

            $app->controllerMap = ArrayHelper::merge($app->controllerMap, [
                'migrate' => [
                    'class' => '\yii\console\controllers\MigrateController',
                    'migrationNamespaces' => [
                        'paskuale75\anagrafica\migrations'
                    ]
                ],
                //'pluto' => 'sjaakp\pluto\commands\PlutoController'
            ]);
        }
    }
}
