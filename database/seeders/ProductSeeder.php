<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $currentTimestamp = now();

        DB::table('products')->insert([
            [
                'created_at' => $currentTimestamp,
                'updated_at' => $currentTimestamp,
                'code' => 'PD001',
                'name' => 'Programming PHP',
                'category_id' => 1,
                'price' => '345.00',
                'description' => "Why is PHP the most widely used programming language on the web?\r\nThis updated edition teaches everything you need to know to create\r\neffective web applications using the latest features in PHP 7.4.\r\nYou'll start with the big picture and then dive into:\r\nlanguage syntax\r\nprogramming techniques\r\nand other details\r\nusing examples that illustrate both correct usage and common idioms.",
            ],
            [
                'created_at' => $currentTimestamp,
                'updated_at' => $currentTimestamp,
                'code' => 'PD002',
                'name' => 'JavaScript: The Definitive Guide',
                'category_id' => 3,
                'price' => '250.00',
                'description' => "JavaScript is the programming language of the web and is used by more\r\nsoftware developers today than any other programming language.\r\nFor nearly 25 years this best seller has been the go-to guide for\r\nJavaScript programmers. The seventh edition is fully updated to cover\r\nthe 2020 version of JavaScript, and new chapters cover:\r\nclasses\r\nmodules\r\niterators\r\ngenerators\r\nPromises\r\nasync/await\r\nand metaprogramming.\r\nYou'll find illuminating and engaging example code throughout.",
            ],
            [
                'created_at' => $currentTimestamp,
                'updated_at' => $currentTimestamp,
                'code' => 'PD003',
                'name' => 'Learning PHP, MySQL & JavaScript',
                'category_id' => 3,
                'price' => '450.00',
                'description' => "Build interactive, data driven websites with the potent combination\r\nof open source technologies and web standards, even if you have only\r\nbasic HTML knowledge. In this update to this popular hands on guide,\r\nyou'll tackle dynamic web programming with the latest versions of\r\ntoday's core technologies:\r\nPHP\r\nMySQL\r\nJavaScript\r\nCSS\r\nHTML5\r\nand key jQuery libraries.",
            ],
            [
                'created_at' => $currentTimestamp,
                'updated_at' => $currentTimestamp,
                'code' => 'PD004',
                'name' => 'Python Crash Course, 2nd Edition',
                'category_id' => 2,
                'price' => '560.00',
                'description' => "In the first half of the book, you'll learn basic programming concepts,\r\nsuch as variables, lists, classes, and loops, and practice writing\r\nclean code with exercises for each topic. You'll also learn how to make\r\nyour programs interactive and test your code safely before adding it to\r\na project. In the second half, you'll put your new knowledge into\r\npractice with three substantial projects:\r\na Space Invaders-inspired arcade game\r\na set of data visualizations with Python's handy libraries\r\nand a simple web app you can deploy online.",
            ],
        ]);
    }
}
