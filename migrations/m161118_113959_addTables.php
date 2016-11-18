<?php

use yii\db\Migration;

class m161118_113959_addTables extends Migration
{
    protected $tabs = [];
    protected $data = [];

    public function init()
    {
        parent::init();
        $this->tabs = [
            'provider' => [
                'id'   => $this->primaryKey(),
                'name' => $this->string(255),
            ],
            'product'  => [
                'id'          => $this->primaryKey(),
                'provider_id' => $this->integer(),
                'name'        => $this->string(255),
                'img'         => $this->string()
            ]
        ];

        $this->data = [
            'provider' => [
                ['id' => 1, 'name' => 'поставщик из БД 1'],
                ['id' => 2, 'name' => 'поставщик из БД 2'],
                ['id' => 3, 'name' => 'поставщик из БД 3'],
                ['id' => 4, 'name' => 'поставщик из БД 4'],
            ],
            'product'  => [
                ['id' => 1, 'provider_id' => 1, 'name' => 'товар из БД 1'],
                ['id' => 2, 'provider_id' => 2, 'name' => 'товар из БД 2'],
                ['id' => 3, 'provider_id' => 3, 'name' => 'товар из БД 3'],
                ['id' => 4, 'provider_id' => 4, 'name' => 'товар из БД 4'],
                ['id' => 5, 'provider_id' => 1, 'name' => 'товар из БД 5'],
            ]
        ];
    }

    public function safeUp()
    {
        foreach ($this->tabs as $tab => $fields) {
            $this->createTable($tab, $fields);
        }

        foreach ($this->data as $tab => $rows) {
            foreach ($rows as $row) {
                $this->insert($tab, $row);
            }
        }

        return true;
    }

    public function safeDown()
    {
        foreach (array_keys($this->tabs) as $tab) {
            $this->dropTable($tab);
        }
    }
}
