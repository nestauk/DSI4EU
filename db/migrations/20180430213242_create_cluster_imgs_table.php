<?php
require_once __DIR__ . '/../../src/include.php';

use Phinx\Migration\AbstractMigration;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

use Models\Relationship\ClusterImg;

class CreateClusterImgsTable extends AbstractMigration
{
    public function up()
    {
        Capsule::schema()->create(ClusterImg::TABLE, function (Blueprint $table) {
            $table->increments(ClusterImg::Id);
            $table->integer(ClusterImg::ClusterLangID);
            $table->string(ClusterImg::Filename);
            $table->text(ClusterImg::Link);
        });
    }

    public function down()
    {
        Capsule::schema()->dropIfExists(ClusterImg::TABLE);
    }
}