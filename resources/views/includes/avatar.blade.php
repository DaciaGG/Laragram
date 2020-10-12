@if(Auth::user()->image)
<div >
    <img class="avatar" src=" {{ route('user.avatar', ['filename'=>Auth::user()->image]) }}" />
</div>
@endif
