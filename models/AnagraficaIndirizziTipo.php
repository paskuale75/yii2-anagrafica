<?php

namespace paskuale75\anagrafica\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "tbl_anagrafica_indirizzi_tipo".
 *
 * @property int $id
 * @property string $descri
 * @property string $abbr
 * @property int $fixed
 * @property int $user_id
 * @property string $last_mod
 */
class AnagraficaIndirizziTipo extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_anagrafica_indirizzi_tipo';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fixed'], 'required'],
            [['fixed', 'user_id'], 'integer'],
            [['last_mod'], 'safe'],
            [['descri'], 'string', 'max' => 45],
            [['abbr'], 'string', 'max' => 3],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'descri' => 'Descrizione',
            'abbr' => 'Abbr',
            'fixed' => 'Fixed',
            'user_id' => 'User ID',
            'last_mod' => 'Last Mod',
        ];
    }
}