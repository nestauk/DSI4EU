<?php


use Phinx\Migration\AbstractMigration;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;
use \Models\Resource;

class AddResourceAuthorIdColumn extends AbstractMigration
{
    public function up()
    {
        Capsule::schema()->table(Resource::TABLE, function (Blueprint $table) {
            $table->integer(Resource::AuthorID);
        });
    }

    public function down()
    {
        Capsule::schema()->table(Resource::TABLE, function (Blueprint $table) {
            $table->dropColumn(Resource::AuthorID);
        });
    }
}
