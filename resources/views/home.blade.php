@extends('layouts.app')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-foreground">Dashboard</h2>
        <a href="{{ route('found') }}" class="inline-flex items-center justify-center rounded-md text-sm font-medium transition-colors bg-primary text-primary-foreground shadow hover:bg-primary/90 h-9 px-4 py-2">
            Report Found Item
        </a>
    </div>

    <!-- Tabs -->
    <div id="tabs-container">
        <div class="border-b border-border mb-4">
            <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                <button data-tab="all" class="tab-trigger whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm border-primary text-primary">
                    All Items
                </button>
                <button data-tab="lost" class="tab-trigger whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm border-transparent text-muted-foreground hover:text-foreground hover:border-border">
                    Lost Items
                </button>
                <button data-tab="found" class="tab-trigger whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm border-transparent text-muted-foreground hover:text-foreground hover:border-border">
                    Found Items
                </button>
            </nav>
        </div>


        <div id="lost-content" class="tab-content hidden">
             <!-- Add loop for lost items here -->
             <p class="text-muted-foreground">Lost items will be shown here.</p>
        </div>

        <div id="found-content" class="tab-content hidden">
            <!-- Add loop for found items here -->
            <p class="text-muted-foreground">Found items will be shown here.</p>
        </div>
    </div>

    <!-- Simple Tab-switching script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tabContainer = document.getElementById('tabs-container');
            const tabTriggers = tabContainer.querySelectorAll('.tab-trigger');
            const tabContents = tabContainer.querySelectorAll('.tab-content');

            tabTriggers.forEach(trigger => {
                trigger.addEventListener('click', function () {
                    const tabName = this.getAttribute('data-tab');

                    // Update trigger states
                    tabTriggers.forEach(t => {
                        t.classList.remove('border-primary', 'text-primary');
                        t.classList.add('border-transparent', 'text-muted-foreground', 'hover:text-foreground', 'hover:border-border');
                    });
                    this.classList.add('border-primary', 'text-primary');
                    this.classList.remove('border-transparent', 'text-muted-foreground', 'hover:text-foreground', 'hover:border-border');


                    // Update content visibility
                    tabContents.forEach(content => {
                        if (content.id === `${tabName}-content`) {
                            content.classList.remove('hidden');
                        } else {
                            content.classList.add('hidden');
                        }
                    });
                });
            });
        });
    </script>
@endsection
