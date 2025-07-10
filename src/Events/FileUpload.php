<?php

namespace PISystems\ExactOnline\Events;

use PISystems\ExactOnline\Exact;

class FileUpload extends AbstractConfiguredEvent
{

    public ?string $denyReason {
        get {
            return $this->propagationStopped ? $this->denyReason : null;
        }
        set {
            if ($this->propagationStopped) {
                throw new \LogicException("Propagation has already stopped, denyReason cannot be set anymore.");
            }
            $this->propagationStopped = true;
            $this->denyReason = $value;
        }
    }

    public function __construct(Exact $exact, public readonly \SplFileInfo $file)
    {
        parent::__construct($exact);
    }

    public function approve(): static
    {
        $this->propagationStopped = false;
        return $this;
    }

    public function stopPropagation(?string $reason = null): static
    {
        trigger_error("stopPropagation() should not be called directly on this event, please use deny(...) with a valid reason argument.");

        $this->deny($reason ?? "No reason specified");
        return $this;
    }

    public function deny(string $reason): static
    {
        $this->denyReason = $reason;
        $this->propagationStopped = true;
        return $this;
    }
}