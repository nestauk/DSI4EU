<?php
require_once __DIR__ . '/../../src/include.php';

use Phinx\Migration\AbstractMigration;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

use \Models\Relationship\ClusterLang;

class UpdateNullableColumns extends AbstractMigration
{
    public function up()
    {
        Capsule::schema()->table(ClusterLang::TABLE, function (Blueprint $table) {
            $table->text(ClusterLang::Description)->nullable()->change();
            $table->text(ClusterLang::GetInTouch)->nullable()->change();
        });
        Capsule::schema()->table(ClusterLang::TABLE, function (Blueprint $table) {
            $table->string(ClusterLang::Subtitle)->nullable()->change();
        });
        Capsule::schema()->table(ClusterLang::TABLE, function (Blueprint $table) {
            $table->string(ClusterLang::Subtitle)->nullable()->change();
        });
    }

    public function down()
    {
        Capsule::schema()->table(ClusterLang::TABLE, function (Blueprint $table) {
            $table->text(ClusterLang::Description)->nullable(false)->change();
            $table->text(ClusterLang::GetInTouch)->nullable(false)->change();
        });
        Capsule::schema()->table(ClusterLang::TABLE, function (Blueprint $table) {
            $table->string(ClusterLang::Subtitle)->nullable(false)->change();
        });
        Capsule::schema()->table(ClusterLang::TABLE, function (Blueprint $table) {
            $table->string(ClusterLang::Subtitle)->nullable(false)->change();
        });
    }
}
