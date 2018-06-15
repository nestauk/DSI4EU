<?php


use Phinx\Migration\AbstractMigration;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

use Models\Relationship\ClusterLang;

class CreateClusterLangsTable extends AbstractMigration
{
    public function up()
    {
        Capsule::schema()->create(ClusterLang::TABLE, function (Blueprint $table) {
            $table->increments(ClusterLang::Id);
            $table->integer(ClusterLang::ClusterID);
            $table->string(ClusterLang::Lang);
            $table->string(ClusterLang::Title);
            $table->text(ClusterLang::Description);
            $table->text(ClusterLang::GetInTouch);
        });
    }

    public function down()
    {
        Capsule::schema()->dropIfExists(ClusterLang::TABLE);
    }
}
