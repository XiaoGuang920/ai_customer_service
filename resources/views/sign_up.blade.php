{!! $header !!}

<div class="sign-up-block">
    <iframe id="map-frame" class="map-frame" :src="map_url" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    <div class="data-block">
        <div class="container">
            <div class="page-title"><i class="fa-solid fa-user-plus"></i>註冊</div>

            <div class="login-input-group sign-up">
                <input v-cloak v-model="name" class="login-input" :class="{ 'error-border': name_error_msg }" required>
                <span class="input-text sign-up">使用者名稱</span>
            </div>
            <div v-cloak v-show="name_error_msg" class="input-error-txt full-width"><i class="fa-solid fa-triangle-exclamation"></i>${ name_error_msg }</div>
            
            <div class="login-input-group sign-up">
                <input v-cloak v-model="account" class="login-input" :class="{ 'error-border': account_error_msg }" required>
                <span class="input-text sign-up">帳號</span>
            </div>
            <div v-cloak v-show="account_error_msg" class="input-error-txt full-width"><i class="fa-solid fa-triangle-exclamation"></i>${ account_error_msg }</div>

            <div class="login-input-group sign-up">
                <input v-cloak v-model="password" class="login-input" :class="{ 'error-border': password_error_msg }" required>
                <span class="input-text sign-up">密碼</span>
            </div>
            <div v-cloak v-show="password_error_msg" class="input-error-txt full-width"><i class="fa-solid fa-triangle-exclamation"></i>${ password_error_msg }</div>

            <div class="login-input-group sign-up">
                <input v-cloak v-model="email" class="login-input" :class="{ 'error-border': email_error_msg }" required>
                <span class="input-text sign-up">Email</span>
            </div>
            <div v-cloak v-show="email_error_msg" class="input-error-txt full-width"><i class="fa-solid fa-triangle-exclamation"></i>${ email_error_msg }</div>

            <div class="login-input-group sign-up">
                <input v-cloak v-model="phone" class="login-input" :class="{ 'error-border': phone_error_msg }" required>
                <span class="input-text sign-up">電話</span>
            </div>
            <div v-cloak v-show="phone_error_msg" class="input-error-txt full-width"><i class="fa-solid fa-triangle-exclamation"></i>${ phone_error_msg }</div>

            <div class="bind-block">
                <div class="bind-input">
                    <div class="login-input-group">
                        <input v-cloak v-model="search_key" class="login-input" :class="{ 'error-border': search_key_error_msg }" required @blur="searchOnGoogleMap()" @keydown.enter="searchOnGoogleMap()">
                        <span class="input-text sign-up">店家名稱及關鍵字</span>
                    </div>
                </div>
                <div class="bind-btn">
                    <button @click="bindStore()"><i class="fa-solid fa-link"></i>綁定</button>
                </div>
            </div>
            <div v-cloak v-show="search_key_error_msg" class="input-error-txt full-width"><i class="fa-solid fa-triangle-exclamation"></i>${ search_key_error_msg }</div>

            <div class="login-input-group sign-up">
                <input v-cloak v-model="store_type" class="login-input" :class="{ 'error-border': store_type_error_msg }" required>
                <span class="input-text sign-up">店家類別</span>
            </div>
            <div v-cloak v-show="store_type_error_msg" class="input-error-txt full-width"><i class="fa-solid fa-triangle-exclamation"></i>${ store_type_error_msg }</div>

            <button v-cloak v-if="success_msg == ''" class="sign-up-btn" @click="signUp()">註冊</button>
        </div>
    </div>

    <button class="page-return-btn" @click="goHome()"><i class="fa-solid fa-house-chimney"></i></button>
</div>

<transition name="alert-msg">
    <div v-cloak v-show="show_error_msg" class="error-alert"><i class="fa-solid fa-circle-exclamation"></i>${ error_msg }</div>
</transition>

<transition name="alert-msg">
    <div v-cloak v-show="show_success_msg" class="success-alert"><i class="fa-solid fa-circle-check"></i>${ success_msg }</div>
</transition>

<transition name="page-loading">
    <div v-cloak v-show="page_loading" class="page-loading">
        <div class="spinner-box">
            <div class="leo-border-1">
                <div class="leo-core-1"></div>
            </div> 
            <div class="leo-border-2">
                <div class="leo-core-2"></div>
            </div> 
        </div>
    </div>
</transition>

<transition name="popup-frame">
    <div v-cloak v-show="check_bind_info" class="popup-frame">
        <transition name="popup-window">
            <div v-cloak v-show="check_bind_info" class="popup-window">
                <div class="popup-title"><i class="fa-solid fa-bell"></i>系統訊息</div>
                <div class="popup-content">
                    <div>
                        <div>請確認您的店家是以下地址:</div>
                        <div class="hint-text">${ check_bind_store_address }</div>
                    </div>
                </div>
                <div class="popup-footer">
                    <button class="false-btn" @click="bind_status = false; check_bind_info = false;">否</button>
                    <button class="true-btn" @click="bind_status = true; check_bind_info = false;">是</button>
                </div>
            </div>
        </transition>
    </div>
</transition>

{!! $footer !!}

<script type="text/javascript">
const app = Vue.createApp({
    delimiters: ['${', '}'],
    
    data() {
        return {
            name: '',
            name_error_msg: '',
            account: '',
            account_error_msg: '',
            password: '',
            password_error_msg: '',
            email: '',
            email_error_msg: '',
            phone: '',
            phone_error_msg: '',
            map_url: '',
            search_key: '',
            search_key_error_msg: '',
            check_bind_info: false,
            check_bind_store_address: '',
            bind_status: false,
            store_type: '',
            store_type_error_msg: '',
            page_loading: false,
            show_success_msg: false,
            success_msg: '',
            show_error_msg: false,
            error_msg: ''
        }
    },

    created() {
        let _this = this;
        
        _this.map_url = 'https://www.google.com/maps/embed/v1/search?key=AIzaSyDjnF-6858jyCTM63FKQjr6UnriT3xnkIg&zoom=14&q=' + '台北市中正區';
    },

    methods: {
        goHome() {
            location.href = './login';
        },

        searchOnGoogleMap() {
            let _this = this;
            
            if (_this.search_key) {
                _this.map_url = 'https://www.google.com/maps/embed/v1/search?key=AIzaSyDjnF-6858jyCTM63FKQjr6UnriT3xnkIg&zoom=14&q=' + _this.search_key;
            }
        },

        bindStore() {
            let _this = this;

            if (_this.search_key == '') {
                return;
            }

            _this.page_loading = true;

            $.ajax({
                type: 'post',
                url: './signUp/getBindStore',
                complete() {
                    _this.page_loading = false;
                },
                data: {
                    store_name: _this.search_key,
                    _token: '{{ csrf_token() }}'
                },
                success(resp) {
                    if (resp.st) {
                        _this.check_bind_store_address = resp.store_address;
                        _this.check_bind_info = true;
                    } else {
                        if (resp.msg == 'have not that store') {
                            _this.showErrorMsg('未搜尋到此店家，請確認店家名稱正確!');
                        } else {
                            _this.showErrorMsg('出現錯誤，請重新整理並再試一次!');
                        }
                    }
                },
                error(msg) {
                    _this.showErrorMsg('出現錯誤，請重新整理並再試一次!');
                }
            });
        },

        signUp() {
            let _this = this;
            
            let form_st = _this.checkFormValidate();
            if (form_st) {
                _this.page_loading = true;

                $.ajax({
                    type: 'post',
                    url: './signUp/signUp',
                    complete() {
                        _this.page_loading = false;
                    },
                    data: {
                        name: _this.name,
                        account: _this.account,
                        password: _this.password,
                        email: _this.email,
                        phone: _this.phone,
                        store_name: _this.search_key,
                        store_address: _this.check_bind_store_address,
                        store_type: _this.store_type,
                        _token: '{{ csrf_token() }}'
                    },
                    success(resp) {
                        if (resp.st) {
                            _this.showSuccessMsg('註冊成功，請返回登入!');
                        } else {    
                            _this.showErrorMsg('出現錯誤，請重新整理並再試一次!');
                        }
                    },
                    error(msg) {
                        _this.showErrorMsg('出現錯誤，請重新整理並再試一次!');
                    }
                });
            } else {
                _this.showErrorMsg('請確定所有欄位都正確!');
            }
        },

        checkFormValidate() {
            let _this = this;
            let form_st = true;

            _this.name_error_msg = '';
            if (_this.name == '') {
                _this.name_error_msg = '請填寫使用者名稱';
                form_st = false;
            }
            _this.account_error_msg = '';
            if (_this.account == '') {
                _this.account_error_msg = '請填寫帳號';
                form_st = false;
            }
            _this.password_error_msg = '';
            if (_this.password == '') {
                _this.password_error_msg = '請填寫密碼';
                form_st = false;
            }
            _this.email_error_msg = '';
            if (_this.email == '') {
                _this.email_error_msg = '請填寫Email';
                form_st = false;
            }
            _this.phone_error_msg = '';
            if (_this.phone == '') {
                _this.phone_error_msg = '請填寫電話';
                form_st = false;
            }
            _this.search_key_error_msg = '';
            if (_this.search_key == '') {
                _this.search_key_error_msg = '請填寫店家名稱及關鍵字';
                form_st = false;
            } else if (_this.search_key != '' && !_this.bind_status) {
                _this.search_key_error_msg = '請先綁定店家資訊';
                form_st = false;
            }
            _this.store_type_error_msg = '';
            if (_this.store_type == '') {
                _this.store_type_error_msg = '請填寫店家類別';
                form_st = false;
            }

            return form_st;
        },

        showSuccessMsg(msg) {
            let _this = this;

            if (_this.show_success_msg) {
                return;
            }

            _this.success_msg = msg;
            _this.show_success_msg = true;
            setTimeout(function() {
                _this.show_success_msg = false;
            }, 3000);
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