@include('header')

<div id="content">
    <section class="content">
        @include('title')

        <div id="page-inner" class="container">
            <div class="user-dashboard-title">
                <div class="user-info-block">
                    <img src="{{ URL::asset('images/avatar.png') }}" alt="">
                    <div class="user-info">
                        <div class="name">${ user_name }</div>
                        <div class="store">${ store_name }</div>
                    </div>
                </div>

                <div class="store-dashboard">
                    <div class="data-group">
                        <span>地址:</span>
                        ${ store_address }
                    </div>
                    <div class="data-group">
                        <span>電話:</span>
                        ${ phone }
                    </div>
                    <div class="data-group">
                        <span>星等:</span>
                        <template v-for="count_star in 5">
                            <i class="fa-solid fa-star" :class="{ 'shine': count_star <= star }"></i>
                        </template>
                        (${ star }顆星)
                    </div>
                    <div v-cloak class="data-group">
                        <span>評論數量:</span>
                        ${ comment_len }則
                    </div> 
                </div>
            </div>

            <div>
                <div class="page-tag">
                    <i class="fa-solid fa-gears"></i>
                </div>

                <div class="input-group">
                    <span>使用者名稱</span>
                    <input v-cloak v-model="user_name" placeholder="請輸入使用者名稱">
                </div>
                <div class="input-group">
                    <span>電話號碼</span>
                    <input v-cloak v-model="phone" placeholder="請輸入電話號碼">
                </div>
                <div class="input-group">
                    <span>Email</span>
                    <input v-cloak v-model="email" placeholder="請輸入Email">
                </div>
                <div class="input-group">
                    <span>店家名稱</span>
                    <input v-cloak v-model="store_name" placeholder="請輸入店家名稱" disabled>
                </div>
                <div class="input-group">
                    <span>店家地址</span>
                    <input v-cloak v-model="store_address" placeholder="請輸入店家地址">
                </div>
                <div class="input-group">
                    <span>店家類別</span>
                    <input v-cloak v-model="store_type" placeholder="請輸入店家類別">
                </div>
                <div class="input-group">
                    <span>回覆風格</span>
                    <select v-cloak v-model="reply_style">
                        <option>友善</option>
                        <option>悲觀</option>
                        <option>尖酸刻薄</option>
                        <option>正直</option>
                        <option>堅定</option>
                    </select>
                </div>
            </div>
        </div>

        <button class="page-return-btn" @click="updateStoreInfo()"><i class="fa-solid fa-floppy-disk"></i></button>
    </section>
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

@include('footer')

<script type="text/javascript">
const app = Vue.createApp({
    delimiters: ['${', '}'],
    
    data() {
        return {
            user_name: '',
            phone: '',
            email: '',
            store_name: '',
            store_address: '',
            store_type: '',
            reply_style: '',
            star: '',
            comment_len: '',
            page_loading: false,
            show_success_msg: false,
            success_msg: '',
            show_error_msg: false,
            error_msg: '',
            hide_nav: true
        }
    },

    created() {
        let _this = this;
        
        _this.getUserContent();
    },

    methods: {  
        getUserContent() {
            let _this = this;

            _this.page_loading = true;

            $.ajax({
                type: 'post',
                url: './home/getUserContent',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                complete() {
                    _this.page_loading = false;
                },
                success(resp) {
                    if (resp.st) {
                        _this.user_name = resp.user_name;
                        _this.email = resp.email;
                        _this.phone = resp.phone;
                        _this.store_name = resp.store_name;
                        _this.store_address = resp.store_address;
                        _this.store_type = resp.store_type;
                        _this.reply_style = resp.reply_style;
                        _this.star = resp.star;
                        _this.comment_len = resp.comment_len;
                    } else {
                        _this.showErrorMsg('出現錯誤，請重新整理並再試一次!');
                    }
                },
                error(msg) {
                    _this.showErrorMsg('出現錯誤，請重新整理並再試一次!');
                }
            });
        },

        updateStoreInfo() {
            let _this = this;

            _this.page_loading = true;

            $.ajax({
                type: 'post',
                url: './home/updateStoreInfo',
                data: {
                    user_name: _this.user_name,
                    email: _this.email,
                    phone: _this.phone,
                    store_name: _this.store_name,
                    store_address: _this.store_address,
                    store_type: _this.store_type,
                    reply_style: _this.reply_style,
                    _token: '{{ csrf_token() }}'
                },
                complete() {
                    _this.page_loading = false;
                },
                success(resp) {
                    if (resp.st) {
                        _this.showSuccessMsg('儲存更改成功!');
                    } else {
                        _this.showErrorMsg('出現錯誤，請重新整理並再試一次!');
                    }
                },
                error(msg) {
                    _this.showErrorMsg('出現錯誤，請重新整理並再試一次!');
                }
            });
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