<?php

declare(strict_types=1);

return [
    'summary' => [
        'entity' => 'Kinetic Gain LLC',
        'kit' => 'WordPress Regulatory Disclosure Kit',
        'leadRecommendation' => 'Clear the model-assisted claims disclosure on pricing and comparison pages before the next regulated launch window, or buyer-facing copy will outrun approval posture.',
        'operatorPosture' => 'wordpress plugin + disclosure manifest + approval evidence',
    ],
    'disclosureLanes' => [
        [
            'surface' => 'Homepage service hero',
            'channel' => 'Public web',
            'status' => 'watch',
            'owner' => 'Content ops',
            'package' => 'AI-assisted service promise',
            'proof' => 'Entity summary is current, but the AI assistance qualifier has not been echoed into the hero footer disclosure.',
            'risk' => 'Buyer-visible copy can imply a broader automation scope than the reviewed operating model.',
            'nextAction' => 'Mirror the reviewed qualifier in the hero footer and schema description before the next homepage push.',
        ],
        [
            'surface' => 'Comparison landing pages',
            'channel' => 'Search + answer engines',
            'status' => 'blocked',
            'owner' => 'Growth engineering',
            'package' => 'Competitive claims pack',
            'proof' => 'Comparison table cites refreshed capability language but the disclosure manifest still points at the older approval packet.',
            'risk' => 'Regulator or procurement review sees orphaned claim evidence and stale review provenance.',
            'nextAction' => 'Rebuild the comparison disclosure packet and publish the updated manifest URL before relaunch.',
        ],
        [
            'surface' => 'Embedded product docs',
            'channel' => 'Docs hub',
            'status' => 'healthy',
            'owner' => 'Platform docs',
            'package' => 'Implementation disclosure card',
            'proof' => 'Docs route, schema node, and disclosure card all point to the same reviewed evidence pack.',
            'risk' => 'Low, current route parity is intact.',
            'nextAction' => 'Keep the docs card on the weekly evidence refresh rail.',
        ],
        [
            'surface' => 'Downloadable sales one-pagers',
            'channel' => 'Field collateral',
            'status' => 'watch',
            'owner' => 'RevOps',
            'package' => 'Commercial disclosure footer',
            'proof' => 'PDF footer has the current disclaimer text, but the linked disclosure URL redirects through an old vanity path.',
            'risk' => 'Procurement reviewers can miss the live approval notes when downloading offline collateral.',
            'nextAction' => 'Swap the PDF footer URL to the canonical disclosure endpoint and re-export the active pack.',
        ],
        [
            'surface' => 'Support knowledge base macros',
            'channel' => 'Agent-assisted support',
            'status' => 'healthy',
            'owner' => 'Support systems',
            'package' => 'Operational guardrail note',
            'proof' => 'Macro snippets are pinned to the same approved disclosure fragment used in the public manifest.',
            'risk' => 'Low, support and public copy remain aligned.',
            'nextAction' => 'No immediate action beyond monthly drift review.',
        ],
    ],
    'policyEvidence' => [
        [
            'artifact' => 'Disclosure manifest',
            'purpose' => 'Canonical machine-readable map of reviewed claims, disclaimers, schema targets, and owner handoff.',
            'owner' => 'Platform governance',
            'status' => 'healthy',
            'anchor' => '/.well-known/kg-disclosure-manifest.json',
        ],
        [
            'artifact' => 'Claims approval packet',
            'purpose' => 'Reviewer-signed evidence bundle for service claims, comparisons, and model-assistance qualifiers.',
            'owner' => 'Legal ops',
            'status' => 'blocked',
            'anchor' => 'comparison-disclosure-pack-v3',
        ],
        [
            'artifact' => 'Schema annotation map',
            'purpose' => 'Line-by-line tie between visible copy, organization schema, FAQ schema, and disclosure fragments.',
            'owner' => 'SEO / GEO',
            'status' => 'watch',
            'anchor' => 'schema-annotation-map',
        ],
        [
            'artifact' => 'Field collateral footer registry',
            'purpose' => 'Canonical footer strings and URLs for downloadable decks, PDFs, and partner sell sheets.',
            'owner' => 'Revenue ops',
            'status' => 'watch',
            'anchor' => 'collateral-footer-registry',
        ],
    ],
    'verificationGates' => [
        [
            'gate' => 'Manifest parity',
            'status' => 'healthy',
            'detail' => 'Public manifest, WordPress admin panel, and rendered disclosure cards agree on version and owner.',
        ],
        [
            'gate' => 'Schema-aligned qualifiers',
            'status' => 'watch',
            'detail' => 'Main service schema is current, but two comparison pages still need the reviewed qualifier mirrored into page copy.',
        ],
        [
            'gate' => 'Commercial packet provenance',
            'status' => 'blocked',
            'detail' => 'Competitive claims packet still references the older approval attachment bundle.',
        ],
        [
            'gate' => 'Support and docs alignment',
            'status' => 'healthy',
            'detail' => 'Knowledge-base macros and docs cards both resolve to the approved public disclosure fragment.',
        ],
    ],
];
