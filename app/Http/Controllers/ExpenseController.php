<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $expense = Expense::all();

        if ($expense->isEmpty()) {
            return response()->json([
                'message' => 'No expenses found',
            ], 404);
        }

        return response()->json($expense);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {   
        //
        $title = $request->input('title');
        $description = $request->input('description');
        $amount = $request->input('amount');
        $currency = $request->input('currency');

        try {
            $expense = Expense::create([
                'title' => $title,
                'description' => $description,
                'amount' => $amount,
                'currency' => $currency
            ]); 

            return response()->json([
                'message' => 'Expense created sucessfully',
                'expense' => $expense,
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
        //
        $title = $request->input('title');
        $description = $request->input('description');
        $amount = $request->input('amount');
        $currency = $request->input('currency');

        $oldExpense = Expense::find($id);
        $newExpense = [
            'title' => $title ? $title : $oldExpense->title,
            'description' => $description ? $description :  $oldExpense->description,
            'amount' => $amount ? $amount : $oldExpense->amount,
            'currency' => $currency ? $currency : $oldExpense->currency,
        ];

        try {
            $oldExpense->update($newExpense);
            return response()->json([
                'message' => 'Expense updated successfully',
                'expense' => $oldExpense,
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
