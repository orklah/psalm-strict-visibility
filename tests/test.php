<?php

class a{
    private function privateMethod() {
        echo "private content";
    }

    public function callPrivateFlagged(a $azertyuiop) {
        $poiuytreza = 12;
        $azertyuiop->privateMethod();
    }

    public function callPrivateLegit() {
        $this->privateMethod();
    }
}

$a = new a();
$b = new a();
$b->callPrivateFlagged($a);
$a->callPrivateLegit();
