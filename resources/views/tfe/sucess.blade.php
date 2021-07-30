@if(Session::has('success'))
    <div class="alert alert_success">
        {{ Session::get('success') }}
    </div>
@endif