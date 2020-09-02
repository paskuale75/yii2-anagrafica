<?php
namespace paskuale75\anagrafica\migrations;

use Yii;
use yii\db\Migration;

class m200901_120001_indirizzi_tables  extends Migration
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
        if (!in_array('tbl_anagrafica_indirizzi', $tables))  {
            if ($dbType == "mysql") {
                $this->createTable('{{%tbl_anagrafica_indirizzi}}', [
                    'id' => 'INT(11) NOT NULL AUTO_INCREMENT',
                    0 => 'PRIMARY KEY (`id`)',
                    'indirizzo' => 'VARCHAR(100) NOT NULL',
                    'comune_hidden' => 'VARCHAR(65) NULL DEFAULT NULL',
                    'nazione_id' => 'INT(11) NULL DEFAULT NULL',
                    'indirizzo_tipo_id' => 'INT(11) NOT NULL',
                    'cab' => 'VARCHAR(6) NULL COMMENT \'solo per banche\'',
                    'posta' => 'TINYINT(1) NULL',
                    'anagrafica_id' => 'INT(11) NOT NULL',
                    'user_id' => 'INT(11) NULL DEFAULT \'1\'',
                    'last_mod' => 'TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ',
                ], $tableOptions_mysql);
            }
        }


        $this->createIndex('idx_user_id_199_00','tbl_anagrafica_indirizzi','user_id',0);
        $this->createIndex('idx_indirizzo_tipo_id_199_01','tbl_anagrafica_indirizzi','indirizzo_tipo_id',0);
        $this->createIndex('idx_anagrafica_id_199_02','tbl_anagrafica_indirizzi','anagrafica_id',0);
    }



    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        $this->execute('DROP TABLE IF EXISTS `tbl_anagrafica_indirizzi`');
        $this->execute('SET foreign_key_checks = 1;');
    }

}

?>