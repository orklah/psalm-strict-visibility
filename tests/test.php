<?php

class a{
    private function privateMethod() {
        echo "private content";
    }

    public function callPrivateFlagged(a $a) {
        $a->privateMethod();
    }

    public function callPrivateLegit() {
        $this->privateMethod();
    }
}

$a = new a();
$b = new a();
$b->callPrivateFlagged($a);
$a->callPrivateLegit();
