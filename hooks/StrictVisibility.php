<?php

declare(strict_types=1);

namespace Orklah\PsalmStrictVisibility\Hooks;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Identifier;
use Psalm\Codebase;
use Psalm\CodeLocation;
use Psalm\Context;
use Psalm\FileManipulation;
use Psalm\Internal\Analyzer\ClassLikeAnalyzer;
use Psalm\Internal\MethodIdentifier;
use Psalm\Issue\PluginIssue;
use Psalm\IssueBuffer;
use Psalm\Plugin\Hook\AfterMethodCallAnalysisInterface;
use Psalm\StatementsSource;
use Psalm\Type\Union;

/**
 * Prevents any assignment to a float value.
 */
class StrictVisibility implements AfterMethodCallAnalysisInterface
{
    /**
     * @param MethodCall|StaticCall $expr
     * @param FileManipulation[]    $file_replacements
     */
    public static function afterMethodCallAnalysis(
        Expr $expr,
        string $method_id,
        string $appearing_method_id,
        string $declaring_method_id,
        Context $context,
        StatementsSource $statements_source,
        Codebase $codebase,
        array &$file_replacements = [],
        Union &$return_type_candidate = null
    ) : void {
        if (!$expr instanceof MethodCall) {
            return;
        }

        if (!$expr->name instanceof Identifier) {
            return;
        }

        try {
            $method_id = new MethodIdentifier(...explode('::', $declaring_method_id));
            $method_storage = $codebase->methods->getStorage($method_id);

            $is_private = $method_storage->visibility === ClassLikeAnalyzer::VISIBILITY_PRIVATE;
            $is_protected = $method_storage->visibility === ClassLikeAnalyzer::VISIBILITY_PROTECTED;
            if ($is_private || $is_protected) {
                //method is private or protected, check if the call was made on $this
                if ($expr->var instanceof Variable && $expr->var->name !== 'this') {
                    if ($is_private) {
                        $issue = new PrivateStrictVisibility(
                            'Calling private method ' . $method_storage->cased_name . ' via proxy',
                            new CodeLocation($statements_source, $expr->name)
                        );
                    } else {
                        $issue = new ProtectedStrictVisibility(
                            'Calling protected method ' . $method_storage->cased_name . ' via proxy',
                            new CodeLocation($statements_source, $expr->name)
                        );
                    }

                    IssueBuffer::accepts($issue, $statements_source->getSuppressedIssues());
                }
            }
        } catch (\Exception $e) {
            // can throw if storage is missing
        }
    }
}

class ProtectedStrictVisibility extends PluginIssue
{
}

class PrivateStrictVisibility extends PluginIssue
{
}
