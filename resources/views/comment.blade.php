@include('header')

<div id="content">
    <section class="content">
        @include('title')

        <div id="page-inner" class="container">
            <template v-if="show_page == 'list'">
                <div class="title-txt">統計資料</div>

                <div class="dashboard">
                    <div class="dashboard-tag">
                        <img src="{{ URL::asset('images/dashboard_light.svg') }}" alt="">
                        <div>評論數量</div>
                        <div class="text-align-r">${ total_comment_quantity }</div>
                    </div>
                    <div class="dashboard-tag">
                        <img src="{{ URL::asset('images/dashboard_dark.svg') }}" alt="">
                        <div>已回覆</div>
                        <div class="text-align-r">${ already_reply_quantity }</div>
                    </div>
                    <div class="dashboard-tag">
                        <img src="{{ URL::asset('images/dashboard_blue.svg') }}" alt="">
                        <div>未回覆</div>
                        <div class="text-align-r">${ not_reply_quantity }</div>
                    </div>
                </div>

                <div class="title-txt">評論列表</div>

                <div class="table container">
                    <div class="table-title row">
                        <div class="col-md-2">評論人</div>
                        <div class="col-md-5">內容</div>
                        <div class="col-md-3">評論時間</div>
                        <div class="col-md-2">操作</div>
                    </div>

                    <div v-cloak v-if="comment_list.length > 0" v-for="comment in comment_list" class="table-content row">
                        <div class="col-md-2">${ comment.user_name }</div>
                        <div class="col-md-5">${ comment.content }</div>
                        <div class="col-md-3">${ comment.date_commented }</div>
                        <div class="col-md-2 operation-block">
                            <button class="operation-btn" @click="getCommentInfo(comment.id)"><i class="fa-solid fa-eye"></i></button>
                            <!--<button class="operation-btn"><i class="fa-solid fa-trash"></i></button>-->
                        </div>
                    </div>
                    <div v-cloak v-else-if="comment_list !== ''" class="table-content row">
                        <div class="col-md-12 text-center">無評論資料</div>
                    </div>
                </div>
            </template>

            <template v-if="show_page == 'info'">
                <div class="title-txt">評論資料</div>

                <div class="comment-info">
                    <div class="data-group">
                        <span>評論人: </span> 
                        ${ comment_info.user_name }
                    </div>
                    <div class="data-group">
                        <span>星等: </span>
                        <template v-for="count_star in 5">
                            <i class="fa-solid fa-star" :class="{ 'shine': count_star <= comment_info.score }"></i>
                        </template>
                        (${ comment_info.score }顆星)
                    </div>
                    <div class="data-group">
                        <span>評論時間: </span>
                        ${ comment_info.date_commented }
                    </div>
                </div>

                <div class="title-txt">評論內容</div>
                <textarea class="comment-textarea" rows="8">${ comment_info.content }</textarea>

                <div class="title-txt">評論回覆</div>
                <textarea class="comment-textarea" rows="8">${ comment_info.response }</textarea>

                <button class="page-return-btn" @click="show_page = 'list'"><i class="fa-solid fa-door-open"></i></button>
            </template>
        </div>
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
            show_page: 'list',
            user_name: '',
            store_name: '',
            comment_list: '',
            total_comment_quantity: 0,
            already_reply_quantity: 0,
            not_reply_quantity: 0,
            comment_info: '',
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
        _this.getComment();
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
                    //_this.page_loading = false;
                },
                success(resp) {
                    if (resp.st) {
                        _this.user_name = resp.user_name;        
                        _this.store_name = resp.store_name;
                    } else {
                        _this.showErrorMsg('出現錯誤，請重新整理並再試一次!');
                    }
                },
                error(msg) {
                    _this.showErrorMsg('出現錯誤，請重新整理並再試一次!');
                }
            });
        },

        getComment() {
            let _this = this;

            _this.page_loading = true;

            $.ajax({
                type: 'post',
                url: './comment/getComment',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                complete() {
                    _this.page_loading = false;
                },
                success(resp) {
                    if (resp.st) {
                        _this.comment_list = resp.comment_list;
                        _this.total_comment_quantity = resp.total_comment_quantity;
                        _this.already_reply_quantity = resp.already_reply_quantity;
                        _this.not_reply_quantity = resp.not_reply_quantity;
                    } else {
                        _this.showErrorMsg('出現錯誤，請重新整理並再試一次!');
                    }
                },
                error(msg) {
                    _this.showErrorMsg('出現錯誤，請重新整理並再試一次!');
                }
            });
        },

        getCommentInfo(id) {
            let _this = this;

            _this.page_loading = true;

            $.ajax({
                type: 'post',
                url: './comment/getCommentInfo',
                data: {
                    id: id,
                    _token: '{{ csrf_token() }}'
                },
                complete() {
                    _this.page_loading = false;
                },
                success(resp) {
                    if (resp.st) {
                        _this.comment_info = resp.comment_info;
                        _this.show_page = 'info';
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