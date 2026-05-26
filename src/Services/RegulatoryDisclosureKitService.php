<?php

declare(strict_types=1);

namespace WordpressRegulatoryDisclosureKit\Services;

final class RegulatoryDisclosureKitService
{
    /** @var array<string, mixed> */
    private array $payload;

    public function __construct()
    {
        /** @var array<string, mixed> $payload */
        $payload = require __DIR__ . '/../Data/sample_regulatory_disclosure.php';
        $this->payload = $payload;
    }

    /**
     * @return array<string, mixed>
     */
    public function summary(): array
    {
        $lanes = $this->disclosureLanes();
        $blocked = array_values(array_filter($lanes, static fn(array $lane): bool => $lane['status'] === 'blocked'));
        $watch = array_values(array_filter($lanes, static fn(array $lane): bool => $lane['status'] === 'watch'));
        $healthy = array_values(array_filter($lanes, static fn(array $lane): bool => $lane['status'] === 'healthy'));

        return [
            'surfaceCount' => count($lanes),
            'healthyCount' => count($healthy),
            'watchCount' => count($watch),
            'blockedCount' => count($blocked),
            'evidenceCount' => count($this->policyEvidence()),
            'operatorPosture' => (string) $this->payload['summary']['operatorPosture'],
            'leadRecommendation' => (string) $this->payload['summary']['leadRecommendation'],
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function disclosureLanes(): array
    {
        /** @var array<int, array<string, mixed>> $lanes */
        $lanes = $this->payload['disclosureLanes'];

        return $lanes;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function policyEvidence(): array
    {
        /** @var array<int, array<string, mixed>> $evidence */
        $evidence = $this->payload['policyEvidence'];

        return $evidence;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function verificationGates(): array
    {
        /** @var array<int, array<string, mixed>> $gates */
        $gates = $this->payload['verificationGates'];

        return $gates;
    }

    /**
     * @return array<string, mixed>
     */
    public function payload(): array
    {
        return [
            'product' => 'WordPress Regulatory Disclosure Kit',
            'purpose' => 'WordPress control plane for regulated claims, disclosure manifests, approval evidence, schema-aligned qualifiers, and publication-safe review posture.',
            'routes' => [
                '/',
                '/disclosure-lane',
                '/policy-evidence',
                '/verification',
                '/docs',
            ],
            'priorities' => [
                'Keep buyer-facing claims and disclosure qualifiers in the same reviewed release path.',
                'Expose manifest drift before legal, SEO, or RevOps discover it in separate tools.',
                'Make downloadable collateral and support macros point at the same canonical disclosure record.',
                'Turn WordPress disclosure governance into a visible operator lane instead of a buried admin setting.',
            ],
            'entity' => (string) $this->payload['summary']['entity'],
        ];
    }
}
