<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddExpenseRequest;
use App\Http\Requests\ImportExpenseRequest;
use App\Http\Requests\UpdateExpenseRequest;
use App\Models\User;
use App\Services\ExpenseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ExpenseController extends Controller
{
    //
    public $expenseService;
    public $userid;
    public function __construct(ExpenseService $expenseService)
    {
        $this->expenseService = $expenseService;
        $this->userid = session("userid");
    }
    public function index()
    {
        $expense = User::with("expenses")->find(session("userid"));
        if (!$expense)
            return abort(404);
        $reimburseFee = $expense->expenses()->where("status", 'pending')->sum("amount");
        return view("emp-dashboard", ["expenses" => $expense->expenses, "action" => "home", "reimfee" => $reimburseFee]);
    }
    public function edit($expid)
    {
        $expense = User::with("expenses")->find(session("userid"));
        if (!$expense)
            return abort(404);
        $reimburseFee = $expense->expenses()->where("status", 'pending')->sum("amount");
        $exp = $expense->expenses()->find($expid);
        if (!$exp)
            return abort(404);
        return view("emp-dashboard", ["expenses" => $expense->expenses, "expdata" => $exp, "action" => "edit", "reimfee" => $reimburseFee]);
    }

    public function update(UpdateExpenseRequest $request, $expid)
    {
        $response = $this->expenseService->updateExpense($request, $expid);
        if (!$response["success"]) {
            if ($response["code"] == 400)
                return abort(404);
            return back()->with("edit-error", $response["message"]);
        }
        return back()->with("edit-success", $response["message"]);
    }
    public function addExpense(AddExpenseRequest $request)
    {
        $response = $this->expenseService->addNewExpense($request);
        if (!$response["success"])
            return back()->with("error", $response["message"]);
        return back()->with("success", $response["message"]);
    }
    public function delete($expid)
    {
        $response = $this->expenseService->deleteExpense($expid);
        if (!$response["success"]) {
            if ($response["code"] == 400)
                return abort(404);
            return back()->with("error", $response["message"]);
        }
        return back()->with("success", $response["message"]);
    }

    public function filter(Request $request)
    {
        $data = $request->all();
        $response = $this->expenseService->filterExpense($data);
        return view("emp-dashboard", ["expenses" => $response["data"], "filterdata" => $data, "action" => "home", "reimfee" => $response["fee"]]);
    }

    public function import(ImportExpenseRequest $request)
    {
        $fileUrl = Storage::putFile("public", $request->file('file'), "private");
        $sheet = IOFactory::load(Storage::get($fileUrl));
        $data = array(1, $sheet->getActiveSheet()
            ->toArray(null, true, true, true));
        return $data;
    }
}
