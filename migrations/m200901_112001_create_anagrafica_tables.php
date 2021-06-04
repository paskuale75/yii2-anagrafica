<?php

namespace paskuale75\anagrafica\migrations;

use Yii;
use yii\db\Migration;

class m200901_112001_create_anagrafica_tables extends Migration
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
        if (!in_array('tbl_anagrafica_anagrafiche', $tables))  {
            if ($dbType == "mysql") {
                $this->createTable('{{%tbl_anagrafica_anagrafiche}}', [
                    'id' => 'INT(11) NOT NULL AUTO_INCREMENT',
                    0 => 'PRIMARY KEY (`id`)',
                    'sex' => 'CHAR(1) NULL',
                    'ragione_sociale_1' => 'VARCHAR(45) NULL',
                    'ragione_sociale_2' => 'VARCHAR(45) NULL',
                    'codfis' => 'VARCHAR(16) NULL',
                    'codiva' => 'VARCHAR(11) NULL',
                    'codsdi' => 'VARCHAR(10) NULL',
                    'ruolo' => 'VARCHAR(45) NULL',
                    'titoli_id' => 'INT(11) NULL',
                    'image' => 'VARCHAR(45) NULL',
                    'lang' => 'VARCHAR(20) NULL',
                    'nazione_id' => 'INT(11) NULL',
                    'user_id' => 'INT(11) NOT NULL DEFAULT \'1\'',
                    'last_mod' => 'TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ',
                ], $tableOptions_mysql);
            }
        }

        /* MYSQL */
        if (!in_array('tbl_anagrafica_contatti', $tables))  {
            if ($dbType == "mysql") {
                $this->createTable('{{%tbl_anagrafica_contatti}}', [
                    'id' => 'INT(11) NOT NULL AUTO_INCREMENT',
                    0 => 'PRIMARY KEY (`id`)',
                    'anagrafica_id' => 'INT(11) NULL',
                    'contatto_tipo_id' => 'INT(11) NULL',
                    'valore' => 'VARCHAR(45) NULL',
                    'descri' => 'VARCHAR(45) NULL',
                    'user_id' => 'INT(11) NOT NULL DEFAULT \'1\'',
                    'last_mod' => 'TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ',
                ], $tableOptions_mysql);
            }
        }

        /* MYSQL */
        if (!in_array('tbl_anagrafica_indirizzi', $tables))  {
            if ($dbType == "mysql") {
                $this->createTable('{{%tbl_anagrafica_indirizzi}}', [
                    'id' => 'INT(11) NOT NULL AUTO_INCREMENT',
                    0 => 'PRIMARY KEY (`id`)',
                    'indirizzo' => 'VARCHAR(100) NOT NULL',
                    'comune_hidden' => 'VARCHAR(65) NOT NULL',
                    'prov' => 'VARCHAR(4) NOT NULL',
                    'nazione_id' => 'INT(11) NOT NULL',
                    'cap' => 'VARCHAR(5) NOT NULL',
                    'indirizzo_tipo_id' => 'INT(11) NOT NULL',
                    'cab' => 'VARCHAR(6) NOT NULL COMMENT \'solo per banche\'',
                    'posta' => 'TINYINT(1) NOT NULL',
                    'anagrafica_id' => 'INT(11) NOT NULL',
                    'user_id' => 'INT(11) NOT NULL DEFAULT \'0\'',
                    'last_mod' => 'TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ',
                ], $tableOptions_mysql);
            }
        }

        /* MYSQL */
        if (!in_array('tbl_anagrafica_nascita', $tables))  {
            if ($dbType == "mysql") {
                $this->createTable('{{%tbl_anagrafica_nascita}}', [
                    'id' => 'INT(11) NOT NULL AUTO_INCREMENT',
                    0 => 'PRIMARY KEY (`id`)',
                    'birthdate' => 'DATE NULL',
                    'comune_hidden' => 'VARCHAR(65) NOT NULL',
                    'prov' => 'VARCHAR(4) NULL',
                    'nazione_id' => 'INT(11) NULL',
                    'cap' => 'VARCHAR(5) NULL',
                    'anagrafica_id' => 'INT(11) NOT NULL',
                    'last_mod' => 'TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ',
                ], $tableOptions_mysql);
            }
        }


        //$this->createIndex('idx_user_id_561_00','tbl_anagrafica_anagrafiche','user_id',0);
        $this->createIndex('idx_titoli_id_561_01','tbl_anagrafica_anagrafiche','titoli_id',0);
        //$this->createIndex('idx_user_id_5627_02','tbl_anagrafica_contatti','user_id',0);
        $this->createIndex('idx_anagrafica_id_5627_03','tbl_anagrafica_contatti','anagrafica_id',0);
        $this->createIndex('idx_contatto_tipo_id_5627_04','tbl_anagrafica_contatti','contatto_tipo_id',0);
        //$this->createIndex('idx_user_id_5647_05','tbl_anagrafica_indirizzi','user_id',0);
        $this->createIndex('idx_indirizzo_tipo_id_5647_06','tbl_anagrafica_indirizzi','indirizzo_tipo_id',0);
        $this->createIndex('idx_anagrafica_id_5647_07','tbl_anagrafica_indirizzi','anagrafica_id',0);
        //$this->createIndex('idx_user_id_5665_08','tbl_anagrafica_nascita','user_id',0);
        $this->createIndex('idx_anagrafica_id_5665_09','tbl_anagrafica_nascita','anagrafica_id',0);
        $this->createIndex('idx_nazione_id_5665_10','tbl_anagrafica_nascita','nazione_id',0);
    }



    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        $this->execute('DROP TABLE IF EXISTS `tbl_anagrafica_anagrafiche`');
        $this->execute('SET foreign_key_checks = 1;');
        $this->execute('SET foreign_key_checks = 0');
        $this->execute('DROP TABLE IF EXISTS `tbl_anagrafica_contatti`');
        $this->execute('SET foreign_key_checks = 1;');
        $this->execute('SET foreign_key_checks = 0');
        $this->execute('DROP TABLE IF EXISTS `tbl_anagrafica_indirizzi`');
        $this->execute('SET foreign_key_checks = 1;');
        $this->execute('SET foreign_key_checks = 0');
        $this->execute('DROP TABLE IF EXISTS `tbl_anagrafica_nascita`');
        $this->execute('SET foreign_key_checks = 1;');
    }

}

?>