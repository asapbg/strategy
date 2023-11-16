@if($children)
    <ul>
        @foreach($children as $child)
            <li id="{{ $child->id }}" data-jstree='{"icon": "glyphicon glyphicon-file"}'>{{ $child->id }}</li>
            @include('admin.strategic_documents.tree_children', ['children' => $child->children])
        @endforeach
    </ul>
@endif
