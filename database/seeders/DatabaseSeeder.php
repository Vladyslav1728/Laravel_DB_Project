<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Note;
use App\Models\Task;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Отключаем проверки внешних ключей и очищаем таблицы
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('note_category')->truncate();
        DB::table('notes')->truncate();
        DB::table('categories')->truncate();
        DB::table('users')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Сидируем пользователей
        $this->call([UserSeeder::class]);

        // Берём всех пользователей после сидирования
        $users = User::all();

        // Создаём категории
        $categories = Category::factory(10)->create();

        // Создаём заметки для каждого пользователя
        foreach ($users as $user) {
            $user->notes()->createMany(
                Note::factory(5)->make()->toArray()
            );
        }

        // Берём все заметки
        $notes = Note::all();

        // Присваиваем категории заметкам
        foreach ($notes as $note) {
            $note->categories()->attach(
                $categories->random(rand(1, 3))->pluck('id')->all()
            );
        }

        // Создаём задачи для каждой заметки
        foreach ($notes as $note) {
            $note->tasks()->createMany(
                Task::factory(rand(2, 6))->make()->toArray()
            );
        }

        // Создаём комментарии к заметкам
        $notes->each(function (Note $note) use ($users) {
            $comments = Comment::factory(rand(0, 2))->make([
                'user_id' => $users->random()->id,
            ]);
            $note->comments()->saveMany($comments);
        });

        // Создаём комментарии к задачам
        $tasks = Task::all();
        $tasks->each(function (Task $task) use ($users) {
            $comments = Comment::factory(rand(0, 1))->make([
                'user_id' => $users->random()->id,
            ]);
            $task->comments()->saveMany($comments);
        });
    }
}
