<?php
require_once __DIR__ . '/../../src/config.php';

use Phinx\Migration\AbstractMigration;
use Illuminate\Database\Capsule\Manager as Capsule;

class CreateClusterLangsTable extends AbstractMigration
{
    public function up()
    {
        Capsule::schema()->create(\Models\ClusterLang::TABLE, function ($table) {
            $table->increments(\Models\ClusterLang::Id);
            $table->integer(\Models\ClusterLang::ClusterID);
            $table->string(\Models\ClusterLang::Lang);
            $table->string(\Models\ClusterLang::Title);
            $table->text(\Models\ClusterLang::Description);
            $table->text(\Models\ClusterLang::GetInTouch);
        });
    }

    public function down()
    {
        Capsule::schema()->dropIfExists(\Models\ClusterLang::TABLE);
    }
}
