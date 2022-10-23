<?php

namespace Tests;

use App\Models\Post;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

	public function setUp(): void
    	{
		parent::setUp();
		$this->withoutExceptionHandling();

    }

    public function createPost($args = [])
    {
        return Post::factory()->create($args);
    }
}
