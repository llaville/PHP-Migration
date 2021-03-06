<?php
namespace PhpMigration\Changes\v5dot4;

/**
 * @author Yuchen Wang <phobosw@gmail.com>
 *
 * Code is compliant with PSR-1 and PSR-2 standards
 * http://www.php-fig.org/psr/psr-1/
 * http://www.php-fig.org/psr/psr-2/
 */

use PhpMigration\Changes\AbstractChange;
use PhpMigration\SymbolTable;
use PhpParser\Node\Expr;

class IncompRegister extends AbstractChange
{
    protected static $version = '5.4.0';

    protected $tableLoaded = false;

    protected $longArray = array(
        'HTTP_POST_VARS',
        'HTTP_GET_VARS',
        'HTTP_ENV_VARS',
        'HTTP_SERVER_VARS',
        'HTTP_COOKIE_VARS',
        'HTTP_SESSION_VARS',
        'HTTP_POST_FILES',
    );

    public function prepare()
    {
        if (!$this->tableLoaded) {
            $this->longArray = new SymbolTable(array_flip($this->longArray), SymbolTable::CS);
            $this->tableLoaded = true;
        }
    }

    public function leaveNode($node)
    {
        /**
         * {Description}
         * The register_globals and register_long_arrays php.ini directives
         * have been removed.
         *
         * {Reference}
         * http://php.net/manual/en/migration54.incompatible.php
         */
        if ($node instanceof Expr\Variable && is_string($node->name) && $this->longArray->has($node->name)) {
            $this->addSpot(
                'WARNING',
                true,
                'The register_long_arrays is removed, $'.$node->name.' no longer available'
            );
        }
    }
}
