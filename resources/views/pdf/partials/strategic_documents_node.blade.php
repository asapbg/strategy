<div>
    {{ $node['text'] }}

    @if (!empty($node['children']))
        @php
        @endphp
        <div style="margin-left: 20px;"> <!-- Adjust the margin-left as needed -->
            @foreach ($node['children'] as $childNode)
                @include('pdf.partials.strategic_documents_node', ['node' => $childNode])
            @endforeach
        </div>
    @endif
</div>
