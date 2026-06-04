<?php

namespace App\Http\Controllers;

use App\Models\student;
use App\Http\Requests\StorestudentRequest;
use App\Http\Requests\UpdatestudentRequest;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $selectedClass = $request->class;
        $selectedBranch = $request->branch;

        $classes = student::whereNotNull('class')->where('class', '!=', '')->distinct()->pluck('class');

        // Dynamic branches depending on selected class
        $branchesQuery = student::whereNotNull('branch')->where('branch', '!=', '');
        if ($selectedClass) {
            $branchesQuery->where('class', $selectedClass);
        }
        $branches = $branchesQuery->distinct()->pluck('branch');

        // Get count for each class
        $classCounts = student::selectRaw('class, count(*) as count')
            ->whereNotNull('class')
            ->where('class', '!=', '')
            ->groupBy('class')
            ->pluck('count', 'class');

        // Get count for branches
        $branchCountsQuery = student::selectRaw('branch, count(*) as count')
            ->whereNotNull('branch')
            ->where('branch', '!=', '');
        if ($selectedClass) {
            $branchCountsQuery->where('class', $selectedClass);
        }
        $branchCounts = $branchCountsQuery->groupBy('branch')->pluck('count', 'branch');

        $totalCount = student::count();

        $query = student::query();

        if ($selectedClass) {
            $query->where('class', $selectedClass);
        }

        if ($selectedBranch) {
            $query->where('branch', $selectedBranch);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        return view('student.index', [
            'students' => $query->paginate(5)->withQueryString(),
            'classes' => $classes,
            'branches' => $branches,
            'classCounts' => $classCounts,
            'branchCounts' => $branchCounts,
            'totalCount' => $totalCount,
            'selectedClass' => $selectedClass,
            'selectedBranch' => $selectedBranch,
            'searchKeyword' => $request->search,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('student.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorestudentRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorestudentRequest $request)
    {
        $data = $request->validated();

        if ($request->filled('photo')) {
            $photoData = $request->photo;
            if (preg_match('/^data:image\/(\w+);base64,/', $photoData, $type)) {
                $photoData = substr($photoData, strpos($photoData, ',') + 1);
                $type = strtolower($type[1]);
                if (in_array($type, ['jpg', 'jpeg', 'gif', 'png'])) {
                    $photoData = base64_decode($photoData);
                    if ($photoData !== false) {
                        $fileName = 'student_' . time() . '_' . uniqid() . '.' . $type;
                        $destinationPath = public_path('/images/students');
                        if (!file_exists($destinationPath)) {
                            mkdir($destinationPath, 0755, true);
                        }
                        file_put_contents($destinationPath . '/' . $fileName, $photoData);
                        $data['photo'] = 'students/' . $fileName;
                    }
                }
            }
        }

        student::create($data);

        return redirect()->route('students');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\student  $student
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $student = student::with(['issued_books.book'])->findOrFail($id);
        return $student;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\student  $student
     * @return \Illuminate\Http\Response
     */
    public function edit(student $student)
    {
        return view('student.edit', [
            'student' => $student
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatestudentRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatestudentRequest $request, $id)
    {
        $student = student::findOrFail($id);
        
        $student->name = $request->name;
        $student->address = $request->address;
        $student->gender = $request->gender;
        $student->class = $request->class;
        $student->branch = $request->branch;
        $student->category = $request->category;
        $student->age = $request->age;
        $student->phone = $request->phone;
        $student->email = $request->email;

        if ($request->filled('photo')) {
            $photoData = $request->photo;
            if (preg_match('/^data:image\/(\w+);base64,/', $photoData, $type)) {
                $photoData = substr($photoData, strpos($photoData, ',') + 1);
                $type = strtolower($type[1]);
                if (in_array($type, ['jpg', 'jpeg', 'gif', 'png'])) {
                    $photoData = base64_decode($photoData);
                    if ($photoData !== false) {
                        // Delete old photo if it exists
                        if ($student->photo && file_exists(public_path('/images/' . $student->photo))) {
                            @unlink(public_path('/images/' . $student->photo));
                        }

                        $fileName = 'student_' . time() . '_' . uniqid() . '.' . $type;
                        $destinationPath = public_path('/images/students');
                        if (!file_exists($destinationPath)) {
                            mkdir($destinationPath, 0755, true);
                        }
                        file_put_contents($destinationPath . '/' . $fileName, $photoData);
                        $student->photo = 'students/' . $fileName;
                    }
                }
            }
        }

        $student->save();

        return redirect()->route('students');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\student  $student
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $student = student::findOrFail($id);
        if ($student->photo && file_exists(public_path('/images/' . $student->photo))) {
            @unlink(public_path('/images/' . $student->photo));
        }
        $student->delete();
        return redirect()->route('students');
    }

    /**
     * Download Student CSV Sample Template.
     */
    public function download_sample()
    {
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=students_import_template.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = ['Name', 'Age', 'Gender', 'Email', 'Phone', 'Address', 'Class', 'Branch', 'Category'];

        $callback = function() use($columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            
            // Add sample rows
            fputcsv($file, ['Amit Kumar', '18', 'male', 'amit@example.com', '9876543210', '123 Market Road', '12th Class', 'Maths', 'General']);
            fputcsv($file, ['Neha Sharma', '17', 'female', 'neha@example.com', '9876543211', '456 Green Street', '12th Class', 'Science', 'OBC']);
            fputcsv($file, ['Rajesh Patel', '18', 'male', 'rajesh@example.com', '9876543212', '789 Link Circle', '10th Class', 'General Science', 'SC']);
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Import Students from CSV File.
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
                    
                    if (isset($rowData['name']) && $rowData['name'] !== '') {
                        student::create([
                            'name' => $rowData['name'],
                            'age' => $rowData['age'] ?? '18',
                            'gender' => strtolower($rowData['gender'] ?? 'male'),
                            'email' => $rowData['email'] ?? (str_replace(' ', '', strtolower($rowData['name'])) . '@example.com'),
                            'phone' => $rowData['phone'] ?? '0000000000',
                            'address' => $rowData['address'] ?? 'N/A',
                            'class' => $rowData['class'] ?? 'N/A',
                            'branch' => $rowData['branch'] ?? 'N/A',
                            'category' => $rowData['category'] ?? 'General',
                        ]);
                        $successCount++;
                    } else {
                        $failedCount++;
                    }
                }
            }
            fclose($handle);
        }

        return redirect()->route('students')->with('success', "Import completed. Successfully added: {$successCount} students. Failed: {$failedCount} rows.");
    }
}
