<?php declare(strict_types=1);

/**
 * @license Apache 2.0
 */

namespace OpenApi\Processors;

use OpenApi\Analysis;
use OpenApi\Annotations\Components;
use OpenApi\Context;
use OpenApi\Generator;

/**
 * Merge reusable annotation into @OA\Schemas.
 */
class MergeIntoComponents
{
    public function __invoke(Analysis $analysis)
    {
        $components = $analysis->openapi->components;
        if (Generator::isDefault($components)) {
            $components = new Components([
                "_context" => new Context(
                    ["generated" => true],
                    $analysis->context
                ),
            ]);
        }

        foreach ($analysis->annotations as $annotation) {
            if (
                Components::matchNested(get_class($annotation)) &&
                $annotation->_context->is("nested") === false
            ) {
                // A top level annotation.
                $components->merge([$annotation], true);
                $analysis->openapi->components = $components;
            }
        }
    }
}
