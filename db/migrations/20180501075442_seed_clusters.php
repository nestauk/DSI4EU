<?php
require_once __DIR__ . '/../../src/include.php';

use Phinx\Migration\AbstractMigration;
use \Models\Cluster;
use Illuminate\Database\Capsule\Manager as Capsule;

class SeedClusters extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function up()
    {
        $data = [
            [],
            [],
            [],
            [],
            [],
            [],
        ];

        Capsule::table(Cluster::TABLE)->insert($data);
    }
}
