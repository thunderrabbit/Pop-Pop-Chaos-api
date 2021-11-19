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
                'bubble_id',
                'category',
                'cx',
                'cy',
                'radius',
                'fill',
                'created_by_id',
            ]
        );

        $query->andWhere(['bubble_id' => $bubbleId]);

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

        $this->queryFactory->newUpdate('bubbles', $row)
            ->andWhere(['bubble_id' => $bubble->bubble_id])
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
        $query->select('bubble_id')->andWhere(['bubble_id' => $bubbleId]);

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
            ->andWhere(['bubble_id' => $bubbleId])
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
            'bubble_id' => $bubble->bubble_id,
            'category' => $bubble->category,
            'cx' => $bubble->cx,
            'cy' => $bubble->cy,
            'radius' => $bubble->radius,
            'fill' => $bubble->fill,
            'created_by_id' => $bubble->createdById,
        ];
    }
}
