<?php

namespace Tests\Feature;

use App\Rules\Isbn;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class IsbnRuleTest extends TestCase
{
    public function test_it_accepts_a_valid_isbn(): void
    {
        $validator = Validator::make(
            ['isbn' => '9780306406157'],
            ['isbn' => [new Isbn]],
        );

        $this->assertFalse($validator->fails());
    }

    public function test_it_rejects_an_invalid_isbn(): void
    {
        $validator = Validator::make(
            ['isbn' => '9780306406158'],
            ['isbn' => [new Isbn]],
        );

        $this->assertTrue($validator->fails());
    }
}
