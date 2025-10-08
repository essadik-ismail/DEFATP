{{-- 
    Laravel Blade Components Library
    
    This file serves as a reference for all available components.
    Copy the components you need to your views.
--}}

{{-- 
    STATISTICS GRID COMPONENT
    Usage: <x-stats-grid :stats="$stats" />
    
    $stats should be an array of:
    [
        'value' => '123',
        'label' => 'Total Items',
        'icon' => 'fas fa-list',
        'color' => 'purple' // purple, blue, orange, green, rose, indigo, teal, amber
    ]
--}}

{{-- 
    CARD COMPONENT
    Usage: <x-card title="Card Title" subtitle="Card Subtitle" variant="gradient" color="green" icon="fas fa-chart" collapsible="true" id="unique-id">
        Card content here
    </x-card>
    
    Props:
    - title: Card title
    - subtitle: Card subtitle
    - headerActions: Actions in header
    - footer: Footer content
    - collapsible: Whether card can be collapsed
    - collapsed: Initial collapsed state
    - id: Unique identifier for collapsible cards
    - variant: Card style (default, gradient, colored, minimal)
    - color: Color theme (green, blue, purple, orange, red, gray)
    - icon: Icon class for header
    - iconColor: Custom icon color (optional)
    - padding: Padding size (normal, compact, spacious)
    
    Examples:
    <x-card title="Statistics" variant="gradient" color="blue" icon="fas fa-chart-bar">
        Content here
    </x-card>
    
    <x-card title="Settings" variant="colored" color="green" icon="fas fa-cog" collapsible="true" id="settings">
        Settings content
    </x-card>
    
    <x-card title="Minimal Card" variant="minimal" padding="compact">
        Minimal content
    </x-card>
--}}

{{-- 
    DATA TABLE COMPONENT
    Usage: <x-data-table :headers="$headers" :total="$total" :pagination="$pagination">
        <tr class="table-row">
            <td class="table-cell">Data</td>
        </tr>
    </x-data-table>
    
    Props:
    - headers: Array of table headers
    - total: Total number of items
    - emptyMessage: Message when no data
    - emptySubmessage: Subtitle when no data
    - emptyIcon: Icon for empty state
    - pagination: Pagination component
    - responsive: Enable horizontal scroll
    - striped: Enable striped rows
    - hover: Enable hover effects
--}}

{{-- 
    FORM INPUT COMPONENT
    Usage: <x-form.input name="field_name" label="Field Label" type="text" required="true" icon="fas fa-user" />
    
    Props:
    - type: text, email, password, number, date, textarea, select, checkbox
    - name: Field name
    - label: Field label
    - placeholder: Placeholder text
    - value: Field value
    - required: Whether field is required
    - icon: Icon class (FontAwesome or Material Icons)
    - help: Help text
    - disabled: Whether field is disabled
    - readonly: Whether field is readonly
    - min/max/step: For number inputs
    - rows: For textarea
    - options: For select (array or array of objects with value/label)
    - selected: Selected value for select
    - multiple: Multiple selection for select
    - accept: File types for file input
--}}

{{-- 
    BUTTON COMPONENT
    Usage: <x-button variant="primary" size="md" icon="fas fa-save">Save</x-button>
    
    Props:
    - type: button, submit, reset
    - variant: primary, secondary, success, danger, warning, info, outline, ghost, link
    - size: xs, sm, md, lg, xl
    - disabled: Whether button is disabled
    - loading: Show loading spinner
    - icon: Icon class
    - iconPosition: left, right
    - fullWidth: Full width button
    - href: Make button a link
    - target: Link target
--}}

{{-- 
    ALERT COMPONENT
    Usage: <x-alert type="success" title="Success!" dismissible="true" autoHide="true">Operation completed successfully!</x-alert>
    
    Props:
    - type: success, error, warning, info
    - title: Alert title
    - dismissible: Whether alert can be dismissed
    - autoHide: Whether alert auto-hides
    - duration: Auto-hide duration in milliseconds
    
    JavaScript functions available:
    - showAlert(type, message, title, options)
    - showSuccessAlert(message, title, options)
    - showErrorAlert(message, title, options)
    - showWarningAlert(message, title, options)
    - showInfoAlert(message, title, options)
--}}

{{-- 
    FILTER SECTION COMPONENT
    Usage: <x-filter-section title="Advanced Filters" collapsible="true" collapsed="false" id="filters">
        Filter form content here
    </x-filter-section>
    
    Props:
    - title: Section title
    - collapsible: Whether section can be collapsed
    - collapsed: Initial collapsed state
    - id: Unique identifier
    
    CSS classes available:
    - .filter-grid: Grid layout for filter fields
    - .filter-grid.cols-2, .filter-grid.cols-3, .filter-grid.cols-4: Column variations
    - .filter-actions: Container for filter action buttons
--}}

{{-- 
    IMPORT/EXPORT SECTION COMPONENT
    Usage: <x-import-export-section 
        exportRoute="{{ route('export.route') }}" 
        importRoute="{{ route('import.route') }}"
        exportLabel="Export Data"
        importLabel="Import Data"
    />
    
    Props:
    - title: Section title
    - collapsible: Whether section can be collapsed
    - collapsed: Initial collapsed state
    - id: Unique identifier
    - exportRoute: Route for export functionality
    - importRoute: Route for import functionality
    - exportLabel: Export button text
    - importLabel: Import button text
    - exportDescription: Export description
    - importDescription: Import description
    - fileTypes: Accepted file types
    - exportFilters: Whether to include current filters in export
--}}

{{-- 
    COMPONENT COMBINATION EXAMPLES
    
    1. Statistics Dashboard:
    <x-stats-grid :stats="$stats" />
    
    2. Filterable Data Table:
    <x-filter-section title="Search & Filters" id="search-filters">
        <div class="filter-grid cols-3">
            <x-form.input name="search" label="Search" placeholder="Search..." icon="fas fa-search" />
            <x-form.select name="category" label="Category" :options="$categories" />
            <x-form.input name="date_from" label="From Date" type="date" />
        </div>
        <div class="filter-actions">
            <x-button type="submit" variant="primary" icon="fas fa-search">Search</x-button>
            <x-button variant="outline" icon="fas fa-undo">Reset</x-button>
        </div>
    </x-filter-section>
    
    <x-data-table :headers="$headers" :total="$total" :pagination="$pagination">
        @foreach($items as $item)
            <tr class="table-row">
                <td class="table-cell">{{ $item->name }}</td>
                <td class="table-cell">{{ $item->description }}</td>
                <td class="table-cell">
                    <x-button variant="primary" size="sm" icon="fas fa-edit">Edit</x-button>
                    <x-button variant="danger" size="sm" icon="fas fa-trash">Delete</x-button>
                </td>
            </tr>
        @endforeach
    </x-data-table>
    
    3. Import/Export with Alerts:
    <x-import-export-section 
        exportRoute="{{ route('data.export') }}" 
        importRoute="{{ route('data.import') }}"
    />
    
    @if(session('success'))
        <x-alert type="success" title="Success!" dismissible="true" autoHide="true">
            {{ session('success') }}
        </x-alert>
    @endif
    
    4. Form with Validation:
    <x-card title="Create New Item" collapsible="false">
        <form method="POST" action="{{ route('items.store') }}">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <x-form.input name="name" label="Name" required="true" icon="fas fa-tag" />
                <x-form.input name="email" label="Email" type="email" required="true" icon="fas fa-envelope" />
                <x-form.select name="category_id" label="Category" :options="$categories" required="true" />
                <x-form.input name="price" label="Price" type="number" step="0.01" icon="fas fa-dollar-sign" />
                <div class="md:col-span-2">
                    <x-form.input name="description" label="Description" type="textarea" rows="4" />
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-6">
                <x-button variant="outline" icon="fas fa-times">Cancel</x-button>
                <x-button type="submit" variant="primary" icon="fas fa-save">Save Item</x-button>
            </div>
        </form>
    </x-card>
--}}
