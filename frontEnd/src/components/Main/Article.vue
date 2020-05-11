<template>
    <div>
        <el-breadcrumb separator-class="el-icon-arrow-right">
            <el-breadcrumb-item :to="{ path: '/home' }">首页</el-breadcrumb-item>
            <el-breadcrumb-item>{{ sortName }}</el-breadcrumb-item>
            <el-breadcrumb-item>{{ partName }}</el-breadcrumb-item>
            <el-breadcrumb-item>{{ title }}</el-breadcrumb-item>
        </el-breadcrumb>
        <div class="article-body">
            <header>{{ title }}</header>
            <div class="article-content">
                <div class="post-owner">
                    <span class="lz-tag">楼主</span>
                    <div>
                        <el-image :src="userData.img_url" fit="cover" alt="" class="avatar"></el-image>
                    </div>
                    <div class="u-name">
                        <!--跳转用户详情页（带参数：用户名）-->
                        <router-link :to="{name:'Userdetail',query:{user_id:userData.Id}}" style="color: black;">{{ userData.from_username }}</router-link>
                    </div>
                    <div class="n-name">{{ userData.nickname }}</div>
                    <div class="level-bar">
                        <router-link :to="'Help'" title="点击进入积分等级说明页">
                            <span class="score">{{ score }}</span>
                            <span class="level">{{ level }}</span>
                        </router-link>
                    </div>
                    <div class="detail">
                        <span class="label">发帖：</span>{{ userData.invitations }}
                        <span class="label">热门：</span>{{ userData.hots }}
                    </div>
                    <div class="functions">
                        <!-- <el-badge :value="msgCount" :max="99" class="count">
                            <router-link :to="'Help'" class="func">
                                <img src="../../assets/images/icons/icon-message.png" style="width: 24px;height: 24px;vertical-align: middle;" alt="">
                            </router-link>
                        </el-badge> -->
                        <router-link :to="{name:'Articleadd',query:{}}" class="func func-env">
                            <img src="../../assets/images/icons/icon-envelope.png" style="width: 24px;height: 24px;vertical-align: middle;" alt="">
                        </router-link>
                        <!-- <el-badge :value="envCount" :max="10" class="count">
                            
                        </el-badge> -->
                    </div>
                </div>
                <div id="arti-content">
                </div>
                <div class="operations">
                    <div class="tools-box">
                        <ul>
                            <li>
                                <el-popover placement="right" width="400" trigger="click">
                                    <el-form :model="form" ref="form" :rules="rules">
                                        <el-form-item label="" prop="comContent">
                                            <el-input type="textarea" v-model="form.comContent" :rows="6" placeholder="评论内容..." auto-complete="off"></el-input>
                                        </el-form-item>
                                        <el-form-item class="btnCom">
                                            <el-button @click.native.prevent="submitForm()" style="width: 20%;">评论</el-button>
                                            <el-button @click="resetForm('form')" style="width: 20%;">重置</el-button>
                                        </el-form-item>
                                    </el-form>
                                    <!-- <el-button >click 激活</el-button> -->
                                    <el-button slot="reference" icon="el-icon-chat-line-round" style="border: 1px solid #fff;">评论</el-button>
                                </el-popover>
                            </li>
                            <li>
                                <el-button icon="el-icon-apple" style="margin-left: 10px;border: 1px solid #fff;">点赞</el-button>
                            </li>
                        </ul>
                    </div>
                    <div class="floors">1 楼</div>
                </div>
            </div>

            <div v-for="(com,i) in comData" :key="i" class="article-content">
                <div v-if="com.Id === userData.Id" class="post-owner">
                    <span class="lz-tag">楼主</span>
                    <div>
                        <el-image :src="userData.img_url" fit="cover" alt="" class="avatar"></el-image>
                    </div>
                    <div class="u-name">
                        <!--跳转用户详情页（带参数：用户名）-->
                        <router-link :to="{name:'Userdetail',query:{user_id:userData.Id}}" style="color: black;" v-if="">{{ userData.from_username }}</router-link>
                    </div>
                    <div class="n-name">{{ userData.nickname }}</div>
                    <div class="level-bar">
                        <router-link :to="'Help'" title="点击进入积分等级说明页">
                            <span class="score">{{ score }}</span>
                            <span class="level">{{ level }}</span>
                        </router-link>
                    </div>
                    <div class="detail">
                        <span class="label">发帖：</span>{{ userData.invitations }}
                        <span class="label">热门：</span>{{ userData.hots }}
                    </div>
                    <div class="functions">
                        <!-- <el-badge :value="msgCount" :max="99" class="count">
                            <router-link :to="'Help'" class="func">
                                <img src="../../assets/images/icons/icon-message.png" style="width: 24px;height: 24px;vertical-align: middle;" alt="">
                            </router-link>
                        </el-badge> -->
                        <router-link :to="{name:'Articleadd',query:{}}" class="func func-env">
                            <img src="../../assets/images/icons/icon-envelope.png" style="width: 24px;height: 24px;vertical-align: middle;" alt="">
                        </router-link>
                        <!-- <el-badge :value="envCount" :max="10" class="count">
                            
                        </el-badge> -->
                    </div>
                </div>
                <div v-else="com.Id != userData.Id" class="post-owner">
                    <!-- <span class="lz-tag">楼主</span> -->
                    <div>
                        <el-image :src="com.img_url" fit="cover" alt="" class="avatar"></el-image>
                    </div>
                    <div class="u-name">
                        <!--跳转用户详情页（带参数：用户名）-->
                        <router-link :to="{name:'Userdetail',query:{user_id:com.Id}}" style="color: black;" v-if="">{{ com.from_username }}</router-link>
                    </div>
                    <div class="n-name">{{ com.nickname }}</div>
                    <div class="level-bar">
                        <router-link :to="'Help'" title="点击进入积分等级说明页">
                            <span class="score">{{ score }}</span>
                            <span class="level">{{ level }}</span>
                        </router-link>
                    </div>
                    <div class="detail">
                        <span class="label">发帖：</span>{{ com.invitations }}
                        <span class="label">热门：</span>{{ com.hots }}
                    </div>
                    <div class="functions">
                        <!-- <el-badge :value="msgCount" :max="99" class="count">
                            <router-link :to="'Help'" class="func">
                                <img src="../../assets/images/icons/icon-message.png" style="width: 24px;height: 24px;vertical-align: middle;" alt="">
                            </router-link>
                        </el-badge> -->
                        <router-link :to="{name:'Articleadd',query:{}}" class="func func-env">
                            <img src="../../assets/images/icons/icon-envelope.png" style="width: 24px;height: 24px;vertical-align: middle;" alt="">
                        </router-link>
                        <!-- <el-badge :value="envCount" :max="10" class="count">
                            
                        </el-badge> -->
                    </div>
                </div>
                <div class="comments">
                    <div v-if="com.Id != userData.Id" class="toWho">{{ com.from_username }} @ {{ com.to_username }}</div>
                    <div v-else="com.Id === userData.Id" class="toWho">{{ userData.from_username }} @ {{ com.to_username }}</div>
                    <!-- <div class="comments-content before-content">{{ com.before_content }}</div> -->
                    <div class="comments-content">{{ com.content }}</div>
                </div>
                <div class="operations">
                    <div class="tools-box">
                        <ul>
                            <li>
                                <el-popover placement="right" width="400" trigger="click">
                                    <el-form :model="form" ref="form1" :rules="rules">
                                        <el-form-item label="" prop="comContent">
                                            <el-input type="textarea" v-model="form.comContent" :rows="6" placeholder="评论内容..." auto-complete="off"></el-input>
                                        </el-form-item>
                                        <el-form-item class="btnCom">
                                            <el-button @click.native.prevent="submitForm()" style="width: 20%;">评论</el-button>
                                            <el-button @click="resetForm('form')" style="width: 20%;">重置</el-button>
                                        </el-form-item>
                                    </el-form>
                                    <!-- <el-button >click 激活</el-button> -->
                                    <el-button slot="reference" icon="el-icon-chat-line-round" style="border: 1px solid #fff;">评论</el-button>
                                </el-popover>
                            </li>
                            <li>
                                <el-button icon="el-icon-apple" style="margin-left: 10px;border: 1px solid #fff;">点赞</el-button>
                            </li>
                        </ul>
                    </div>
                    <div class="floors">{{i+2}} 楼</div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import 'quill/dist/quill.snow.css'

    export default {
        name: "Aticle",
        data() {
            // 生日的表单验证
            var validateCom = (rule, value, callback) => {
                if (value === '') {
                    callback(new Error("评论内容不能为空"));
                }else {
                    callback();
                }
            };
            return {
                // 模块名
                sortName: '',
                // 版面名
                partName: '',
                // 文章id
                articleId: '',
                // 文章标题
                title: '',
                // 文章内容
                content: '',
                // 用户信息
                userData: [],
                // imgUrl: userData.img_url,

                // 评论者id
                from_uid: '',
                // 被评论者id
                to_uid: '',

                // 评论内容
                comData: [],
                score: 2.5,
                level: '青铜',
                msgCount: 100,
                envCount: 5,
                // invitations: '',
                // hots: '',

                // 评论内容
                form: {
                    comContent: ''
                },
                rules: {
                    comContent: [
                        { require: true, message: '评论内容...', trigger: 'blur' },
                        { validator: validateCom, trigger: 'blur' }
                    ]
                }
            }
        },
        computed: {
            
        },
        created() {
            // this.userData = Lockr.get('userInfo');
            // this.getUserBase();
            // console.log(this.userData);
            
        },
        mounted() {
            // 获取文章id
            this.getParams();
            // 获取文章内容
            this.getArticleMsg();
            // 获取文章评论
            this.getAllComs();
        },
        methods: {

            // 获取参数
			getParams() {
				// params 传参
				let self = this;
                self.articleId = self.$route.params.articleId;
                let res = Lockr.get('userInfo');
                self.from_uid = res['Id'];
                // console.log(self.articleId)
				// 如果是 query 传参，那就是this.$route.query.email
            },
            // 获取文章内容
            getArticleMsg(){
                // 区分 this
                const self = this;
                let data = {
                    "article_id": self.articleId
                }
                self.axios.post('http://vt.com/php/index.php/admin/articles/getArticleMsg', data).then(function (res) {
                    console.log(res);
                    if (res.status === 200 && res.data['error'] === '') {
                        // self.data = res.data.data;
                        self.getArticlePart(res.data.data['part_id']);
                        self.setArticleCnt(res.data.data);
                        // console.log(res.data.data['user_id']);
                        // 获取文章或评论的用户信息
                        self.getUserBase(res.data.data['user_id']);
                        
                    }else {
                        _g.toastMsg('error', '读取文章内容失败')
                    }
                }).catch(function (error) {
                    if(error.status === 504){
                        bus.$message({
                            message: '请求超时，请检查网络',
                            type: 'warning'
                        })
                    }
                });
            },
            // 获取文章所属版面/模块
            getArticlePart(partId){
                // 区分 this
                const self = this;
                let data = {
                    "part_id": partId
                }
                self.axios.post('http://vt.com/php/index.php/admin/articles/getArticlePart', data).then(function (res) {
                    console.log(res);
                    if (res.status === 200 && res.data['error'] === '') {
                        self.sortName = res.data.data['sortName'];
                        self.partName = res.data.data['partName'];
                    }else {
                        _g.toastMsg('error', '读取文章内容失败')
                    }
                }).catch(function (error) {
                    if(error.status === 504){
                        bus.$message({
                            message: '请求超时，请检查网络',
                            type: 'warning'
                        })
                    }
                });
            },
            // 文章内容赋值
            setArticleCnt(arr){
                const self =this;
                self.to_uid = arr['user_id'];
                self.title = arr['title'];
                self.content = arr['content'];
                let node =  document.getElementById('arti-content');
                node.innerHTML = '<div class="ql-container ql-snow" style="border: none;"><div class="ql-editor">'+self.content+"</div></div>";
            },

            // 用户文章基本信息
            getUserBase(user_id){
                // 区分 this
                const self = this;
                let data = {
                    "from_uid": user_id,
                    // "content": ''
                }
                self.axios.post('http://vt.com/php/index.php/admin/articles/getUserBase', data).then(function (res) {
                    console.log(res);
                    if (res.status === 200 && res.data['error'] === '') {
                        self.userData = res.data.data;
                    }else {
                        _g.toastMsg('error', res.data['error'])
                    }
                }).catch(function (error) {
                    if(error.status === 504){
                        bus.$message({
                            message: '请求超时，请检查网络',
                            type: 'warning'
                        })
                    }
                });
            },
            // 获取文章所有评论
            getAllComs(){
                const self = this;
                let data = {
                    "article_id": self.articleId
                }
                self.axios.post('http://vt.com/php/index.php/admin/articles/getAllComs', data).then(function (res) {
                    console.log(res);
                    if (res.status === 200 && res.data['error'] === '') {
                        self.setAllComs(res.data.data);
                        // self.comData = res.data.data;
                        // console.log(self.comData)
                    }
                }).catch(function (error) {
                    if(error.status === 504){
                        bus.$message({
                            message: '请求超时，请检查网络',
                            type: 'warning'
                        })
                    }
                });

            },
            // 设置文章评论(为达到评论升序展示，需要取消axios的异步请求，改为同步请求)
            async setAllComs(arr){
                const self = this;
                for (let i = 0; i < arr.length; i++) {
                    let data = {
                        "from_uid": arr[i]['from_uid'],
                        "to_uid": arr[i]['to_uid'],
                        "content": arr[i]['content'],
                        // "before_content": arr[i]['content']
                    }
                    await self.axios.post('http://vt.com/php/index.php/admin/articles/getUserBase', data).then(function (res) {
                        // console.log(res);
                        if (res.status === 200 && res.data['error'] === '') {
                            self.comData.push(res.data.data)
                            // console.log(self.comData['before_content']);
                        }
                    }).catch(function (error) {
                        if(error.status === 504){
                            bus.$message({
                                message: '请求超时，请检查网络',
                                type: 'warning'
                            })
                        }
                    });
                    
                }
            },
            // 提交用户评论数据到后端
            submitForm() {
				// 区分 this
				const self = this;
                // self.$refs[formName].validate((valid) => {
                    // if (valid) {
                        // 向后端传输数据（application/json）
                        let data = {
                            // 评论者id，就是当时登录的用户id
                            "from_uid": self.from_uid,
                            // 被评论者id，就是文章的作者id
                            "to_uid": self.to_uid,
                            "article_id": self.articleId,
                            "content": self.form.comContent
                        };
                        self.axios.post('http://vt.com/php/index.php/admin/comments/commentSend',data).then(function (res) {
                            console.log(res)
                            if (res.status === 200 && res.data['error'] === '') {
                                _g.toastMsg('success', '评论成功');
                                // console.log(self.form.comContent);
								setTimeout(() => {
                                    self.$router.go(0);
								}, 1000)
                            } else{
                            	// self.handleError(res.data);
                            }
                        }).catch(function (error) {
                            if(error.status === 504){
                            	bus.$message({
                            		message: '请求超时，请检查网络',
                            		type: 'warning'
                            	})
                            }
                            // _g.toastMsg('error', '注册失败')
                        })
            },
            // 重置信息
            resetForm(formName) {
                this.$refs[formName].resetFields();
            },
        },
    }
</script>

<style scoped>
    .el-breadcrumb{
        margin: 25px 0;
        letter-spacing: 0.1em;
        font-weight: 500;
    }
    .article-body{
        border-top: 4px solid #00bfff;
        background-color: #ffffff;
    }
    .article-body header{
        font-size: 24px;
        padding: 20px 10px;
        text-align: center;
        border-bottom: 1px solid #eeeeee;
    }
    .article-content{
        position: relative;
        border-bottom: 1px solid #eee;
    }
    .post-owner{
        vertical-align: top;
        display: inline-block;
        position: relative;
        width: 210px;
        text-align: center;
        font-size: 14px;
        line-height: normal;
        /* border-right: 1px solid #eeeeee; */
    }
    .lz-tag{
        display: inline-block;
        position: absolute;
        top: 10px;
        right: 10px;
        font-size: 14px;
        padding: 2px 2px;
        background-color:#dcdcdc;
        border-radius: 3px;
        color: deepskyblue;
    }
    .avatar {
        display: inline-block;
        width: 100px;
        height: 100px;
        cursor: pointer;
        margin-top: 20px;
        overflow: hidden;
        border-radius: 50%;
        text-align: center;
    }
    .u-name{
        margin: 5px;
        font-size: 18px;
        color: black;
    }
    .n-name{
        margin-top: 6px;
        font-size: 16px;
        color: #959595;
    }
    .level-bar{
        margin-top: 10px;
        height: 20px;
        line-height: 20px;
    }
    .level-bar span{
        /*display: inline-block;*/
        font-size: 14px;
        padding: 4px 6px;
    }
    .level-bar .score{
        position: relative;
        left: 4px;
        color: #aa5511;
        background: #fadec5;
    }
    .level-bar .level{
        color: slategray;
        background: #dcdcdc;
    }
    .detail{
        margin: 10px;
        font-size: 14px;
        color: #959595;
    }
    .detail .label{
        color: #bfbfbf;
    }
    .detail .label:last-child{
        margin-left: 8px;
    }

    .functions{
        margin: 15px;
    }
    .functions .func{
        display: inline-block;
        width: 40px;
        height: 40px;
        line-height: 40px;
        border-radius: 50%;
        background-color: deepskyblue;
    }
    /* .functions .func-env {
        margin-left: 10px;
    } */
    .functions .count .el-badge__content.is-fixed{
        position: absolute;
        top: 0;
        right: 15px;
    }
    #arti-content{
        display: inline-block;
        width: 785px;
        min-height: 700px;
        padding: 10px 5px;
        border-left: 1px solid #eee;
    }
    .operations{
        width: 770px;
        height: 50px;
        position: absolute;
        left: 230px;
        bottom: 8px;
        border-top: 1px solid #eeeeee;
    }
    .tools-box{
        display: inline-block;
        height: 50px;
        line-height: 50px;
        margin-top: 4px;
        vertical-align: middle;
    }
    .tools-box li{
        display: inline-block;
    }
    .floors{
        display: inline-block;
        position: absolute;
        top: 20px;
        right: 10px;
        font-size: 12px;
        color: #959595;
    }
    /* 重写最后按钮的样式 */
    .btnCom{
        margin-bottom: 10px;
    }

    /* 评论内容 */
    .comments{
        display: inline-block;
        width: 785px;
        min-height: 300px;
        padding: 10px 5px;
        font-size: 14px;
        border-left: 1px solid #eee;
    }
    .toWho{
        padding: 5px;
        font-size: 14px;
        color: #959595;
    }
    .comments-content{
        padding: 10px;
    }
</style>