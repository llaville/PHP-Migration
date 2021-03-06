<?php
namespace PhpMigration\Changes\v5dot3;

/**
 * @author Yuchen Wang <phobosw@gmail.com>
 *
 * Code is compliant with PSR-1 and PSR-2 standards
 * http://www.php-fig.org/psr/psr-1/
 * http://www.php-fig.org/psr/psr-2/
 */

use PhpMigration\Changes\AbstractChange;
use PhpMigration\Utils\ParserHelper;
use PhpParser\Node\Stmt;

class IncompMagicInvoked extends AbstractChange
{
    protected static $version = '5.3.0';

    protected function emitSpot($node, $non_public)
    {
        /**
         * {Description}
         * The __call() magic method is now invoked on access to private and
         * protected methods.
         *
         * {Reference}
         * http://php.net/manual/en/migration53.incompatible.php
         */

        $message = sprintf(
            'The __call() magic method will be invoked on access to non-public mehtods in %s',
            $this->visitor->getClassname()
        );
        $this->addSpot('NOTICE', false, $message, $node->getLine());
    }

    public function leaveNode($node)
    {
        $non_public = array();
        $has_magic_call = false;

        if ($node instanceof Stmt\Class_) {
            foreach ($node->getMethods() as $mnode) {
                if (ParserHelper::isSameFunc($mnode->name, '__call')) {
                    $has_magic_call = true;
                    $magic_node = $mnode;
                } elseif (!$mnode->isPublic()) {
                    $non_public[] = $mnode->name;
                }
            }
        }

        if ($has_magic_call && $non_public) {
            $this->emitSpot($magic_node, $non_public);
        }
    }
}
