<?php
require_once __DIR__ . '/../../src/include.php';

use Phinx\Migration\AbstractMigration;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

class UpdateNullableColumns extends AbstractMigration
{
    public function up()
    {
        Capsule::schema()->table(\Models\ClusterLang::TABLE, function (Blueprint $table) {
            $table->text(\Models\ClusterLang::Description)->nullable()->change();
            $table->text(\Models\ClusterLang::GetInTouch)->nullable()->change();
        });
        Capsule::schema()->table(\Models\ClusterLang::TABLE, function (Blueprint $table) {
            $table->string(\Models\ClusterLang::Subtitle)->nullable()->change();
        });
        Capsule::schema()->table(\Models\ClusterLang::TABLE, function (Blueprint $table) {
            $table->string(\Models\ClusterLang::Subtitle)->nullable()->change();
        });
    }

    public function down()
    {
        Capsule::schema()->table(\Models\ClusterLang::TABLE, function (Blueprint $table) {
            $table->text(\Models\ClusterLang::Description)->nullable(false)->change();
            $table->text(\Models\ClusterLang::GetInTouch)->nullable(false)->change();
        });
        Capsule::schema()->table(\Models\ClusterLang::TABLE, function (Blueprint $table) {
            $table->string(\Models\ClusterLang::Subtitle)->nullable(false)->change();
        });
        Capsule::schema()->table(\Models\ClusterLang::TABLE, function (Blueprint $table) {
            $table->string(\Models\ClusterLang::Subtitle)->nullable(false)->change();
        });
    }
}
