<?php


use Phinx\Migration\AbstractMigration;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

use Models\EmailSubscription;

class CreateEmailSubscriptionsTable extends AbstractMigration
{
    public function up()
    {
        Capsule::schema()->create(EmailSubscription::TABLE, function (Blueprint $table) {
            $table->increments(EmailSubscription::Id);
            $table->integer(EmailSubscription::UserID);
            $table->boolean(EmailSubscription::Subscribed);
            $table->timestamps();
        });
    }

    public function down()
    {
        Capsule::schema()->dropIfExists(EmailSubscription::TABLE);
    }
}
