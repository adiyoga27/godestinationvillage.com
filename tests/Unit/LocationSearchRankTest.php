<?php

namespace Tests\Unit;

use App\Http\Controllers\Front\LocationSearchController;
use PHPUnit\Framework\TestCase;

class LocationSearchRankTest extends TestCase
{
    public function test_keyword_drops_administrative_prefixes(): void
    {
        $this->assertSame('Karangasem', LocationSearchController::normalizeKeyword('Kab. Karangasem'));
        $this->assertSame('Denpasar', LocationSearchController::normalizeKeyword('kota  Denpasar'));
    }

    public function test_city_match_ranks_above_village_match(): void
    {
        $village = ['province' => 'BANTEN', 'city' => 'Kota Cilegon', 'district' => 'CIBEBER', 'subdistrict' => 'KARANGASEM', 'postal_code' => '42426'];
        $city = ['province' => 'BALI', 'city' => 'Kab. Karangasem', 'district' => 'ABANG', 'subdistrict' => 'TISTA', 'postal_code' => '80852'];

        $this->assertSame([$city, $village], LocationSearchController::rank([$village, $city], 'karangasem'));
    }
}
