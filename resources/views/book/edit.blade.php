@extends('layouts.app')
@section('content')
    <div id="admin-content">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <h2 class="admin-heading">Update Book</h2>
                </div>
            </div>
            <div class="row">
                <div class="offset-md-3 col-md-6">
                    <form class="yourform" action="{{ route('book.update', $book->id) }}" method="post"
                        autocomplete="off">
                        @csrf
                        <div class="form-group">
                            <label>Book Name</label>
                            <input type="text" class="form-control @error('name') isinvalid @enderror"
                                placeholder="Book Name" name="name" value="{{ $book->name }}" >
                            @error('name')
                                <div class="alert alert-danger" role="alert">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Type</label>
                            <select class="form-control @error('type') isinvalid @enderror" name="type" required>
                                <option value="">Select Type</option>
                                <option value="Book" {{ $book->type == 'Book' ? 'selected' : '' }}>Book (पुस्तक)</option>
                                <option value="Story" {{ $book->type == 'Story' ? 'selected' : '' }}>Story (कहानी)</option>
                                <option value="Poem" {{ $book->type == 'Poem' ? 'selected' : '' }}>Poem (कविता)</option>
                                <option value="Blog" {{ $book->type == 'Blog' ? 'selected' : '' }}>Blog (ब्लॉग)</option>
                                <option value="Article" {{ $book->type == 'Article' ? 'selected' : '' }}>Article (लेख)</option>
                                <option value="Research Paper" {{ $book->type == 'Research Paper' ? 'selected' : '' }}>Research Paper (शोध-पत्र)</option>
                            </select>
                            @error('type')
                                <div class="alert alert-danger" role="alert">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Category</label>
                            <select class="form-control @error('category_id') isinvalid @enderror " name="category_id"
                                >
                                <option value="">Select Category</option>
                                @foreach ($categories as $category)
                                    @if ($category->id == $book->category_id)
                                        <option value="{{ $category->id }}" selected>{{ $category->name }}</option>
                                    @else
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="alert alert-danger" role="alert">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Author</label>
                            <select class="form-control @error('author_id') isinvalid @enderror " name="author_id">
                                <option value="">Select Author</option>
                                @foreach ($authors as $author)
                                    @if ($author->id == $book->author_id)
                                        <option value="{{ $author->id }}" selected>{{ $author->name }}</option>
                                    @else
                                        <option value="{{ $author->id }}">{{ $author->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                            @error('author_id')
                                <div class="alert alert-danger" role="alert">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Publisher</label>
                            <select class="form-control @error('publisher_id') isinvalid @enderror "
                                name="publisher_id" >
                                <option value="">Select Publisher</option>
                                @foreach ($publishers as $publisher)
                                    @if ($publisher->id == $book->publisher_id)
                                        <option value="{{ $publisher->id }}" selected>{{ $publisher->name }}</option>
                                    @else
                                        <option value="{{ $publisher->id }}">{{ $publisher->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                            @error('publisher_id')
                                <div class="alert alert-danger" role="alert">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Quantity</label>
                            <input type="number" class="form-control @error('quantity') isinvalid @enderror"
                                placeholder="Quantity" name="quantity" value="{{ $book->quantity }}" min="0" required>
                            @error('quantity')
                                <div class="alert alert-danger" role="alert">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <input type="submit" name="save" class="btn btn-danger" value="Update" >
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
