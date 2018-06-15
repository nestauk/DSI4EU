<?php
require_once __DIR__ . '/../../src/include.php';

use Phinx\Migration\AbstractMigration;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

use \Models\Relationship\ResourceCluster;

class CreateResourceClusterTable extends AbstractMigration
{
    public function up()
    {
        Capsule::schema()->create(ResourceCluster::TABLE, function (Blueprint $table) {
            $table->integer(ResourceCluster::ResourceID);
            $table->integer(ResourceCluster::ClusterID);
            $table->unique([ResourceCluster::ResourceID, ResourceCluster::ClusterID], 'resource_cluster');
        });


    }

    public function down()
    {
        Capsule::schema()->dropIfExists(ResourceCluster::TABLE);
    }
}
