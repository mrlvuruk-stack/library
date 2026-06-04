@extends('layouts.app')
@section('content')

<style>
    .filter-tabs {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 24px;
        padding-bottom: 12px;
        border-bottom: 1px solid #E2E8F0;
    }
    
    .filter-tab-item {
        font-size: 13px;
        font-weight: 600;
        padding: 8px 16px;
        border-radius: 8px;
        color: #64748B;
        background: #ffffff;
        border: 1px solid #E2E8F0;
        text-decoration: none;
        transition: all 0.2s ease-in-out;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .filter-tab-item:hover {
        color: #0F172A;
        border-color: #CBD5E1;
        background: #F8FAFC;
        text-decoration: none;
    }
    
    .filter-tab-item.active {
        color: #ffffff;
        background: #020617;
        border-color: #020617;
    }
    
    .filter-badge {
        font-size: 11px;
        font-weight: 700;
        padding: 2px 6px;
        border-radius: 4px;
        background: #F1F5F9;
        color: #475569;
    }
    
    .filter-tab-item.active .filter-badge {
        background: rgba(255, 255, 255, 0.2);
        color: #ffffff;
    }
    
    .sub-filter-pills {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: -12px;
        margin-bottom: 24px;
        padding: 6px;
        background: #F8FAFC;
        border-radius: 10px;
        border: 1px dashed #E2E8F0;
    }
    
    .sub-filter-pill-item {
        font-size: 12px;
        font-weight: 500;
        padding: 6px 12px;
        border-radius: 6px;
        color: #475569;
        text-decoration: none;
        transition: all 0.15s ease-in-out;
        background: transparent;
    }
    
    .sub-filter-pill-item:hover {
        color: #0F172A;
        background: #E2E8F0;
        text-decoration: none;
    }
    
    .sub-filter-pill-item.active {
        color: #0F172A;
        background: #ffffff;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.08);
        font-weight: 600;
    }
</style>

    <div id="admin-content">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <h2 class="admin-heading">All Books</h2>
                </div>
                <div class="offset-md-5 col-md-4 text-right" style="display: flex; justify-content: flex-end; gap: 10px; align-items: center; margin-bottom: 20px;">
                    <button class="add-new btn-success" id="importCsvBtn" style="border: none; padding: 10px 18px; border-radius: 8px; font-weight: 600; cursor: pointer; background: #10B981; color: white;">Import CSV</button>
                    <a class="add-new" href="{{ route('book.create') }}" style="padding: 10px 18px; border-radius: 8px; font-weight: 600;">Add Book</a>
                </div>
            </div>
            
            @if(session('success'))
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: 8px; margin-bottom: 20px;">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>
                </div>
            @endif
            
            <div class="row">
                <div class="col-md-12">
                    <div class="filter-tabs">
                        <a href="{{ route('books') }}" class="filter-tab-item {{ !$selectedType ? 'active' : '' }}">
                            <span>All (सभी)</span>
                            <span class="filter-badge">{{ $counts['All'] }}</span>
                        </a>
                        <a href="{{ route('books', ['type' => 'Book']) }}" class="filter-tab-item {{ $selectedType == 'Book' ? 'active' : '' }}">
                            <span>पुस्तक (Book)</span>
                            <span class="filter-badge">{{ $counts['Book'] }}</span>
                        </a>
                        <a href="{{ route('books', ['type' => 'Story']) }}" class="filter-tab-item {{ $selectedType == 'Story' ? 'active' : '' }}">
                            <span>कहानी (Story)</span>
                            <span class="filter-badge">{{ $counts['Story'] }}</span>
                        </a>
                        <a href="{{ route('books', ['type' => 'Poem']) }}" class="filter-tab-item {{ $selectedType == 'Poem' ? 'active' : '' }}">
                            <span>कविता (Poem)</span>
                            <span class="filter-badge">{{ $counts['Poem'] }}</span>
                        </a>
                        <a href="{{ route('books', ['type' => 'Blog']) }}" class="filter-tab-item {{ $selectedType == 'Blog' ? 'active' : '' }}">
                            <span>ब्लॉग (Blog)</span>
                            <span class="filter-badge">{{ $counts['Blog'] }}</span>
                        </a>
                        <a href="{{ route('books', ['type' => 'Article']) }}" class="filter-tab-item {{ $selectedType == 'Article' ? 'active' : '' }}">
                            <span>लेख (Article)</span>
                            <span class="filter-badge">{{ $counts['Article'] }}</span>
                        </a>
                        <a href="{{ route('books', ['type' => 'Research Paper']) }}" class="filter-tab-item {{ $selectedType == 'Research Paper' ? 'active' : '' }}">
                            <span>शोध-पत्र (Research Paper)</span>
                            <span class="filter-badge">{{ $counts['Research Paper'] }}</span>
                        </a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    @if(!empty($availableCategories) && count($availableCategories) > 0)
                        <div class="sub-filter-pills">
                            <a href="{{ route('books', ['type' => $selectedType]) }}" class="sub-filter-pill-item {{ !$selectedCategory ? 'active' : '' }}">
                                All Categories (सभी)
                            </a>
                            @foreach($availableCategories as $cat)
                                <a href="{{ route('books', ['type' => $selectedType, 'category' => $cat->id]) }}" class="sub-filter-pill-item {{ $selectedCategory == $cat->id ? 'active' : '' }}">
                                    {{ $cat->name }}
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="message"></div>
                    <table class="content-table">
                        <thead>
                            <th>S.No</th>
                            <th>Book Name</th>
                            <th>Type</th>
                            <th>Category</th>
                            <th>Author</th>
                            <th>Publisher</th>
                            <th>Quantity</th>
                            <th>Status</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </thead>
                        <tbody>
                            @forelse ($books as $book)
                                <tr>
                                    <td class="id">{{ $book->id }}</td>
                                    <td>{{ $book->name }}</td>
                                    <td>{{ $book->type }}</td>
                                    <td>{{ $book->category->name }}</td>
                                    <td>{{ $book->author->name }}</td>
                                    <td>{{ $book->publisher->name }}</td>
                                    <td>{{ $book->quantity }}</td>
                                    <td>
                                        @if ($book->status == 'Y')
                                            <span class='badge badge-success'>Available</span>
                                        @else
                                            <span class='badge badge-danger'>Issued</span>
                                        @endif
                                    </td>
                                    <td class="edit">
                                        <a href="{{ route('book.edit', $book) }}" class="btn btn-success">Edit</a>
                                    </td>
                                    <td class="delete">
                                        <form action="{{ route('book.destroy', $book) }}" method="post"
                                            class="form-hidden">
                                            <button class="btn btn-danger delete-book">Delete</button>
                                            @csrf
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10">No Books Found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    {{ $books->links('vendor/pagination/bootstrap-4') }}
                    
                    <!-- CSV Import Modal -->
                    <div id="import-modal" class="custom-modal" style="display: none;">
                        <div class="custom-modal-content">
                            <div class="custom-modal-header">
                                <h3>Import Books Catalog</h3>
                                <span class="close-import-modal" style="font-size: 24px; cursor: pointer; color: #9CA3AF;">&times;</span>
                            </div>
                            <form action="{{ route('book.import') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="custom-modal-body">
                                    <p style="font-size: 13px; color: #4B5563; margin-bottom: 15px; text-align: left;">
                                        Upload a CSV file containing your book details. Our importer will automatically match or create Authors, Publishers, and Categories in the database by their text names!
                                    </p>
                                    <div class="form-group" style="text-align: left;">
                                        <label style="font-weight: 600; font-size: 13px; color: #374151;">Select CSV File</label>
                                        <input type="file" name="csv_file" class="form-control" accept=".csv" required style="padding-top: 5px; height: auto;">
                                    </div>
                                    <div style="margin-top: 15px; text-align: left;">
                                        <a href="{{ route('book.import.sample') }}" class="text-primary" style="font-weight: 600; font-size: 13px; text-decoration: underline;">
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
        $(document).ready(function() {
            // CSV Import modal controls
            $('#importCsvBtn').on("click", function() {
                $('#import-modal').show();
            });

            $('.close-import-modal, #import-cancel-btn').on("click", function() {
                $('#import-modal').hide();
            });
        });
    </script>
@endsection
