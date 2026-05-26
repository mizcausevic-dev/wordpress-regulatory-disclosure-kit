<?php
/**
 * Plugin Name: WordPress Regulatory Disclosure Kit
 * Plugin URI: https://disclosure.kineticgain.com/
 * Description: Publishes disclosure manifests, reviewed claim footers, and machine-readable regulatory disclosure payloads for WordPress-driven sites.
 * Version: 0.1.0
 * Author: Kinetic Gain
 * License: AGPL-3.0-or-later
 * License URI: https://www.gnu.org/licenses/agpl-3.0.html
 */

declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}

if (! function_exists('kg_disclosure_manifest_payload')) {
    /**
     * @return array<string, mixed>
     */
    function kg_disclosure_manifest_payload(): array
    {
        return [
            'entity' => 'Kinetic Gain LLC',
            'kit' => 'WordPress Regulatory Disclosure Kit',
            'version' => '0.1.0',
            'updatedAt' => gmdate('c'),
            'surfaces' => [
                'homepage-service-hero',
                'comparison-landing-pages',
                'embedded-product-docs',
                'field-collateral-footers',
                'support-knowledge-base-macros',
            ],
            'operatorNote' => 'Synthetic demonstration payload only. Review legal, regulatory, and commercial disclosures before production use.',
        ];
    }
}

if (! function_exists('kg_render_disclosure_manifest')) {
    function kg_render_disclosure_manifest(): string
    {
        $payload = kg_disclosure_manifest_payload();

        return '<pre class="kg-disclosure-manifest">' . esc_html(wp_json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)) . '</pre>';
    }
}

add_shortcode('kg_disclosure_manifest', 'kg_render_disclosure_manifest');

add_action('rest_api_init', static function (): void {
    register_rest_route(
        'kg-disclosure/v1',
        '/manifest',
        [
            'methods' => 'GET',
            'permission_callback' => '__return_true',
            'callback' => static function () {
                return rest_ensure_response(kg_disclosure_manifest_payload());
            },
        ]
    );
});
