<?php
require_once __DIR__ . '/../../src/include.php';

use Phinx\Migration\AbstractMigration;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

use \Models\AuthorOfResource;

class CreateAuthorOfResourceTable extends AbstractMigration
{
    public function up()
    {
        Capsule::schema()->create(AuthorOfResource::TABLE, function (Blueprint $table) {
            $table->increments(AuthorOfResource::Id);
            $table->string(AuthorOfResource::Name);
        });
    }

    public function down()
    {
        Capsule::schema()->dropIfExists(AuthorOfResource::TABLE);
    }
}
