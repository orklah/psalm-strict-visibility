<?php

class ProtectedTests{
    protected function protectedMethod1(): void {echo 'protected content';}
    protected function protectedMethod2(): void {echo 'protected content';}
    protected function protectedMethod3(): void {echo 'protected content';}
    protected function protectedMethod4(): void {echo 'protected content';}
    protected function protectedMethod5(): void {echo 'protected content';}
    protected function protectedMethod6(): void {echo 'protected content';}
    protected function protectedMethod7(): void {echo 'protected content';}
    protected function protectedMethod8(): void {echo 'protected content';}

    public function legitCall(): void {
        $this->protectedMethod1(); //This won't be flagged
    }

    public function proxyByParam(ProtectedTests $a): void {
        $a->protectedMethod2(); //This will be flagged
    }

    public function proxyInstantiation(): void {
        $a = new self();
        $a->protectedMethod3(); //This will be flagged
    }

    public function proxyChildInstantiation(): void {
        $a = new ProtectedTestsChild();
        $a->protectedMethod4(); //This will be flagged
    }
}

class ProtectedTestsChild extends ProtectedTests{
    public function legitCallChild(): void {
        $this->protectedMethod5(); //This won't be flagged
    }

    public function proxyByParamChild(ProtectedTests $a): void {
        $a->protectedMethod6(); //This will be flagged
    }

    public function proxyInstantiationChild(): void {
        $a = new self();
        $a->protectedMethod7(); //This will be flagged
    }
}

class ProtectedTestsChild2 extends ProtectedTests{
    public function proxySameParentChild(): void{
        $a = new ProtectedTestsChild();
        $a->protectedMethod8(); //This will be flagged
    }
}

$a = new ProtectedTests();
$b = new ProtectedTests();
$c = new ProtectedTestsChild();
$d = new ProtectedTestsChild2();

$a->legitCall();
$c->legitCallChild();

$a->proxyByParam($b);
$a->proxyInstantiation();
$a->proxyChildInstantiation();

$c->proxyByParamChild($b);
$c->proxyInstantiationChild();

$d->proxySameParentChild();
