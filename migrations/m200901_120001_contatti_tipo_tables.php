<?php
namespace paskuale75\anagrafica\migrations;

use Yii;
use yii\db\Migration;

class m200901_120001_contatti_tipo_tables  extends Migration
{
    public function up()
    {
        $tables = Yii::$app->db->schema->getTableNames();
        $dbType = $this->db->driverName;
        $tableOptions_mysql = "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB";
        $tableOptions_mssql = "";
        $tableOptions_pgsql = "";
        $tableOptions_sqlite = "";
        /* MYSQL */
        if (!in_array('tbl_anagrafica_contatti_tipo', $tables))  {
            if ($dbType == "mysql") {
                $this->createTable('{{%tbl_anagrafica_contatti_tipo}}', [
                    'contatto_tipo_id' => 'INT(11) NOT NULL AUTO_INCREMENT',
                    0 => 'PRIMARY KEY (`contatto_tipo_id`)',
                    'descri' => 'VARCHAR(50) NOT NULL',
                    'abbr' => 'VARCHAR(10) NOT NULL',
                ], $tableOptions_mysql);
            }
        }


        $this->execute('SET foreign_key_checks = 0');
        $this->insert('{{%tbl_anagrafica_contatti_tipo}}',['contatto_tipo_id'=>'1','descri'=>'telefono','abbr'=>'home']);
        $this->insert('{{%tbl_anagrafica_contatti_tipo}}',['contatto_tipo_id'=>'2','descri'=>'fax','abbr'=>'']);
        $this->insert('{{%tbl_anagrafica_contatti_tipo}}',['contatto_tipo_id'=>'3','descri'=>'cellulare','abbr'=>'']);
        $this->insert('{{%tbl_anagrafica_contatti_tipo}}',['contatto_tipo_id'=>'4','descri'=>'email','abbr'=>'']);
        $this->execute('SET foreign_key_checks = 1;');
    }



    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        $this->execute('DROP TABLE IF EXISTS `tbl_anagrafica_contatti_tipo`');
        $this->execute('SET foreign_key_checks = 1;');
    }

}

?>