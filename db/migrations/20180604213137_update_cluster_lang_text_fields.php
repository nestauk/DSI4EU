<?php
require_once __DIR__ . '/../../src/include.php';

use Phinx\Migration\AbstractMigration;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

class UpdateClusterLangTextFields extends AbstractMigration
{
    public function up()
    {
        Capsule::schema()->table(\Models\ClusterLang::TABLE, function (Blueprint $table) {
            $table->text(\Models\ClusterLang::Paragraph)->nullable()->change();
        });
    }

    public function down()
    {
        Capsule::schema()->table(\Models\ClusterLang::TABLE, function (Blueprint $table) {
            $table->text(\Models\ClusterLang::Paragraph)->nullable(false)->change();
        });
    }
}
