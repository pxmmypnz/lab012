<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Product02Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $currentTstmp = now();

        DB::table('products')->insert([
            [
                'created_at' => $currentTstmp,
                'updated_at' => $currentTstmp,
                'code' => 'PD101',
                'name' => 'Modern PHP Web Development',
                'category_id' => 1,
                'price' => '758.55',
                'description' => <<<EOD
                    This comprehensive guide teaches you how to build real-world
                    web applications using modern PHP. You will learn core language
                    features, database integration with MySQL, secure user
                    authentication, and best practices for deploying robust web
                    services.
                    \t- Master PHP syntax, object-oriented programming,
                    \t  and MVC architecture.
                    \t- Database & Security: Connect safely with PDO,
                    \t  prevent SQL injection, and hash passwords securely.
                    \t- Project Workflow: Build a complete dynamic web
                    \t  application step-by-step.
                    EOD,
            ],
            [
                'created_at' => $currentTstmp,
                'updated_at' => $currentTstmp,
                'code' => 'PD102',
                'name' => 'Web Security for Developers',
                'category_id' => 3,
                'price' => '1369.18',
                'description' => <<<EOD
                    You will learn how to:
                    \t- Protect against SQL injection attacks, malicious
                    \t  JavaScript, and cross-site request forgery.
                    \t- Add authentication and shape access control
                    \t  to protect accounts.
                    \t- Lock down user accounts to prevent attacks that
                    \t  rely on guessing passwords, stealing sessions,
                    \t  or escalating privileges.
                    \t- Implement encryption.
                    \t- Manage vulnerabilities in legacy code.
                    \t- Prevent information leaks that disclose
                    \t  vulnerabilities.
                    \t- Mitigate advanced attacks like malvertising and
                    \t  denial-of-service
                    EOD,
            ],
            [
                'created_at' => $currentTstmp,
                'updated_at' => $currentTstmp,
                'code' => 'PD103',
                'name' => 'How To Be A Web Developer In 90 Days',
                'category_id' => 1,
                'price' => '892.72',
                'description' => <<<EOD
                    You are going to enjoy this book because I have made coding fun
                    by doing something that has never been done before. I’ve
                    included animations that explain daily lessons. You will also
                    receive a free 15 minute live chat with a Certified Web
                    Developer. Plus, you can learn at your own pace. If you need
                    additional help, there’s an option to attend live online
                    classes. At the end of this book, for your final project, you
                    will build your own website
                    EOD,
            ],
            [
                'created_at' => $currentTstmp,
                'updated_at' => $currentTstmp,
                'code' => 'PD104',
                'name' => 'Designing Web APIs',
                'category_id' => 1,
                'price' => '756.49',
                'description' => <<<EOD
                    Using a web API to provide services to application developers
                    is one of the more satisfying endeavors that software engineers
                    undertake. But building a popular API with a thriving developer
                    ecosystem is also one of the most challenging. With this
                    practical guide, developers, architects, and tech leads will
                    learn how to navigate complex decisions for designing, scaling,
                    marketing, and evolving interoperable APIs.
                    EOD,
            ],
            [
                'created_at' => $currentTstmp,
                'updated_at' => $currentTstmp,
                'code' => 'PD105',
                'name' => 'Visual Studio Code: End-to-End Editing and Debugging Tools',
                'category_id' => 3,
                'price' => '978.38',
                'description' => <<<EOD
                    Visual Studio Code, a free, open source, cross-compatible source
                    code editor, is one of the most popular choices for web
                    developers. It is fast, lightweight, customizable, and contains
                    built-in support for JavaScript, Typescript, and Node.js
                    extensions for other languages, including C++, Python, and PHP.
                    Features such as debugging capability,embedded Git control,
                    syntax highlighting, code snippets, and IntelliSense intelligent
                    code completion support―several of which set it apart from the
                    competition―help make Visual Studio Code an impressive,
                    out-of-the-box solution.
                    EOD,
            ],
        ]);
    }
}
