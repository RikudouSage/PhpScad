<?php

namespace Rikudou\PhpScad\Value;

use Rikudou\PhpScad\Implementation\Wither;

final class RangeValue extends Literal
{
    use Wither;

    public function __construct(
        private readonly float $start,
        private readonly float $end,
        private readonly float $step = 1,
    ) {
        parent::__construct([$this->start, $this->step, $this->end]);
    }

    /**
     * @return array<float>
     */
    public function getValue(): array
    {
        return parent::getValue();
    }

    public function getScadRepresentation(): string
    {
        return "[{$this->start}:{$this->step}:{$this->end}]";
    }

    public function withStep(float $step): self
    {
        return $this->with('step', $step);
    }

    public function withStart(float $start): self
    {
        return $this->with('start', $start);
    }

    public function withEnd(float $end): self
    {
        return $this->with('end', $end);
    }
}
