<?php
namespace PhpMigration\Changes\v5dot3;

/**
 * @author Yuchen Wang <phobosw@gmail.com>
 *
 * Code is compliant with PSR-1 and PSR-2 standards
 * http://www.php-fig.org/psr/psr-1/
 * http://www.php-fig.org/psr/psr-2/
 */

use PhpMigration\Changes\AbstractChangeTest;

class IncompMagicInvokedTest extends AbstractChangeTest
{
    public function test()
    {
        $code = <<<'EOC'
class Sample {
    protected function oh() {}
    public function __call() {}
}
EOC;
        $this->assertHasSpot($code);

        $code = <<<'EOC'
class Sample {
    public function oh() {}
    public function __call() {}
}
EOC;
        $this->assertNotSpot($code);

        $code = <<<'EOC'
class Sample {
    protected function oh() {}
}
EOC;
        $this->assertNotSpot($code);
    }
}
