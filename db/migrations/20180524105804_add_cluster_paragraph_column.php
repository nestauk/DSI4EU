<?php
require_once __DIR__ . '/../../src/include.php';

use Phinx\Migration\AbstractMigration;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;
use Models\Relationship\ClusterLang;

class AddClusterParagraphColumn extends AbstractMigration
{
    public function up()
    {
        Capsule::schema()->table(ClusterLang::TABLE, function (Blueprint $table) {
            $table->string(ClusterLang::Paragraph);
        });
    }

    public function down()
    {
        Capsule::schema()->table(ClusterLang::TABLE, function (Blueprint $table) {
            $table->dropColumn(ClusterLang::Paragraph);
        });
    }
}
