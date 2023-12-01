<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-size: 14px;
        }

        .easy-tree li {
            list-style-type: none;
            margin: 0;
            padding: 10px 5px 0 5px;
            position: relative;
        }

        .easy-tree li::before {
            border-left: 2px solid #c0dbf2;
            bottom: 50px;
            height: 100%;
            top: 0;
            width: 1px;
        }

        .easy-tree li::after {
            border-top: 2px solid #c0dbf2;
            height: 20px;
            top: 29px;
            width: 35px;
        }

        .easy-tree li::before,
        .easy-tree li::after {
            content: '';
            left: -30px;
            position: absolute;
            right: auto;
        }

        .easy-tree li:last-child::before {
            height: 29px;
        }

        .easy-tree>ul>li::after,
        .easy-tree>ul>li::before {
            border: 0;
        }

        .easy-tree li::before,
        .easy-tree li::after {
            content: '';
            left: -30px;
            position: absolute;
            right: auto;
        }

        .easy-tree li.parent_li>span {
            cursor: pointer;
        }

        .easy-tree li>span {
            border: 2px solid #c0dbf2;
            border-radius: 3px;
            display: inline-block;
            padding: 5px 10px;
            text-decoration: none;
        }

        .easy-tree li>span>a {
            color: #333;
            text-decoration: none;
        }

    </style>
</head>

    @php $processedFiles = []; @endphp
    @foreach ($data as $strategicDocument)
        <div class="easy-tree">
            <ul>
                <li class="parent_li">
                    <span>{!! '<a href="' . route('strategy-document.view', ['id' => $strategicDocument->id]) . '">' . $strategicDocument->document_display_name . '</a>' !!}</span>
                    @php $mainFile = null; @endphp
                    @foreach ($strategicDocument->files as $file)
                        @if($file->locale == 'en')
                            @continue
                        @endif
                        @if ($file->is_main)
                            {{-- Store the main file --}}
                            @php $mainFile = $file; @endphp
                        @endif
                    @endforeach
                    @if ($mainFile)
                        <ul>
                            <li class="parent_li">
                                <span>{{ $mainFile->document_display_name }} {{ $mainFile->id }}</span>
                                @foreach ($strategicDocument->files as $file)
                                    @if(in_array($file->id, $processedFiles) || $file->locale == 'en' || $file->is_main || !$file->visible_in_report)
                                        @continue
                                    @endif
                                    @php $processedFiles[] = $file->id; @endphp
                                    <ul>
                                        <li class="parent_li">
                                            <span>{{ $file->document_display_name }}</span>
                                            @if ($file->childDocuments->isNotEmpty())
                                                @foreach ($file->childDocuments as $childDocument)
                                                    @php $processedFiles[] = $childDocument->id; @endphp
                                                    <ul>
                                                        <li class="parent_li">
                                                            <span>{{ $childDocument->document_display_name }}</span>
                                                            @if ($childDocument->childDocuments->isNotEmpty())
                                                                @foreach ($childDocument->childDocuments as $grandchildDocument)
                                                                    @php $processedFiles[] = $grandchildDocument->id; @endphp
                                                                    <ul>
                                                                        <li class="parent_li">
                                                                            <span>{{ $grandchildDocument->document_display_name }}
                                                                            </span>
                                                                        </li>
                                                                    </ul>
                                                                @endforeach
                                                            @endif
                                                        </li>
                                                    </ul>
                                                @endforeach
                                            @endif
                                        </li>
                                    </ul>
                                @endforeach
                                @endif
                            </li>
                        </ul>
            </ul>
        </div>
    @endforeach
</body>
</html>
