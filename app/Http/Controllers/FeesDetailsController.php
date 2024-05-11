<?php

namespace App\Http\Controllers;

use App\Models\AcademicYearModel;
use App\Models\AdmissionModel;
use App\Models\ClassesModel;
use App\Models\CreateClass;
use App\Models\FeeReceiptModel;
use App\Models\FeesDetailsModel;
use PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FeesDetailsController extends Controller
{
    public function feesReceipts() {
        return view('pages.fees.fees-receipts');
    }

    public function feePaying(Request $req) {
        $validator = Validator::make($req->all(), [
            "id" => ["required", "numeric"],
            "annualFee" => ["required", "numeric"],
            "feesPaid" => ["required", "numeric"],
            "balance" => ["required", "numeric"],
            "paying" => ["required", "numeric"],
        ]);
    
        if ($validator->fails()) {
            return response()->json(["msg" => "failed"]);
        }
    
        $paying = $req->paying + $req->feesPaid;
        $balance = $req->annualFee - $paying;
    
        $data = [
            "student" => $req->student,
            "year" => $req->year,
            "class" => $req->class,
            "amt_paid" => $req->paying,
            "receipt_no" => $req->receipt_no
        ];
    
        $receipt = FeeReceiptModel::create($data);
    
        if ($receipt) {
            $updatedRows = CreateClass::where("id", $req->id)->update([
                "paid" => $paying,
                "balance" => $balance
            ]);
    
            if ($updatedRows > 0) {
                return response()->json(["msg" => "success"]);
            }
        }
    
        return response()->json(["msg" => "failed"]);
    }

    public function receiptCancellation() {
        return view('pages.fees.receipt-cancellation');
    }
    public function feesArrears() {

        return view('pages.fees.fees-arrears')->with([
            'years' => AcademicYearModel::get(),
            'classes' => ClassesModel::get(),
        ]);
        
    }

    public function submitFeesArrears(Request $req) {
            $details = CreateClass::with(['getStudent:id,name'])
            ->where(['year' => $req->year, 'standard' => $req->class])
            ->where('balance', '!=', 0)
            ->get();
            
            $details->transform(function ($detail) {
                $student = $detail->getStudent;
                $detail->id = $student->id;
                $detail->name = $student->name;
                return $detail;
            });
            
        $pdf = PDF::loadView('pdfs.classwisefees', ["fees" => $details->sortBy('name'), 
            'year' => AcademicYearModel::where("id", $req->year)->first()->year, 
            'class' => ClassesModel::where('id', $req->class)->first()->name,
            'amt_exp' => $details->sum('total'),
            'balance' => $details->sum('balance'),  
            'collected' => $details->sum('paid')
        ]);

        return $pdf->stream("Classwise Fees Arrears".'.pdf');
    }
    

    public function dayBook() {
        return view('pages.fees.day-book');
    }
    public function daybookSubmit(Request $req) {
        $section = $req->section;
        $date = $req->date;
    
        $query = FeeReceiptModel::whereDate('created_at', '=', $date);
    
        if ($section == 1) {
            $query->where('class', '>', 0)->where('class', '<', 11);
        } else {
            $query->where('class', '>', 10);
        }
    
        $receipts = $query->get();
    
        $total = $receipts->sum('amt_paid');
    
        $receipts->transform(function ($receipt) {
            $receipt->student = $receipt->studentDetail;
            $receipt->class = $receipt->classes->name;
            return $receipt;
        });
    
        $sectionName = $section == 1 ? "PRIMARY" : "HIGHER";
    
        $pdf = PDF::loadView('pdfs.daybook', ['receipts' => $receipts, 'date'=>$date, 'section'=>$section]);
    
        return $pdf->stream("Day Book $sectionName.pdf");
    }

    public function feesRegister() {

        return view('pages.fees.fees-register')->with([
            'years' => AcademicYearModel::get(),
            'classes' => ClassesModel::get(),
        ]);
    }

    public function pdfFeesRegister(Request $req) {
        // dd($req->all());
       $fees = FeeReceiptModel::where(['year' => $req->year, 'class' => $req->class])->get();
        
       $year_id = '';

       if((int)date("m") >= 6) {
        $crr = date("Y");
        $nxt = date("Y")[2].date("Y")[3];
        $year_id = $crr."-".(int)$nxt+1;
        } else {
            $crr = date("Y")-1;
            $nxt = date("Y")[2].date("Y")[3];
            $year_id = $crr."-".(int)$nxt;
        }

       $year = AcademicYearModel::where('year', $year_id)->first();

        foreach($fees as $fee) {
            $std = $fee->studentDetail;
            $fee['std_id'] = $std->id;
            $fee['name'] = $std->name;

            $fee['type'] = $std->year == $year->id ? 'NEW' : 'OLD';
        }

       $totalAmt = FeesDetailsModel::where(['year' => $req->year, 'class' => $req->class])->first()->amt_per_annum;

       $year = AcademicYearModel::where('id', $req->year)->first(['year'])->year;
       $class = ClassesModel::where('id', $req->class)->first(['name'])->name;
       $total = count($fees);
       $pdf = PDF::loadView('pdfs.feesregister', ["total" => $total, "fees" => $fees->sortBy('name'), 'amount' => $totalAmt, 'year' => $year, 'class' => $class]);

       return $pdf->stream("Fees Register.pdf");
    }

    public function receiptDatewise() {
        return view('pages.fees.receipt-datewise');
    }

    public function receiptToday(Request $req) {
        $receipts = '';

        $receiptsQuery = \DB::table('fee_receipt AS fr')
        ->select('fr.student AS register_id','fr.id', 'fr.receipt_no', 'a.name AS student_name', 'fr.class', 'fr.amt_paid', 'c.name AS class')
        ->whereDate('fr.created_at', '=', $req->date)
        ->join('admission AS a', 'fr.student', '=', 'a.id')
        ->join('classes AS c', 'fr.class', '=', 'c.id')
        ->groupBy('fr.student','fr.id', 'a.name', 'fr.class', 'c.name', 'fr.amt_paid', 'fr.receipt_no');

        if ($req->section == 1) {
            $receiptsQuery->where('fr.class', '>', 0)->where('fr.class', '<', 11);
        } elseif ($req->section == 2) {
            $receiptsQuery->where('fr.class', '>', 10);
        }
        
        $receipts = $receiptsQuery->get();
        $name = ($req->section == 1) ? "PRIMARY" : (($req->section == 2) ? "HIGHER" : "");

       $d = date('d-m-Y', strtotime($req->date));

       $pdf = PDF::loadView('pdfs.datewisereceipt', ["fees" => $receipts, 
            'date' => $d,
            'section' => $name,
            'total' => $receipts->sum('amt_paid')
        ]);

        return $pdf->stream("Datewise Receipt $name ($d).pdf");
    }

    public function receiptBetweenDates(Request $req) {
       
        $receiptsQuery = \DB::table('fee_receipt AS fr')
        ->select('fr.student AS register_id','fr.id', 'fr.receipt_no', 'a.name AS student_name', 'fr.class', 'fr.amt_paid', 'c.name AS class')
        ->whereDate('fr.created_at', '>=', $req->from_date)
        ->whereDate('fr.created_at', '<=', $req->to_date)
        ->join('admission AS a', 'fr.student', '=', 'a.id')
        ->join('classes AS c', 'fr.class', '=', 'c.id')
        ->groupBy('fr.student','fr.id', 'a.name', 'fr.class', 'c.name', 'fr.amt_paid', 'fr.receipt_no');

        if ($req->section == 1) {
            $receiptsQuery->where('fr.class', '>', 0)->where('fr.class', '<', 11);
        } elseif ($req->section == 2) {
            $receiptsQuery->where('fr.class', '>', 10);
        }
        
        $receipts = $receiptsQuery->get();
        $name = ($req->section == 1) ? "PRIMARY" : (($req->section == 2) ? "HIGHER" : "");

       $from_d = date('d-m-Y', strtotime($req->from_date));
       $to_d = date('d-m-Y', strtotime($req->to_date));

       $pdf = PDF::loadView('pdfs.datewisereceipt', ["fees" => $receipts, 
            'date' => $from_d,
            'section' => $name,
            'to_date' => $to_d,
            'total' => $receipts->sum('amt_paid')
        ]);
        
        return $pdf->stream("Datewise Receipt $name ($from_d / $to_d) .pdf");
    }

    public function duplicateReceipt() {
        return view('pages.fees.duplicate-receipt')->with([
            "years" => AcademicYearModel::get()
        ]);
    }

    public function stdReceiptID(Request $req) {

        $receipts = FeeReceiptModel::with(['classes', 'years'])->where("student", $req->id)->get();
        foreach($receipts as $r) {
            $r['class'] = $r->class == null ? '' : $r->classes->name;
            $r['year'] = $r->year == null ? '' : $r->years->year;
        }
       
        if(!isset($receipts[0])) {
            return redirect()->back();
        }

        $student = AdmissionModel::where("reg", $receipts[0]->student)->withTrashed()->first();

        return view("pages.fees.receipts-std")->with([
            "receipts" => $receipts,
            "student" => $student
        ]);
    }

    public function getDuplicate(Request $req) {
        $receipt = FeeReceiptModel::where("id", $req->id)->first();
        $fees = FeesDetailsModel::with('feeHead')->where(["year" => $receipt->year, "class" => $receipt->class])->get();
        foreach($fees as $fee) {
            $fee['fee_head'] = $fee->feeHead->desc;
        }
        $tpb = CreateClass::select("total", "paid", "balance")->where(["year" => $receipt->year, "standard" => $receipt->class])->first();
        $receipt['class'] = $receipt->class == null ? '' : $receipt->classes->name;
        $receipt['year'] =  $receipt->year == null ? '' : $receipt->years->year;
        $student = AdmissionModel::where("reg", $receipt->student)->withTrashed()->first();
        
        $pdf = PDF::loadView('pdfs.duplicate-receipt', [
            "receipt" => $receipt,
            "student" => $student,
            "fees" => $fees,
            "tpb" => $tpb

        ]);

        return $pdf->stream("Duplicate Receipt.pdf");
    }

    public function editReceipt(){

        return view('pages.fees.edit-receipt')->with([
            'years' => AcademicYearModel::get(),
            'calsses' => ClassesModel::get(),
        ]);
    }

    public function updateRecipt(Request $request){
        $request->validate([
            'student' => ['required'],
            'class' => ['required'],
            'year' => ['required'],
        ]);
        // dd($request->class);

        $receipts = FeeReceiptModel::where([
            'student' => $request->student,
            'year' => $request->year,
            'class' => $request->class
            ])->get();

            $class = ClassesModel::where('id', $request->class)->first(['name'])->name;
            $year = AcademicYearModel::where('id', $request->year)->first(['year'])->year;

            $detail = "$class , $year";
        
        $student = AdmissionModel::where('id', $request->student)->first(['fname', 'lname']);
        return view('pages.fees.update-receipt', [
            'student' => "$student->fname, $student->lname",
            'id' => $request->student,
            'detail' => $detail,
            'receipts' => $receipts,
            'year' => $request->year,
            'class' => $request->class,
        ]);
    }

    public function update(Request $request){
        $request->validate([
            'id' => ['required'],
            'amount' => ['required', 'numeric'],
        ]);

        $old = $request->old_amount;
        $new = $request->amount;

        $admission = CreateClass::where(['student' => $request->student, 'year' => $request->year])->first();
        
        $admission->paid = $admission->paid  - ($old-$new);
        $admission->balance = $admission->total - $admission->paid;

        $admission->save();

        FeeReceiptModel::where('id', $request->id)->update([
            'amt_paid' => $new
        ]);
        return back();
    }
}

/* 
total = 1000
paid = 500
balance = 500

transaction = 500

old = 500
new = 200

500 - 200 = 300

paid = 500 - 300

balance = 800
*/
