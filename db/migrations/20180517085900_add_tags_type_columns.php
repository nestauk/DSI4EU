<?php


use Phinx\Migration\AbstractMigration;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;
use Models\Tag;

class AddTagsTypeColumns extends AbstractMigration
{
    public function up()
    {
        Capsule::schema()->table(Tag::TABLE, function (Blueprint $table) {
            $table->boolean(Tag::IsTechnologyMain);
            $table->integer(Tag::TechnologyOrder);
        });
    }

    public function down()
    {
        Capsule::schema()->table(Tag::TABLE, function (Blueprint $table) {
            $table->dropColumn(Tag::IsTechnologyMain);
            $table->dropColumn(Tag::TechnologyOrder);
        });
    }
}
