<?php

class a{
    private function privateMethod(): void {
        echo "private content";
    }

    public function callPrivateFlagged(a $a): void {
        $a->privateMethod();
    }

    public function callPrivateLegit(): void {
        $this->privateMethod();
    }
}

$a = new a();
$b = new a();
$b->callPrivateFlagged($a);
$a->callPrivateLegit();
