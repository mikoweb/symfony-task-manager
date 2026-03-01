<?php

namespace App\Core\Bus;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Messenger\Stamp\DelayStamp;
use Symfony\Component\Messenger\Stamp\StampInterface;

/**
 * @extends ArrayCollection<int, StampInterface>
 */
class StampCollection extends ArrayCollection
{
    /**
     * @param array<int, StampInterface> $stamps
     */
    public static function createWithDelay(int $delayMs, array $stamps = []): self
    {
        $collection = new self($stamps);
        $collection->add(new DelayStamp($delayMs));

        return $collection;
    }
}
