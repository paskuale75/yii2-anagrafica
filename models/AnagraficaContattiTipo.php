<?php

namespace paskuale75\anagrafica\models;

use Yii;

/**
 * This is the model class for table "tbl_anagrafica_contatti_tipo".
 *
 * @property int $contatto_tipo_id
 * @property string $descri
 * @property string $abbr
 */
class AnagraficaContattiTipo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_anagrafica_contatti_tipo';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['descri', 'abbr'], 'required'],
            [['descri'], 'string', 'max' => 50],
            [['abbr'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'contatto_tipo_id' => 'Contatto Tipo ID',
            'descri' => 'Descri',
            'abbr' => 'Abbr',
        ];
    }
}