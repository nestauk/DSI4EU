<?php
require_once __DIR__ . '/../../src/include.php';

use Phinx\Migration\AbstractMigration;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;
use DSI\Entity\User;

class AddUserEmailSubscriptionColumn extends AbstractMigration
{
    public function up()
    {
        Capsule::schema()->table(User::TABLE, function (Blueprint $table) {
            $table->boolean(User::EmailSubscription);
        });
    }

    public function down()
    {
        Capsule::schema()->table(User::TABLE, function (Blueprint $table) {
            $table->dropColumn(User::EmailSubscription);
        });
    }
}
