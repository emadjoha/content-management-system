
<?php $navHtml = \App\MainMenu::getNavBarList(); ?>
<?php function getKeyWord($content){
        $lang_id = session('lang');
        $word_id = \App\KeyWords::whereName($content)->first()->id;
        //dd($word_id);
        $word = \App\Translator::whereLanguageId($lang_id)->whereTranslatorsId($word_id)->whereTranslatorsType('App\KeyWords')->first();

        if( !$word ){
            $word = new stdClass();
            $word->content = "????";
        }
        return $word;
}; ?>




<div class="navbar navbar-inverse"  role="navigation" >
    <div class="container" >
        <div class="navbar-header navbar-left" >
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <?= $navHtml['buttons'] ?>
            </button>
            <a class="navbar-brand " href="#"><span style="color: red">
                    <?= getKeyWord('site_name')->content ?>
                </span></a>
        </div>
        <div class="collapse navbar-collapse ">
            <ul class="nav navbar-nav navbar-right">
                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                    </li>
                    <li class="nav-item">
                        @if (Route::has('register'))
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                        @endif
                    </li>
                @else

                    <li>
                        <a id="navbarDropdown"  href="#" class='dropdown-toggle' data-toggle='dropdown'>
                            <?= getKeyWord('Language')->content ?> <b class="caret"></b>
                        </a>
                        <?php $langs = \App\Language::all(); ?>

                        <ul class="dropdown-menu" >
                            @foreach( $langs as $lang )
                                <li>
                                    <a class="dropdown-item text-center" href="#" onclick="event.preventDefault();
                                                   document.getElementById('change_language_{{$lang->id}}').submit();"
                                    >
                                        {{$lang->name}}
                                    </a>

                                    <form action="{{ route('lang.change') }}" id="change_language_{{$lang->id}}" method="POST" >
                                        <input type="hidden" name="lang" value="{{$lang->id}}">
                                        @csrf
                                    </form>
                                </li>
                            @endforeach
                        </ul>
                    </li>

                    <li>
                        <a id="navbarDropdown"  href="#" class='dropdown-toggle' data-toggle='dropdown'>
                            <?= getKeyWord('template')->content ?> <b class="caret"></b>
                        </a>
                        <ul class="dropdown-menu" >
                            <li>
                                <a class="dropdown-item text-center" href="#" onclick="event.preventDefault();
                                               document.getElementById('change_template_1').submit();"
                                >
                                   blue-ray
                                </a>
                                <form action="{{ route('template.change') }}" id="change_template_1" method="POST" >
                                    <input type="hidden" name="temp" value="1">
                                    @csrf
                                </form>
                            </li>

                            <li>
                                <a class="dropdown-item text-center" href="#" onclick="event.preventDefault();
                                               document.getElementById('change_template_2').submit();"
                                >
                                   Gray-ray
                                </a>
                                <form action="{{ route('template.change') }}" id="change_template_2" method="POST" >
                                    <input type="hidden" name="temp" value="2">
                                    @csrf
                                </form>
                            </li>

                        </ul>
                    </li>


                    <li>
                        <a id="navbarDropdown"  href="#" class='dropdown-toggle' data-toggle='dropdown'>
                            {{ Auth::user()->name }} <b class="caret"></b>
                        </a>

                        <ul class="dropdown-menu" >
                            <li>
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>
                            </li>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </ul>
                    </li>
                @endguest
            </ul>
            <ul class="nav navbar-nav navbar-left">
                    @guest
                    @else
                        @if( \App\Capability::hasCapability('access','dashboard') )
                        <li>
                            <a id="navbarDropdown"  href="#" class='dropdown-toggle' data-toggle='dropdown'>
                                <?= getKeyWord('site_mng')->content ?> <b class="caret"></b>
                            </a>
                            <ul class="dropdown-menu" >
                                    <li><a href='{{route('dashboard')}}'><?= getKeyWord('dashboard')->content ?></a></li>

                                @if(\App\Capability::hasCapability('access','role'))
                                    <li><a href='{{route('role.index')}}'><?= getKeyWord('role_mng')->content ?></a></li>
                                @endif

                                @if(\App\Capability::hasCapability('access','assign'))
                                    <li><a href='{{route('assign.index')}}'><?= getKeyWord('role_asg_mng')->content ?></a></li>
                                @endif

                                @if(\App\Capability::hasCapability('access','lang'))
                                    <li><a href='{{route('lang.index')}}'><?= getKeyWord('lang_mng')->content ?></a></li>
                                @endif

                                @if(\App\Capability::hasCapability('access','keyword'))
                                    <li><a href='{{route('keyword.index')}}'><?= getKeyWord('keyword_mng')->content ?></a></li>
                                @endif
                            </ul>
                        </li>
                    @endif
                        <?= $navHtml['list'] ?>
                    @endguest

            </ul>
        </div><!--/.nav-collapse -->
    </div>
</div>



