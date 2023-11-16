@if($children)
    <ul>
        @foreach($children as $child)
            @php
                $iconMapping = [
                    'application/pdf' => 'fas fa-file-pdf text-danger me-1',
                    'application/msword' => 'fas fa-file-word text-info me-1',
                    'application/vnd.ms-excel' => 'fas fa-file-excel',
                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'fas fa-file-excel',
                ];

                $fileExtension = $child->content_type;
                $iconClass = $iconMapping[$fileExtension] ?? 'fas fa-file';
            @endphp
            <li id="{{ $child->id }}" data-jstree='{"icon": "{{ $iconClass }}"}'>{{ $child->display_name }}</li>

            @include('admin.strategic_documents.tree_children', ['children' => $child->children])
        @endforeach
    </ul>
@endif
