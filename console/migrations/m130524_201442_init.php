<?php

use yii\db\Migration;

class m130524_201442_init extends Migration
{

    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user}}', [
            'id'                   => $this->primaryKey(),
            'auth_key'             => $this->string(32)->notNull(),
            'password_hash'        => $this->string()->notNull(),
            'password_reset_token' => $this->string()->unique(),
            'email'                => $this->string()->notNull()->unique(),
            'name'                 => $this->string()->notNull(),
            'sex'                  => $this->string()->notNull(),
            'location'             => $this->string()->notNull(),
            'status'               => $this->string()->notNull(),
            'role'                 => $this->string(),
            'activate_code'        => $this->string(),
            'invite_by_user'       => $this->integer()->notNull(),
            'sent_date'            => $this->integer()->notNull(),
            'registration_date'    => $this->integer()->notNull(),
            'created_at'           => $this->integer()->notNull(),
            'updated_at'           => $this->integer()->notNull(),
        ], $tableOptions);

        /**
         * Seeding data.
         * We also can use use faker
         */


        $status = ['blocked', 'registered', 'invited', 'deleted'];

        $this->insert('user', [
            'name'              => 'admin',
            'email'             => 'admin@admin.ua',
            'sex'               => 24,
            'location'          => 'Ukraine',
            'status'            => 'registered',
            'password_hash'     => Yii::$app->security->generatePasswordHash('111111'),
            'role'              => 'admin',
            'invite_by_user'    => 0,
            'sent_date'         => time(),
            'registration_date' => time()
        ]);


        for ($i = 0; $i < 100; $i++) {
            $new_status = $status[mt_rand(0, 3)];
            $invite = 'registered' ? 0 : mt_rand(1, 1000);

            $this->insert('user', [
                'name'              => 'test' . $i,
                'email'             => 'test@test.ua' . $i,
                'sex'               => mt_rand(1, 100),
                'location'          => 'country' . $i,
                'password_hash'     => mt_rand(100000, 999999),
                'status'            => $new_status,
                'invite_by_user'    => $invite,
                'sent_date'         => time(),
                'registration_date' => time()
            ]);
        }
    }

    public function down()
    {
        $this->dropTable('{{%user}}');
    }
}
