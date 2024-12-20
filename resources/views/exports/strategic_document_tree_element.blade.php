@php
    $dItem = \App\Models\StrategicDocumentChildren::find($d->id);
    $translation = json_decode($d->translations, true);
    $defaultTranslation = array_filter($translation, function ($el){ return $el['locale'] == app()->getLocale(); });
    $defaultTranslation = array_values($defaultTranslation);
    $docFiles = json_decode($d->files, true)
@endphp
    <h4 style="text-align: center; font-size: 28px !important; margin-top: 30px;">{{ isset($defaultTranslation[0]) ? $defaultTranslation[0]['title'] : '---' }}</h4><br>
    @if(isset($defaultTranslation[0]) && !empty($defaultTranslation[0]['description']))
        <div style="margin:10px 30px;">
            {{ htmlToText(html_entity_decode($defaultTranslation[0]['description'])) }}
        </div>
    @endif
    <table style="margin-bottom: 50px; width: 100%;">
        @if($d->strategic_document_type_id)
            <tr>
                <td class="myp">
                    {{ trans_choice('custom.strategic_document_type', 1) }}
                </td>
                <td>
                    {{ $d->strategic_document_type_name ?? __('custom.unidentified') }}
                </td>
            </tr>
        @endif
        @if($d->document_date_accepted)
            <tr>
                <td class="myp">
                    {{ __('custom.effective_at') }}
                </td>
                <td>
                    {{ displayDate($d->document_date_accepted) }}
                </td>
            </tr>
        @endif
        <tr>
            <td class="myp">
                {{ trans_choice('custom.date_expiring', 1) }}
            </td>
            <td>
                @if($d->document_date_expiring)
                    {{ \Carbon\Carbon::parse($d->document_date_expiring)->format('d.m.Y') }}
                @else
                    {{ trans_choice('custom.date_indefinite_name', 1) }}
                @endif
            </td>
        </tr>
        @if($d->public_consultation_id)
            <tr>
                <td class="myp">
                    {{ trans_choice('custom.public_consultation_link', 1) }}
                </td>
                <td>
                    {{ $d->consultation_reg_num }}
                </td>
            </tr>
        @endif
        @if($d->link_to_monitorstat)
            <tr>
                <td class="myp">
                    {{ trans_choice('custom.link_to_monitorstrat', 1) }}
                </td>
                <td>
                    <a href="{{ $d->link_to_monitorstat }}" target="_blank" class="main-color text-decoration-none">
                        {{ trans_choice('custom.link_to_monitorstrat', 1) }}
                    </a>
                </td>
            </tr>
        @endif
        @if(isset($docFiles) && sizeof($docFiles))
            <tr>
                <td class="myp">
                    {{ trans_choice('custom.files', 2) }}
                </td>
                <td>
                    @foreach ($docFiles as $f)
                        <a style="display: block;"  href="{{ route('strategy-document.download-file', $f['id']) }}" title="{{ __('custom.download') }}">
                            {{ $f['description_'.app()->getLocale()] }} - {{ displayDate($f['created_at']) }}
                        </a>
                    @endforeach
                </td>
            </tr>
        @endif
    </table>
    @if(isset($d->children) && sizeof($d->children))
        @foreach($d->children as $doc)
            @include('exports.strategic_document_tree_element', ['d' => $doc])
        @endforeach
    @endif

