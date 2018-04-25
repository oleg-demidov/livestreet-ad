<?php

class PluginAd_Update_CreateTable extends ModulePluginManager_EntityUpdate
{
    /**
     * Выполняется при обновлении версии
     */
    public function up()
    {
        //$this->exportSQL(Plugin::GetPath(__CLASS__) . '/update/1.0.0/dump.sql');
        
    }

    /**
     * Выполняется при откате версии
     */
    public function down()
    {
         //$this->exportSQL(Plugin::GetPath(__CLASS__) . '/update/1.0.0/drop_dump.sql');
    }
}