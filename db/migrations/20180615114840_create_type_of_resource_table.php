<?php

// print_r($configuration);

use Phinx\Migration\AbstractMigration;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

use \Models\TypeOfResource;

class CreateTypeOfResourceTable extends AbstractMigration
{
    public function up()
    {
        Capsule::schema()->create(TypeOfResource::TABLE, function (Blueprint $table) {
            $table->increments(TypeOfResource::Id);
            $table->string(TypeOfResource::Name);
        });
    }

    public function down()
    {
        Capsule::schema()->dropIfExists(TypeOfResource::TABLE);
    }
}
