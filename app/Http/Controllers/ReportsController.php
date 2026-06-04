<?php

namespace App\Http\Controllers;

use App\Models\book;
use App\Models\book_issue;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    public function index()
    {
        return view('report.index');
    }

    public function date_wise()
    {
        return view('report.dateWise', ['books' => '', 'date' => '']);
    }

    public function generate_date_wise_report(Request $request)
    {
        $request->validate(['date' => "required|date"]);
        return view('report.dateWise', [
            'books' => book_issue::where('issue_date', $request->date)->latest()->get(),
            'date' => $request->date
        ]);
    }

    public function month_wise()
    {
        return view('report.monthWise', ['books' => '', 'month' => '']);
    }

    public function generate_month_wise_report(Request $request)
    {
        $request->validate(['month' => "required"]);
        return view('report.monthWise', [
            'books' => book_issue::where('issue_date', 'LIKE', '%' . $request->month . '%')->latest()->get(),
            'month' => $request->month
        ]);
    }

    public function not_returned()
    {
        return view('report.notReturned',[
            'books' => book_issue::latest()->get()
        ]);
    }

    public function export_date_wise(Request $request)
    {
        $request->validate(['date' => "required|date"]);
        $books = book_issue::where('issue_date', $request->date)->latest()->get();
        
        $fileName = 'date_wise_report_' . $request->date . '.csv';
        
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];
        
        $columns = ['S.No', 'Student Name', 'Book Name', 'Phone', 'Email', 'Issue Date'];
        
        $callback = function() use($books, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            
            foreach ($books as $book) {
                fputcsv($file, [
                    $book->id,
                    $book->student->name,
                    $book->book->name,
                    $book->student->phone,
                    $book->student->email,
                    $book->issue_date->format('d M, Y')
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    public function export_month_wise(Request $request)
    {
        $request->validate(['month' => "required"]);
        $books = book_issue::where('issue_date', 'LIKE', '%' . $request->month . '%')->latest()->get();
        
        $fileName = 'month_wise_report_' . $request->month . '.csv';
        
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];
        
        $columns = ['S.No', 'Student Name', 'Book Name', 'Phone', 'Email', 'Issue Date'];
        
        $callback = function() use($books, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            
            foreach ($books as $book) {
                fputcsv($file, [
                    $book->id,
                    $book->student->name,
                    $book->book->name,
                    $book->student->phone,
                    $book->student->email,
                    $book->issue_date->format('d M, Y')
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    public function export_not_returned()
    {
        $books = book_issue::latest()->get();
        
        $fileName = 'not_returned_books_report.csv';
        
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];
        
        $columns = ['S.No', 'Student Name', 'Book Name', 'Phone', 'Email', 'Issue Date', 'Return Date', 'Over Days'];
        
        $callback = function() use($books, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            
            foreach ($books as $book) {
                $date1 = date_create(date('Y-m-d'));
                $date2 = date_create($book->return_date->format('d-m-Y'));
                if($date1 > $date2){
                    $diff = date_diff($date1,$date2);
                    $days = $diff->format('%a days');
                }else{
                    $days = '0 days';
                }
                
                fputcsv($file, [
                    $book->id,
                    $book->student->name,
                    $book->book->name,
                    $book->student->phone,
                    $book->student->email,
                    $book->issue_date->format('d M, Y'),
                    $book->return_date->format('d M, Y'),
                    $days
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}
