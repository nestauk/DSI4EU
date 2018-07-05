<?php

use Phinx\Migration\AbstractMigration;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;
use \Models\Cluster;

class AddClusterIsOtherColumn extends AbstractMigration
{
    public function up()
    {
        Capsule::schema()->table(Cluster::TABLE, function (Blueprint $table) {
            $table->boolean(Cluster::IsOther);
        });
    }

    public function down()
    {
        Capsule::schema()->table(Cluster::TABLE, function (Blueprint $table) {
            $table->dropColumn(Cluster::IsOther);
        });
    }
}
