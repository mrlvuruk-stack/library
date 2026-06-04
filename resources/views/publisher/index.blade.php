@extends('layouts.app')
@section('content')
<style>
    /* Premium Publisher details modal */
    .publisher-name-link {
        color: #2563EB;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        transition: color 0.2s;
    }
    .publisher-name-link:hover {
        color: #1D4ED8;
        text-decoration: underline;
    }
    
    .custom-modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(4px);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 2000;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .custom-modal-overlay.show {
        display: flex;
        opacity: 1;
    }
    
    .custom-modal-card {
        background: #ffffff;
        border-radius: 16px;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        width: 100%;
        max-width: 600px;
        overflow: hidden;
        transform: translateY(20px);
        opacity: 0;
        transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    }
    
    .custom-modal-overlay.show .custom-modal-card {
        transform: translateY(0);
        opacity: 1;
    }
    
    .modal-card-header {
        background: #F8FAFC;
        border-bottom: 1px solid #E2E8F0;
        padding: 20px 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .modal-card-title {
        font-size: 18px;
        font-weight: 700;
        color: #0F172A;
    }
    
    .modal-close-btn {
        background: none;
        border: none;
        font-size: 24px;
        color: #64748B;
        cursor: pointer;
        outline: none;
        line-height: 1;
        transition: color 0.2s;
    }
    
    .modal-close-btn:hover {
        color: #0F172A;
    }
    
    .modal-card-body {
        padding: 24px;
        max-height: 450px;
        overflow-y: auto;
    }
    
    /* Type Grid Styling */
    .type-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }
    
    @media (max-width: 576px) {
        .type-grid {
            grid-template-columns: 1fr;
        }
    }
    
    .type-section {
        border: 1px solid #E2E8F0;
        border-radius: 12px;
        padding: 16px;
        background: #ffffff;
        transition: all 0.2s ease-in-out;
    }
    
    .type-section:hover {
        border-color: #CBD5E1;
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.03);
    }
    
    .type-header {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 14px;
        font-weight: 700;
        color: #1E293B;
        border-bottom: 1px solid #F1F5F9;
        padding-bottom: 8px;
        margin-bottom: 12px;
    }
    
    .type-icon {
        width: 28px;
        height: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 700;
    }
    
    .type-book { background: #EFF6FF; color: #2563EB; }
    .type-story { background: #ECFDF5; color: #059669; }
    .type-poem { background: #FDF2F8; color: #DB2777; }
    .type-blog { background: #F5F3FF; color: #7C3AED; }
    .type-article { background: #FFFBEB; color: #D97706; }
    .type-research { background: #F0FDF4; color: #16A34A; }
    
    .work-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .work-item {
        font-size: 13px;
        color: #334155;
        padding: 6px 0;
        border-bottom: 1px solid #F1F5F9;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .work-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }
    
    .work-qty {
        font-size: 11px;
        font-weight: 600;
        color: #64748B;
        background: #F1F5F9;
        padding: 2px 6px;
        border-radius: 4px;
    }
    
    .no-works {
        font-size: 12px;
        color: #94A3B8;
        font-style: italic;
    }
</style>

    <div id="admin-content">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <h2 class="admin-heading">All Publishers</h2>
                </div>
                <div class="offset-md-7 col-md-2">
                    <a class="add-new" href="{{ route('publisher.create') }}">Add Publisher</a>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="message"></div>
                    <table class="content-table">
                        <thead>
                            <th>S.No</th>
                            <th>Publisher Name</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </thead>
                        <tbody>
                            @forelse ($publishers as $publisher)
                                <tr>
                                    <td>{{ $publisher->id }}</td>
                                    <td>
                                        <a href="#" class="publisher-name-link" onclick="event.preventDefault(); openPublisherModal({{ $publisher->id }})">
                                            {{ $publisher->name }}
                                        </a>
                                    </td>
                                    <td class="edit">
                                        <a href="{{ route('publisher.edit', $publisher) }}" class="btn btn-success">Edit</a>
                                    </td>
                                    <td class="delete">
                                        <form action="{{ route('publisher.destroy', $publisher) }}" method="post"
                                            class="form-hidden">
                                            <button class="btn btn-danger delete-author">Delete</button>
                                            @csrf
                                        </form>
                                    </td>
                                </tr>
                                
                                <!-- Modal for this Publisher -->
                                <div class="custom-modal-overlay" id="publisherModal-{{ $publisher->id }}">
                                    <div class="custom-modal-card">
                                        <div class="modal-card-header">
                                            <h3 class="modal-card-title">{{ $publisher->name }} - Catalog Profile</h3>
                                            <button class="modal-close-btn" onclick="closePublisherModal({{ $publisher->id }})">&times;</button>
                                        </div>
                                        <div class="modal-card-body">
                                            @php
                                                $works = $publisher->books;
                                                $booksList = $works->where('type', 'Book');
                                                $storiesList = $works->where('type', 'Story');
                                                $poemsList = $works->where('type', 'Poem');
                                                $blogsList = $works->where('type', 'Blog');
                                                $articlesList = $works->where('type', 'Article');
                                                $researchList = $works->where('type', 'Research Paper');
                                            @endphp
                                            
                                            <div class="type-grid">
                                                <!-- Books -->
                                                <div class="type-section">
                                                    <div class="type-header">
                                                        <span class="type-icon type-book">
                                                            <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                                            </svg>
                                                        </span>
                                                        <span>पुस्तक (Book)</span>
                                                    </div>
                                                    <ul class="work-list">
                                                        @forelse($booksList as $b)
                                                            <li class="work-item">
                                                                <span>{{ $b->name }}</span>
                                                                <span class="work-qty">Qty: {{ $b->quantity }}</span>
                                                            </li>
                                                        @empty
                                                            <li class="no-works">No books published</li>
                                                        @endforelse
                                                    </ul>
                                                </div>

                                                <!-- Stories -->
                                                <div class="type-section">
                                                    <div class="type-header">
                                                        <span class="type-icon type-story">
                                                            <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                            </svg>
                                                        </span>
                                                        <span>कहानी (Story)</span>
                                                    </div>
                                                    <ul class="work-list">
                                                        @forelse($storiesList as $s)
                                                            <li class="work-item">
                                                                <span>{{ $s->name }}</span>
                                                                <span class="work-qty">Qty: {{ $s->quantity }}</span>
                                                            </li>
                                                        @empty
                                                            <li class="no-works">No stories published</li>
                                                        @endforelse
                                                    </ul>
                                                </div>

                                                <!-- Poems -->
                                                <div class="type-section">
                                                    <div class="type-header">
                                                        <span class="type-icon type-poem">
                                                            <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                                            </svg>
                                                        </span>
                                                        <span>कविता (Poem)</span>
                                                    </div>
                                                    <ul class="work-list">
                                                        @forelse($poemsList as $p)
                                                            <li class="work-item">
                                                                <span>{{ $p->name }}</span>
                                                                <span class="work-qty">Qty: {{ $p->quantity }}</span>
                                                            </li>
                                                        @empty
                                                            <li class="no-works">No poems published</li>
                                                        @endforelse
                                                    </ul>
                                                </div>

                                                <!-- Blogs -->
                                                <div class="type-section">
                                                    <div class="type-header">
                                                        <span class="type-icon type-blog">
                                                            <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                                            </svg>
                                                        </span>
                                                        <span>ब्लॉग (Blog)</span>
                                                    </div>
                                                    <ul class="work-list">
                                                        @forelse($blogsList as $bl)
                                                            <li class="work-item">
                                                                <span>{{ $bl->name }}</span>
                                                                <span class="work-qty">Qty: {{ $bl->quantity }}</span>
                                                            </li>
                                                        @empty
                                                            <li class="no-works">No blogs published</li>
                                                        @endforelse
                                                    </ul>
                                                </div>

                                                <!-- Articles -->
                                                <div class="type-section">
                                                    <div class="type-header">
                                                        <span class="type-icon type-article">
                                                            <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                                                            </svg>
                                                        </span>
                                                        <span>लेख (Article)</span>
                                                    </div>
                                                    <ul class="work-list">
                                                        @forelse($articlesList as $a)
                                                            <li class="work-item">
                                                                <span>{{ $a->name }}</span>
                                                                <span class="work-qty">Qty: {{ $a->quantity }}</span>
                                                            </li>
                                                        @empty
                                                            <li class="no-works">No articles published</li>
                                                        @endforelse
                                                    </ul>
                                                </div>

                                                <!-- Research Papers -->
                                                <div class="type-section">
                                                    <div class="type-header">
                                                        <span class="type-icon type-research">
                                                            <svg style="width: 14px; height: 14px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 9.172V5L8 4z"></path>
                                                            </svg>
                                                        </span>
                                                        <span>शोध-पत्र (Research Paper)</span>
                                                    </div>
                                                    <ul class="work-list">
                                                        @forelse($researchList as $r)
                                                            <li class="work-item">
                                                                <span>{{ $r->name }}</span>
                                                                <span class="work-qty">Qty: {{ $r->quantity }}</span>
                                                            </li>
                                                        @empty
                                                            <li class="no-works">No research papers published</li>
                                                        @endforelse
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <tr>
                                    <td colspan="4">No Publisher Found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    {{ $publishers->links('vendor/pagination/bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>

<script>
    function openPublisherModal(id) {
        const modal = document.getElementById('publisherModal-' + id);
        if (modal) {
            modal.style.display = 'flex';
            setTimeout(() => {
                modal.classList.add('show');
            }, 10);
        }
    }
    
    function closePublisherModal(id) {
        const modal = document.getElementById('publisherModal-' + id);
        if (modal) {
            modal.classList.remove('show');
            setTimeout(() => {
                modal.style.display = 'none';
            }, 300);
        }
    }
    
    // Close on overlay click
    document.addEventListener('DOMContentLoaded', function() {
        const overlays = document.querySelectorAll('.custom-modal-overlay');
        overlays.forEach(overlay => {
            overlay.addEventListener('click', function(e) {
                if (e.target === this) {
                    const id = this.id.split('-')[1];
                    closePublisherModal(id);
                }
            });
        });
    });
</script>
@endsection
