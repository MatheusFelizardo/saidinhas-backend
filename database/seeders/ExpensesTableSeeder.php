<?php

namespace Database\Seeders;

use App\Models\Expense;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ExpensesTableSeeder extends Seeder
{
   /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Find the user with the specified email
        $user = User::where('email', 'matheus.felizardo2@gmail.com')->first();

        // Create expenses to Matheus Felizardo
        $februaryExpenses = $this->createExpenses($user->id, 3, 'February');
        $januaryExpenses = $this->createExpenses($user->id, 2, 'January');
        $juneExpenses = $this->createExpenses($user->id, 5, 'June');
        $julyExpenses = $this->createExpenses($user->id, 10, 'July');

     
        // Create expenses to user with id 1
        $julyExpensesIdOne = $this->createExpenses(1, 2, 'July');
        $juneExpensesIdOne = $this->createExpenses(1, 5, 'June');

        // Merge all expenses into a single array
        $expenses = array_merge($januaryExpenses, $februaryExpenses, $juneExpenses, $julyExpenses, $juneExpensesIdOne, $julyExpensesIdOne );

        // Insert expenses into the database
        Expense::insert($expenses);
    }

    /**
     * Create multiple expenses for the given user and month.
     *
     * @param int $userId
     * @param int $count
     * @param string $month
     * @return array
     */
    private function createExpenses($userId, $count, $month)
    {
        $expenses = [];

        for ($i = 0; $i < $count; $i++) {
            $date = $this->getRandomDateInMonth($month);
            $expense = [
                'title' => 'Expense ' . ($i + 1) . ' ' . $month,
                'description' => 'Description for Expense ' . ($i + 1),
                'amount' => rand(10, 100),
                'user_id' => $userId,
                'created_at' => $date,
                'updated_at' => $date,
            ];

            $expenses[] = $expense;
        }

        return $expenses;
    }

    /**
     * Get a random date within the given month.
     *
     * @param string $month
     * @return string
     */
    private function getRandomDateInMonth($month)
    {
        $date = Carbon::parse($month . ' 1');
        $startOfMonth = $date->startOfMonth()->timestamp;
        $endOfMonth = $date->endOfMonth()->timestamp;

        return Carbon::createFromTimestamp(rand($startOfMonth, $endOfMonth))->toDateTimeString();
    }
}
