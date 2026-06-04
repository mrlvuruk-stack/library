@extends('layouts.app')
@section('content')
<style>
    .dashboard-container {
        padding: 40px;
        max-width: 1440px;
        margin: 0 auto;
    }
    
    .dashboard-header {
        margin-bottom: 32px;
    }
    
    .dashboard-title {
        font-size: 28px;
        font-weight: 700;
        color: #0F172A;
        margin-bottom: 6px;
    }
    
    .dashboard-subtitle {
        font-size: 14px;
        color: #64748B;
    }
    
    /* Metrics grid */
    .metrics-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(360px, 1fr));
        gap: 24px;
        margin-bottom: 40px;
    }
    
    @media (max-width: 768px) {
        .metrics-grid {
            grid-template-columns: 1fr;
        }
    }
    
    .metric-card {
        background: #ffffff;
        border: 1px solid #E2E8F0;
        border-radius: 12px;
        padding: 24px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        position: relative;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05);
        height: 180px;
    }
    
    .metric-card-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }
    
    .metric-icon-wrapper {
        background: #EFF6FF;
        color: #3B82F6;
        width: 48px;
        height: 48px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .metric-icon-wrapper svg {
        width: 24px;
        height: 24px;
    }
    
    .metric-badge {
        font-size: 11px;
        font-weight: 600;
        padding: 4px 10px;
        border-radius: 12px;
        background: #F1F5F9;
        color: #475569;
    }
    
    .metric-badge.active {
        background: #ECFDF5;
        color: #047857;
    }
    
    .metric-badge.current {
        background: #FEF3C7;
        color: #B45309;
    }
    
    .metric-card-bottom {
        margin-top: auto;
        text-align: left;
    }
    
    .metric-value {
        font-size: 40px;
        font-weight: 700;
        color: #0F172A;
        line-height: 1;
        margin-bottom: 8px;
    }
    
    .metric-label {
        font-size: 14px;
        font-weight: 500;
        color: #64748B;
    }
    
    /* Quick Actions */
    .quick-actions-card {
        background: #ffffff;
        border: 1px solid #E2E8F0;
        border-radius: 12px;
        padding: 32px;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05);
    }
    
    .quick-actions-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        border-bottom: 1px solid #F1F5F9;
        padding-bottom: 16px;
    }
    
    .quick-actions-title {
        font-size: 18px;
        font-weight: 700;
        color: #0F172A;
    }
    
    .quick-actions-link {
        font-size: 13px;
        font-weight: 600;
        color: #2563EB;
        text-decoration: none;
    }
    
    .quick-actions-link:hover {
        text-decoration: underline;
    }
    
    .action-buttons {
        display: flex;
        gap: 16px;
        flex-wrap: wrap;
    }
    
    .btn-action-dark {
        background: #020617;
        color: #ffffff;
        border: none;
        border-radius: 8px;
        padding: 12px 20px;
        font-size: 14px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        text-decoration: none;
        transition: background-color 0.2s;
    }
    
    .btn-action-dark:hover {
        background: #0F172A;
        color: #ffffff;
        text-decoration: none;
    }
    
    .btn-action-light {
        background: #EFF6FF;
        color: #1E40AF;
        border: none;
        border-radius: 8px;
        padding: 12px 20px;
        font-size: 14px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        text-decoration: none;
        transition: background-color 0.2s;
    }
    
    .btn-action-light:hover {
        background: #DBEAFE;
        color: #1D4ED8;
        text-decoration: none;
    }
    
    .btn-action-dark svg, .btn-action-light svg {
        width: 18px;
        height: 18px;
    }
</style>

<div class="dashboard-container">
    <div class="dashboard-header">
        <h1 class="dashboard-title">Dashboard Overview</h1>
        <p class="dashboard-subtitle">Welcome back. Here is a summary of your library statistics.</p>
    </div>
    
    <div class="metrics-grid">
        <!-- Authors Card -->
        <div class="metric-card">
            <div class="metric-card-top">
                <div class="metric-icon-wrapper">
                    <!-- Users Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.109A11.386 11.386 0 0110.089 18H8.25a11.386 11.386 0 01-4.909-1.233.109.109 0 01-.08-.109v-2.06c0-1.328.793-2.5 2.007-3.023 1.1-.476 2.3-.737 3.564-.737v.003c.045-.03.09-.06.135-.09M15 9.75a3 3 0 11-6 0 3 3 0 016 0zm6 2.25a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zM6.25 12a2.25 2.25 0 100-4.5 2.25 2.25 0 000 4.5z" />
                    </svg>
                </div>
                <span class="metric-badge">Total</span>
            </div>
            <div class="metric-card-bottom">
                <div class="metric-value">{{ $authors }}</div>
                <div class="metric-label">Authors Listed</div>
            </div>
        </div>
        
        <!-- Publishers Card -->
        <div class="metric-card">
            <div class="metric-card-top">
                <div class="metric-icon-wrapper" style="background: #EEF2F6; color: #475569;">
                    <!-- Office Building Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0012 9.75c-2.685 0-5.3.232-7.5.612V21M3.75 19.5h16.5M12 6.75a.75.75 0 100-1.5.75.75 0 000 1.5z" />
                    </svg>
                </div>
                <span class="metric-badge">Total</span>
            </div>
            <div class="metric-card-bottom">
                <div class="metric-value">{{ $publishers }}</div>
                <div class="metric-label">Publishers Listed</div>
            </div>
        </div>
        
        <!-- Categories Card -->
        <div class="metric-card">
            <div class="metric-card-top">
                <div class="metric-icon-wrapper" style="background: #F5F3FF; color: #7C3AED;">
                    <!-- Shapes Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                    </svg>
                </div>
                <span class="metric-badge">Total</span>
            </div>
            <div class="metric-card-bottom">
                <div class="metric-value">{{ $categories }}</div>
                <div class="metric-label">Categories Listed</div>
            </div>
        </div>
        
        <!-- Books Card -->
        <div class="metric-card">
            <div class="metric-card-top">
                <div class="metric-icon-wrapper" style="background: #FDF2F8; color: #DB2777;">
                    <!-- Book Open Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                    </svg>
                </div>
                <span class="metric-badge">Total</span>
            </div>
            <div class="metric-card-bottom">
                <div class="metric-value">{{ $books }}</div>
                <div class="metric-label">Books Listed</div>
            </div>
        </div>
        
        <!-- Registered Students Card -->
        <div class="metric-card">
            <div class="metric-card-top">
                <div class="metric-icon-wrapper" style="background: #ECFDF5; color: #059669;">
                    <!-- Graduation Cap Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.62 48.62 0 0112 20.904a48.62 48.62 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84a50.58 50.58 0 00-2.658.814m-15.482 0A50.717 50.717 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75" />
                    </svg>
                </div>
                <span class="metric-badge active">Active</span>
            </div>
            <div class="metric-card-bottom">
                <div class="metric-value">{{ $students }}</div>
                <div class="metric-label">Registered Students</div>
            </div>
        </div>
        
        <!-- Books Issued Card -->
        <div class="metric-card">
            <div class="metric-card-top">
                <div class="metric-icon-wrapper" style="background: #FFFBEB; color: #D97706;">
                    <!-- Bookmark Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.593 3.322c1.1.128 1.907 1.077 1.907 2.185V21L12 17.25 4.5 21V5.507c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0111.186 0z" />
                    </svg>
                </div>
                <span class="metric-badge current">Current</span>
            </div>
            <div class="metric-card-bottom">
                <div class="metric-value">{{ $issued_books }}</div>
                <div class="metric-label">Books Issued</div>
            </div>
        </div>
    </div>
    
    <!-- Quick Actions Card -->
    <div class="quick-actions-card">
        <div class="quick-actions-header">
            <h3 class="quick-actions-title">Quick Actions</h3>
            <a href="#" class="quick-actions-link">View All Actions</a>
        </div>
        
        <div class="action-buttons">
            <!-- Issue New Book -->
            <a href="{{ route('book_issue.create') }}" class="btn-action-dark">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-11.25a.75.75 0 00-1.5 0v2.5h-2.5a.75.75 0 000 1.5h2.5v2.5a.75.75 0 001.5 0v-2.5h2.5a.75.75 0 000-1.5h-2.5v-2.5z" clip-rule="evenodd" />
                </svg>
                Issue New Book
            </a>
            
            <!-- Register Student -->
            <a href="{{ route('student.create') }}" class="btn-action-light">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                </svg>
                Register Student
            </a>
            
            <!-- Add Catalog Entry (Book) -->
            <a href="{{ route('book.create') }}" class="btn-action-light">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-11.25a.75.75 0 00-1.5 0v2.5h-2.5a.75.75 0 000 1.5h2.5v2.5a.75.75 0 001.5 0v-2.5h2.5a.75.75 0 000-1.5h-2.5v-2.5z" clip-rule="evenodd" />
                </svg>
                Add Catalog Entry
            </a>
        </div>
    </div>
</div>
@endsection
