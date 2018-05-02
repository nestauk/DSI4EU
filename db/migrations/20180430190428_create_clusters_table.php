<?php
require_once __DIR__ . '/../../src/config.php';

use Phinx\Migration\AbstractMigration;
use Illuminate\Database\Capsule\Manager as Capsule;

class CreateClustersTable extends AbstractMigration
{
    public function up()
    {
        Capsule::schema()->create(\Models\Cluster::TABLE, function ($table) {
            $table->increments(\Models\Cluster::Id);
        });
    }

    public function down()
    {
        Capsule::schema()->dropIfExists(\Models\Cluster::TABLE);
    }
}
