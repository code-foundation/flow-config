<?php
declare(strict_types=1);

namespace CodeFoundation\FlowConfig\Tests\Accessibility;

use CodeFoundation\FlowConfig\Accessibility\NullAccessibility;
use PHPUnit\Framework\TestCase;

/**
 * @covers \CodeFoundation\FlowConfig\Accessibility\NullAccessibility
 */
class NullAccessibilityTest extends TestCase
{
    /**
     * Tests that the constructed instance of the NullAccessibility class returns `true` for both `canGetKey` and
     * `canSetKey` methods, as this is the intended default of this class.
     *
     * @return void
     */
    public function testConstructedInstance(): void
    {
        $accessibility = new NullAccessibility();

        self::assertTrue($accessibility->canGetKey('test'));
        self::assertTrue($accessibility->canSetKey('test'));
    }
}
