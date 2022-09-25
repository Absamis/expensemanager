<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\User;
use Illuminate\Http\Request;

class ExpenseAdminController extends Controller
{
    //
    public function index()
    {
        $expense = Expense::get();
        if (!$expense)
            return abort(404);
        $reimburseFee = $expense->where("status", 'pending')->sum("amount");
        $imburseFee = $expense->where("status", 'approved')->sum("amount");
        return view("admin.dashboard", ["expenses" => $expense, "action" => "home", "reimfee" => $reimburseFee, "refee" => $imburseFee]);
    }

    public function approve($expid, $action)
    {
        $actions = ["approve", "reject"];
        if (!in_array($action, $actions))
            return abort(404);
        $expense = Expense::find($expid);
        if (!$expense)
            return abort(404);
        $expense->status = $action;
        $expense->save();
        return back()->with("success", "Expense approved");
    }

    public function view($expid)
    {
        $exp = Expense::find($expid);
        $expense = Expense::get();
        if (!$expense)
            return abort(404);
        $reimburseFee = $expense->where("status", 'pending')->sum("amount");
        $imburseFee = $expense->where("status", 'approve')->sum("amount");
        return view("admin.dashboard", ["expenses" => $expense, "expdata" => $exp, "action" => "edit", "reimfee" => $reimburseFee, "refee" => $imburseFee]);
    }
}
