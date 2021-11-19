<?php

namespace App\Domain\Bubble\Data;

use Selective\ArrayReader\ArrayReader;

/**
 * Data Model.
 */
final class BubbleData
{
    public ?int $bubble_id = null;

    public ?int $category = null;

    public ?int $cx = null;

    public ?int $cy = null;

    public ?int $radius = null;

    public ?string $fill = null;

    public ?int $createdById = null;


    /**
     * The constructor.
     *
     * @param array $data The data
     */
    public function __construct(array $data = [])
    {
        $reader = new ArrayReader($data);

        $this->bubble_id = $reader->findInt('bubble_id');
        $this->category = $reader->findInt('category');
        $this->cx = $reader->findInt('cx');
        $this->cy = $reader->findInt('cy');
        $this->radius = $reader->findInt('radius');
        $this->fill = $reader->findString('fill');
        $this->createdById = $reader->findInt('created_by_id');
    }
}
