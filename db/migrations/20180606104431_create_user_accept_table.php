<?php
require_once __DIR__ . '/../../src/include.php';

use Phinx\Migration\AbstractMigration;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

use \Models\UserAccept;

class CreateUserAcceptTable extends AbstractMigration
{
    public function up()
    {
        Capsule::schema()->create(UserAccept::TABLE, function (Blueprint $table) {
            $table->increments(UserAccept::Id);
            $table->integer(UserAccept::UserID);
            $table->timestamps();
        });
    }

    public function down()
    {
        Capsule::schema()->dropIfExists(UserAccept::TABLE);
    }
}
