<?php

namespace Tests\Feature;

use App\Models\Review;
use Tests\TestCase;

class StorefrontReviewStarsTest extends TestCase
{
    public function test_review_star_label_renders_filled_and_empty_stars(): void
    {
        $review = new Review(['rating' => 4]);

        $this->assertSame('★★★★☆', $review->star_label);
    }
}
