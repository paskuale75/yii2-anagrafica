<?php
namespace paskuale75\anagrafica\migrations;

use yii\db\Migration;

class m200901_120001_indirizzi_tipo_tables  extends Migration
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
        if (!in_array('tbl_anagrafica_indirizzi_tipo', $tables))  {
            if ($dbType == "mysql") {
                $this->createTable('{{%tbl_anagrafica_indirizzi_tipo}}', [
                    'id' => 'INT(11) NOT NULL AUTO_INCREMENT',
                    0 => 'PRIMARY KEY (`id`)',
                    'descri' => 'VARCHAR(45) NULL',
                    'abbr' => 'VARCHAR(3) NULL',
                    'fixed' => 'INT(1) NOT NULL',
                    'user_id' => 'INT(11) NOT NULL DEFAULT \'1\'',
                    'last_mod' => 'TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ',
                ], $tableOptions_mysql);
            }
        }


        $this->createIndex('idx_user_id_0902_00','tbl_anagrafica_indirizzi_tipo','user_id',0);

        $this->execute('SET foreign_key_checks = 0');
        $this->insert('{{%tbl_anagrafica_indirizzi_tipo}}',['id'=>'1','descri'=>'Sede Legale','abbr'=>'SL','fixed'=>'0','user_id'=>'1','last_mod'=>'2012-11-02 09:34:37']);
        $this->insert('{{%tbl_anagrafica_indirizzi_tipo}}',['id'=>'2','descri'=>'Magazzino','abbr'=>'MAG','fixed'=>'0','user_id'=>'1','last_mod'=>'2012-11-02 09:34:37']);
        $this->insert('{{%tbl_anagrafica_indirizzi_tipo}}',['id'=>'3','descri'=>'Filiale','abbr'=>'FIL','fixed'=>'0','user_id'=>'1','last_mod'=>'2012-11-02 09:34:37']);
        $this->insert('{{%tbl_anagrafica_indirizzi_tipo}}',['id'=>'4','descri'=>'Domicilio','abbr'=>'DOM','fixed'=>'1','user_id'=>'1','last_mod'=>'2013-01-29 03:29:05']);
        $this->insert('{{%tbl_anagrafica_indirizzi_tipo}}',['id'=>'5','descri'=>'Residenza','abbr'=>'RES','fixed'=>'1','user_id'=>'1','last_mod'=>'2013-01-29 03:29:05']);
        $this->execute('SET foreign_key_checks = 1;');
    }



    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        $this->execute('DROP TABLE IF EXISTS `tbl_anagrafica_indirizzi_tipo`');
        $this->execute('SET foreign_key_checks = 1;');
    }

}

?>