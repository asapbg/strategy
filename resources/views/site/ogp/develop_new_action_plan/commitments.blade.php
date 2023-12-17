{{--@php $ci = 1; @endphp--}}
@foreach($item->commitments as $c)
    @php $ci = $loop->iteration; @endphp
    <div class="col-md-12 mb-3">
        <p class="fs-18 fw-600 mb-2">
            {{ $ci }}. {{ $c->name }}
        </p>
        @foreach($c->arrangements as $a)
            @php $ai = $loop->iteration; @endphp
            <p class="fs-18 fw-600 mb-2">
                {{ $ci }}.{{ $ai }}. {{ $a->name }}
            </p>
            @foreach($a->fields as $field)
                <p>
                    <strong>
                        @if($field->is_system)
                            @php $f = \App\Enums\OgpAreaArrangementFieldEnum::fromName($field->name); @endphp
                            {{ __('ogp.arrangement_fields.'.$f->value) }}
                        @else
                            {{ $field->name }}
                        @endif
                        :</strong>
                    {!! $field->content !!}
                </p>
            @endforeach
        @endforeach
    </div>
@endforeach
