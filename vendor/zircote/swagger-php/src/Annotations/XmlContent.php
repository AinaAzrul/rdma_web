<?php declare(strict_types=1);

/**
 * @license Apache 2.0
 */

namespace OpenApi\Annotations;

use OpenApi\Generator;

/**
 * Shorthand for a xml response.
 *
 * Use as `@OA\Schema` inside a `Response` and `MediaType`->`'application/xml'` will be generated.
 *
 * @Annotation
 */
class XmlContent extends Schema
{
    /**
     * @var array<string,Examples>
     */
    public $examples = Generator::UNDEFINED;

    /**
     * @inheritdoc
     */
    public static $_parents = [];

    /**
     * @inheritdoc
     */
    public static $_nested = [
        Discriminator::class => "discriminator",
        Items::class => "items",
        Property::class => ["properties", "property"],
        ExternalDocumentation::class => "externalDocs",
        Xml::class => "xml",
        AdditionalProperties::class => "additionalProperties",
        Examples::class => ["examples", "example"],
        Attachable::class => ["attachables"],
    ];
}
