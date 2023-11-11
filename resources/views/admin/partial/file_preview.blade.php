@if(isset($file) && $file)
    <div class="row mt-3">
        <embed src="{{ asset(DIRECTORY_SEPARATOR.'help'.DIRECTORY_SEPARATOR.\App\Models\File::USER_GUIDE_FILE) }}" width="800px" height="2100px" />
    </div>
@endif
