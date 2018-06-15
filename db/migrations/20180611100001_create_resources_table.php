<?php


use Phinx\Migration\AbstractMigration;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

use \Models\Resource;

class CreateResourcesTable extends AbstractMigration
{
    public function up()
    {
        Capsule::schema()->create(Resource::TABLE, function (Blueprint $table) {
            $table->increments(Resource::Id);
            $table->string(Resource::Image);
            $table->string(Resource::Title);
            $table->text(Resource::Description);
            $table->text(Resource::LinkUrl);
            $table->string(Resource::LinkText);
        });
    }

    public function down()
    {
        Capsule::schema()->dropIfExists(Resource::TABLE);
    }
}
