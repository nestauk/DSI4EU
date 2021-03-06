<?php

use Phinx\Migration\AbstractMigration;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

class CreateClustersTable extends AbstractMigration
{
    public function up()
    {
        Capsule::schema()->create(\Models\Cluster::TABLE, function (Blueprint $table) {
            $table->increments(\Models\Cluster::Id);
        });
    }

    public function down()
    {
        Capsule::schema()->dropIfExists(\Models\Cluster::TABLE);
    }
}
