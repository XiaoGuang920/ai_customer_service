{!! $header !!}

<div class="login-block">
    <img src="{{ URL::asset('images/logo.png') }}" alt="">

    <div class="login-input-group">
        <input v-cloak v-model="account" class="login-input" :class="{ 'error-border': account_error }" required>
        <span class="input-text">帳號</span>
    </div>
    <div v-cloak v-show="account_error" class="input-error-txt"><i class="fa-solid fa-triangle-exclamation"></i>請填寫帳號</div>
    
    <div class="login-input-group">
        <input v-cloak v-model="password" class="login-input password" :class="{ 'error-border': password_error }" type="password" required>
        <span class="input-text">密碼</span>
    </div>
    <div v-cloak v-show="password_error" class="input-error-txt"><i class="fa-solid fa-triangle-exclamation"></i>請填寫密碼</div>

    <div class="login-btn-group">
        <button class="sign-up" @click="goSignUp()"><span>註冊<span></button>
        <button class="login" @click="goLogin()">
            <span v-cloak v-show="login_loading" class="v-h-center"><span class="loader"></span>登入中</span>
            <span v-cloak v-show="!login_loading"><i class="fa-solid fa-right-to-bracket"></i>登入</span>
        </button>
    </div>
</div>

<transition name="alert-msg">
    <div v-cloak v-show="show_error_msg" class="error-alert"><i class="fa-solid fa-circle-exclamation"></i>${ error_msg }</div>
</transition>

{!! $footer !!}

<script type="text/javascript">
const app = Vue.createApp({
    delimiters: ['${', '}'],
    
    data() {
        return {
            account: '',
            password: '',
            account_error: false,
            password_error: false,
            login_loading: false,
            show_error_msg: false,
            error_msg: ''
        }
    },

    methods: {
        goSignUp() {
            location.href = './signUp';
        },

        goLogin() {
            let _this = this;

            if (_this.login_loading) {
                return;
            }

            _this.login_loading = true;

            _this.account_error = false;
            if (_this.account == '') {
                _this.account_error = true;
            }
            _this.password_error = false;
            if (_this.password == '') {
                _this.password_error = true;
            }

            if (_this.account_error || _this.password_error) {
                _this.login_loading = false;
                return;
            }

            $.ajax({
                type: 'post',
                url: './login/login',
                complete() {
                    _this.login_loading = false;
                },
                data: {
                    account: _this.account,
                    password: _this.password,
                    _token: '{{ csrf_token() }}'
                },
                success(resp) {
                    if (resp.st) {
                        if (resp.auth) {
                            location.href = './comment';
                        } else {
                            _this.showErrorMsg('帳號或密碼錯誤!');
                        }
                    } else {
                        if (resp.msg == 'no such user') {
                            _this.showErrorMsg('沒有這位使用者，請確認輸入的帳號正確!');
                        } else {
                            _this.showErrorMsg('出現錯誤，請再試一次!');
                        }
                    }
                },
                error(msg) {
                    _this.showErrorMsg('出現錯誤，請重新整理並再試一次!');
                }
            });
        },

        showErrorMsg(msg) {
            let _this = this;

            if (_this.show_error_msg) {
                return;
            }

            _this.error_msg = msg;
            _this.show_error_msg = true;
            setTimeout(function() {
                _this.show_error_msg = false;
            }, 3000);
        }
    }
}).mount('#body-content');
</script>