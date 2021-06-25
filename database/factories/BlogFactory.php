<?php

namespace Database\Factories;

use App\Models\Blog;
use Illuminate\Http\UploadedFile;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class BlogFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Blog::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $title = $this->faker->sentence();
        return [
            'title'=> $title,
            // 'slug'=> Str::slug($title),
            'body'=>$this->faker->text(),
            'image'=>UploadedFile::fake()->image('photo1.jpg'), 
            'user_id'=>1
        ];
    }
}
