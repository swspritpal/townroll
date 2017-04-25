<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', app_name())</title>

        <!-- Meta -->
        <meta name="keywords" content="@yield('meta_keywords', '')">
        <meta name="description" content="@yield('meta_description', '')">
        <meta name="author" content="@yield('meta_author', '')">
        @yield('meta')

        <!-- Styles -->
        @yield('before-styles')

        <!-- Check if the language is set to RTL, so apply the RTL layouts -->
        <!-- Otherwise apply the normal LTR layouts -->
        @langRTL
            {{ Html::style(getRtlCss(mix('css/frontend.css'))) }}
        @else
            {{ Html::style(mix('css/frontend.css')) }}
        @endif

            {!! Html::style('//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css') !!}
            {!! Html::style('//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css') !!}
        
            {!! Html::style(asset('css/vendor/select2.min.css')) !!}
            {!! Html::style(asset('css/jcrop/jcrop.min.css')) !!}  
        @yield('after-styles')
         {{ Html::style(asset('css/slick.css')) }}
         {{ Html::style(asset('css/style.css')) }}
         {{ Html::style(asset('css/ionicons.css')) }}


        <!-- Scripts -->
        <script>
            var APP_URL = {!! json_encode(url('/')) !!}
            window.Laravel = <?php echo json_encode([
                'csrfToken' => csrf_token(),
                'APP_URL' =>  route('frontend.index'),
            ]); ?>
        </script>
   

    </head>
    <body >
        @include('frontend.includes.nav')
        @include('frontend.includes.popups')
        @include('frontend.includes.popups.signup-form')
        @include('frontend.includes.popups.locate-me')
        @include('frontend.includes.popups.post-add')

        <div id="page-contents">
            <div class="container-fluid" >
                @include('includes.partials.logged-in-as')
                <div class="row paddingUnsetMobile">
                    @include('frontend.includes.left')
                    
                    @yield('content')
                    @include('frontend.includes.right')
                </div><!-- container -->
            </div>
        </div>

        <!-- Scripts -->
        @yield('before-scripts')
            {!! Html::script(mix('js/frontend.js')) !!}
            {!! Html::script(asset('js/slick.min.js')) !!}
            
            {!! Html::script(asset('js/jquery_002.js')) !!}
            {!! Html::script(asset('js/script.js')) !!}
            {!! Html::script(asset('js/jquery.jscroll.min.js')) !!}
            {!! Html::script(asset('js/jq-ajax-progress.min.js')) !!}

            {!! Html::script(asset('js/jquery.validate.min.js')) !!}
            {!! Html::script(asset('js/geo/geo-min.js')) !!}
            {!! Html::script(asset('js/signup_form.js')) !!}
            {!! Html::script(asset('js/getstream.js')) !!}

        @yield('after-scripts')

        @include('includes.partials.ga')
        
        {!! Html::script(asset('js/stream_activity.js')) !!}
        {!! Html::script(asset('js/custom.js')) !!} 
    </body>
</html>