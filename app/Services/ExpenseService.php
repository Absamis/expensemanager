<?php

namespace App\Services;

use App\Http\Requests\AddExpenseRequest;
use App\Http\Requests\UpdateExpenseRequest;
use App\Models\Expense;
use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ExpenseService
{

    public function addNewExpense(AddExpenseRequest $request)
    {
        try {
            $data = $request->all();
            $date = $data["date"];
            if (strtotime($date) > time())
                return ["success" => false, "message" => "Invalid date selected"];
            $fileUrl = Storage::putFile("receipts", $request->file('receipt'));
            if (!$fileUrl)
                return ["success" => false, "message" => "Error occured, try again"];
            $userid = session("userid");
            $expid = Str::random(30);
            Expense::create([
                "exp_id" => $expid,
                "userid" => $userid,
                "merchant" => $data["merchant"],
                "date" => $date,
                "amount" => $data["amount"],
                "remark" => $data["remark"],
                "receipt" => $fileUrl
            ]);
            return ["success" => true, "message" => "Expense added successfully"];
        } catch (Exception $ex) {
            return ["success" => false, "message" => "Error occured, try again" . $ex->getMessage()];
        }
    }

    public function updateExpense(UpdateExpenseRequest $request, $expid)
    {
        try {
            $expense = Expense::find($expid);
            if (!$expense)
                return ["success" => false, "code" => 404, "message" => "Expense not found"];
            $data = $request->all();
            $date = $data["date"];
            if (strtotime($date) > time())
                return ["success" => false, "code" => 200, "message" => "Invalid date selected"];
            $fileUrl = $expense->receipt;
            if ($request->hasFile('receipt')) {
                Storage::delete($fileUrl);
                $fileUrl = Storage::putFile("receipts", $request->file('receipt'));
            }
            if (!$fileUrl)
                return ["success" => false, "code" => 200, "message" => "Error occured, try again"];
            $userid = session("userid");
            $expense->update([
                "merchant" => $data["merchant"],
                "date" => $date,
                "amount" => $data["amount"],
                "remark" => $data["remark"],
                "receipt" => $fileUrl
            ]);
            return ["success" => true, "message" => "Expense updated successfully"];
        } catch (Exception $ex) {
            return ["success" => false, "code" => 200, "message" => "Error occured, try again" . $ex->getMessage()];
        }
    }

    public function deleteExpense($expid)
    {
        try {
            $expense = Expense::find($expid);
            if (!$expense)
                return ["success" => false, "code" => 404, "message" => "Expense not found"];
            $receipt = $expense->receipt;
            Storage::delete($receipt);
            $expense->delete();
            return ["success" => true, "message" => "Expense deleted successfully"];
        } catch (Exception $ex) {
            return ["success" => false, "code" => 200, "message" => "Error occured, try again" . $ex->getMessage()];
        }
    }

    public function filterExpense($data = [])
    {
        try {
            $exps = Expense::where("userid", session("userid"));
            $fee = $exps->where("status", 'pending')->sum("amount");
            if (!$data)
                return ["success" => false, "message" => "Empty value", "fee" => $fee, "data" => []];
            $from = (isset($data['from'])) ? $data['from'] : null;
            $to = (isset($data['to'])) ? $data['to'] : null;
            $merchant = (isset($data['merchant'])) ? $data['merchant'] : null;
            if (!$from && $to)
                return ["success" => false, "message" => "Invalid filter date", "fee" => $fee, "data" => []];
            $to = (!$to) ? date("Y-m-d") : $to;
            if ($from)
                $expense = $exps->where("date", '>=', $from)->get();
            if ($to)
                $expense = $exps->where("date", '>=', $from)->where("date", '<=', $to)->get();
            if ($merchant)
                $expense = $exps->where("date", '>=', $from)->where("date", '<=', $to)->where("merchant", $merchant)
                    ->get();
            return ["success" => true, "message" => "success", "fee" => $fee, "data" => $expense];
        } catch (Exception $ex) {
            return ["success" => false, "data" => [], "fee" => $fee, "message" => "Error occured, try again" . $ex->getMessage()];
        }
    }
}
