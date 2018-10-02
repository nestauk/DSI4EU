<?php

use Phinx\Migration\AbstractMigration;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

use \Models\Relationship\CaseStudyTag;

class CreateCaseStudyTagTable extends AbstractMigration
{
    public function up()
    {
        Capsule::schema()->create(CaseStudyTag::TABLE, function (Blueprint $table) {
            $table->integer(CaseStudyTag::CaseStudyID);
            $table->integer(CaseStudyTag::TagID);
            $table->unique([CaseStudyTag::CaseStudyID, CaseStudyTag::TagID], 'case_study_tag');
        });


    }

    public function down()
    {
        Capsule::schema()->dropIfExists(CaseStudyTag::TABLE);
    }
}
