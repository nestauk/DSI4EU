<?php
require_once __DIR__ . '/../../src/include.php';

use Phinx\Migration\AbstractMigration;
use Illuminate\Database\Capsule\Manager as Capsule;

class CreateClusterImgsTable extends AbstractMigration
{
    public function up()
    {
        Capsule::schema()->create(\Models\ClusterImg::TABLE, function ($table) {
            $table->increments(\Models\ClusterImg::Id);
            $table->integer(\Models\ClusterImg::ClusterLangID);
            $table->string(\Models\ClusterImg::Filename);
            $table->text(\Models\ClusterImg::Link);
        });
    }

    public function down()
    {
        Capsule::schema()->dropIfExists(\Models\ClusterImg::TABLE);
    }
}