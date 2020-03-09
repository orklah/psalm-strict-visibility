<?php

class PrivateTests{
    private function privateMethod1(): void {echo 'private content';}
    private function privateMethod2(): void {echo 'private content';}
    private function privateMethod3(): void {echo 'private content';}

    public function legitCall(): void {
        $this->privateMethod1(); //This won't be flagged
    }

    public function proxyByParam(PrivateTests $a): void {
        $a->privateMethod2(); //This will be flagged
    }

    public function proxyInstantiation(): void {
        $a = new self();
        $a->privateMethod3(); //This will be flagged
    }

}

$a = new PrivateTests();
$b = new PrivateTests();
$a->legitCall();

$a->proxyByParam($b);
$a->proxyInstantiation();
