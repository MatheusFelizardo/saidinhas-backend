<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\CurrencyConverterService;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $converter = new CurrencyConverterService();
        $user_id = $request->input('user_id');
        $user = User::find($user_id);
        // $expense = Expense::all();
        // get expense for the user
        $expense = $user->expenses;

        if ($expense->isEmpty()) {
            return response()->json([
                'message' => 'No expenses found',
            ], 404);
        }

        $parsedExpenses = $converter->convert($expense, $user->currency);
        return response()->json($parsedExpenses);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {   
        $converter = new CurrencyConverterService();
        //
        $title = $request->input('title');
        $description = $request->input('description');
        $amount = $request->input('amount');
        $currency = $request->input('currency'); // We use here to send the response after save
        $user_id = $request->input('user_id');
        

        try {
            $expense = Expense::create([
                'title' => $title,
                'description' => $description,
                'amount' => $converter->convert_amount_to_eur($amount, $currency),
                'currency' => 'EUR',
                'user_id' => $user_id
            ])->toArray(); 

            $parsedExpense = $converter->format_currency_after_save($expense, $currency);

            return response()->json([
                'message' => 'Expense created sucessfully',
                'expense' => $parsedExpense,
            ], 201);
        }   catch (\Exception $e) {
            return response()->json([
                'message' => 'Expense creation failed',
                'error' => $e->getMessage(),
            ], 500);
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $expense = Expense::find($id);

        if (!$expense) {
            return response()->json([
                'message' => 'Expense not found',
            ], 404);
        }

        return response()->json($expense);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $converter = new CurrencyConverterService();

        $title = $request->input('title');
        $description = $request->input('description');
        $amount = $request->input('amount');
        $currency = $request->input('currency');

        $oldExpense = Expense::find($id);
        $newExpense = [
            'title' => $title ? $title : $oldExpense->title,
            'description' => $description ? $description :  '',
            'amount' => $amount ? 
                        $converter->convert_amount_to_eur($amount, $currency, $oldExpense->created_at ) : 
                        $converter->convert_amount_to_eur($oldExpense->amount, $currency, $oldExpense->created_at),
            'currency' => 'EUR',
        ];

        try {
            $oldExpense->update($newExpense);

            $parsedExpense = $converter->format_currency_after_save($oldExpense->toArray(), $currency);
            return response()->json([
                'message' => 'Expense updated successfully',
                'expense' => $parsedExpense,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Expense update failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $expense = Expense::find($id);
        try {
            $expense->delete();

            return response()->json([
                'message' => 'Expense deleted successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Expense deletion failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
