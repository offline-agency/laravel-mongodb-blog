<?php

namespace Tests\Unit;

use Carbon\Carbon;
use DateTime;
use MongoDB\BSON\UTCDateTime;
use PHPUnit\Framework\TestCase;

class HelpersTest extends TestCase
{
    public function test_format_date_accepts_a_utc_date_time(): void
    {
        $this->assertSame('14 March 2026', formatDate(new UTCDateTime(new DateTime('2026-03-14 10:00:00 UTC'))));
    }

    public function test_format_date_accepts_a_carbon_instance(): void
    {
        $this->assertSame('14 March 2026', formatDate(Carbon::parse('2026-03-14 10:00:00', 'UTC')));
    }

    public function test_fix_date_accepts_a_utc_date_time(): void
    {
        $this->assertSame('14-03-2026 10:00:00', fixDate(new UTCDateTime(new DateTime('2026-03-14 10:00:00 UTC'))));
    }

    public function test_fix_date_accepts_a_carbon_instance(): void
    {
        $this->assertSame('14-03-2026 10:00:00', fixDate(Carbon::parse('2026-03-14 10:00:00', 'UTC')));
    }
}
