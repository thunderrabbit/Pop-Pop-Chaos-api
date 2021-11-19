<?php

namespace App\Domain\Bubble\Repository;

use App\Domain\Bubble\Data\BubbleData;
use App\Factory\QueryFactory;
use DomainException;

/**
 * Repository.
 */
final class BubbleRepository
{
    private QueryFactory $queryFactory;

    /**
     * The constructor.
     *
     * @param QueryFactory $queryFactory The query factory
     */
    public function __construct(QueryFactory $queryFactory)
    {
        $this->queryFactory = $queryFactory;
    }

    /**
     * Insert user row.
     *
     * @param BubbleData $bubble The user data
     *
     * @return int The new ID
     */
    public function insertBubble(BubbleData $bubble): int
    {
        return (int)$this->queryFactory->newInsert('bubbles', $this->toRow($bubble))
            ->execute()
            ->lastInsertId();
    }

    /**
     * Get user by id.
     *
     * @param int $bubbleId The user id
     *
     * @throws DomainException
     *
     * @return BubbleData The user
     */
    public function getBubbleById(int $bubbleId): BubbleData
    {
        $query = $this->queryFactory->newSelect('bubbles');
        $query->select(
            [
                'id',
                'username',
                'first_name',
                'last_name',
                'email',
                'user_role_id',
                'locale',
                'enabled',
            ]
        );

        $query->andWhere(['id' => $bubbleId]);

        $row = $query->execute()->fetch('assoc');

        if (!$row) {
            throw new DomainException(sprintf('Bubble not found: %s', $bubbleId));
        }

        return new BubbleData($row);
    }

    /**
     * Update user row.
     *
     * @param BubbleData $bubble The user
     *
     * @return void
     */
    public function updateBubble(BubbleData $bubble): void
    {
        $row = $this->toRow($bubble);

        // Updating the password is another use case
        unset($row['password']);

        $this->queryFactory->newUpdate('bubbles', $row)
            ->andWhere(['id' => $bubble->id])
            ->execute();
    }

    /**
     * Check user id.
     *
     * @param int $bubbleId The user id
     *
     * @return bool True if exists
     */
    public function existsBubbleId(int $bubbleId): bool
    {
        $query = $this->queryFactory->newSelect('bubbles');
        $query->select('id')->andWhere(['id' => $bubbleId]);

        return (bool)$query->execute()->fetch('assoc');
    }

    /**
     * Delete user row.
     *
     * @param int $bubbleId The user id
     *
     * @return void
     */
    public function deleteBubbleById(int $bubbleId): void
    {
        $this->queryFactory->newDelete('bubbles')
            ->andWhere(['id' => $bubbleId])
            ->execute();
    }

    /**
     * Convert to array.
     *
     * @param BubbleData $bubble The user data
     *
     * @return array The array
     */
    private function toRow(BubbleData $bubble): array
    {
        return [
            'id' => $bubble->id,
            'username' => $bubble->username,
            'password' => $bubble->password,
            'first_name' => $bubble->firstName,
            'last_name' => $bubble->lastName,
            'email' => $bubble->email,
            'user_role_id' => $bubble->userRoleId,
            'locale' => $bubble->locale,
            'enabled' => (int)$bubble->enabled,
        ];
    }
}
