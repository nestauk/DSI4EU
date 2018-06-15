<?php


use Phinx\Migration\AbstractMigration;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

use \Models\Relationship\ResourceType;

class CreateResourceTypeTable extends AbstractMigration
{
    public function up()
    {
        Capsule::schema()->create(ResourceType::TABLE, function (Blueprint $table) {
            $table->integer(ResourceType::ResourceID);
            $table->integer(ResourceType::TypeID);
            $table->unique([ResourceType::ResourceID, ResourceType::TypeID], 'resource_type');
        });


    }

    public function down()
    {
        Capsule::schema()->dropIfExists(ResourceType::TABLE);
    }
}
