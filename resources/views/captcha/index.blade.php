@extends('layouts.app')
@section('content')

<div class="auth-card">
    <label class="d-block mt-3">
        <div class="card" style="border: none;">
            <div id="recaptcha" style="margin: 0 auto;" data-callback="captcha" class="g-recaptcha" data-sitekey="6LeWhuIpAAAAAPRvdy3x68tMmbHrBOvfXO89OTBI"></div>
        </div>
    </label>
</div>
@endsection
@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script>
        var _token = null;
        function captcha () {
            $.ajax({
                url: "{{route('captcha.verify')}}",
                type: 'POST',
                headers: {'x-csrf-token': _token},
                data: {
                    verify: true,
                },
                success: (res) => {
                    res = JSON.parse(res);
                    if (res) {
                        window.location.href = "{{route('admin.home')}}";
                    } else {
                        window.location.href = "{{route('captcha')}}";
                    }
                }
            });
        };
        $(document).ready(function(){
            _token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        });
    </script>
@endsection