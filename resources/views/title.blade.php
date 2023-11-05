<div id="title">
    <div class="logo">
        <a href="{{ URL::asset('comment') }}"><img src="{{ URL::asset('images/logo.png') }}" alt=""></a>
    </div>

    <div v-cloak v-show="user_name && store_name" class="flex-user">
        <div class="user-info" @click="hide_nav = !hide_nav">
            <div class="avatar"><img src="{{ URL::asset('images/avatar.png') }}" alt=""></div>
            <div class="user-info-data">
                <div class="name">${ user_name }</div>
                <div class="store">${ store_name }</div>        
            </div>
            <i class="fa-solid fa-circle-chevron-down" :class="{ 'hide': hide_nav }"></i>
        </div>
    </div>

    <div class="nav-container">
        <div class="nav" :class="{ 'hide': hide_nav }">
            <a class="nav-tag" href="{{ URL::asset('home') }}">
                <i class="fa-solid fa-user-tie"></i>帳號管理
            </a>
            <a class="nav-tag hint" href="{{ URL::asset('logout') }}">
                <i class="fa-solid fa-right-from-bracket"></i>登出
            </a>
        </div>
    </div>
</div>