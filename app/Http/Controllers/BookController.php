<?php

namespace App\Http\Controllers;

use App\Models\book;
use App\Http\Requests\StorebookRequest;
use App\Http\Requests\UpdatebookRequest;
use App\Models\author;
use App\Models\category;
use App\Models\publisher;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = book::query();
        
        if ($request->has('type') && in_array($request->type, ['Book', 'Story', 'Poem', 'Blog', 'Article', 'Research Paper'])) {
            $query->where('type', $request->type);
        }

        // Secondary category filter
        if ($request->has('category') && !empty($request->category)) {
            $query->where('category_id', $request->category);
        }

        $counts = [
            'All' => book::count(),
            'Book' => book::where('type', 'Book')->count(),
            'Story' => book::where('type', 'Story')->count(),
            'Poem' => book::where('type', 'Poem')->count(),
            'Blog' => book::where('type', 'Blog')->count(),
            'Article' => book::where('type', 'Article')->count(),
            'Research Paper' => book::where('type', 'Research Paper')->count(),
        ];

        // Get categories that exist for the selected type to show sub-filter pills
        $availableCategories = [];
        if ($request->has('type') && !empty($request->type)) {
            $catIds = book::where('type', $request->type)->distinct()->pluck('category_id');
            $availableCategories = category::whereIn('id', $catIds)->get();
        }

        return view('book.index', [
            'books' => $query->paginate(5)->withQueryString(),
            'counts' => $counts,
            'selectedType' => $request->type,
            'selectedCategory' => $request->category,
            'availableCategories' => $availableCategories
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('book.create',[
            'authors' => author::latest()->get(),
            'publishers' => publisher::latest()->get(),
            'categories' => category::latest()->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorebookRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorebookRequest $request)
    {
        book::create($request->validated() + [
            'status' => 'Y'
        ]);
        return redirect()->route('books');
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\book  $book
     * @return \Illuminate\Http\Response
     */
    public function edit(book $book)
    {
        return view('book.edit',[
            'authors' => author::latest()->get(),
            'publishers' => publisher::latest()->get(),
            'categories' => category::latest()->get(),
            'book' => $book
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatebookRequest  $request
     * @param  \App\Models\book  $book
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatebookRequest $request, $id)
    {
        $book = book::find($id);
        $book->name = $request->name;
        $book->author_id = $request->author_id;
        $book->category_id = $request->category_id;
        $book->publisher_id = $request->publisher_id;
        $book->quantity = $request->quantity;
        $book->type = $request->type;
        $book->save();
        return redirect()->route('books');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\book  $book
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        book::find($id)->delete();
        return redirect()->route('books');
    }

    /**
     * Download Book CSV Sample Template.
     */
    public function download_sample()
    {
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=books_import_template.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = ['Title', 'Author', 'Publisher', 'Category', 'Quantity', 'Type'];

        $callback = function() use($columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            
            // Add sample rows
            fputcsv($file, ['Clean Code', 'Robert C. Martin', 'Pearson', 'Programming', '5', 'Book']);
            fputcsv($file, ['Mahabharat', 'Ved Vyas', 'Geeta Press', 'Story', '2', 'Story']);
            fputcsv($file, ['In the Woods', 'Tana French', 'Penguin', 'Mystery', '3', 'Book']);
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Import Books from CSV File.
     */
    public function import_csv(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:2048'
        ]);

        $file = $request->file('csv_file');
        $path = $file->getRealPath();
        $successCount = 0;
        $failedCount = 0;

        if (($handle = fopen($path, "r")) !== FALSE) {
            $header = fgetcsv($handle, 1000, ",");
            if ($header) {
                // Strip BOM
                $header[0] = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $header[0]);
                $header = array_map('strtolower', $header);
                
                while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    if (empty(array_filter($row))) {
                        continue;
                    }

                    $rowData = array_combine($header, array_pad($row, count($header), ''));
                    
                    if (isset($rowData['title']) && $rowData['title'] !== '') {
                        $authorName = trim($rowData['author'] ?? 'Unknown Author');
                        $author = author::firstOrCreate(['name' => $authorName]);

                        $publisherName = trim($rowData['publisher'] ?? 'Unknown Publisher');
                        $publisher = publisher::firstOrCreate(['name' => $publisherName]);

                        $categoryName = trim($rowData['category'] ?? 'General');
                        $category = category::firstOrCreate(['name' => $categoryName]);

                        book::create([
                            'name' => $rowData['title'],
                            'author_id' => $author->id,
                            'publisher_id' => $publisher->id,
                            'category_id' => $category->id,
                            'quantity' => intval($rowData['quantity'] ?? '1'),
                            'type' => $rowData['type'] ?? 'Book',
                            'status' => 'Y'
                        ]);
                        $successCount++;
                    } else {
                        $failedCount++;
                    }
                }
            }
            fclose($handle);
        }

        return redirect()->route('books')->with('success', "Import completed. Successfully added: {$successCount} books. Failed: {$failedCount} rows.");
    }
}
