<?php
require_once __DIR__ . '/../../src/include.php';

use Phinx\Migration\AbstractMigration;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

use \Models\Text;

class CreateTextsTable extends AbstractMigration
{
    public function up()
    {
        Capsule::schema()->create(Text::TABLE, function (Blueprint $table) {
            $table->increments(Text::Id);
            $table->string(Text::Identifier);
            $table->longText(Text::Copy)->default('');
            $table->char(Text::Lang, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Capsule::schema()->dropIfExists(Text::TABLE);
    }
}
