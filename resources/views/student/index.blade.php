@extends('layouts.app')
@section('content')
    <div id="admin-content">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h2 class="admin-heading">All Students</h2>
                </div>
                <div class="offset-md-4 col-md-4 text-right" style="display: flex; justify-content: flex-end; gap: 10px; align-items: center; margin-bottom: 20px;">
                    <button class="add-new btn-success" id="importCsvBtn" style="border: none; padding: 10px 18px; border-radius: 8px; font-weight: 600; cursor: pointer; background: #10B981; color: white;">Import CSV</button>
                    <a class="add-new" href="{{ route('student.create') }}" style="padding: 10px 18px; border-radius: 8px; font-weight: 600;">Add Student</a>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <!-- Dynamic Class & Branch Filter Tabs -->
                    <div style="margin-bottom: 24px;">
                        <!-- Class Tabs -->
                        <div class="filter-tabs">
                            <a href="{{ route('students', array_filter(['branch' => $selectedBranch, 'search' => $searchKeyword])) }}" 
                               class="filter-tab-item {{ empty($selectedClass) ? 'active' : '' }}">
                                All Classes (सभी कक्षाएं) <span class="filter-badge">{{ $totalCount }}</span>
                            </a>
                            @foreach ($classes as $cls)
                                <a href="{{ route('students', ['class' => $cls, 'branch' => $selectedBranch, 'search' => $searchKeyword]) }}" 
                                   class="filter-tab-item {{ $selectedClass == $cls ? 'active' : '' }}">
                                    {{ $cls }} <span class="filter-badge">{{ $classCounts[$cls] ?? 0 }}</span>
                                </a>
                            @endforeach
                        </div>

                        <!-- Branch Pills -->
                        @if($branches->count() > 0)
                            <div class="sub-filter-pills">
                                <a href="{{ route('students', array_filter(['class' => $selectedClass, 'search' => $searchKeyword])) }}" 
                                   class="sub-filter-pill-item {{ empty($selectedBranch) ? 'active' : '' }}">
                                    All Branches (सभी शाखाएं)
                                </a>
                                @foreach ($branches as $brh)
                                    <a href="{{ route('students', ['class' => $selectedClass, 'branch' => $brh, 'search' => $searchKeyword]) }}" 
                                       class="sub-filter-pill-item {{ $selectedBranch == $brh ? 'active' : '' }}">
                                        {{ $brh }} <span class="badge badge-light" style="font-size: 10px; margin-left: 4px; background: #E2E8F0; color: #475569;">{{ $branchCounts[$brh] ?? 0 }}</span>
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- Search Input and Clear -->
                    <div class="student-filter-container" style="padding: 15px; margin-bottom: 20px;">
                        <form action="{{ route('students') }}" method="get">
                            @if($selectedClass)
                                <input type="hidden" name="class" value="{{ $selectedClass }}">
                            @endif
                            @if($selectedBranch)
                                <input type="hidden" name="branch" value="{{ $selectedBranch }}">
                            @endif
                            <div style="display: flex; gap: 10px; align-items: center;">
                                <input type="text" name="search" placeholder="Search by name, email or phone..." value="{{ $searchKeyword }}" style="flex-grow: 1; height: 40px; border-radius: 8px; border: 1px solid #CBD5E1; padding: 0 15px;">
                                <button type="submit" class="btn btn-danger" style="height: 40px; border-radius: 8px; font-weight: 600; padding: 0 20px;">Search</button>
                                @if($selectedClass || $selectedBranch || $searchKeyword)
                                    <a href="{{ route('students') }}" class="filter-btn-clear" style="height: 40px; padding: 0 15px; line-height: 38px; display: inline-flex; align-items: center; justify-content: center; font-weight: 600; text-decoration: none;">Clear</a>
                                @endif
                            </div>
                        </form>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: 8px; margin-bottom: 20px;">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <div class="message"></div>
                    <table class="content-table">
                        <thead>
                            <th>S.No</th>
                            <th>Photo</th>
                            <th>Student Name</th>
                            <th>Gender</th>
                            <th>Class & Branch</th>
                            <th>Category</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>View</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </thead>
                        <tbody>
                            @forelse ($students as $student)
                                <tr>
                                    <td class="id">{{ $student->id }}</td>
                                    <td>
                                        <img src="{{ $student->photo ? asset('images/' . $student->photo) : asset('images/avatar.png') }}" 
                                             class="student-avatar" alt="Avatar">
                                    </td>
                                    <td>{{ $student->name }}</td>
                                    <td class="text-capitalize">{{ $student->gender }}</td>
                                    <td>{{ $student->class }} <br> <small class="text-muted">{{ $student->branch }}</small></td>
                                    <td>
                                        <span class="badge badge-secondary" style="background-color: #EF4444; color: white; padding: 4px 8px; border-radius: 4px; font-size: 11px;">
                                            {{ $student->category }}
                                        </span>
                                    </td>
                                    <td>{{ $student->phone }}</td>
                                    <td>{{ $student->email }}</td>
                                    <td class="view">
                                        <button data-sid='{{ $student->id }}'
                                            class="btn btn-primary view-btn">View</button>
                                    </td>
                                    <td class="edit">
                                        <a href="{{ route('student.edit', $student) }}" class="btn btn-success">Edit</a>
                                    </td>
                                    <td class="delete">
                                        <form action="{{ route('student.destroy', $student->id) }}" method="post"
                                            class="form-hidden">
                                            <button class="btn btn-danger delete-student">Delete</button>
                                            @csrf
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11">No Students Found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    {{ $students->links('vendor/pagination/bootstrap-4') }}
                    
                    <!-- Detail View Modal -->
                    <div id="modal">
                        <div id="modal-form">
                            <div class="modal-card-content">
                                
                            </div>
                            <div id="close-btn">X</div>
                        </div>
                    </div>

                    <!-- CSV Import Modal -->
                    <div id="import-modal" class="custom-modal" style="display: none;">
                        <div class="custom-modal-content">
                            <div class="custom-modal-header">
                                <h3>Import Students List</h3>
                                <span class="close-import-modal" style="font-size: 24px; cursor: pointer; color: #9CA3AF;">&times;</span>
                            </div>
                            <form action="{{ route('student.import') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="custom-modal-body">
                                    <p style="font-size: 13px; color: #4B5563; margin-bottom: 15px; text-align: left;">
                                        Upload a CSV file containing your student records. You can download our template below to format your data correctly.
                                    </p>
                                    <div class="form-group" style="text-align: left;">
                                        <label style="font-weight: 600; font-size: 13px; color: #374151;">Select CSV File</label>
                                        <input type="file" name="csv_file" class="form-control" accept=".csv" required style="padding-top: 5px; height: auto;">
                                    </div>
                                    <div style="margin-top: 15px; text-align: left;">
                                        <a href="{{ route('student.import.sample') }}" class="text-primary" style="font-weight: 600; font-size: 13px; text-decoration: underline;">
                                            📥 Download Sample CSV Template
                                        </a>
                                    </div>
                                </div>
                                <div class="custom-modal-footer">
                                    <button type="button" id="import-cancel-btn" class="btn btn-secondary" style="border-radius: 8px;">Cancel</button>
                                    <button type="submit" class="btn btn-danger" style="border-radius: 8px;">Import Now</button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/jquery-3.6.0.min.js') }}"></script>
    <script type="text/javascript">
        //Show student detail
        $(".view-btn").on("click", function() {
            var student_id = $(this).data("sid");
            $.ajax({
                url: "/student/show/"+student_id,
                type: "get",
                success: function(student) {
                    console.log(student);
                    
                    let photoUrl = student['photo'] ? "/images/" + student['photo'] : "/images/avatar.png";
                    let category = student['category'] ? student['category'] : "General";
                    let branch = student['branch'] ? student['branch'] : "N/A";
                    
                    let cardHtml = "<div class='profile-modal-card'>" +
                               "  <img src='" + photoUrl + "' class='student-avatar-lg' alt='Avatar'>" +
                               "  <h4>" + student['name'] + "</h4>" +
                               "  <span class='profile-category-badge'>" + category + "</span>" +
                               "  <table class='profile-modal-details-table'>" +
                               "    <tr><td>Class</td><td>" + student['class'] + "</td></tr>" +
                               "    <tr><td>Branch</td><td>" + branch + "</td></tr>" +
                               "    <tr><td>Gender</td><td>" + student['gender'] + "</td></tr>" +
                               "    <tr><td>Age</td><td>" + student['age'] + " years</td></tr>" +
                               "    <tr><td>Phone</td><td>" + student['phone'] + "</td></tr>" +
                               "    <tr><td>Email</td><td>" + student['email'] + "</td></tr>" +
                               "    <tr><td>Address</td><td>" + student['address'] + "</td></tr>" +
                               "  </table>";

                    // Add Issued Books list inside Student detail card
                    let booksHtml = "";
                    if (student['issued_books'] && student['issued_books'].length > 0) {
                        booksHtml += "<h5 style='margin: 20px 0 10px; font-weight: 700; text-align: left; color: #1F2937; border-bottom: 2px solid #EF4444; padding-bottom: 4px;'>Issued Books History</h5>";
                        booksHtml += "<table class='profile-modal-details-table' style='margin-top: 5px; text-align: left;'>";
                        booksHtml += "  <thead>";
                        booksHtml += "    <tr style='font-weight: 700; color: #374151; border-bottom: 2px solid #E5E7EB;'><th style='padding: 6px 4px;'>Book</th><th style='padding: 6px 4px;'>Issued</th><th style='padding: 6px 4px;'>Status</th></tr>";
                        booksHtml += "  </thead>";
                        booksHtml += "  <tbody>";
                        student['issued_books'].forEach(function(issue) {
                            let bookName = issue['book'] ? issue['book']['name'] : 'Unknown Book';
                            // Format date
                            let issueDate = new Date(issue['issue_date']).toLocaleDateString('en-US', { day: 'numeric', month: 'short', year: 'numeric' });
                            let statusBadge = "";
                            if (issue['issue_status'] === 'Y') {
                                statusBadge = "<span class='badge badge-success' style='background-color: #10B981; color: white; padding: 2px 6px; border-radius: 4px; font-size: 11px;'>Returned</span>";
                            } else {
                                statusBadge = "<span class='badge badge-danger' style='background-color: #EF4444; color: white; padding: 2px 6px; border-radius: 4px; font-size: 11px;'>Issued</span>";
                            }
                            booksHtml += "    <tr style='border-bottom: 1px solid #F3F4F6;'><td style='padding: 8px 4px; font-size: 13px; font-weight: 600;'>" + bookName + "</td><td style='padding: 8px 4px; font-size: 12px;'>" + issueDate + "</td><td style='padding: 8px 4px;'>" + statusBadge + "</td></tr>";
                        });
                        booksHtml += "  </tbody>";
                        booksHtml += "</table>";
                    } else {
                        booksHtml += "<p style='margin: 20px 0 10px; font-size: 13px; color: #6B7280; font-style: italic; text-align: center;'>No books issued to this student.</p>";
                    }

                    cardHtml += booksHtml + "</div>";

                    $("#modal-form .modal-card-content").html(cardHtml);
                    $("#modal").show();
                }
            });
        });

        //Hide modal box
        $('#close-btn').on("click", function() {
            $("#modal").hide();
        });

        // CSV Import modal controls
        $('#importCsvBtn').on("click", function() {
            $('#import-modal').show();
        });

        $('.close-import-modal, #import-cancel-btn').on("click", function() {
            $('#import-modal').hide();
        });
    </script>
@endsection
