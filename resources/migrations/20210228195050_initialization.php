<?php

use Phoenix\Migration\AbstractMigration;

class Initialization extends AbstractMigration
{
    protected function up(): void
    {
        $this->table('users', 'user_id') /* was id in the Slim 4 Skeleton, but I prefer user_id to make JOINs easier (if written manually) */
            ->setCharset('utf8mb4')
            ->setCollation('utf8mb4_unicode_ci')
            ->addColumn('user_id', 'integer', ['autoincrement' => true]) /* was id in the Slim 4 Skeleton, but I prefer user_id to make JOINs easier (if written manually) */
            ->addColumn('username', 'string', ['null' => true])
            ->addColumn('password', 'string', ['null' => true])
            ->addColumn('email', 'string', ['null' => true])
            ->addColumn('first_name', 'string', ['null' => true])
            ->addColumn('last_name', 'string', ['null' => true])
            ->addColumn('user_role_id', 'integer', ['null' => true, 'default' => 2])
            ->addColumn('locale', 'string', ['null' => true])
            ->addColumn('enabled', 'boolean', ['default' => false])
            ->addIndex('user_role_id', '', 'btree', 'user_role_id')
            ->addIndex('username', 'unique', 'btree', 'username')
            ->create();
    }

    protected function down(): void
    {
        $this->table('users')
            ->drop();
    }
}
