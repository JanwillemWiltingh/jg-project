{{--  Alert for handling error and succes message  --}}
@if(session()->get('message'))
    <div class="alert alert-{{ session()->get('message')['type'] }} alert-dismissible fade show" role="alert">
        {{ session()->get('message')['message'] }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
        </button>
    </div>
@endif
