<?php

use Phinx\Migration\AbstractMigration;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;
use \Models\Relationship\ClusterImg;

class AddClusterImgCaptionColumn extends AbstractMigration
{
    public function up()
    {
        Capsule::schema()->table(ClusterImg::TABLE, function (Blueprint $table) {
            $table->string(ClusterImg::Caption);
        });
    }

    public function down()
    {
        Capsule::schema()->table(ClusterImg::TABLE, function (Blueprint $table) {
            $table->dropColumn(ClusterImg::Caption);
        });
    }
}
