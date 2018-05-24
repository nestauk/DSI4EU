<?php
require_once __DIR__ . '/../../src/include.php';

use Phinx\Migration\AbstractMigration;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;
use Models\Tag;

class AddTagsTypeColumns extends AbstractMigration
{
    public function up()
    {
        Capsule::schema()->table(Tag::TABLE, function (Blueprint $table) {
            $table->boolean(Tag::IsImpact);
            $table->boolean(Tag::IsTechnology);
        });
    }

    public function down()
    {
        Capsule::schema()->table(Tag::TABLE, function (Blueprint $table) {
            $table->dropColumn(Tag::IsImpact);
            $table->dropColumn(Tag::IsTechnology);
        });
    }
}
