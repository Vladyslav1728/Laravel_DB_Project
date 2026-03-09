<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {
    use WithoutModelEvents;
    public function run(): void {
        // clean tables
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::table('note_category')->truncate();
        DB::table('notes')->truncate();
        DB::table('categories')->truncate();
        DB::table('users')->truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->call([
            UserSeeder::class,
            CategorySeeder::class,
            NoteSeeder::class,
            NoteCategorySeeder::class,
        ]);
    }
}
