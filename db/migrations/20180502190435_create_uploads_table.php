<?php


use Phinx\Migration\AbstractMigration;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

class CreateUploadsTable extends AbstractMigration
{
    public function up()
    {
        Capsule::schema()->create(\Models\Upload::TABLE, function (Blueprint $table) {
            $table->increments(\Models\Upload::Id);
            $table->string(\Models\Upload::Filename);
        });
    }

    public function down()
    {
        Capsule::schema()->dropIfExists(\Models\Upload::TABLE);
    }
}
